<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cookie;
use Auth;

class UserTrustedDevice extends Model
{
    public $timestamps = false;

    public static function isTrusted()
    {
        if (Cookie::has('device_token')) {
            $token = Cookie::get('device_token');
            $is_trusted = self::where('user_id', Auth::user()->id)
                ->where('enterprise_id', Auth::user()->enterprise_id)
                ->where('token', $token)
                ->where('expire_end_at', '>', date('Y-m-d H:i:s'))
                ->count();
            return $is_trusted;
        }
        return false;
    }


    public static function createTrustedToken()
    {
        $token = str_random(16);
        $u_t_d = new self();
        $u_t_d->user_id = Auth::user()->id;
        $u_t_d->enterprise_id = Auth::user()->enterprise_id;
        $u_t_d->token = $token;
        $u_t_d->created_at = date('Y-m-d H:i:s');
        $u_t_d->expire_end_at = date('Y-m-d H:i:s', strtotime('+3days'));
        $u_t_d->save();
        return $token;
    }
}
