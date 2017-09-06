<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Enterprise;
use App\Setting;
use Auth;
use App;

class CoreController extends Controller
{

    public function index()
    {
        return view('welcome');
    }
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
            'password' => 'required|string|min:8|confirmed',
            'email' => 'required|email|unique:users',
            'login' => 'required|alpha_dash|unique:users',
        ]);
        $enterprise = new Enterprise;
        $enterprise->name = $request->e_name;
        $enterprise->namespace = $request->namespace;
        $enterprise->description = $request->e_description;
        $enterprise->is_active = 1;
        $enterprise->save();

        Setting::setDefaultEnterpriseSettings($enterprise->id);
        App\Theme::setDefaultSettings($enterprise->id);

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
        return redirect(config('ems.prefix') . "$request->namespace");
    }

    public function goToEnterprise($ent_id)
    {
        $namespace = Enterprise::where('id', $ent_id)->value('namespace');
        return redirect(config('ems.prefix') . "{$namespace}");
    }

    public function callActionUrl($namespace, $module, $controller, $action, $parametr = false, Request $request)
    {
        if ($parametr) {
            return App::make("\App\Http\Controllers\\" . ucfirst($module) . "\\" . ucfirst($controller) ."Controller")
                ->callAction($action, [$namespace, $parametr, $request]);
        }
        return App::make("\App\Http\Controllers\\" . ucfirst($module) . "\\" . ucfirst($controller) ."Controller")
            ->callAction($action, [$namespace, $request]);
    }
}
