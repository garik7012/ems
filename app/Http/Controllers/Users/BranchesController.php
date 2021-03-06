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
    public function showList($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $count_branches = Branch::where('enterprise_id', $ent_id)->where('is_active', 1)->count();
        if ($count_branches == 0) {
            return view('branch.users');
        }
        $page_c = 0;
        if ($request->has('page')) {
            $page_c = ($request->page - 1) * 25;
        }
        $users_and_branches = DB::table('users')->where('users.enterprise_id', $ent_id)
            ->where('users.is_active', 1)
            ->leftJoin('branches', function ($join) {
                $join->on('branches.id', '=', 'users.branch_id')
                    ->where('branches.is_active', 1);
            })
            ->select(
                'users.id as id',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.login as login',
                'branches.name as branch'
            )
            ->orderBy('users.id', 'desc')
            ->paginate(25);
        return view('branch.users', compact('users_and_branches', 'page_c'));
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
            return redirect(config('ems.prefix') . "{$namespace}/Users/Branches/showList");
        }
        $branches = Branch::where('is_active', 1)->where('enterprise_id', $ent_id)->get();
        return view('branch.user', compact('branches', 'user'));
    }
}
