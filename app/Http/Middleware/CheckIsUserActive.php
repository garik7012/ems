<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Setting;

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
        $security_code = Setting::where('type',3)
            ->where('item_id', Auth::user()->id)
            ->where('key', 'confirmation_code')
            ->value('value');
        if($security_code){
            Setting::where('type',3)
                ->where('item_id', Auth::user()->id)
                ->where('key', 'confirmation_code')
                ->update(['value'=>""]);
            Auth::logout();
            return redirect()->back();
        }
        if(Auth::user()->is_active) {
            return $next($request);
        }
        return redirect("/security/user-not-active");
    }
}
