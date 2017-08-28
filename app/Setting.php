<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;
    protected $fillable = ['type', 'key', 'value', 'item_id'];

    const ENTERPRISESETTINGS  = [
            'auth_type_id' => 1,
            'password_policy_id' =>1,
            'is_sms_allow' =>0,
            'max_login_attempts' => 10,
            'max_login_period' =>30,
            'max_hours_ban' =>10,
            'password_change_days' => 30,
            'self_signup' => 0
        ];

    public static function setEnterpriseSecurity($ent_id, $request)
    {
        foreach (self::ENTERPRISESETTINGS as $key => $value) {
            Setting::where('type', 2)
                ->where('item_id', $ent_id)
                ->where('key', $key)
                ->update(['value' => $request[$key]]);
        }
    }

    public static function setDefaultEnterpriseSettings($ent_id)
    {
        foreach (self::ENTERPRISESETTINGS as $key => $value) {
            $default = new Setting();
            $default->type = 2;
            $default->item_id = $ent_id;
            $default->key = $key;
            $default->value = $value;
            $default->save();
        }
    }

    public static function getValue($type, $item_id, $key)
    {
        return self::where('type', $type)->where('item_id', $item_id)->where('key', $key)->value('value');
    }

    public static function updateValue($type, $item_id, $key, $value)
    {
        self::where('type', $type)
            ->where('item_id', $item_id)
            ->where('key', $key)
            ->update(['value'=>$value]);
    }
}
