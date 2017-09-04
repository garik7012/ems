<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Enterprise extends Model
{
    protected $table = "enterprises";

    public function theme()
    {
        return $this->hasMany('App\Theme');
    }

    public static function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::with('theme')->where('namespace', $namespace)->firstOrFail();
        if ($enterprise->parent_id) {
            $enterprise->parent_name = self::where('id', $enterprise->parent_id)->value('name');
        }
        $path = File::where('enterprise_id', $enterprise->id)->where('file_type_id', 1)->value('file_path');
        if ($path and Storage::exists($path)) {
            $enterprise->logo = true;
        } else {
            $enterprise->logo = false;
        }
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
