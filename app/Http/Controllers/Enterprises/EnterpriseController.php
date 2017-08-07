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

    public function createUser($namespace)
    {
        $this->shareEnterpriseToView($namespace);
        return view('enterprise.user.create');
    }


    public function showUsers($namespace)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        $ent_users = User::where('enterprise_id', $ent_id)->orderBy('id')->paginate(5);
        return view('enterprise.user.list', compact('ent_users'));
    }

    public function loginAsUser($namespace, $user_id)
    {
        $user = User::findOrFail($user_id);

        if (!$user->is_superadmin) {
            Session::put('auth_from_admin_asd', Auth::user()->id);
            Auth::loginUsingId($user_id);

            return redirect(config('ems.prefix') . "{$namespace}");
        }
        abort(403);
    }

    public function backToAdmin($namespace)
    {
        if (!Session::has('auth_from_admin_asd')) {
            abort(404);
        }
        Auth::loginUsingId((int) Session::get('auth_from_admin_asd'));
        Session::forget('auth_from_admin_asd');
        if (! Auth::user()->is_superadmin) {
            Auth::logout();
            return redirect(config('ems.prefix') . "$namespace");
        }
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
