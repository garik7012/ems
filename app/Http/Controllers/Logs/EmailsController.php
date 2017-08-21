<?php

namespace App\Http\Controllers\Logs;

use App\EmailStat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use Illuminate\Support\Facades\DB;

class EmailsController extends Controller
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
        $emails_stats = EmailStat::where('enterprise_id', $ent_id)->orderBy($orderBy, $desc)->paginate(50);
        $users = DB::table('users')->where('enterprise_id', $ent_id)->pluck('login', 'id')->toArray();
        return view('logs.emailsStats', compact('emails_stats', 'page_c', 'users'));
    }
}
