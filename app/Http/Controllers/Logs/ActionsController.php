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
        $orderBy = 'created_at';
        $desc = 'desc';
        $page_c = 0;
        if ($request->has('page')) {
            $page_c = ($request->page - 1) * 50;
        }
        $ent_id = $this->shareEnterpriseToView($namespace);
        $login_stats = ActionStat::where('enterprise_id', $ent_id)->orderBy($orderBy, $desc)->paginate(50);
        return view('logs.actionStats', compact('login_stats', 'page_c'));
    }

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
