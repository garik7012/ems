<?php

namespace App\Http\Controllers\Enterprises;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Enterprise;
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
            'e_namespace' => 'required|max:15',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $enterprise = new Enterprise;
        $enterprise->name = $request->e_name;
        $enterprise->namespace = $request->e_namespace;
        $enterprise->description = $request->e_description;
        $enterprise->is_active = 1;
        $enterprise->save();

        $user = new User;
        $user->enterprise_id = $enterprise->id;
        $user->login = $request->login;
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect('/enterprises');
    }

    public function showEnterprise($namespace){
        $enterprise = Enterprise::where('namespace', $namespace)->first();
        $user = Auth::user();
        if($user and $user->enterprise_id != $enterprise->id){
            return view('enterprise.forbiden');
        }
        return view('enterprise.show', ['enterprise' => $enterprise]);
    }
}
