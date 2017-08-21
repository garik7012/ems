<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use App\ActionStat;
use Illuminate\Support\Facades\DB;

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
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $login_stats = ActionStat::where('enterprise_id', $ent_id)->orderBy($orderBy, $desc)->paginate(50);
        $actions_raw = DB::table('actions')->where('actions.is_active', 1)
            ->join('controllers', 'controllers.id', '=', 'actions.controller_id')
            ->join('modules', 'modules.id', '=', 'controllers.module_id')
            ->select(
                'actions.id as id',
                'modules.name as module',
                'controllers.name as controller',
                'actions.name as action'
            )
            ->get();
        $actions = [];
        foreach ($actions_raw as $item) {
            $actions[$item->id] = $item->module . '.' . $item->controller . '.' . $item->action;
        }
        $users = DB::table('users')->where('enterprise_id', $ent_id)->pluck('login', 'id')->toArray();
        return view('logs.actionStats', compact('login_stats', 'page_c', 'actions', 'users'));
    }
}
