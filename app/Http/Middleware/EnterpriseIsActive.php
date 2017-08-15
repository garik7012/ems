<?php

namespace App\Http\Middleware;

use Closure;
use App\Enterprise;
use Illuminate\Support\Facades\Session;
use Auth;

class EnterpriseIsActive
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
        $is_active = Enterprise::where('namespace', $request->route('namespace'))->value('is_active');
        if ($is_active or Session::has('old_adm_namespace')) {
            return $next($request);
        }

        Auth::logout();
        abort('404');
    }
}
