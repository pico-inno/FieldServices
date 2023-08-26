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

function fprice($price){
    $loadSetting=SettingHelpers::load();
    $setting=$loadSetting->getSettingsValue();
    $formattedPrice=number_format($price,$setting->currency_decimal_places,'.','');
    return $formattedPrice;
}
function fDate($date,$br=false)
{
    $dateTime =date_create($date);
    $formattedDate = $dateTime->format("d-M-Y " );
    $formattedTime = $dateTime->format(" h:i A " );
    if($br){
        return $formattedDate.'<br>'.'('.$formattedTime.')';
    }else{
     return $formattedDate.' '.'('.$formattedTime.')';
    }
}

function getSettingsValue($selector){
    return SettingHelpers::load()->getSettingsValue($selector)->$selector;
}

function isUsePaymnetAcc(){
    return getSettingsValue('use_paymentAccount')==1 ? true :false;
}


/**
 *
 * @param  $newEnvVariable ($key => $value)
 * @return Renderable
 */
function updenv($newEnvVariables)
{
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    foreach ($newEnvVariables as $key => $newValue) {
        // Update the environment variable
        $newValue='"'.$newValue.'"';
        $currentValue = '"'.env($key).'"';
        // Replace the existing value with the new value
        if ($currentValue !== false) {
            $str = preg_replace("/$key=" . preg_quote($currentValue, '/') . "/", "$key=" .$newValue, $str);
        } else {
            // If the key doesn't exist, add it to the .env file
            $str .= "\n$key=$newValue";dd('what');
        }

        // Refresh the environment variables
        putenv("$key=$newValue");
    }

    file_put_contents($envFile, $str);
}

function updenvWithoutQuote($newEnvVariables)
{
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    foreach ($newEnvVariables as $key => $newValue) {
        $newValue= str_replace(' ', '_', $newValue);
        // Update the environment variable
        $currentValue = env($key);
        // Replace the existing value with the new value
        if ($currentValue !== false) {
            $str = preg_replace("/$key=" . preg_quote($currentValue, '/') . "/", "$key=" . $newValue, $str);
        } else {
            // If the key doesn't exist, add it to the .env file
            $str .= "\n$key=$newValue";
            dd('what');
        }

        // Refresh the environment variables
        putenv("$key=$newValue");
    }

    file_put_contents($envFile, $str);
}
