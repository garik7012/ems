<?php

namespace App\Http\Controllers\Users;

use App\Setting;
use App\User;
use App\UsersAndRoles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use Auth;

class DashboardController extends Controller
{
    public function show($namespace, Request $request)
    {
        $status = Setting::getStatus(Auth::user()->id);
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'user_status' => 'max:50'
            ]);
            $status->value = $request->user_status ?: '';
            $status->save();
            return back();
        }
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $subs_id = User::where('parent_id', Auth::user()->id)->where('is_active', 1)->pluck('id')->toArray();
        $is_supervisor = count($subs_id);
        $subs_id[] = Auth::user()->id;
        $roles = UsersAndRoles::getRolesByUsersId($subs_id);
        $positions = Auth::user()->positions->where('is_active', 1);
        $user_positions_id = $positions->pluck('id')->toArray();
        $people = UsersAndRoles::getUsersByPositionsId($ent_id, $user_positions_id, Auth::user()->id);
        return view('user.dashboard', compact('is_supervisor', 'roles', 'positions', 'people', 'status'));
    }
}
