<?php

namespace App\Helpers;

use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\Storage;

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
        // if($this->settings){
        //     return $this->settings;
        // }else{
            $this->settings=businessSettings::select($selector)->first();
            return $this->settings;
        // }
    }

    public static function getSettingsVersionInfo($key = null)
    {
        $filePath = base_path('system_version.json');
        $jsonData = file_get_contents($filePath);

        $systemVersion =  json_decode($jsonData, true);

        if ($key && isset($systemVersion[$key])) {
            return $systemVersion[$key];
        }

        return null;
    }

}
