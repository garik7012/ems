<?php

namespace App\Http\Controllers\Enterprises;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use App\User;
use Auth;

class DepartmentsController extends Controller
{
    public function create($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->first();
        $user = Auth::user();
        if($user->enterprise_id != $enterprise->id){
            abort('403');
        }
        return view('department.create', ['enterprise' => $enterprise]);
    }

    public function showList($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->first();
        $user = Auth::user();
        if($user->enterprise_id != $enterprise->id){
            abort('403');
        }
        return view('department.list', ['enterprise' => $enterprise]);
    }
}
