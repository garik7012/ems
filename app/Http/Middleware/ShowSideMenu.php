<?php

namespace App\Http\Middleware;

use App\UsersAndRoles;
use Closure;
use Auth;
use App\Enterprise;
use \Illuminate\Support\Facades\View;
use App\Menu;
use App\Action;
use App\Controller;
use App\Module;
use App\RolesAndActions;

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
        if(Auth::guest()){
            View::share(['menu_items'=>[]]);
            return $next($request);
        }

        if(Auth::user()->is_superadmin){
            $menu_items = Menu::orderBy('position')->where('is_active', 1)->get();
        } else {
            //Prevent repetition of the menu item;
            $menu_items_id = [];

            $menu_items = Menu::where('is_for_all_users', 1)->orderBy('position')->get();
            foreach ($menu_items as $menu_item){
                if($menu_item->parent_id and !in_array($menu_item->parent_id, $menu_items_id)){
                    $menu_items->prepend(Menu::find($menu_item->parent_id));
                    $menu_items_id[] = $menu_item->parent_id;
                }
                $menu_items_id[] = $menu_item->id;
            }
            //TODO if role is not active. Sort menu list order by position
            $user_roles_ids = UsersAndRoles::where('user_id', Auth::user()->id)->select('role_id')->get()->toArray();
            foreach ($user_roles_ids as $role_id){
                $actions = RolesAndActions::where('role_id',$role_id['role_id'])->select('action_id')->get();
                foreach ($actions as $action){
                    $menu_item = Menu::where('action_id', $action->action_id)->where('is_active',1)->first();
                    if($menu_item) {
                        if(in_array($menu_item->id, $menu_items_id)) continue;
                        if($menu_item->parent_id and !in_array($menu_item->parent_id, $menu_items_id)){
                            $menu_items->prepend(Menu::find($menu_item->parent_id));
                            $menu_items_id[] = $menu_item->parent_id;
                        }
                        $menu_items->prepend($menu_item);
                        $menu_items_id[] = $menu_item->id;
                    }
                }
            }
        }
        $this->createMenuLinks($menu_items);
        View::share(['menu_items'=>$menu_items]);
        return $next($request);
    }


    private function createMenuLinks(&$menu_items)
    {
        foreach ($menu_items as &$menuItem){
            if($menuItem->action_id != false) {
                 $action =  Action::where('id', $menuItem->action_id)->first();
                 $controller = Controller::where('id', $action->controller_id)->first();
                 $module = Module::where('id', $controller->module_id)->value('name');
                 $link = '/'. $module . '/' . $controller->name . '/' . $action->name;
                $menuItem->link = $link;
            }
            else{
                $menuItem->link = null;
            }
        }
    }
}
