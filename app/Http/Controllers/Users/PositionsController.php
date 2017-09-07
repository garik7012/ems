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
    public function showList($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $count_positions = Position::where('enterprise_id', $ent_id)->where('is_active', 1)->count();
        if ($count_positions == 0) {
            return view('position.users');
        }
        $page_c = 0;
        if ($request->has('page')) {
            $page_c = ($request->page - 1) * 25;
        }
        $users_and_positions = User::with('positions')->where('enterprise_id', $ent_id)
            ->where('is_active', 1)
            ->orderBy('id')
            ->paginate(25);

        return view('position.users', compact('users_and_positions', 'page_c'));
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
            return redirect(config('ems.prefix') . "{$namespace}/Users/Positions/showList");
        }
        $positions = Position::where('enterprise_id', $ent_id)->where('positions.is_active', 1)->get();
        $user_positions = UsersAndPosition::where('enterprise_id', $ent_id)
            ->where('user_id', $user->id)
            ->pluck('position_id')->toArray();
        return view('position.user', compact('user', 'positions', 'user_positions'));
    }
}
