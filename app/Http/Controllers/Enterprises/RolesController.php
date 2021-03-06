<?php

namespace App\Http\Controllers\Enterprises;

use App\Action;
use App\RolesAndActions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use App\Role;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    public function showRoles($namespace)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $roles = Role::all();
        foreach ($roles as &$role) {
            $action_ids = RolesAndActions::where('role_id', $role->id)
                ->where('enterprise_id', $ent_id)
                ->select('action_id')
                ->get()
                ->toArray();
            $actions_arr = [];
            foreach ($action_ids as $actionId) {
                $action = Action::where('is_active', 1)->find($actionId['action_id']);
                $controller = DB::table('controllers')->find($action->controller_id);
                $module = DB::table('modules')->where('id', $controller->module_id)->value('name');
                $actions_arr[] = ['action_id' => $action->id,
                    'full_path' => $module . '\\' . $controller->name . '\\' . $action->name];
            }
            $role->actions = $actions_arr;
        }

        return view('roles.show', compact("roles"));
    }

    public function addNewRole($namespace, Request $request)
    {
        //save new role
        if ($request->isMethod('post')) {
            $ent_id = Enterprise::shareEnterpriseToView($namespace);
            $role = new Role;
            $role->name = $request->name;
            $role->description = $request->description;
            $role->enterprise_id = $ent_id;
            if (!$request->is_never_expires) {
                $this->validate($request, [
                    'expire_begin_at' => 'required',
                    'expire_end_at' => 'required'
                ]);
                $role->expire_begin_at = $request->expire_begin_at;
                $role->expire_end_at = $request->expire_end_at;
                $role->is_never_expires = 0;
            }
            $role->save();
            foreach ($request->actions as $action) {
                $role_action = new RolesAndActions;
                $role_action->role_id = $role->id;
                $role_action->enterprise_id = $ent_id;
                $role_action->action_id = $action;
                $role_action->save();
            }
            return redirect(config('ems.prefix') . "$namespace/Enterprises/Roles/ShowRoles");
        }

        //show creation form;
        $actions = $this->getActionsIdAndFullPath();
        Enterprise::shareEnterpriseToView($namespace);
        return view('roles.add', compact("actions"));
    }

    public function deactivate($namespace, $role_id)
    {
        $role = Role::findOrFail($role_id);
        $role->is_active = 0;
        $role->save();
        return redirect(config('ems.prefix') . "{$namespace}/Users/Roles/showRoles");
    }

    public function activate($namespace, $role_id)
    {
        $role = Role::findOrFail($role_id);
        $role->is_active = 1;
        $role->save();
        return redirect(config('ems.prefix') . "{$namespace}/Users/Roles/showRoles");
    }

    public function edit($namespace, $role_id, Request $request)
    {
        //edit role
        if ($request->isMethod('post')) {
            $ent_id = Enterprise::shareEnterpriseToView($namespace);
            $role = Role::where('enterprise_id', $ent_id)->where('id', $role_id)->firstOrFail();
            $role->name = $request->name;
            $role->description = $request->description;
            $role->enterprise_id = $ent_id;
            if (!$request->is_never_expires) {
                $this->validate($request, [
                    'expire_begin_at' => 'required',
                    'expire_end_at' => 'required'
                ]);
                $role->expire_begin_at = $request->expire_begin_at;
                $role->expire_end_at = $request->expire_end_at;
                $role->is_never_expires = 0;
            } else {
                $role->expire_begin_at = null;
                $role->expire_end_at = null;
                $role->is_never_expires = 1;
            }
            $role->save();
            RolesAndActions::where('enterprise_id', $ent_id)->where('role_id', $role_id)->delete();
            foreach ($request->actions as $action) {
                $role_action = new RolesAndActions;
                $role_action->role_id = $role->id;
                $role_action->enterprise_id = $ent_id;
                $role_action->action_id = $action;
                $role_action->save();
            }
            return redirect()->back()->with(['success' => true]);
        }
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $role = Role::findOrFail($role_id);
        if ($role->enterprise_id != $ent_id) {
            abort('404');
        }
        $current_actions = DB::table('roles_and_actions')
            ->where('roles_and_actions.role_id', $role_id)
            ->where('roles_and_actions.enterprise_id', $ent_id)
            ->join('actions', 'actions.id', '=', 'roles_and_actions.action_id')
            ->where('actions.is_active', 1)
            ->select('actions.id')
            ->get()->toArray();
        $role_actions = [];
        foreach ($current_actions as $current_action) {
            $role_actions[] = $current_action->id;
        }
        $actions = $this->getActionsIdAndFullPath();
        return view('roles.edit', compact('role', 'actions', 'role_actions'));
    }

    private function getActionsIdAndFullPath()
    {
        $actions = DB::table('actions')
            ->where('actions.is_active', 1)
            ->join('controllers', 'controllers.id', '=', 'actions.controller_id')
            ->where('controllers.is_active', 1)
            ->join('modules', 'modules.id', '=', 'controllers.module_id')
            ->where('modules.is_active', 1)
            ->select('actions.id', 'modules.name as module', 'controllers.name as controller', 'actions.name as action')
            ->orderBy('modules.name')
            ->orderBy('controllers.name')
            ->orderBy('actions.name')
            ->get()->toArray();
        return $actions;
    }
}
