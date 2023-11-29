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


function settings($key = null){ //key for relation
    if ($key == null){
        return businessSettings::all()->first();
    }else{
        return businessSettings::with("$key")->get()->first();
    }
}

