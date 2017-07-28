<?php

namespace App\Http\Controllers\Enterprises;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Enterprise;
use App\Setting;
use Auth;

class EnterpriseController extends Controller
{
    public function welcome()
    {
        $list = Enterprise::all();
        return view('enterprise.welcome', ['enterprises' => $list]);
    }

    public function registration()
    {
        return view('enterprise.registration');
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'namespace' => 'required|max:15|unique:enterprises',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'unique:users'
        ]);
        $enterprise = new Enterprise;
        $enterprise->name = $request->e_name;
        $enterprise->namespace = $request->e_namespace;
        $enterprise->description = $request->e_description;
        $enterprise->is_active = 1;
        $enterprise->save();

        Setting::setDefaultEnterpriseSettings($enterprise->id);

        $user = new User;
        $user->enterprise_id = $enterprise->id;
        $user->login = $request->login;
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->is_superadmin = 1;
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect('/enterprises');
    }

    public function showEnterprise($namespace)
    {
        $this->shareEnterpriseToView($namespace);
        return view('enterprise.show');
    }

    public function loginEnterprise($namespace)
    {
        $this->shareEnterpriseToView($namespace);
        return view('enterprise.show');
    }

    public function createUser($namespace)
    {
        $this->shareEnterpriseToView($namespace);
        return view('enterprise.user.create');
    }

    public function createUserByAdmin($namespace, Request $request)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        $new_user_id = User::createNewUserByAdmin($request, $ent_id);
        $confirm = Setting::where('type', 3)
            ->where('item_id', $new_user_id)
            ->where('key', 'confirmation_code')
            ->value('value');
        return view('enterprise.user.success', ['confirm'=> "{$_SERVER['SERVER_NAME']}/security/confirm/{$new_user_id}/{$confirm}"]);
    }




    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->first();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }

}
