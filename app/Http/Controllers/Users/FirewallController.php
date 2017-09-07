<?php

namespace App\Http\Controllers\Users;

use App\EnterpriseFirewall;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use Illuminate\Support\Facades\DB;

class FirewallController extends Controller
{
    public function showList($namespace)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $firewall_users = DB::table('enterprise_firewall')->where('enterprise_firewall.enterprise_id', $ent_id)
            ->join('users', 'users.id', '=', 'enterprise_firewall.user_id')
            ->where('users.is_active', 1)
            ->select(
                'enterprise_firewall.user_id as user_id',
                'enterprise_firewall.id as id',
                'enterprise_firewall.ip_from as ip_from',
                'enterprise_firewall.ip_to as ip_to',
                'enterprise_firewall.action as action',
                'enterprise_firewall.note as note',
                'enterprise_firewall.priority as priority',
                'enterprise_firewall.is_active as is_active',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.login as login'
            )
            ->orderBy('enterprise_firewall.id', 'desc')
            ->get();
        return view('firewall.list', compact('firewall_users'));
    }

    public function create($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'user_id' => 'required|numeric',
                'ip_from' => 'required|ipv4',
                'ip_to' => 'required|ipv4',
                'priority' => 'required|numeric|max:100',
                'action' => 'required|in:allow,block'
            ]);
            $user = User::where('enterprise_id', $ent_id)->where('id', $request->user_id)->firstOrFail();
            $ent_firewall = new EnterpriseFirewall();
            $ent_firewall->user_id = $user->id;
            $ent_firewall->enterprise_id = $ent_id;
            $ent_firewall->ip_from = $request->ip_from;
            $ent_firewall->ip_to = $request->ip_to;
            $ent_firewall->action = $request->action;
            $ent_firewall->note = $request->note;
            $ent_firewall->priority = $request->priority;
            $ent_firewall->is_active = 1;
            $ent_firewall->save();
            return redirect(config('ems.prefix') . "{$namespace}/Users/Firewall/showList");
        }
        $users = User::where('enterprise_id', $ent_id)->where('is_active', 1)
            ->select('first_name', 'last_name', 'login', 'id')->get();
        return view('firewall.create', compact('users'));
    }

    public function activate($namespace, $id)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $ent_firewall = EnterpriseFirewall::where('id', $id)->where('enterprise_id', $ent_id)->firstOrFail();
        $ent_firewall->is_active = 1;
        $ent_firewall->save();
        return back();
    }

    public function deactivate($namespace, $id)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $ent_firewall = EnterpriseFirewall::where('id', $id)->where('enterprise_id', $ent_id)->firstOrFail();
        $ent_firewall->is_active = 0;
        $ent_firewall->save();
        return back();
    }

    public function edit($namespace, $id, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $ent_firewall = EnterpriseFirewall::where('id', $id)->where('enterprise_id', $ent_id)->firstOrFail();
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'ip_from' => 'required|ipv4',
                'ip_to' => 'required|ipv4',
                'priority' => 'required|numeric|max:100',
                'action' => 'required|in:allow,block'
            ]);
            $ent_firewall->ip_from = $request->ip_from;
            $ent_firewall->ip_to = $request->ip_to;
            $ent_firewall->action = $request->action;
            $ent_firewall->note = $request->note;
            $ent_firewall->priority = $request->priority;
            $ent_firewall->save();
            return back()->with(['success' => true]);
        }
        $user = User::where('id', $ent_firewall->user_id)->select('first_name', 'last_name', 'login')->first();
        return view('firewall.edit', compact('user', 'ent_firewall'));
    }
}
