<?php

use App\Models\Product\UOM;
use App\Models\settings\businessSettings;

function getSettingValue($key){
    try {
        $setting=businessSettings::select("$key")->first();
        return $setting->$key;
    } catch (\Throwable $th) {
        return abort('500','Your Key Is Not In Setting DB');
    }

}

function getReferenceUomId($currentUomId){
    $currentUnit = UOM::where('id', $currentUomId)->with(['unit_category' => function ($q) {
        $q->with('uomByCategory');
    }])->first();
    $unitsByCategory = $currentUnit->unit_category['uomByCategory'];
    $referenceUnit = $unitsByCategory->first(function ($item) {
        return $item['unit_type'] === 'reference';
    });
    return $referenceUnit;
}

