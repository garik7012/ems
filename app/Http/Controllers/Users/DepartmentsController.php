<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Department;
use App\Enterprise;

class DepartmentsController extends Controller
{
    public function showList($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $count_departments = Department::where('enterprise_id', $ent_id)->where('is_active', 1)->count();
        if ($count_departments == 0) {
            return view('department.users');
        }
        $page_c = 0;
        if ($request->has('page')) {
            $page_c = ($request->page - 1) * 25;
        }
        $users_and_departments = DB::table('users')->where('users.enterprise_id', $ent_id)
            ->where('users.is_active', 1)
            ->leftJoin('departments', function ($join) {
                $join->on('departments.id', '=', 'users.department_id')
                    ->where('departments.is_active', 1);
            })
            ->select(
                'users.id as id',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.login as login',
                'departments.name as department'
            )
            ->orderBy('users.id', 'desc')
            ->paginate(25);
        return view('department.users', compact('users_and_departments', 'page_c'));
    }

    public function editUsersDepartment($namespace, $user_id, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $user = User::where('id', $user_id)->where('enterprise_id', $ent_id)->firstOrFail();
        if ($request->isMethod('post')) {
            $department = $request->department_id;
            if ($department) {
                $department = Department::where('id', $department)->where('enterprise_id', $ent_id)->firstOrFail()->id;
            }
            $user->department_id = $department;
            $user->save();
            return redirect(config('ems.prefix') . "{$namespace}/Users/Departments/showList");
        }
        $departments = Department::where('is_active', 1)->where('enterprise_id', $ent_id)->get();
        return view('department.user', compact('departments', 'user'));
    }
}
