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
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        if ($request->isMethod('post')) {
            $this->validateRequest($request);
            $branch = new Branch();
            $this->saveBranch($branch, $request, $ent_id);
            return redirect()->back();
        }

        return view('branch.create');
    }

    public function showList($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        if ($request->has_item_id) {
            $branches = Branch::where('enterprise_id', $ent_id)->whereIn('id', $request->has_item_id)->get();
        } else {
            $branches = Branch::where('enterprise_id', $ent_id)->orderBy('is_main', 'desc')->get();
        }
        return view('branch.list', compact('branches'));
    }

    public function edit($namespace, $id, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $branch = Branch::where('id', $id)->where('enterprise_id', $ent_id)->firstOrFail();
        if ($request->isMethod('post')) {
            $this->validateRequest($request);
            $this->saveBranch($branch, $request, $ent_id);
        }
        return view('branch.edit', compact('branch'));
    }

    public function activate($namespace, $id)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $branch = Branch::where('id', $id)->where('enterprise_id', $ent_id)->where('is_active', 0)->firstOrFail();
        $branch->is_active = 1;
        $branch->save();
        return back();
    }

    public function deactivate($namespace, $id)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $branch = Branch::where('id', $id)->where('enterprise_id', $ent_id)->where('is_active', 1)->firstOrFail();
        $branch->is_active = 0;
        $branch->save();
        return back();
    }

    private function validateRequest($request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'latitude' => 'required_with:longitude',
            'longitude' => 'required_with:latitude',
        ]);
    }

    private function saveBranch(&$branch, $request, $ent_id)
    {
        $branch->name = $request->name;
        $branch->enterprise_id = $ent_id;
        $branch->country = $request->country;
        $branch->city = $request->city;
        $branch->postal_code = $request->postal_code;
        $branch->address_1 = $request->address_1;
        $branch->address_2 = $request->address_2;
        $branch->is_main = +$request->is_main;
        $branch->latitude = $request->latitude;
        $branch->longitude = $request->longitude;
        $branch->save();
        if ($branch->is_main) {
            Branch::where('is_main', 1)->where('id', '<>', $branch->id)->update(['is_main' => 0]);
        }
    }
}
