<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Auth;
use Illuminate\Support\Facades\DB;

class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //check is path exists
        $current_path = $request->route('module').'\\'.$request->route('controller').'\\'.$request->route('action');
        $all_paths_raw = DB::table('actions')->where('actions.is_active', 1)
            ->join('controllers', 'controllers.id', '=', 'actions.controller_id')->where('controllers.is_active', 1)
            ->join('modules', 'modules.id', '=', 'controllers.module_id')
            ->select('actions.name as action', 'controllers.name as controller', 'modules.name as module')
            ->get()->toArray();
        $all_paths = [];
        foreach ($all_paths_raw as $path_raw) {
            $all_paths[] = strtolower($path_raw->module.'\\'.$path_raw->controller.'\\'.$path_raw->action);
        }
        if (!in_array(strtolower($current_path), $all_paths)) {
            abort('404');
        }

        if (!Auth::user()->is_superadmin) {
            //If user is supervisor
            if (Auth::user()->parent_id == null) {
                $subs_id = User::where('parent_id', Auth::user()->id)->where('is_active', 1)->pluck('id')->toArray();
            } else {
                $subs_id = [];
            }
            $subs_id[] = Auth::user()->id;
            $actions = DB::table('users_and_roles')->whereIn('users_and_roles.user_id', $subs_id)
                ->join('roles', 'roles.id', '=', 'users_and_roles.role_id')->where('roles.is_active', 1)
                ->join('roles_and_actions', 'users_and_roles.role_id', '=', 'roles_and_actions.role_id')
                ->join('actions', 'actions.id', '=', 'roles_and_actions.action_id')->where('actions.is_active', 1)
                ->select('roles_and_actions.action_id', 'actions.name', 'actions.controller_id')
                ->distinct()
                ->get();

            $permission_paths = [];
            $this->addToPermissionPath($actions, $permission_paths);
            $menu_for_all_user = DB::table('menu')->where('menu.is_for_all_users', 1)
                ->join('actions', 'actions.id', '=', 'menu.action_id')->where('actions.is_active', 1)
                ->select('actions.name', 'actions.controller_id')
                ->get()->toArray();
            $this->addToPermissionPath($menu_for_all_user, $permission_paths);
            if (!in_array($current_path, $permission_paths)) {
                abort('403');
            }
        }
        return $next($request);
    }

    private function addToPermissionPath($actions, &$permission_paths)
    {
        foreach ($actions as $action) {
            $controller = DB::table('controllers')->find($action->controller_id);
            $module = DB::table('modules')->where('id', $controller->module_id)->value('name');
            $permission_paths[] = $module.'\\'.$controller->name.'\\'.$action->name;
        }
    }
}
