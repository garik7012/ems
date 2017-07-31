<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;

    const enterpriseSettings  = [
            'auth_type_id' => 1,
            'password_policy_id' =>0,
            'is_sms_allow' =>0,
            'max_login_attempts' => 10,
            'max_login_period' =>30,
            'max_hours_ban' =>10,
            'password_change_days' => 30
        ];

    public static function setEnterpriseSecurity($ent_id, $request)
    {
        foreach (self::enterpriseSettings as $key=>$value) {
            Setting::where('type', 2)
                ->where('item_id', $ent_id)
                ->where('key', $key)
                ->update(['value' => $request[$key]]);
        }

    }

    public static function setDefaultEnterpriseSettings($ent_id)
    {
        foreach (self::enterpriseSettings as $key=>$value) {
            $default = new Setting();
            $default->type = 2;
            $default->item_id = $ent_id;
            $default->key = $key;
            $default->value = $value;
            $default->save();
        }
    }

}