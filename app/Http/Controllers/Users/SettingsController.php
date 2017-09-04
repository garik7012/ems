<?php

namespace App\Http\Controllers\Users;

use App\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Enterprise;
use Auth;
use Storage;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function userProfile($namespace)
    {
        Enterprise::shareEnterpriseToView($namespace);
        return view('user.profile', ['user'=>Auth::user()]);
    }

    public function editUserProfile($namespace, Request $request)
    {
        if ($request->file('avatar')) {
            $this->validate($request, [
                'avatar' => 'image|max:1024'
            ]);
            $file_name = Auth::user()->id . '.' . $request->file('avatar')->extension();
            $avatar = Auth::user()->avatar;
            if ($avatar != null) {
                Storage::delete($avatar->file_path);
            } else {
                $avatar = new File();
            }
            $path = Storage::putFileAs('avatars', $request->file('avatar'), $file_name);
            $avatar->enterprise_id = Auth::user()->enterprise_id;
            $avatar->file_name = $file_name;
            $avatar->file_mime_type = $request->file('avatar')->getMimeType();
            $avatar->file_size = $request->file('avatar')->getSize();
            $avatar->file_path = $path;
            $avatar->file_type_id = 2;
            $avatar->user_id = Auth::user()->id;
            $avatar->save();
            return back();
        }
        $rules_for_number = 'required|max:50|unique:users';
        if ($request->phone_number == Auth::user()->phone_number) {
            $rules_for_number = 'required';
        }
        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'phone_number' => $rules_for_number,
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
            return redirect()->back()->with(['status' => 'success']);
        }
        return redirect()->back()->withErrors(['password' => 'wrong password']);
    }

    public function makeSuperadmin($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $user = User::where('id', $request->user_id)->where('enterprise_id', $ent_id)->firstOrFail();
        $user->is_superadmin = 1;
        $user->save();
        return redirect()->back();
    }

    public function depriveSuperadmin($namespace, Request $request)
    {
        if (Auth::user()->id != $request->user_id) {
            $ent_id = Enterprise::shareEnterpriseToView($namespace);
            $user = User::where('id', $request->user_id)->where('enterprise_id', $ent_id)->firstOrFail();
            $user->is_superadmin = 0;
            $user->save();
        }
        return redirect()->back();
    }
}
