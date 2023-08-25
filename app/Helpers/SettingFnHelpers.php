<?php

use Illuminate\Support\Facades\Auth;
use App\Models\settings\businessSettings;


function getSettingValue($key){
    try {
        $setting=businessSettings::where('id',Auth::user()->business_id)->select("$key")->first();
        return $setting->$key;
    } catch (\Throwable $th) {
        return abort('500','Your Key Is Not In Setting DB');
    }

}


