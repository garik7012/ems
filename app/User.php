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

    private static function getDefaultUserSettings()
    {
        $arr = [
            'is_email_confirmed' => 0,
            'confirmation_code' =>str_random(16),
            'confirmation_date_end' =>strtotime('+1 days'),
            'date_last_change_password' => strtotime('now'),
            'date_end_ban' => 0,
            'permission_hash' => 0,
            'auth_type_id' => 0,
            'password_policy_id' => 0,
            'email_frequency_hours' => 1,
            'last_email_send_at' => strtotime('now'),
            'auth_category_id' => 0,
        ];
        return $arr;
    }

    private static function getDefaultAdminSettings()
    {
        $arr = [
            'is_email_confirmed' => 1,
            'confirmation_code' =>"",
            'confirmation_date_end' =>strtotime('+1 days'),
            'date_last_change_password' => strtotime('now'),
            'date_end_ban' => 0,
            'permission_hash' => 0,
            'auth_type_id' => 0,
            'password_policy_id' => 0,
            'email_frequency_hours' => 1,
            'last_email_send_at' => strtotime('now'),
            'auth_category_id' => 1,
        ];
        return $arr;
    }

    public static function createNewUserByAdmin($request, $ent_id)
    {
        $user = new User;
        $user->enterprise_id = $ent_id;
        $user->login = $request->login;
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->is_superadmin = 0;
        $user->password = bcrypt($request->password);
        $user->save();
        self::setDefaultUserSettings($user->id);
        return $user->id;
    }

    public static function setDefaultUserSettings($user_id)
    {
        foreach (self::getDefaultUserSettings() as $key => $value) {
            $default = new Setting();
            $default->type = 3;
            $default->item_id = $user_id;
            $default->key = $key;
            $default->value = $value;
            $default->save();
        }
    }

    public static function setDefaultAdminSettings($user_id)
    {
        foreach (self::getDefaultAdminSettings() as $key => $value) {
            $default = new Setting();
            $default->type = 3;
            $default->item_id = $user_id;
            $default->key = $key;
            $default->value = $value;
            $default->save();
        }
    }

    public static function getSimpleUserById($user_id, $ent_id)
    {
        $user = self::where('enterprise_id', $ent_id)
            ->where('id', $user_id)
            ->where('is_active', 1)
            ->where('is_superadmin', 0)
            ->select('id', 'first_name', 'last_name', 'login')
            ->first();
        if (!$user) {
            abort('404');
        }
        return $user;
    }

    public static function getAllSimpleUsers($ent_id)
    {
        $users = self::where('enterprise_id', $ent_id)
                    ->where('is_active', 1)
                    ->where('is_superadmin', 0)
                    ->select('id', 'first_name', 'last_name', 'login')
                    ->get();
        return $users;
    }
}
