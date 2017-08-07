<?php

namespace App\Http\Controllers\Enterprises;

use App\AuthType;
use App\PasswordPolicy;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Enterprise;

class UsersController extends Controller
{
    public function showUserSettings($namespace, $user_id)
    {
        $user = [];
        $user['user_id'] = $user_id;
        $user['auth_type_id'] = Setting::where('type', 3)
            ->where('item_id', $user_id)
            ->where('key', 'auth_type_id')
            ->value('value');
        $user['password_policy_id'] = Setting::where('type', 3)
            ->where('item_id', $user_id)
            ->where('key', 'password_policy_id')
            ->value('value');

        $password_policies = PasswordPolicy::all();
        $auth_types = AuthType::all();
        $this->shareEnterpriseToView($namespace);
        return view('enterprise.user.settings', compact('user', 'password_policies', 'auth_types'));
    }

    public function changeUsersSettings($namespace, Request $request)
    {
        $user_enterprise_id = User::where('id', $request->user_id)->value('enterprise_id');
        $ent_id = $this->shareEnterpriseToView($namespace);
        if ($user_enterprise_id != $ent_id) {
            abort('403');
        }
        Setting::where('type', 3)
            ->where('item_id', $request->user_id)
            ->where('key', 'password_policy_id')
            ->update(['value' => $request->password_policy_id]);
        Setting::where('type', 3)
            ->where('item_id', $request->user_id)
            ->where('key', 'auth_type_id')
            ->update(['value' => $request->auth_type_id]);

        return redirect()->back();
    }

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
