<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use Closure;
use Auth;
use Illuminate\Support\Facades\Session;

class EnterpirseNetwork
{
    /**
     * Handle an incoming request.
     * User always auth because of belong middleware
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $action = DB::table('enterprises')->where('enterprises.namespace', $request->route('namespace'))
            ->join('enterprise_firewall', 'enterprise_firewall.enterprise_id', '=', 'enterprises.id')
            ->where('enterprise_firewall.is_active', 1)
            ->where('enterprise_firewall.user_id', '=', Auth::user()->id)
            ->where('enterprise_firewall.ip_from', '<=', $request->ip())
            ->where('enterprise_firewall.ip_to', '>=', $request->ip())
            ->orderBy('priority', 'desc')
            ->value('action');
        if ($action == 'block') {
            abort('403');
        }
        if ($action != 'allow' and !Session::has('firewall_has_been_changed')) {
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
