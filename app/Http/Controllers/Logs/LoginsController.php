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
        $orderBy = 'created_at';
        $desc = 'desc';
        $page_c = 0;
        if ($request->has('page')) {
            $page_c = ($request->page - 1) * 50;
        }
        $ent_id = $this->shareEnterpriseToView($namespace);
        $login_stats = LoginStat::where('enterprise_id', $ent_id)->orderBy($orderBy, $desc)->paginate(50);
        return view('logs.loginStats', compact('login_stats', 'page_c'));
    }

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
