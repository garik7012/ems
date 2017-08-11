<?php

namespace App\Http\Controllers\Enterprises;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;

class SupervisorsController extends Controller
{
    public function showList($namespace)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        $supervisors_id = User::where('parent_id', '>', 0)
            ->where('is_active', 1)
            ->where('enterprise_id', $ent_id)
            ->distinct()
            ->pluck('parent_id')->toArray();
        $supervisors = [];
        foreach ($supervisors_id as $supervisor_id) {
            $supervisor = User::find($supervisor_id);
            $supervisor_subs = User::where('parent_id', $supervisor_id)->where('is_active', 1)->get()->toArray();
            $supervisors[] = ['sup' => $supervisor, 'sub' => $supervisor_subs];
        }
        return view('supervisor.list', compact('supervisors'));
    }

    public function edit($namespace, $id, Request $request)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        User::where('enterprise_id', $ent_id)->where('id', $id)->firstOrFail();
        if ($request->isMethod('post')) {
            if (count($request->users_id)) {
                User::where('parent_id', $id)->update(['parent_id' => null]);
                User::whereIn('id', $request->users_id)->update(['parent_id' => $id]);
                return back();
            }
            return back();
        }
        $supervisor = User::where('enterprise_id', $ent_id)->where('id', $id)->firstOrFail();
        $current_users = User::where('parent_id', $id)->where('is_active', 1)->pluck('id')->toArray();
        $supervisors_id = User::where('parent_id', '>', 0)
            ->where('is_active', 1)
            ->where('enterprise_id', $ent_id)
            ->distinct()
            ->pluck('parent_id')->toArray();
        $users = User::where('parent_id', $id)
            ->orWhere('parent_id', null)
            ->where('is_active', 1)
            ->whereNotIn('id', $supervisors_id)
            ->where('is_superadmin', 0)
            ->get();
        return view('supervisor.edit', compact('supervisor', 'users', 'current_users'));
    }

    public function add($namespace, Request $request)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'supervisor_id' => 'required',
            ]);
            $sup_id = $request->supervisor_id;
            $subs_id = $request->subs_id;
            if (in_array($sup_id, $subs_id)) {
                unset($subs_id[array_search($sup_id, $subs_id)]);
            }
            if ($sup_id and count($subs_id)) {
                User::whereIn('id', $subs_id)->update(['parent_id' => $sup_id]);
                return back();
            }
            return back()->withErrors(['subs_id' => 'Select subordinates. You cannot be your own supervisor']);
        }
        $supervisors_id = User::where('parent_id', '>', 0)
            ->where('is_active', 1)
            ->where('enterprise_id', $ent_id)
            ->distinct()
            ->pluck('parent_id')->toArray();
        $users = User::where('parent_id', null)
            ->whereNotIn('id', $supervisors_id)
            ->where('is_superadmin', 0)
            ->get();
        return view('supervisor.add', compact('users'));
    }

    public function delete($namespace, $id)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        User::where('enterprise_id', $ent_id)->where('parent_id', $id)->update(['parent_id' => null]);
        return back();
    }

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
