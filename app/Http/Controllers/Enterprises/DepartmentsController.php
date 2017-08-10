<?php

namespace App\Http\Controllers\Enterprises;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use App\User;
use Auth;
use App\Department;

class DepartmentsController extends Controller
{
    public function create($namespace, Request $request)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        if ($request->isMethod('post')) {
            $this->validateRequest($request);
            $branch = new Department();
            $this->saveDepartment($branch, $request, $ent_id);
            return redirect()->back();
        }
        $departments= Department::where('enterprise_id', $ent_id)->where('is_active', 1)->get();
        return view('department.create', compact('departments'));
    }

    public function showList($namespace, Request $request)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        if ($request->has_item_id) {
            $departments = Department::where('enterprise_id', $ent_id)->whereIn('id', $request->has_item_id)->get();
        } else {
            $departments = Department::where('enterprise_id', $ent_id)->orderBy('id')->get();
        }
        return view('department.list', compact('departments'));
    }

    public function edit($namespace, $id, Request $request)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        $department = Department::where('id', $id)->where('enterprise_id', $ent_id)->firstOrFail();
        if ($request->isMethod('post')) {
            $this->validateRequest($request);
            $this->saveDepartment($department, $request, $ent_id);
        }
        $departments = Department::where('enterprise_id', $ent_id)->orderBy('id')->get();
        return view('department.edit', compact('department', 'departments'));
    }

    public function activate($namespace, $id)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        $department = Department::where('id', $id)->where('enterprise_id', $ent_id)->where('is_active', 0)->firstOrFail();
        $department->is_active = 1;
        $department->save();
        return back();
    }

    public function deactivate($namespace, $id)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        $department = Department::where('id', $id)->where('enterprise_id', $ent_id)->where('is_active', 1)->firstOrFail();
        $department->is_active = 0;
        $department->save();
        return back();
    }

    private function validateRequest($request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);
    }

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }

    private function saveDepartment(&$department, $request, $ent_id)
    {
        $department->name = $request->name;
        $department->enterprise_id = $ent_id;
        $department->parent_id = $request->parent_id;
        $department->description = $request->description;
        $department->save();
    }
}
