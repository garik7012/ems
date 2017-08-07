<?php

namespace App\Http\Controllers\Enterprises;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use App\User;
use Auth;
use App\Branch;

class BranchesController extends Controller
{

    public function create($namespace, Request $request)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        if ($request->isMethod('post')) {
            $branch = new Branch();
            $branch->name = $request->name;
            $branch->enterprise_id = $ent_id;
            $branch->country = $request->country;
            $branch->city = $request->city;
            $branch->postal_code = $request->postal_code;
            $branch->address_1 = $request->address_1;
            $branch->address_2 = $request->address_2;
            $branch->is_main = $request->is_main;
            $branch->save();
            return redirect()->back();
        }

        return view('branch.create');
    }

    public function showList($namespace)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        $branches = Branch::where('is_active', 1)->where('enterprise_id', $ent_id)->orderBy('is_main', 'desc')->get();
        return view('branch.list', compact('branches'));
    }

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
