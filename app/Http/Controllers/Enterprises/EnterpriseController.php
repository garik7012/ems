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
        $this->shareEnterpriseToView($namespace);
        return view('enterprise.show');
    }

    public function backToAdmin($namespace)
    {
        if (!Session::has('auth_from_admin_asd')) {
            abort(404);
        }
        Auth::loginUsingId((int) Session::get('auth_from_admin_asd'));
        Session::forget('auth_from_admin_asd');

        $this->shareEnterpriseToView($namespace);
        return redirect(config('ems.prefix') . "$namespace/");
    }


    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }

}
