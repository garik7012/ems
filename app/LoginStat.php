<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginStat extends Model
{
    public $timestamps = false;

    public static function logLogin($ent_id, $user_id, $request)
    {
        $logs = new self();
        $logs->enterprise_id = $ent_id;
        $logs->user_id = $user_id;
        $logs->is_ok = 0;
        $logs->login = $request->login;
        $logs->ip = $request->ip();
        $logs->user_agent = base64_encode($request->header('User-Agent'));
        $logs->created_at = date('Y-m-d H:i:s');
        $logs->save();
        return $logs;
    }
}
