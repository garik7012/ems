<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class SettingsController extends Controller
{
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
}
