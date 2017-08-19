<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function getImage($namespace, Request $request)
    {
        $categories_id = session('categories_grid');
        $cat_id = $categories_id[$request->id - 1];
        $path = "/app/public/categories/{$cat_id}/" . random_int(1, 3) . ".jpg";
        return response()->file(storage_path($path));
    }
}
