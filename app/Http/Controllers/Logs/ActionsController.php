<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use App\ActionStat;

class ActionsController extends Controller
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
        $login_stats = ActionStat::where('enterprise_id', $ent_id)->orderBy($orderBy, $desc)->paginate(50);
        return view('logs.actionStats', compact('login_stats', 'orderBy', 'desc'));
    }

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
