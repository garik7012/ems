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
            $is_trusted = UserTrustedDevice::where('user_id', Auth::user()->id)
                ->where('enterprise_id', Auth::user()->enterprise_id)
                ->where('token', $token)
                ->where('expire_end_at', '>', date('Y-m-d H:i:s'))
                ->count();
            return $is_trusted;
        }
        return false;
    }
}
