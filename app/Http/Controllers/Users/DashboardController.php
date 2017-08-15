<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;

class DashboardController extends Controller
{
    public function show($namespace)
    {
        Enterprise::shareEnterpriseToView($namespace);
        return view('user.dashboard');
    }
}
