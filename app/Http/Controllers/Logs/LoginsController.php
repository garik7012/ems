<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LoginStat;
use App\Enterprise;

class LoginsController extends Controller
{
    public function show($namespace, Request $request)
    {
        $orderBy = 'id';
        $desc = 'desc';
        if ($request->has('order')) {
            $orderBy = $request->order;
            $desc = $request->desc;
        }
        $ent_id = $this->shareEnterpriseToView($namespace);
        $login_stats = LoginStat::where('enterprise_id', $ent_id)->orderBy($orderBy, $desc)->paginate(5);
        return view('logs.loginStats', compact('login_stats', 'orderBy', 'desc'));
    }

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
