<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckIsAdmin
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
        $is_admin = Auth::user()->is_superadmin;
        if($is_admin) {
            return $next($request);
        }
        abort('403');
    }
}
