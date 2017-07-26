<?php

namespace App\Http\Controllers\Enterprises;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use App\User;
use Auth;

class BranchesController extends Controller
{

    public function create($namespace)
    {
        if(Auth::guest()){
            return redirect("/e/{$namespace}");
        }
        $enterprise = Enterprise::where('namespace', $namespace)->first();
        $user = Auth::user();
        //dd($user, $enterprise);
        if($user->enterprise_id != $enterprise->id){
            dd($user, $enterprise);
            return view('enterprise.forbiden');
        }

        return view('branch.create', ['enterprise' => $enterprise]);
    }
}
