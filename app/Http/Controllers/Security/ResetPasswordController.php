<?php

namespace App\Http\Controllers\Security;

use App\Enterprise;
use App\Setting;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EmailStat;
use App\PasswordPolicy;
use Illuminate\Support\Facades\Session;

class ResetPasswordController extends Controller
{
    public function showForm($namespace)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $is_sms = Setting::getValue(2, $ent_id, 'is_sms_allow');
        return view('security.forgotPassword', compact('is_sms'));
    }

    public function sendResetLink($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $user_id = User::where('email', $request->email)->where('enterprise_id', $ent_id)->value('id');
        if (!$user_id) {
            return back()->withErrors(['email' => 'wrong email']);
        }
        $token = str_random(16);
        Setting::updateOrCreate(
            ['type' => 3, 'item_id' => $user_id, 'name' => null, 'key' => 'reset_pwd_code'],
            ['value' => $token]
        );
        $link = "{$_SERVER['SERVER_NAME']}" . config('ems.prefix') .
            "$namespace/security/reset/{$user_id}/{$token}";
        $data = base64_encode("To reset your password please <a href='{$link}'>Click here</a>");
        EmailStat::logEmail($ent_id, $user_id, 'no-reply@domain.com', $request->email, 'confirm email', $data);

        return redirect()->back()->with('status', 'success')->with('link', $link);
    }

    public function showResetForm($namespace, $user_id, $token)
    {
        $is_correct = Setting::where('type', 3)->where('item_id', $user_id)
            ->where('key', 'reset_pwd_code')->where('value', $token)->count();
        if (!$is_correct) {
            abort('404');
        }
        $user = User::find($user_id);
        $password_policy = $this->getPasswordPolicy($user);
        Enterprise::shareEnterpriseToView($namespace);
        return view('security.resetForm', compact('user_id', 'token', 'password_policy'));
    }

    public function reset($namespace, Request $request)
    {
        $is_correct = Setting::where('type', 3)->where('item_id', $request->user_id)
            ->where('key', 'reset_pwd_code')->where('value', $request->token)->count();
        if (!$is_correct) {
            abort('404');
        }
        $user = User::find($request->user_id);
        $password_policy = $this->getPasswordPolicy($user);
        $password_pattern = $password_policy->pattern;
        $this->validate($request, [
            'password' => "required|string|regex:/${password_pattern}/|confirmed"
        ]);
        $user->password = bcrypt($request->password);
        $user->save();
        Setting::updateValue(3, $user->id, 'reset_pwd_code', '');
        return redirect(config('ems.prefix') . "{$namespace}/login");
    }

    //if SMS allow
    public function sendSMSCode($namespace, Request $request)
    {
        $ent_id = Enterprise::shareEnterpriseToView($namespace);
        $user_id = User::where('phone_number', $request->phone_number)->where('enterprise_id', $ent_id)->value('id');
        if (!$user_id) {
            return back()->withErrors(['phone_number' => 'wrong number']);
        }
        $token = str_random(8);
        Setting::updateOrCreate(
            ['type' => 3, 'item_id' => $user_id, 'name' => null, 'key' => 'reset_pwd_code'],
            ['value' => $token]
        );
        //TODO send SMS
        return view('security.smsCode', compact('user_id', 'token'));
    }

    public function checkCode($namespace, Request $request)
    {
        return redirect(config('ems.prefix') . "{$namespace}/security/reset/" . $request->user_id . '/' . $request->sms_code);
    }

    private function getPasswordPolicy($user)
    {
        $password_policy_id = Setting::getValue(3, $user->id, 'password_policy_id') ?:
            Setting::getValue(2, $user->enterprise_id, 'password_policy_id');

        $password_policy = PasswordPolicy::find($password_policy_id);

        return $password_policy;
    }
}
