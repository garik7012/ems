<?php

namespace App\Http\Controllers\Tools;

use App\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Auth;

class FilesController extends Controller
{
    public function getImage($namespace, Request $request)
    {
        $categories_id = session('categories_grid');
        $cat_id = $categories_id[$request->id - 1];
        $path = "/app/public/categories/{$cat_id}/" . random_int(1, 3) . ".jpg";
        return response()->file(storage_path($path));
    }

    public function getLogo($namespace, $ent_id)
    {
        $file = File::where('enterprise_id', $ent_id)->value('file_name');
        return response()->file(storage_path("/app/logos/{$file}"));
    }

    public function getAvatar($namespace)
    {
        $file = Auth::user()->avatar->file_name;
        return response()->file(storage_path("/app/avatars/{$file}"));
    }
}
