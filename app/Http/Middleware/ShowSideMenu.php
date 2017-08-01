<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Enterprise;
use \Illuminate\Support\Facades\View;
use App\Menu;

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
        $ent_id = Enterprise::where('namespace', $request->route('namespace'))->firstOrFail()->id;
        if(Auth::user()->enterprise_id == $ent_id) {
            if(Auth::user()->is_superadmin){
                $menu_items = Menu::orderBy('position')->get();
            } else {
                $menu_items = Menu::where('is_for_all_users', 1)->orderBy('position')->get();
            }

            View::share(['menu_items'=>$menu_items]);
            return $next($request);
        }
    }
}
