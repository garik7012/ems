<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use \Illuminate\Support\Facades\View;
use App\Menu;
use Illuminate\Support\Facades\DB;

class ShowSideMenu
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
        if (Auth::guest()) {
            View::share(['menu_items'=>[]]);
            return $next($request);
        }

        if (Auth::user()->is_superadmin) {
            $menu_items = Menu::orderBy('position')->where('is_active', 1)->get();
        } else {
            $menu_items = DB::table('users_and_roles')->where('users_and_roles.user_id', '=', Auth::user()->id)
                ->join('roles', 'roles.id', '=', 'users_and_roles.role_id')->where('roles.is_active', 1)
                ->join('roles_and_actions', 'roles_and_actions.role_id', '=', 'roles.id')
                ->join('actions', 'actions.id', '=', 'roles_and_actions.action_id')->where('actions.is_active', 1)
                ->join('menu', function ($join) {
                    $join->on('menu.action_id', '=', 'actions.id')
                        ->orWhere('menu.is_for_all_users', '=', '1');
                })
                ->where('menu.is_active', 1)
                ->distinct()
                ->select('menu.id', 'menu.parent_id')
                ->get();
            //we have array collections. make array of assoc arrays
            // $menu_items = collect($menu_items)->map(function($x){ return (array) $x; })->toArray();
            //all menu id we will show;
            $menu_id = [];
            foreach ($menu_items as $menuItem) {
                $menu_id[] = $menuItem->id;
                if (!$menuItem->parent_id) {
                    continue;
                }
                $menu_id[] = $menuItem->parent_id;
            }
            $menu_items = Menu::whereIn('id', $menu_id)
                ->orderBy('position')
                ->get();
            //if was no roles of user we have empty array. But we need to show is_for_all_users;
            if (!count($menu_items)) {
                $menu_items = Menu::where('is_active', 1)->where('is_for_all_users', 1)->get();
            }
        }

        $this->createMenuLinks($menu_items);
        View::share(['menu_items'=>$menu_items]);
        return $next($request);
    }


    private function createMenuLinks(&$menu_items)
    {
        foreach ($menu_items as &$menuItem) {
            if ($menuItem->action_id != false) {
                $link_raw = DB::table('actions')->where('actions.id', $menuItem->action_id)
                    ->join('controllers', 'controllers.id', '=', 'actions.controller_id')
                    ->join('modules', 'modules.id', '=', 'controllers.module_id')
                    ->select('actions.name as action', 'controllers.name as controller', 'modules.name as module')
                    ->first();
                 $link = '/'. $link_raw->module . '/' . $link_raw->controller . '/' . $link_raw->action;
                $menuItem->link = $link;
            } else {
                $menuItem->link = null;
            }
        }
    }
}
