<?php

namespace App\Http\Controllers\Enterprises;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Enterprise;
use Auth;
use Session;

class EnterpriseController extends Controller
{

    public function showEnterprise($namespace)
    {
        Enterprise::shareEnterpriseToView($namespace);
        if (Session::has('old_adm_namespace') and session('old_adm_namespace') == $namespace) {
            Session::forget('old_adm_namespace');
        }
        return view('enterprise.show');
    }

    public function backToAdmin($namespace)
    {
        if (!Session::has('auth_from_admin_asd')) {
            abort(404);
        }
        Auth::loginUsingId((int) Session::get('auth_from_admin_asd'));
        Session::forget('auth_from_admin_asd');

        Enterprise::shareEnterpriseToView($namespace);
        return redirect(config('ems.prefix') . "$namespace/");
    }
}
