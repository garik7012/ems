<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Enterprise;

class CheckUserEnterprise
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
            return redirect(config('ems.prefix') . "{$request->route('namespace')}/login");
        };
        $ent = Enterprise::where('namespace', $request->route('namespace'))->firstOrFail();
        if (Auth::user()->enterprise_id == $ent->id) {
            return $next($request);
        }
        if (Auth::user()->enterprise_id == $ent->parent_id) {
            return $next($request);
        }
        abort(403);
    }
}
