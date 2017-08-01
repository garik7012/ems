<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Enterprise;
use \Illuminate\Support\Facades\View;
use App\Menu;
use App\Action;
use App\Controller;
use App\Module;

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
            View::share(['menu_items'=>""]);
            return $next($request);
        }

        if(Auth::user()->is_superadmin){
            $menu_items = Menu::orderBy('position')->where('is_active', 1)->get();
        } else {
            $menu_items = Menu::where('is_for_all_users', 1)->orderBy('position')->get();
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
                $menuItem->link = strtolower($link);
            }
            else{
                $menuItem->link = null;
            }
        }
    }
}
