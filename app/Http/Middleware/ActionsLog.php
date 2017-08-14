<?php

namespace App\Http\Middleware;

use App\ActionStat;
use App\Enterprise;
use Closure;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Input;

class ActionsLog
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
        $ent_id = Enterprise::where('namespace', $request->route('namespace'))->value('id');
        $action_id = DB::table('actions')->where('actions.name', $request->route('action'))
            ->join('controllers', 'controllers.id', '=', 'actions.controller_id')
            ->where('controllers.name', $request->route('controller'))
            ->join('modules', 'modules.id', '=', 'controllers.module_id')
            ->where('modules.name', $request->route('module'))
            ->value('actions.id');
        $log = new ActionStat();
        $log->enterprise_id = $ent_id;
        $log->user_id = Auth::user()->id;
        $log->action_id = $action_id;
        $log->data = base64_encode(json_encode(Input::all()));
        $log->user_agent = base64_encode($request->header('User-Agent'));
        $log->ip = $request->ip();
        $log->created_at = date('Y-m-d H:i:s');
        $log->save();
        return $next($request);
    }
}
