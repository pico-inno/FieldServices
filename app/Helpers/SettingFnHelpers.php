<?php

use Illuminate\Support\Facades\Auth;
use App\Models\settings\businessSettings;


function getSettingValue($key){
    try {
        $setting=businessSettings::select("$key")->first();
        return $setting->$key;
    } catch (\Throwable $th) {
        dd($th);
        return abort('500','Your Key Is Not In Setting DB');
    }

}


