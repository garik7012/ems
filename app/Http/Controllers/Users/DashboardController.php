<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;

class DashboardController extends Controller
{
    public function show($namespace)
    {
        $this->shareEnterpriseToView($namespace);
        return view('enterprise.user.dashboard');
    }


    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
