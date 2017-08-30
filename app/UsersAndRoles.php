<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class UsersAndRoles extends Model
{
    public $timestamps = false;

    public static function getRolesByUsersId($subs_id)
    {
        return DB::table('users_and_roles')->whereIn('users_and_roles.user_id', $subs_id)
                ->join('roles', 'roles.id', '=', 'users_and_roles.role_id')->where('roles.is_active', 1)
                ->where('roles.is_never_expires', 1)
                ->orWhere(function ($q) {
                    $q->where('roles.expire_begin_at', '<=', date('Y-m-d'))
                        ->where('roles.expire_end_at', '>=', date('Y-m-d'));
                })->distinct()->get();
    }

    public static function getUsersByPositionsId($ent_id, $user_positions_id, $user_id)
    {
        return DB::table('users')->where('users.is_active', 1)->where('users.enterprise_id', $ent_id)
                ->join('users_and_positions', 'users_and_positions.user_id', '=', 'users.id')
                ->whereIn('users_and_positions.position_id', $user_positions_id)
                ->where('users_and_positions.user_id', '<>', $user_id)
                ->select('users.first_name as first_name', 'users.last_name as last_name', 'users.id as id')
                ->distinct('id')
                ->get();
    }
}
