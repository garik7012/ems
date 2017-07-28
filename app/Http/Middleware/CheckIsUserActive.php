<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

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
        if(Auth::user()->is_active) {
            return $next($request);
        }
        return redirect("/security/user-not-active");
    }
}
