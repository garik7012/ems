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
        $ent_id = Enterprise::where('namespace', $request->route('namespace'))->firstOrFail()->id;
        if (Auth::user()->enterprise_id == $ent_id) {
            return $next($request);
        }
        abort(403);
    }
}
