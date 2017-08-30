<?php

namespace App\Http\Controllers\Users;

use App\User;
use App\UsersAndPosition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use Auth;
use DB;

class DashboardController extends Controller
{
    public function show($namespace)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $subs_id = User::where('parent_id', Auth::user()->id)->where('is_active', 1)->pluck('id')->toArray();
        $is_supervisor = count($subs_id);
        $subs_id[] = Auth::user()->id;
        $roles = DB::table('users_and_roles')->whereIn('users_and_roles.user_id', $subs_id)
            ->join('roles', 'roles.id', '=', 'users_and_roles.role_id')->where('roles.is_active', 1)
            ->where('roles.is_never_expires', 1)
            ->orWhere(function ($q) {
                $q->where('roles.expire_begin_at', '<=', date('Y-m-d'))
                    ->where('roles.expire_end_at', '>=', date('Y-m-d'));
            })->distinct()->get();
        $positions = Auth::user()->positions->where('is_active', 1);
        $user_positions_id = $positions->pluck('id')->toArray();
        $people = DB::table('users')->where('users.is_active', 1)->where('users.enterprise_id', $ent_id)
            ->join('users_and_positions', 'users_and_positions.user_id', '=', 'users.id')
            ->whereIn('users_and_positions.position_id', $user_positions_id)
            ->where('users_and_positions.user_id', '<>', Auth::user()->id)
            ->select('users.first_name as first_name', 'users.last_name as last_name', 'users.id as id')
            ->distinct('id')
            ->get();
        return view('user.dashboard', compact('is_supervisor', 'roles', 'positions', 'people'));
    }
}
