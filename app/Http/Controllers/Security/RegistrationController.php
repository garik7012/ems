<?php

namespace App\Http\Controllers\Security;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use App\User;
use App\Enterprise;

class RegistrationController extends Controller
{
    public function confirmEmail($user_id, $pass)
    {
        $user_pass = Setting::where('type', 3)
            ->where('item_id', $user_id)
            ->where('key', 'confirmation_code')
            ->value('value');
        if($user_pass and $user_pass == $pass){
            $user = User::findOrFail($user_id);
            Setting::where('type', 3)
                ->where('item_id', $user_id)
                ->where('key', 'is_email_confirmed')
                ->update(['value' => 1]);
            return view('enterprise.user.confirmed', ['user' => $user, 'pass'=>$user_pass]);
        }
        abort('404');
    }

    public function finishRegistration(Request $request)
    {
        $user_id = +$request->user_id;
        $user_pass = Setting::where('type', 3)
            ->where('item_id', $user_id)
            ->where('key', 'confirmation_code')
            ->value('value');
        if($user_pass and $user_pass == $request->pass){
            $this->validate($request, [
                'first_name' => 'required|max:50',
                'last_name' => 'required|max:50',
                'login' => 'required|max:50',
                'phone_number' => 'required|max:50',
                'date_born' => 'required|date',
                'password' => 'required|string|min:6|confirmed'
            ]);
            $user = User::findOrFail($request->user_id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->login = $request->login;
            $user->phone_number = $request->phone_number;
            $user->password = bcrypt($request->password);
            $user->date_born = $request->date_born;
            $user->is_active = 1;
            $user->save();
            Setting::where('type', 3)
                ->where('item_id', $user->id)
                ->where('key', 'confirmation_code')
                ->update(['value' => '']);
            $ent = Enterprise::where('id', $user->enterprise_id)->value('namespace');
            return redirect("/e/{$ent}/login");
        }
        abort('403');
    }
}