<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Enterprise;
use Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function userProfile($namespace)
    {
        $this->shareEnterpriseToView($namespace);
        return view('user.profile', ['user'=>Auth::user()]);
    }

    public function editUserProfile($namespace, Request $request)
    {
        $this->shareEnterpriseToView($namespace);
        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'phone_number' => 'required|max:50',
            'date_born' => 'required|date',
            'password' => 'required|string|min:6'
        ]);

        $user = Auth::user();
        if (Hash::check($request->password, $user->password)) {
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone_number = $request->phone_number;
            $user->date_born = $request->date_born;
            $user->save();
            return redirect()->back();
        }
        return redirect()->back()->withErrors(['password' => 'wrong password']);
    }

    public function makeSuperadmin($namespace, Request $request)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        $user = User::where('id', $request->user_id)->where('enterprise_id', $ent_id)->firstOrFail();
        $user->is_superadmin = 1;
        $user->save();
        return redirect()->back();
    }

    public function depriveSuperadmin($namespace, Request $request)
    {
        if (Auth::user()->id != $request->user_id) {
            $ent_id = $this->shareEnterpriseToView($namespace);
            $user = User::where('id', $request->user_id)->where('enterprise_id', $ent_id)->firstOrFail();
            $user->is_superadmin = 0;
            $user->save();
        }
        return redirect()->back();
    }

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
