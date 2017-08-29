<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
