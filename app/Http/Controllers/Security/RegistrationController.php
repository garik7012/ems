<?php

namespace App\Http\Controllers\Security;

use App\EmailStat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use App\User;
use App\Enterprise;
use App\PasswordPolicy;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RegistrationController extends Controller
{
    public function createUserByAdmin($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        if ($request->has('external_id')) {
            $ext = Enterprise::where('parent_id', $ent_id)
                ->where('id', $request->external_id)
                ->firstOrFail();
            $ent_id = $ext->id;
            $namespace = $ext->namespace;
        }
        $this->validate($request, [
            'email' => 'unique:users',
            'login' => 'unique:users',
        ]);
        $new_user_id = User::createNewUserByAdmin($request, $ent_id);
        $confirm = Setting::where('type', 3)
            ->where('item_id', $new_user_id)
            ->where('key', 'confirmation_code')
            ->value('value');

        //TODO Send email to user with confirm link
        $link = "{$_SERVER['SERVER_NAME']}" . config('ems.prefix') .
            "$namespace/security/confirm/{$new_user_id}/{$confirm}";
        $data = base64_encode("To complete your registration please <a href='{$link}'>Click here</a>");
        EmailStat::logEmail($ent_id, $new_user_id, 'no-reply@domain.com', $request->email, 'confirm email', $data);
        return view('enterprise.user.success', ['confirm' => $link]);
    }

    public function confirmEmail($namespace, $user_id, $pass)
    {
        if (Auth::user()) {
            Auth::logout();
        }
        $user_pass = Setting::where('type', 3)
            ->where('item_id', $user_id)
            ->where('key', 'confirmation_code')
            ->value('value');
        if ($user_pass and $user_pass == $pass) {
            $user = User::findOrFail($user_id);
            Setting::where('type', 3)
                ->where('item_id', $user_id)
                ->where('key', 'is_email_confirmed')
                ->update(['value' => 1]);
            $password_policy = $this->getPasswordPolicy($user);
            Enterprise::shareEnterpriseToView($namespace);
            return view('enterprise.user.confirmed', compact('user', 'password_policy', 'pass'));
        }
        abort('404');
    }

    public function finishRegistration(Request $request)
    {
        $user_id = +$request->user_id;
        $user_pass = Setting::where('type', 3)
            ->where('item_id', $user_id)
            ->where('key', 'confirmation_code')
            ->value('value');
        if ($user_pass and $user_pass == $request->pass) {
            $user = User::findOrFail($request->user_id);
            $password_policy = $this->getPasswordPolicy($user);
            $password_pattern = $password_policy->pattern;
            $login_validate = 'required|alpha_dash|unique:users|max:50';
            if ($user->login == $request->login) {
                $login_validate = 'required';
            }
            $this->validate($request, [
                'first_name' => 'required|max:50',
                'last_name' => 'required|max:50',
                'login' => $login_validate,
                'phone_number' => 'required|max:50',
                'date_born' => 'required|date',
                'password' => "required|string|regex:/${password_pattern}/|confirmed"
            ]);
            $user = User::findOrFail($request->user_id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->login = $request->login;
            $user->phone_number = $request->phone_number;
            $user->password = bcrypt($request->password);
            $user->date_born = $request->date_born;
            $user->is_active = 1;
            $user->save();
            Setting::where('type', 3)
                ->where('item_id', $user->id)
                ->where('key', 'confirmation_code')
                ->update(['value' => '']);
            Setting::where('type', 3)
                ->where('item_id', $user->id)
                ->where('key', 'date_last_change_password')
                ->update(['value' => strtotime('now')]);
            $ent = Enterprise::where('id', $user->enterprise_id)->value('namespace');
            return redirect(config('ems.prefix') . "{$ent}/login");
        }
        abort('403');
    }

    public function showChangePasswordForm($namespace)
    {
        Enterprise::shareEnterpriseToView($namespace);
        $password_policy = $this->getPasswordPolicy(Auth::user());
        return view('security.changePassword', compact('password_policy'));
    }

    public function changePassword($namespace, Request $request)
    {
        $user = User::find(Auth::user()->id);
        if (Hash::check($request->old_password, $user->password)) {
            $password_policy = $this->getPasswordPolicy($user);
            $password_pattern = $password_policy->pattern;
            $this->validate($request, [
                'password' => "required|string|regex:/${password_pattern}/|confirmed"
            ]);
            $user->password = bcrypt($request->password);
            $user->save();
            Setting::where('type', 3)
                ->where('item_id', $user->id)
                ->where('key', 'date_last_change_password')
                ->update(['value' => strtotime('now')]);

            $request->session()->forget('password_need_to_change');
            Enterprise::shareEnterpriseToView($namespace);
            return redirect(config('ems.prefix') . "{$namespace}/");
        }
        return redirect()->back()->withErrors(['old_password' => 'wrong password']);
    }

    public function selectCategories($namespace, Request $request)
    {
        if (!session('need_to_select_categories')) {
            abort('404');
        }
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'first_cat' => 'required|different:third_cat|numeric|min:1|max:24',
                'second_cat' => 'required|different:first_cat|numeric|min:1|max:24',
                'third_cat' => 'required|different:second_cat|numeric|min:1|max:24'
            ]);
            $cat_ids = $request->first_cat . ', ' . $request->second_cat . ', ' . $request->third_cat;
            Setting::updateValue(3, Auth::user()->id, 'auth_category_id', $cat_ids);
            Session::forget('need_to_select_categories');
            return redirect(config('ems.prefix') . "{$namespace}/");
        }
        Enterprise::shareEnterpriseToView($namespace);
        return view('security.selectCategories');
    }

    public function changeUserCategories($namespace, Request $request)
    {
        //
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
}
