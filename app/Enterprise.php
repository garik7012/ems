<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    protected $table = "enterprises";

    public static function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
