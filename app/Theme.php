<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    public $timestamps = false;

    public static function setDefaultSettings($ent_id)
    {
        $default_theme_settings = [
            ['key' => 'main_background', 'value' => '#ffffff'],
            ['key' => 'side_background', 'value' => '#222222'],
        ];
        foreach ($default_theme_settings as $defaultThemeSetting) {
            $theme = new self();
            $theme->enterprise_id = $ent_id;
            $theme->key = $defaultThemeSetting['key'];
            $theme->value = $defaultThemeSetting['value'];
            $theme->save();
        }
    }

    public static function updateValue($ent_id, $key, $value)
    {
        self::where('enterprise_id', $ent_id)->where('key', $key)->update(['value' => $value]);
    }
}
