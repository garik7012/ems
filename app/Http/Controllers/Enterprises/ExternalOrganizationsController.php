<?php

namespace App\Http\Controllers\Enterprises;

use App\Theme;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use App\Setting;
use Illuminate\Support\Facades\Session;

class ExternalOrganizationsController extends Controller
{
    public function showList($namespace)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $externals = Enterprise::where('parent_id', $ent_id)->get();
        return view('external.list', compact('externals'));
    }

    public function create($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        if (Enterprise::find($ent_id)->parent_id) {
            //forbidden to create an external organization if you are one
            abort('403');
        }
        if ($request->isMethod('post')) {
            $ext_namespace = "{$namespace}_" . $request->namespace;
            $this->validate($request, [
               'name' => 'required',
               'namespace' => 'required',
               'description' => 'required',
            ]);
            if (Enterprise::where('namespace', $ext_namespace)->count()) {
                return back()->withErrors(['namespace' => "this namespace already exists"])->withInput();
            }
            $external = new Enterprise();
            $external->name = $request->name;
            $external->description = $request->description;
            $external->namespace = $ext_namespace;
            $external->is_active = 1;
            $external->parent_id = $ent_id;
            $external->save();

            Setting::setDefaultEnterpriseSettings($external->id);
            Theme::setDefaultSettings($external->id);
            return redirect(config('ems.prefix') . "{$namespace}/Enterprises/ExternalOrganizations/showList");
        }
        return view('external.create');
    }

    public function activate($namespace, $external_id)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $external = Enterprise::where('is_active', 0)
            ->where('parent_id', $ent_id)
            ->where('id', $external_id)
            ->firstOrFail();
        $external->is_active = 1;
        $external->save();
        return back();
    }

    public function deactivate($namespace, $external_id)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $external = Enterprise::where('is_active', 1)
            ->where('parent_id', $ent_id)
            ->where('id', $external_id)
            ->firstOrFail();
        $external->is_active = 0;
        $external->save();
        return back();
    }

    public function edit($namespace, $external_id, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $external = Enterprise::where('parent_id', $ent_id)
            ->where('id', $external_id)
            ->firstOrFail();
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
            ]);
            $external->name = $request->name;
            $external->description = $request->description;
            $external->save();
            return back();
        }

        return view('external.edit', compact('external'));
    }

    public function addUser($namespace, $external_id, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $external = Enterprise::where('parent_id', $ent_id)
            ->where('id', $external_id)
            ->firstOrFail();
        if ($request->isMethod('post')) {
            return back();
        }

        return view('external.addUser', compact('external'));
    }

    public function goToExt($namespace, $ext_id)
    {
        $ext_namespace = Enterprise::where('id', $ext_id)->value('namespace');
        Session::put('old_adm_namespace', $namespace);
        return redirect(config('ems.prefix') . "{$ext_namespace}/");
    }
}
