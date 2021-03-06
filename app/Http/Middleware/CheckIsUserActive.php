<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Setting;
use Session;

class CheckIsUserActive
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
        if (Session::has('auth_from_admin_asd')) {
            return $next($request);
        }
        $security_code = Setting::where('type', 3)
            ->where('item_id', Auth::user()->id)
            ->where('key', 'confirmation_code')
            ->value('value');
        if ($security_code) {
            Setting::where('type', 3)
                ->where('item_id', Auth::user()->id)
                ->where('key', 'confirmation_code')
                ->update(['value'=>""]);
            Auth::logout();
            return redirect()->back();
        }
        if (session('categories_grid')) {
            Auth::logout();
            return redirect()->back();
        }
        if (Auth::user()->is_active or Auth::user()->is_superadmin) {
            return $next($request);
        }
        Auth::logout();
        return redirect("/enterprises/user-not-active");
    }
}
