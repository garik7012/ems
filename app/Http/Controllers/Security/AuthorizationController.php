<?php

namespace App\Http\Controllers\Security;

use App\Enterprise;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Setting;
use App\PasswordPolicy;
use Illuminate\Support\Facades\Session;

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
        $security_code = Setting::where('type',3)
            ->where('item_id', Auth::user()->id)
            ->where('key', 'confirmation_code')
            ->value('value');
        if ($request->confirm == $security_code) {
            Setting::where('type',3)
                ->where('item_id', Auth::user()->id)
                ->where('key', 'confirmation_code')
                ->update(['value'=>""]);

            $namespace = Enterprise::where('id', Auth::user()->enterprise_id)->value('namespace');

            return redirect("/e/{$namespace}");
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
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

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

        return redirect("/e/{$namespace}");
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
            $this->credentials($request), $request->has('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
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

        $this->clearLoginAttempts($request);
        
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
        $auth_type = Setting::where('type',2)
            ->where('item_id', Auth::user()->enterprise_id)
            ->where('key', 'auth_type_id')
            ->value('value');

        if($auth_type != 1){
            $security_code = str_random(8);
            Setting::where('type',3)
                ->where('item_id', Auth::user()->id)
                ->where('key', 'confirmation_code')
                ->update(['value'=>$security_code]);

            $is_sms = Setting::where('type',2)
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
        return redirect("/e/{$request->route('namespace')}");
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
        return 'email';
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
        //get enterprise's password policy
        $password_policy_id = Setting::where('type', 2)
            ->where('item_id', $user->enterprise_id)
            ->where('key', 'password_policy_id')
            ->value('value');
        $password_policy = PasswordPolicy::find($password_policy_id);
        //TODO get user's password policy
        return $password_policy;
    }
}
