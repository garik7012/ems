<?php

namespace App\Http\Controllers\Enterprises;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use App\Enterprise;
use App\AuthType;
use App\PasswordPolicy;
use App\User;

class SettingsController extends Controller
{
    public function getSecurity($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->first();
        $enterpriseSecurity = Setting::where('type', 2)->where('item_id', $enterprise->id)->pluck('value', 'key');
        $auth_types = AuthType::all();
        $password_policies = PasswordPolicy::orderBy('id')->get();
        return view('enterprise.security', array(
            'enterprise'=> $enterprise,
            'enSec' => $enterpriseSecurity,
            'auth_types' => $auth_types,
            'password_policies' => $password_policies)
        );
    }

    public function setSecurity($namespace, Request $request)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->first();
        Setting::setEnterpriseSecurity($enterprise->id, $request);
        return $this->getSecurity($namespace);
    }

}
