<?php

namespace App\Http\Controllers\Security;

use App\EnterpriseNetwork;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;

class EnterpriseNetworksController extends Controller
{
    public function showList($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $ent_ips = EnterpriseNetwork::where('enterprise_id', $ent_id)->get();
        $ips = $ent_ips->where('is_active', 1)->pluck('ristrict_network_ip')->toArray();
        $current_ip = $request->ip();
        $warning = false;
        if (count($ips) and !in_array($current_ip, $ips)) {
            $warning = true;
        }
        return view('security.ristrict', compact('ent_ips', 'warning', 'current_ip'));
    }

    public function addIP($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $this->validate($request, [
            'ristrict_network_ip' => 'required|ipv4',
            'description' => 'max:255'
        ]);
        $ent_ip = new EnterpriseNetwork();
        $ent_ip->enterprise_id = $ent_id;
        $ent_ip->description = $request->description;
        $ent_ip->ristrict_network_ip = $request->ristrict_network_ip;
        $ent_ip->save();
        return back();
    }

    public function deleteIP($namespace, $id)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $ent_ip = EnterpriseNetwork::where('enterprise_id', $ent_id)->where('id', $id)->firstOrFail();
        $ent_ip->delete();
        return back();
    }

    public function activate($namespace, $id)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $ent_ip = EnterpriseNetwork::where('enterprise_id', $ent_id)->where('id', $id)->firstOrFail();
        $ent_ip->is_active = 1;
        $ent_ip->save();
        return back();
    }

    public function deactivate($namespace, $id)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $ent_ip = EnterpriseNetwork::where('enterprise_id', $ent_id)->where('id', $id)->firstOrFail();
        $ent_ip->is_active = 0;
        $ent_ip->save();
        return back();
    }
}
