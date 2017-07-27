<?php

namespace App\Http\Controllers\Enterprises;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use App\Enterprise;
use App\User;

class SettingsController extends Controller
{
    public function getEnterpriseSecuritySettings($namespace){
        $enterprise = Enterprise::where('namespace', $namespace)->first();
        $enterpriseSecurity = Setting::where('type', 2)->where('item_id', $enterprise->id)->pluck('value', 'key');
        return view('enterprise.security', ['enterprise'=> $enterprise, 'enSec' => $enterpriseSecurity]);
    }

    public function setEnterpriseSecuritySettings($namespace, Request $request){
        $enterprise = Enterprise::where('namespace', $namespace)->first();
        Setting::setEnterpriseSecurity($enterprise->id, $request);
        $enterpriseSecurity = Setting::where('type', 2)->where('item_id', $enterprise->id)->pluck('value', 'key');
        return view('enterprise.security', ['enterprise'=> $enterprise, 'enSec' => $enterpriseSecurity]);
    }

}
