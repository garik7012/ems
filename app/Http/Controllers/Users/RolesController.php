<?php

namespace App\Http\Controllers\Users;

use App\UsersAndController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use App\User;
use App\UsersAndRoles;
use App\Role;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    public function showList($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        if ($request->has_item_id) {
            $users = User::where('is_active', 1)->where('enterprise_id', $ent_id)->whereIn('id', $request->has_item_id)->get();
        } else {
            $users = User::where('is_active', 1)->where('enterprise_id', $ent_id)->get();
        }

        $user_and_roles = [];
        $i = 0;
        foreach ($users as $user) {
            $roles = [];
            $user_and_roles[$i]['user'] = $user;
            $user_roles_id = UsersAndRoles::where('user_id', $user->id)->where('enterprise_id', $ent_id)->get();
            foreach ($user_roles_id as $item) {
                $role = Role::where('id', $item->role_id)->where('enterprise_id', $ent_id)
                    ->where('is_active', 1)
                    ->select('name', 'description')
                    ->get()->toArray();
                if (count($role)) {
                    $roles[] = $role[0];
                }
            }
            $user_and_roles[$i]['roles'] = $roles;
            $i++;
        }
        $supervisors_id = User::where('parent_id', '>', 0)
            ->where('enterprise_id', $ent_id)
            ->where('is_active', 1)
            ->pluck('parent_id')->toArray();
        $u_and_c = UsersAndController::where('enterprise_id', $ent_id)->pluck('user_id')->toArray();
        $users_and_roles = $user_and_roles;

        return view('roles.usersRoles', compact('users_and_roles', 'supervisors_id', 'u_and_c'));
    }

    public function showRolesOfUser($namespace, $user_id)
    {
        $user_roles_id = UsersAndRoles::where('user_id', $user_id)->select('role_id')->get()->toArray();
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $roles = Role::where('is_active', 1)->where('enterprise_id', $ent_id)->get();
        return view('roles.userRoles', compact('user_roles_id', 'roles', 'user_id'));
    }

    public function deleteUsersRole($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        UsersAndRoles::where('user_id', $request->user_id)
            ->where('role_id', $request->role_id)
            ->where('enterprise_id', $ent_id)
            ->delete();

        return redirect(config('ems.prefix') . "{$namespace}/Users/Roles/showRolesOfUser/{$request->user_id}");
    }

    public function addRoleToUser($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $new_role = new UsersAndRoles;
        $new_role->enterprise_id = $ent_id;
        $new_role->role_id = $request->role_id;
        $new_role->user_id = $request->user_id;
        $new_role->save();

        return redirect(config('ems.prefix') . "{$namespace}/Users/Roles/showRolesOfUser/{$request->user_id}");
    }
}
