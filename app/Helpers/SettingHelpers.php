<?php

namespace App\Helpers;

use App\Models\settings\businessSettings;

class SettingHelpers
{
    static $SettingHelpers = null;
    public $settings = null;
    protected function __construct()
    {

    }
    static function load()
    {
        if(!static::$SettingHelpers) {
            static::$SettingHelpers = new static;
        }

        return static::$SettingHelpers;
    }
    public function getSettingsValue($selector='*'){
        if($this->settings){
            return $this->settings;
        }else{
            $this->settings=businessSettings::select($selector)->first();
            return $this->settings;
        }
    }

}
