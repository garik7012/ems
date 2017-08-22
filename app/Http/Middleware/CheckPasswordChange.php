<?php

namespace App\Http\Middleware;

use Closure;

class CheckPasswordChange
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
        if (session()->has('need_to_select_categories')) {
            return redirect(config('ems.prefix') . "{$request->route('namespace')}/user/selectCategories");
        }
        if (session()->has('password_need_to_change')) {
            return redirect(config('ems.prefix') . "{$request->route('namespace')}/user/changePassword");
        }
        return $next($request);
    }
}
