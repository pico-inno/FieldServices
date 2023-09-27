<?php

use App\Models\Currencies;
use App\Models\Product\UOM;
use App\Helpers\SettingHelpers;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Auth;
use App\Models\settings\businessSettings;





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
    // $loadSetting=SettingHelpers::load();
    $setting=getSettings();
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
function dateCreate($date,$format) {
    $dateTime = date_create($date);
    $formattedDate = $dateTime->format($format);
    return $formattedDate ;
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
 * @return collection
 */
function getSettings()
{
    return businessSettings::where('id',Auth::user()->business_id)
        ->select(
            'currency_decimal_places',
            'quantity_decimal_places'
        )
        ->first();
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


/**
 *
 *
 * @param mixed $currentUOMId
 *  @param mixed $currentUomPrice
 * @param mixed $newUOMID
 *  @return mixed $resultPrice
 *
 */
function priceChangeByUom($currentUOMId, $currentUomPrice,$newUOMID) {
        $newUomInfo = UOM::where('id', $newUOMID)->first();
        $newUomValue=$newUomInfo->value;
        $newUomType = $newUomInfo->unit_type;
        $currentUomInfo = UOM::where('id', $currentUOMId)->first();
        $currentUomType=$currentUomInfo->unit_type;
        $currentUomValue= $currentUomInfo->value;
        $resultPrice=0;
        if ($currentUomType == 'reference' && $newUomType == 'smaller') {
            $resultPrice = $currentUomPrice /($newUomInfo->value * $currentUomInfo->value);
        }else if ($currentUomType == 'reference' && $newUomType == 'bigger') {
            $resultPrice = $currentUomPrice * $newUomValue;
        }else if ($currentUomType == 'bigger' && $newUomType == 'reference') {
            $resultPrice = $currentUomPrice / $currentUomValue;
        }else if ($currentUomType == 'bigger' && $newUomType == 'bigger') {
            $resultPrice = $currentUomPrice *($newUomValue / $currentUomValue);
        }else if ($currentUomType == 'smaller' && $newUomType == 'bigger') {
            $resultPrice = $currentUomPrice * ($currentUomValue * $newUomValue) ;
        }else if ($currentUomType == 'smaller' && $newUomType == 'smaller') {
            $resultPrice = $currentUomPrice / $newUomValue;
        }else if ($currentUomType == 'smaller' && $newUomType == 'reference') {
            $resultPrice = $currentUomPrice * $currentUomValue ;
        }else{
            $resultPrice = $currentUomPrice  ;
        }
        return $resultPrice;
}




// format char to print in 48
function printFormat($productName, $quantity, $price)
{
    $columnWidthName = 22;
    $columnWidthQuantity = 8;
    $columnWidthPrice = 18;

    $formattedName = str_split($productName, $columnWidthName);
    $formattedQuantity = str_split($quantity, $columnWidthQuantity);
    $formattedPrice = str_split($price, $columnWidthPrice);

    $lengths = [
        'formattedName' => count($formattedName),
        'formattedQuantity' => count($formattedQuantity),
        'formattedPrice' => count($formattedPrice)
    ];

    $longestArrayName = '';
    $longestArrayLength = 0;

    foreach ($lengths as $arrayName => $arrayLength) {
        if ($arrayLength > $longestArrayLength) {
            $longestArrayName = $arrayName;
            $longestArrayLength = $arrayLength;
        }
    }
    $str = "";
    foreach ($$longestArrayName as $index => $la) {
        $forName = isset($formattedName[$index]) ? str_pad($formattedName[$index], $columnWidthName, " ") : str_pad(" ", $columnWidthName, " ");
        $forQty = isset($formattedQuantity[$index]) ? str_pad($formattedQuantity[$index], $columnWidthQuantity + 1, " ", STR_PAD_LEFT) : str_pad(" ", $columnWidthQuantity + 1, " ", STR_PAD_LEFT);
        $forPrice = isset($formattedPrice[$index]) ? str_pad($formattedPrice[$index], $columnWidthPrice, " ", STR_PAD_LEFT) : str_pad(" ", $columnWidthPrice, " ", STR_PAD_LEFT);
        $str .= $forName . $forQty . $forPrice . "\n";
    }
    return $str;
}
