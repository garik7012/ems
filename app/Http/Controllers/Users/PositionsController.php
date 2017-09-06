<?php

namespace App\Http\Controllers\Users;

use App\Position;
use App\UsersAndPosition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use Illuminate\Support\Facades\DB;
use App\User;

class PositionsController extends Controller
{
    public function showList($namespace)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $users_and_positions_raw = DB::table('users')->where('users.enterprise_id', $ent_id)
            ->where('users.is_active', 1)
            ->leftJoin('users_and_positions', 'users_and_positions.user_id', '=', 'users.id')
            ->leftJoin('positions', function ($join) {
                $join->on('positions.id', '=', 'users_and_positions.position_id')
                    ->where('positions.is_active', 1);
            })
            ->select(
                'users.id as id',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.login as login',
                'positions.name as position'
            )
            ->orderBy('id')
            ->get();
        $users_and_positions = $users_and_positions_raw->groupBy('id')->toArray();
        return view('position.users', compact('users_and_positions'));
    }

    public function editUsersPositions($namespace, $user_id, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $user = User::where('id', $user_id)->where('enterprise_id', $ent_id)->firstOrFail();
        if ($request->isMethod('post')) {
            if ($request->positions_id) {
                UsersAndPosition::where('enterprise_id', $ent_id)->where('user_id', $user->id)->delete();
                foreach ($request->positions_id as $position_id) {
                    if ($position_id) {
                        $u_and_p  = new UsersAndPosition;
                        $u_and_p->user_id = $user_id;
                        $u_and_p->enterprise_id = $ent_id;
                        $u_and_p->position_id = $position_id;
                        $u_and_p->save();
                    }
                }
            }
            return back();
        }
        $positions = Position::where('enterprise_id', $ent_id)->where('positions.is_active', 1)->get();
        $user_positions = UsersAndPosition::where('enterprise_id', $ent_id)
            ->where('user_id', $user->id)
            ->pluck('position_id')->toArray();
        return view('position.user', compact('user', 'positions', 'user_positions'));
    }
}
