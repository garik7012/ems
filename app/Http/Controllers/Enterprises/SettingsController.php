<?php

namespace App\Http\Controllers\Enterprises;

use App\Theme;
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
        $enSec = Setting::where('type', 2)->where('item_id', $enterprise->id)->pluck('value', 'key');
        $auth_types = AuthType::all();
        $password_policies = PasswordPolicy::orderBy('id')->get();
        return view('enterprise.security', compact('enterprise', 'enSec', 'auth_types', 'password_policies'));
    }

    public function setSecurity($namespace, Request $request)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->first();
        Setting::setEnterpriseSecurity($enterprise->id, $request);
        return $this->getSecurity($namespace);
    }

    public function theme($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        if ($request->isMethod('post')) {
            $hex_pattern = '^#?([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$';
            $this->validate($request, [
               'main_background' => ["regex:/${hex_pattern}/"],
               'side_background' => ["regex:/${hex_pattern}/"]
            ]);
            Theme::updateValue($ent_id, 'main_background', $request->main_background);
            Theme::updateValue($ent_id, 'side_background', $request->side_background);
            return back();
        }
        $theme = Theme::where('enterprise_id', $ent_id)->pluck('value', 'key');
        return view('enterprise.theme', compact('theme'));
    }

}
