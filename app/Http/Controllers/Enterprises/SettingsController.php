<?php

namespace App\Http\Controllers\Enterprises;

use App\File;
use App\Theme;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use App\Enterprise;
use App\AuthType;
use App\PasswordPolicy;
use Auth;
use Storage;

class SettingsController extends Controller
{
    public function getSecurity($namespace)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $enSec = Setting::where('type', 2)->where('item_id', $ent_id)->pluck('value', 'key');
        $auth_types = AuthType::all();
        $password_policies = PasswordPolicy::orderBy('id')->get();
        return view('enterprise.security', compact('enSec', 'auth_types', 'password_policies'));
    }

    public function setSecurity($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        Setting::setEnterpriseSecurity($ent_id, $request);
        return back()->with(['success' => true]);
    }

    public function showSettings($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $theme = Theme::where('enterprise_id', $ent_id)->pluck('value', 'key');
        return view('enterprise.settings', compact('theme'));
    }

    public function theme($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $hex_pattern = '^#?([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$';
        $this->validate($request, [
            'main_background' => ["regex:/${hex_pattern}/"],
            'side_background' => ["regex:/${hex_pattern}/"]
        ]);
        Theme::updateValue($ent_id, 'main_background', $request->main_background);
        Theme::updateValue($ent_id, 'side_background', $request->side_background);
        return back()->with(['success' => true]);
    }

    public function logo($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        if ($request->file('ent_logo')) {
            $this->validate($request, [
                'ent_logo' => 'image|max:1024'
            ]);
            $file_name = $ent_id . '.' . $request->file('ent_logo')->extension();
            $logo = File::where('enterprise_id', $ent_id)->where('file_type_id', 1)->first();
            if ($logo != null) {
                Storage::delete($logo->file_path);
            } else {
                $logo = new File();
            }
            $path = Storage::putFileAs('logos', $request->file('ent_logo'), $file_name);
            $logo->enterprise_id = $ent_id;
            $logo->file_name = $file_name;
            $logo->file_mime_type = $request->file('ent_logo')->getMimeType();
            $logo->file_size = $request->file('ent_logo')->getSize();
            $logo->file_path = $path;
            $logo->file_type_id = 1;
            $logo->user_id = Auth::user()->id;
            $logo->save();
        }
        return back()->with(['success' => true]);
    }

    public function saveSettings($namespace, Request $request)
    {
        $this->validate($request, [
            'ent_name' => 'required|max:255',
            'description' => 'required|max:255'
        ]);
        Enterprise::whereNamespace($namespace)->update([
           'name' => $request->ent_name,
            'description' => $request->description
        ]);
        return back()->with(['success' => true]);
    }
}
