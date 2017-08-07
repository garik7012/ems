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
        return view('enterprise.user.profile', ['user'=>Auth::user()]);
    }

    public function editUserProfile($namespace, Request $request)
    {
        $this->shareEnterpriseToView($namespace);
        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'login' => 'required|max:50',
            'phone_number' => 'required|max:50',
            'date_born' => 'required|date',
            'password' => 'required|string|min:6'
        ]);

        $user = Auth::user();
        if (Hash::check($request->password, $user->password)) {
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->login = $request->login;
            $user->phone_number = $request->phone_number;
            $user->password = bcrypt($request->password);
            $user->date_born = $request->date_born;
            $user->is_active = 1;
            $user->save();
            return redirect()->back();
        }
        return redirect()->back()->withErrors(['password' => 'wrong password']);
    }

    public function activate($n, $id)
    {
        $user = User::findOrFail($id);
        $user->is_active = 1;
        $user->save();
        return redirect()->back();
    }

    public function deactivate($n, $id)
    {
        $user = User::findOrFail($id);
        $user->is_active = 0;
        $user->save();
        return redirect()->back();
    }

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
