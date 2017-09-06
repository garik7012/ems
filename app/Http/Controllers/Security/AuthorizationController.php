<?php

namespace App\Http\Controllers\Security;

use App\EmailStat;
use App\UserTrustedDevice;
use App\Enterprise;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Setting;
use App\PasswordPolicy;
use Illuminate\Support\Facades\Session;
use App\LoginStat;

class AuthorizationController extends Controller
{
    use AuthenticatesUsers;

    public function showLoginForm($namespace)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $self_signup = Setting::getValue(2, $ent_id, 'self_signup');
        return view('enterprise.login', compact('self_signup'));
    }

    public function checkConfirmCode(Request $request)
    {
        $namespace = Enterprise::where('id', Auth::user()->enterprise_id)->value('namespace');
        $cat_user = session('categories_user');
        if ($cat_user and $cat_user['count_cat'] == count($request->cat_id)) {
            foreach ($request->cat_id as $cat_id) {
                if (!in_array($cat_id, $cat_user['categories_ids'])) {
                    $this->wrongConfirmAttempt();
                    Auth::logout();
                    return redirect()->back();
                }
            }
            Setting::updateValue(3, Auth::user()->id, 'confirmation_attempt_count', 0);
            Session::forget('categories_user');
            Session::forget('categories_grid');
            return redirect(config('ems.prefix') . "$namespace");
        } elseif ($cat_user and $cat_user['count_cat'] != count($request->cat_id)) {
            $this->wrongConfirmAttempt();
            Auth::logout();
            return redirect()->back();
        }
        $security_code = Setting::getValue(3, Auth::user()->id, 'confirmation_code');
        if ($request->confirm == $security_code) {
            Setting::updateValue(3, Auth::user()->id, 'confirmation_code', '');
            Setting::updateValue(3, Auth::user()->id, 'confirmation_attempt_count', 0);
            if ($request->trusted) {
                $token = UserTrustedDevice::createTrustedToken();
                return redirect(config('ems.prefix') . "$namespace")->cookie('device_token', $token, 3*60*24);
            }
            return redirect(config('ems.prefix') . "$namespace");
        }
        $this->wrongConfirmAttempt();
        Auth::logout();
        return redirect()->back()->with(['wrong-confirm' => true]);
    }

    public function userNotActive()
    {
        return view('security.notactive');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::where($this->username(), $request->login)->select('id', 'expire_end_at')->first();
        $ent_id = Enterprise::where('namespace', $request->route('namespace'))->value('id');
        $this->validateLogin($request);
        if ($user) {
            $logs = LoginStat::logLogin($ent_id, $user->id, $request);
            $end_ban = $this->hasBan($request, $user->id);
            if ($end_ban) {
                $hours_mins_left = date('H \h i \m\i\n', $end_ban - strtotime('now'));

                return back()->withErrors(['login' => "This user has ban. Please try again in $hours_mins_left"]);
            }
            if ($user->expire_end_at and $user->expire_end_at < date('Y-m-d')) {
                return back()->withErrors(['login' => "Your account has expired"]);
            }
            if ($this->attemptLogin($request)) {
                $logs->is_ok = 1;
                $logs->save();
                return $this->sendLoginResponse($request);
            }
            if ($user->id) {
                $this->checkCountLoginAttempts($ent_id, $user->id);
            }
        } else {
            LoginStat::logLogin($ent_id, null, $request);
        }
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout($namespace, Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect(config('ems.prefix') . "{$namespace}");
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $request->only($this->username(), 'password')
        );
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        return $this->authenticated($request, $this->guard()->user())
            ?: $this->authorizationFactor($request);
    }

    /**
     * Check enterprise auth type settings
     * if not single authorization - send sms or email
     * according to is_sms_allow
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authorizationFactor($request)
    {
        $this->comparePasswordWithPasswordPolicy($request);
        if (Auth::user()->is_active == 0) {
            Auth::logout();
            return back()->withErrors(['login' => 'this user is not active']);
        }
        $user_id = Auth::user()->id;
        $ent_id = Auth::user()->enterprise_id;
        $auth_type = Setting::getValue(3, $user_id, 'auth_type_id') ?:
            Setting::getValue(2, $ent_id, 'auth_type_id');
        in_array($auth_type, [3, 5]) ? $with_trusted_device = true: $with_trusted_device = false;
        //single factor or 2 factor with trusted device(trusted device ok)
        if ($auth_type == 1 or $with_trusted_device && UserTrustedDevice::isTrusted()) {
            return redirect(config('ems.prefix') . "{$request->route('namespace')}");
        }
        //2 factor (picture based)
        if (in_array($auth_type, [4, 5]) and $this->picturesGridGenerate()) {
            return redirect()->back()->with('security_code', true)->with(compact('with_trusted_device'));
        }
        //2 factor(email or sms)
        $security_code = str_random(8);
        Setting::updateValue(3, $user_id, 'confirmation_code', $security_code);

        $is_sms = Setting::getValue(2, $ent_id, 'is_sms_allow');
        if ($is_sms) {
            //TODO send sms
            return redirect()->back()->with([
                'security_code_temp' => "SMS. Code: $security_code",
                'security_code' => true,
                'with_trusted_device' => $with_trusted_device
            ]);
        } else {
            $from_email = 'no-reply@domain.com';
            $to_email = Auth::user()->email;
            $subject = 'Security code';
            $data = base64_encode("your security code is $security_code");
            //TODO send email
            EmailStat::logEmail($ent_id, $user_id, $from_email, $to_email, $subject, $data);
            return redirect()->back()->with([
                'security_code_temp' => "Email. Code: $security_code",
                'security_code' => true,
                'with_trusted_device' => $with_trusted_device
            ]);
        }
    }


    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        $login = request()->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'login';
        request()->merge([$field => $login]);
        return $field;
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    private function comparePasswordWithPasswordPolicy($request)
    {
        $password_pattern = $this->getPasswordPolicy(Auth::user())->pattern;
        $is_it = preg_match("/^{$password_pattern}$/", $request->password);

        $password_change_days = Setting::getValue(2, Auth::user()->enterprise_id, 'password_change_days');
        $password_last_change = Setting::getValue(3, Auth::user()->id, 'date_last_change_password');
        $has_password_expired = $password_last_change - strtotime("-{$password_change_days}days") < 0 ;
        if (!$is_it or $has_password_expired) {
            session(['password_need_to_change' => true]);
        }
    }

    private function getPasswordPolicy($user)
    {
        $password_policy_id = Setting::getValue(3, $user->id, 'password_policy_id') ?:
            Setting::getValue(2, $user->enterprise_id, 'password_policy_id');

        return PasswordPolicy::find($password_policy_id);
    }

    private function hasBan($request, $user_id)
    {
        $date_end_ban = Setting::getValue(3, $user_id, 'date_end_ban');
        if ($date_end_ban) {
            $is_end = $date_end_ban - strtotime('now') < 0;
            if ($is_end) {
                Setting::updateValue(3, $user_id, 'date_end_ban', 0);
                return false;
            }
            return $date_end_ban;
        }
        return false;
    }

    private function checkCountLoginAttempts($ent_id, $user_id)
    {
        $ent_settings = Setting::where('type', 2)
            ->where('item_id', $ent_id)
            ->where(function ($q) {
                $q->where('key', 'max_login_attempts')
                    ->orWhere('key', 'max_login_period')
                    ->orWhere('key', 'max_hours_ban');
            })
            ->pluck('value', 'key')->toArray();

        $date = date('Y-m-d H:i:s', strtotime("-{$ent_settings['max_login_period']}min"));

        $count = LoginStat::where('enterprise_id', $ent_id)
            ->where('user_id', $user_id)
            ->where('created_at', '>', $date)
            ->where('is_ok', 0)
            ->count();

        if ($count >= $ent_settings['max_login_attempts']) {
            $date_end_ban = strtotime("+{$ent_settings['max_hours_ban']}hours");
            Setting::updateValue(3, $user_id, 'date_end_ban', $date_end_ban);
            //TODO send email
            $from_email = 'no-reply@domain.com';
            $to_email = User::where('id', $user_id)->value('email');
            $subject = "Account ban";
            $data = "After " . $ent_settings['max_login_attempts'] . " wrong login attempts your account was baned" .
                " You can try again after " . $ent_settings['max_hours_ban'] . 'hours' .
                " If you did not attempt to log in, contact the administrator";
            $data = base64_encode($data);
            EmailStat::logEmail($ent_id, $user_id, $from_email, $to_email, $subject, $data);
        }
    }

    private function picturesGridGenerate()
    {
        $categories_id = Setting::getValue(3, Auth::user()->id, 'auth_category_id');
        if (!$categories_id) {
            Session::put('need_to_select_categories', true);
            return false;
        }
        $categories_ids = explode(', ', $categories_id);
        $categories_grid = [];
        $count_cat = 0;
        for ($i = 0; $i < random_int(1, 3); $i++) {
            $categories_grid[] = +$categories_ids[random_int(0, 2)];
            $count_cat++;
        }
        Session::put(['categories_user' => compact('count_cat', 'categories_ids')]);
        while ($count_cat < 9) {
            $rand_id = random_int(1, 24);
            if (in_array($rand_id, $categories_ids)) {
                continue;
            }
            $categories_grid[] = $rand_id;
            $count_cat++;
        }
        shuffle($categories_grid);
        session(['categories_grid' => $categories_grid]);
        return true;
    }

    private function wrongConfirmAttempt()
    {
        $old_value = Setting::getValue(3, Auth::user()->id, 'confirmation_attempt_count');
        if ($old_value > 1) {
            User::where('id', Auth::user()->id)->update(['is_active' => 0]);
            Setting::updateValue(3, Auth::user()->id, 'confirmation_attempt_count', 0);
            //TODO send email
            $from_email = 'no-reply@domain.com';
            $to_email = Auth::user()->email;
            $subject = "Account deactivation";
            $data = "After three wrong confirm attempts your account was deactivated" .
                        " To activate it, please contact your enterprise admin ";
            $data = base64_encode($data);
            EmailStat::logEmail(Auth::user()->enterprise_id, Auth::user()->id, $from_email, $to_email, $subject, $data);
            Auth::logout();
            return redirect()->back();
        }
        Setting::updateValue(3, Auth::user()->id, 'confirmation_attempt_count', $old_value + 1);
    }
}
