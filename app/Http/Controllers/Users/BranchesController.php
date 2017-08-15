<?php

namespace App\Http\Controllers\Users;

use App\Branch;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Enterprise;

class BranchesController extends Controller
{
    public function showList($namespace)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $users_and_branches = DB::table('users')->where('users.enterprise_id', $ent_id)
            ->where('users.is_active', 1)
            ->leftJoin('branches', 'branches.id', '=', 'users.branch_id')
            ->select(
                'users.id as id',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.login as login',
                'branches.name as branch'
            )
            ->get();
        return view('branch.users', compact('users_and_branches'));
    }

    public function editUsersBranch($namespace, $user_id, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $user = User::where('id', $user_id)->where('enterprise_id', $ent_id)->firstOrFail();
        if ($request->isMethod('post')) {
            $branch = $request->branch_id;
            if ($branch) {
                $branch = Branch::where('id', $request->branch_id)->where('enterprise_id', $ent_id)->firstOrFail()->id;
            }
            $user->branch_id = $branch;
            $user->save();
            return back();
        }
        $branches = Branch::where('is_active', 1)->where('enterprise_id', $ent_id)->get();
        return view('branch.user', compact('branches', 'user'));
    }
}
