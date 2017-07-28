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
        $enterprise = Enterprise::where('namespace', $namespace)->first();
        $user = Auth::user();
        if($user->enterprise_id != $enterprise->id){
            abort('403');
        }
        return view('branch.create', ['enterprise' => $enterprise]);
    }

    public function showList($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->first();
        $user = Auth::user();
        if($user->enterprise_id != $enterprise->id){
            abort('403');
        }
        return view('branch.list', ['enterprise' => $enterprise]);
    }


}
