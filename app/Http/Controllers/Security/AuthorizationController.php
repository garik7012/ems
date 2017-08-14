<?php

namespace App\Http\Controllers\Security;

use App\Enterprise;
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
        $this->shareEnterpriseToView($namespace);
        return view('enterprise.login');
    }

    public function checkConfirmCode(Request $request)
    {
        $security_code = Setting::where('type', 3)
            ->where('item_id', Auth::user()->id)
            ->where('key', 'confirmation_code')
            ->value('value');
        if ($request->confirm == $security_code) {
            Setting::where('type', 3)
                ->where('item_id', Auth::user()->id)
                ->where('key', 'confirmation_code')
                ->update(['value'=>""]);

            $namespace = Enterprise::where('id', Auth::user()->enterprise_id)->value('namespace');

            return redirect(config('ems.prefix') . "$namespace");
        }
        //TODO log not confirm attempts
        Auth::logout();
        return redirect()->back();
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
        $user_id = User::where($this->username(), $request->login)->value('id');
        $ent_id = Enterprise::where('namespace', $request->route('namespace'))->value('id');
        $this->validateLogin($request);
        $logs = new LoginStat();
        $logs->enterprise_id = $ent_id;
        $logs->user_id = $user_id;
        $logs->is_ok = 0;
        $logs->login = $request->login;
        $logs->ip = $request->ip();
        $logs->user_agent = base64_encode($request->header('User-Agent'));
        $logs->created_at = date('Y-m-d H:i:s');
        $logs->save();

        $end_ban = $this->hasBan($request, $user_id);
        if ($end_ban) {
            $hours_mins_left = date('H \h i \m\i\n', $end_ban - strtotime('now'));
            return back()->withErrors(['login' => "This user has ban. Please try again in $hours_mins_left"]);
        }

        if ($this->attemptLogin($request)) {
            $logs->is_ok = 1;
            $logs->save();
            return $this->sendLoginResponse($request);
        }
        if ($user_id) {
            $this->checkCountLoginAttempts($ent_id, $user_id);
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
        if (!$this->isUserActive($request)) {
            Auth::logout();
            return back()->withErrors(['login' => 'this user is not active']);
        }
        $auth_type = Setting::where('type', 3)
            ->where('item_id', Auth::user()->id)
            ->where('key', 'auth_type_id')
            ->value('value');
        if (!$auth_type) {
            $auth_type = Setting::where('type', 2)
                ->where('item_id', Auth::user()->enterprise_id)
                ->where('key', 'auth_type_id')
                ->value('value');
        }
        if ($auth_type != 1) {
            $security_code = str_random(8);
            Setting::where('type', 3)
                ->where('item_id', Auth::user()->id)
                ->where('key', 'confirmation_code')
                ->update(['value'=>$security_code]);

            $is_sms = Setting::where('type', 2)
                ->where('item_id', Auth::user()->enterprise_id)
                ->where('key', 'is_sms_allow')
                ->value('value');

            if ($is_sms) {
                //TODO send sms
                return redirect()->back()->with('security_code', "SMS. Code: $security_code");
            } else {
                //TODO send email
                return redirect()->back()->with('security_code', "Email. Code: $security_code");
            }
        }

        return redirect(config('ems.prefix') . "{$request->route('namespace')}");
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

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }

    private function comparePasswordWithPasswordPolicy($request)
    {
        $password_pattern = $this->getPasswordPolicy(Auth::user())->pattern;
        $is_it = preg_match("/^{$password_pattern}$/", $request->password);

        $password_change_days = Setting::where('type', 2)
            ->where('item_id', Auth::user()->enterprise_id)
            ->where('key', 'password_change_days')
            ->value('value');
        $password_last_change = Setting::where('type', 3)
            ->where('item_id', Auth::user()->id)
            ->where('key', 'date_last_change_password')
            ->value('value');
        $has_password_expired = $password_last_change - strtotime("-{$password_change_days}days") < 0 ;
        if (!$is_it or $has_password_expired) {
            session(['password_need_to_change' => true]);
        }
    }

    private function getPasswordPolicy($user)
    {
        $password_policy_id = Setting::where('type', 3)
            ->where('item_id', $user->id)
            ->where('key', 'password_policy_id')
            ->value('value');
        if (!$password_policy_id) {
            $password_policy_id = Setting::where('type', 2)
                ->where('item_id', $user->enterprise_id)
                ->where('key', 'password_policy_id')
                ->value('value');
        }
        $password_policy = PasswordPolicy::find($password_policy_id);

        return $password_policy;
    }

    private function isUserActive($request)
    {
        if (!Auth::user()->is_active) {
            return false;
        }
        return true;
    }

    private function hasBan($request, $user_id)
    {
        $date_end_ban = Setting::where('type', 3)
            ->where('item_id', $user_id)
            ->where('key', 'date_end_ban')
            ->value('value');
        if ($date_end_ban) {
            $is_end = $date_end_ban - strtotime('now') < 0;
            if ($is_end) {
                Setting::where('type', 3)->where('item_id', $user_id)->where('key', 'date_end_ban')->update(['value' => 0]);
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
            Setting::where('type', 3)
                ->where('item_id', $user_id)
                ->where('key', 'date_end_ban')
                ->update(['value' => $date_end_ban]);
        }
    }
}
