<?php

use App\Models\Currencies;
use App\Models\Product\UOM;
use App\Helpers\SettingHelpers;
use Nwidart\Modules\Facades\Module;





function hasModule($moduleName){
    $moduleName=ucfirst($moduleName);
    return  Module::has($moduleName);
}



function isEnableModule($moduleName){
    $moduleName=ucfirst($moduleName);
    $status = Module::find($moduleName)->isEnabled();
    return  $status;
}


function getModuleVer($moduleName,$ver='version'){
    $moduleName=strtolower($moduleName);
    return config($moduleName.'.'.$ver);
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

function price($price,$currencyId='default'){
    $loadSetting=SettingHelpers::load();
    $setting=$loadSetting->getSettingsValue();
    $formattedPrice=number_format($price,$setting->currency_decimal_places,'.',',');

    try {
        if($currencyId != 'default'){
            $currency=Currencies::where('id',$setting->currency_id)->firstOrFail();
        }else{
            $currency=Currencies::where('id',$currencyId)->firstOrFail();

        }
        if($setting->currency_symbol_placement == 'before'){
                return $currency->symbol.' '.$formattedPrice;
        }else{
                return $formattedPrice.' '.$currency->symbol;
        }
    } catch (\Throwable $th) {
        return $formattedPrice;
    }
}
