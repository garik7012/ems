<?php

namespace App\Http\Controllers\Users;

use App\Action;
use App\Module;
use App\RolesAndActions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use App\User;
use App\UsersAndRoles;
use App\Role;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    public function showRoles($namespace)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
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
                $module = DB::table('modules')->where('id',$controller->module_id)->value('name');
                $actions_arr[] = ['action_id' => $action->id,
                    'full_path' => $module . '\\' . $controller->name . '\\' . $action->name];
            }
            $role->actions = $actions_arr;
        }

        return view('roles.show', ["roles" => $roles]);
    }

    public function listUsersAndRoles($namespace)
    {
        $users = User::where('is_active', 1)->get();
        $user_and_roles = [];
        $i = 0;
        foreach ($users as $user){
            $roles = [];
            $user_and_roles[$i]['user'] = $user;
            $user_roles_id = UsersAndRoles::where('user_id', $user->id)->get();
            foreach ($user_roles_id as $item) {
                $role = Role::where('id', $item->role_id)->where('is_active', 1)->select('name', 'description')->get()->toArray();
                if(count($role)) $roles[] = $role[0];
            }
            $user_and_roles[$i]['roles'] = $roles;
            $i++;
        }
        $this->shareEnterpriseToView($namespace);
        return view('roles.usersRoles', ['users_and_roles'=>$user_and_roles]);
    }

    public function addNewRole($namespace, Request $request)
    {
        //save new role
        if($request->isMethod('post')){
            $ent_id = $this->shareEnterpriseToView($namespace);
            $role = new Role;
            $role->name = $request->name;
            $role->description = $request->description;
            $role->enterprise_id = $ent_id;
            $role->save();
            foreach ($request->actions as $action) {
                $role_action = new RolesAndActions;
                $role_action->role_id = $role->id;
                $role_action->enterprise_id = $ent_id;
                $role_action->action_id = $action;
                $role_action->save();
            }
        }

        //show creation form;
        $actions = $this->getActionsIdAndFullPath();
        $this->shareEnterpriseToView($namespace);
        return view('roles.add', ["actions" => $actions]);
    }

    public function showRolesOfUser($namespace, $user_id)
    {
        $user_roles_id = UsersAndRoles::where('user_id', $user_id)->select('role_id')->get()->toArray();
        $ent_id = $this->shareEnterpriseToView($namespace);
        $roles = Role::where('is_active', 1)->where('enterprise_id', $ent_id)->get();
        return view('roles.userRoles', ['user_roles_id'=>$user_roles_id ,'roles'=>$roles, 'user_id'=>$user_id]);
    }

    public function deleteUsersRole($namespace, Request $request)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        UsersAndRoles::where('user_id', $request->user_id)
            ->where('role_id', $request->role_id)
            ->where('enterprise_id', $ent_id)
            ->delete();

        return redirect("/e/{$namespace}/Users/Roles/showRolesOfUser/{$request->user_id}");
    }

    public function addRoleToUser($namespace, Request $request)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        $new_role = new UsersAndRoles;
        $new_role->enterprise_id = $ent_id;
        $new_role->role_id = $request->role_id;
        $new_role->user_id = $request->user_id;
        $new_role->save();

        return redirect("/e/{$namespace}/Users/Roles/showRolesOfUser/{$request->user_id}");
    }

    public function deactivate($namespace, $role_id)
    {
        $role = Role::findOrFail($role_id);
        $role->is_active = 0;
        $role->save();
        return redirect("/e/{$namespace}/Users/Roles/showRoles");
    }

    public function activate($namespace, $role_id)
    {
        $role = Role::findOrFail($role_id);
        $role->is_active = 1;
        $role->save();
        return redirect("/e/{$namespace}/Users/Roles/showRoles");
    }

    public function edit($namespace, $role_id, Request $request)
    {
        //edit role
        if ($request->isMethod('post')) {
            $ent_id = $this->shareEnterpriseToView($namespace);
            $role = Role::where('enterprise_id', $ent_id)->where('id', $role_id)->firstOrFail();
            $role->name = $request->name;
            $role->description = $request->description;
            $role->enterprise_id = $ent_id;
            $role->save();
            RolesAndActions::where('enterprise_id', $ent_id)->where('role_id', $role_id)->delete();
            foreach ($request->actions as $action) {
                $role_action = new RolesAndActions;
                $role_action->role_id = $role->id;
                $role_action->enterprise_id = $ent_id;
                $role_action->action_id = $action;
                $role_action->save();
            }
            return redirect()->back();
        }
        $ent_id = $this->shareEnterpriseToView($namespace);
        $role = Role::findOrFail($role_id);
        if($role->enterprise_id != $ent_id) abort('404');
        $current_actions = DB::table('roles_and_actions')
            ->where('roles_and_actions.role_id', $role_id)
            ->where('roles_and_actions.enterprise_id', $ent_id)
            ->join('actions', 'actions.id', '=', 'roles_and_actions.action_id')
            ->where('actions.is_active', 1)
            ->select('actions.id')
            ->get()->toArray();
        $current_actions_arr = [];
        foreach ($current_actions as $current_action) {
            $current_actions_arr[] = $current_action->id;
        }
        $actions = $this->getActionsIdAndFullPath();
        return view('roles.edit', ['role'=>$role, 'actions'=>$actions, 'role_actions'=>$current_actions_arr]);
    }

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
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