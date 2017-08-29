<?php

namespace App\Http\Controllers\Users;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use Auth;

class DashboardController extends Controller
{
    public function show($namespace)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $is_supervisor = User::where('parent_id', Auth::user()->id)->count();
        return view('user.dashboard', compact('is_supervisor'));
    }
}
