<?php

namespace App\Http\Controllers\Enterprises;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use App\User;
use App\Position;
use Auth;

class PositionsController extends Controller
{
    public function create($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        if ($request->isMethod('post')) {
            $this->validateRequest($request);
            $position = new Position();
            $this->savePosition($position, $request, $ent_id);
            return redirect()->back();
        }
        $positions = Position::where('enterprise_id', $ent_id)->where('is_active', 1)->get();
        return view('position.create', compact('positions'));
    }

    public function showList($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        if ($request->has_item_id) {
            $positions = Position::where('enterprise_id', $ent_id)->whereIn('id', $request->has_item_id)->get();
        } else {
            $positions = Position::where('enterprise_id', $ent_id)->orderBy('id')->get();
        }
        return view('position.list', compact('positions'));
    }

    public function edit($namespace, $id, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $position = Position::where('id', $id)->where('enterprise_id', $ent_id)->firstOrFail();
        if ($request->isMethod('post')) {
            $this->validateRequest($request);
            $this->savePosition($position, $request, $ent_id);
        }
        $positions = Position::where('enterprise_id', $ent_id)->orderBy('id')->get();
        return view('position.edit', compact('position', 'positions'));
    }

    public function activate($namespace, $id)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $position = Position::where('id', $id)->where('enterprise_id', $ent_id)->where('is_active', 0)->firstOrFail();
        $position->is_active = 1;
        $position->save();
        return back();
    }

    public function deactivate($namespace, $id)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $position = Position::where('id', $id)->where('enterprise_id', $ent_id)->where('is_active', 1)->firstOrFail();
        $position->is_active = 0;
        $position->save();
        return back();
    }

    private function validateRequest($request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);
    }

    private function savePosition(&$position, $request, $ent_id)
    {
        $position->name = $request->name;
        $position->enterprise_id = $ent_id;
        $position->is_default = $request->is_default;
        $position->description = $request->description;
        $position->save();
        if ($position->is_default) {
            Position::where('is_default', 1)->where('id', '<>', $position->id)->update(['is_default' => 0]);
        }
    }
}
