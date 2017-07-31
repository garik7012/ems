<?php

namespace App\Http\Controllers\Enterprises;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Enterprise;
use App\Setting;
use Auth;
use Hash;
use Session;

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
            'namespace' => 'required|max:25|unique:enterprises',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'unique:users'
        ]);
        $enterprise = new Enterprise;
        $enterprise->name = $request->e_name;
        $enterprise->namespace = $request->namespace;
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
        $user->is_active = 1;
        $user->password = bcrypt($request->password);
        $user->save();

        User::setDefaultAdminSettings($user->id);
        Auth::logout();
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

        //TODO Send email to user with confirm link

        return view('enterprise.user.success', ['confirm'=> "{$_SERVER['SERVER_NAME']}/security/confirm/{$new_user_id}/{$confirm}"]);
    }

    public function showUsers($namespace)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        $ent_users = User::where('enterprise_id', $ent_id)->orderBy('id')->paginate(5);
        return view('enterprise.user.list', ['ent_users' => $ent_users]);
    }

    public function loginAsUser($namespace, $user_id)
    {
        $user = User::findOrFail($user_id);

        if (!$user->is_superadmin)
        {
            Session::put('auth_from_admin_asd',Auth::user()->id);
            Auth::loginUsingId($user_id);

            return redirect("/e/{$namespace}");
        }
        abort(403);
    }

    public function userProfile($namespace)
    {
        $this->shareEnterpriseToView($namespace);
        return view('enterprise.user.profile', ['user'=>Auth::user()]);
    }

    public function editUserProfile($namespace, Request $request)
    {
       $this->shareEnterpriseToView($namespace);
        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'login' => 'required|max:50',
            'phone_number' => 'required|max:50',
            'date_born' => 'required|date',
            'password' => 'required|string|min:6'
        ]);

        $user = Auth::user();
        if(Hash::check($request->password, $user->password)){
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
            return redirect()->back();
        }
        return redirect()->back()->withErrors(['password' => 'wrong password']);
    }

    public function backToAdmin($namespace)
    {
        if (!Session::has('auth_from_admin_asd'))
        {
            abort(404);
        }
        Auth::loginUsingId((int) Session::get('auth_from_admin_asd'));
        Session::forget('auth_from_admin_asd');
        if (! Auth::user()->is_superadmin)
        {
            Auth::logout();
            return redirect("/e/$namespace");
        }
        $this->shareEnterpriseToView($namespace);
        return redirect("/e/$namespace/user/list");
    }



    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }

}
