<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    private function getDefaultUserSettings()
    {
        $arr = [
            'is_email_confirmed' => 0,
            'confirmation_code' =>str_random(16),
            'confirmation_date_end' =>date() + 30,
            'date_last_change_password' => 0,
            'date_end_ban' => 0,
            'permission_hash' => 0,
            'auth_type_id' => 0,
            'password_policy_id' => 0,
            'email_frequency_hours' => 1,
            'last_email_send_at' => date(),
            'auth_category_id' => 0,
        ];
        return $arr;
    }

    public static function setDefaultUserSettings($user_id)
    {
        foreach (self::getDefaultUserSettings() as $key=>$value) {
            $default = new Setting();
            $default->type = 3;
            $default->item_id = $user_id;
            $default->key = $key;
            $default->value = $value;
            $default->save();
        }
    }
}
