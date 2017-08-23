<?php

namespace App\Http\Controllers\Enterprises;

use App\AuthType;
use App\PasswordPolicy;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Enterprise;
use Session;
use Auth;

class UsersController extends Controller
{
    public function showList($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        if ($request->has_item_id) {
            $ent_users = User::where('enterprise_id', $ent_id)->whereIn('id', $request->has_item_id)->paginate(25);
        } else {
            $ent_users = User::where('enterprise_id', $ent_id)->orderBy('id')->paginate(25);
        }
        return view('enterprise.user.list', compact('ent_users'));
    }

    public function createUser($namespace)
    {
        Enterprise::shareEnterpriseToView($namespace);
        return view('enterprise.user.create');
    }

    public function loginAsUser($namespace, $user_id)
    {
        $user = User::findOrFail($user_id);

        if (Auth::user()->is_superadmin or !$user->is_superadmin) {
            Session::put('auth_from_admin_asd', Auth::user()->id);
            Auth::loginUsingId($user_id);

            return redirect(config('ems.prefix') . "{$namespace}");
        }
        abort(403);
    }

    public function showUserSettings($namespace, $user_id)
    {
        $user = [];
        $user['user_id'] = $user_id;
        $user['auth_type_id'] = Setting::getValue(3, $user_id, 'auth_type_id');
        $user['password_policy_id'] = Setting::getValue(3, $user_id, 'password_policy_id');
        $user['expire_end_at'] = User::findOrFail($user_id)->expire_end_at;

        $password_policies = PasswordPolicy::all();
        $auth_types = AuthType::all();
        Enterprise::shareEnterpriseToView($namespace);
        return view('enterprise.user.settings', compact('user', 'password_policies', 'auth_types'));
    }

    public function changeUsersSettings($namespace, $user_id, Request $request)
    {
        $user_enterprise_id = User::where('id', $request->user_id)->value('enterprise_id');
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        if ($user_enterprise_id != $ent_id) {
            abort('403');
        }
        Setting::updateValue(3, $request->user_id, 'password_policy_id', $request->password_policy_id);
        Setting::updateValue(3, $request->user_id, 'auth_type_id', $request->auth_type_id);
        User::where('id', $request->user_id)->update(['expire_end_at' => $request->expire_end_at]);

        return redirect()->back();
    }

    public function showUserProfile($namespace, $user_id)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $user = User::where('id', $user_id)->where('enterprise_id', $ent_id)->firstOrFail();
        return view('enterprise.user.profile', compact('user'));
    }

    public function changeUserProfile($namespace, $user_id, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'phone_number' => 'required|max:50',
            'date_born' => 'required|date',
        ]);
        $user = User::where('id', $user_id)->where('enterprise_id', $ent_id)->firstOrFail();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone_number = $request->phone_number;
        $user->date_born = $request->date_born;
        $user->save();
        return back();
    }

    public function activate($n, $id)
    {
        $user = User::findOrFail($id);
        if (!$user->is_superadmin or Auth::user()->is_superadmin) {
            $user->is_active = 1;
            $user->save();
            return redirect()->back();
        }
        abort('403');
    }

    public function deactivate($n, $id)
    {
        $user = User::findOrFail($id);
        if (!$user->is_superadmin or Auth::user()->is_superadmin) {
            $user->is_active = 0;
            $user->save();
            return redirect()->back();
        }
        abort('403');
    }
}
