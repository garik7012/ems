<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use Closure;
use Auth;

class EnterpirseNetwork
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
            $ent_ips = DB::table('enterprises')->where('enterprises.namespace', $request->route('namespace'))
                ->join('enterprise_networks', 'enterprise_networks.enterprise_id', '=', 'enterprises.id')
                ->where('enterprise_networks.is_active', 1)
                ->pluck('ristrict_network_ip')->toArray();
            if (count($ent_ips) and !in_array($request->ip(), $ent_ips)) {
                abort('404');
            }
        }
        return $next($request);
    }
}
