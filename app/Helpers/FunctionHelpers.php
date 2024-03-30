<?php

use App\Models\data;
use App\Helpers\UomHelper;
use App\Models\Currencies;
use App\Models\sale\sales;
use App\Models\Product\UOM;
use App\Models\openingStocks;
use App\Models\stock_history;
use App\Models\systemSetting;
use App\Helpers\SettingHelpers;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Helpers\generatorHelpers;
use App\Models\sale\sale_details;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\expenseTransactions;
use App\Models\purchases\purchases;
use App\Models\Stock\StockTransfer;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock\StockAdjustment;
use Illuminate\Support\Facades\Cache;
use App\Services\Report\reportServices;
use App\Models\Product\ProductVariation;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use Modules\ComboKit\Services\RoMService;
use Modules\Ecommerce\Entities\EcommerceOrder;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;
use App\Actions\currentStockBalance\currentStockBalanceActions;

function hasModule($moduleName)
{
    $moduleName = ucfirst($moduleName);
    return  Module::has($moduleName);
}



function isEnableModule($moduleName)
{
    $moduleName = ucfirst($moduleName);
    $status = Module::find($moduleName)->isEnabled();
    return  $status;
}


function getModuleVer($moduleName, $ver = 'version')
{
    $moduleName = strtolower($moduleName);
    return config($moduleName . '.' . $ver);
}

function getReferenceUomId($currentUomId)
{
    $currentUnit = UOM::where('id', $currentUomId)->with(['unit_category' => function ($q) {
        $q->with('uomByCategory');
    }])->first();
    $unitsByCategory = $currentUnit->unit_category['uomByCategory'];
    $referenceUnit = $unitsByCategory->first(function ($item) {
        return $item['unit_type'] === 'reference';
    });
    return $referenceUnit;
}

function formatPrice($price, $currency)
{
    $formattedPrice = number_format($price,2, '.', ',');
    if(!$currency){
       $currencyId= getSettingValue('currency_id');
       $currency=Currencies::where('id',$currencyId)->first();
    }
    $symobl= $currency['symbol'] ?? '';
    return $formattedPrice .' '. $symobl ;
}
function price($price, $currencyId = 'default')
{
    $loadSetting = SettingHelpers::load();
    $setting = $loadSetting->getSettingsValue();
    $formattedPrice = number_format($price, $setting->currency_decimal_places, '.', ',');

    try {
        if ($currencyId != 'default') {
            $currency = Currencies::where('id', $currencyId)->firstOrFail();
        } else {
            $currency = Currencies::where('id', $setting->currency_id)->firstOrFail();
        }
        if ($setting->currency_symbol_placement == 'before') {
            return $currency->symbol . ' ' . $formattedPrice;
        } else {
            return $formattedPrice . ' ' . $currency->symbol;
        }
    } catch (\Throwable $th) {
        return $formattedPrice;
    }
}


function fprice($price)
{
    // $loadSetting=SettingHelpers::load();
    $setting = getSettings();
    $formattedPrice = number_format($price, $setting->currency_decimal_places, '.', '');
    return $formattedPrice;
}
function fquantity($qty)
{
    // $loadSetting=SettingHelpers::load();
    $setting = getSettings();
    $formattedQty = number_format($qty, $setting->quantity_decimal_places, '.', '');
    return $formattedQty;
}
function getFullNameAttribute($contact)
{
    $name_array = [];
    if (isset($contact['prefix'])) {
        $name_array[] = $contact['prefix'];
    }
    if (isset($contact['first_name'])) {
        $name_array[] = $contact['first_name'];
    }
    if (isset($contact['middle_name'])) {
        $name_array[] = $contact['middle_name'];
    }
    if (isset($contact['last_name'])) {
        $name_array[] = $contact['last_name'];
    }
    return implode(' ', $name_array);
}
function fDate($date, $br = false, $time = true)
{
    // dd($setting->time_format);
    if($date){
        $dateTime = date_create($date);
        $setting = getSettings();
        if($setting->date_format){
            $formattedDate = $dateTime->format("$setting->date_format");
        }else{
            $formattedDate=$dateTime->format("m/d/Y");
        }
        if ($setting->time_format == '12') {
            $formattedTime = $dateTime->format(" h:i A ");
        } else {
            $formattedTime = $dateTime->format(" H:i A ");
        }
        if ($time) {
            if ($br) {
                return $formattedDate . '<br>' . '(' . $formattedTime . ')';
            } else {
                return $formattedDate . ' ' . '(' . $formattedTime . ')';
            }
        } else {
            return $formattedDate;
        }
    }else{
        return $date;
    }
}

function numericToDate($date)
{
    $setting = getSettings();
    // dd($setting->time_format);
    $dateTime = date("d/M/Y", $date);
    return $dateTime;
}

function formateDate($dateTime)
{

    $setting = getSettings();
    return $dateTime->format("$setting->date_format");
}
function getSettingsValue($selector)
{
    return SettingHelpers::load()->getSettingsValue($selector)->$selector;
}

function isUsePaymnetAcc()
{
    return getSettingsValue('use_paymentAccount') == 1 ? true : false;
}


/**
 *
 * @param  $newEnvVariable ($key => $value)
 * @return collection
 */
function getSettings()
{
    return businessSettings::where('id', Auth::user()->business_id)
        ->with('currency')
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
        $newValue = '"' . $newValue . '"';
        $currentValue = '"' . env($key) . '"';
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

function updenvWithoutQuote($newEnvVariables)
{
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    foreach ($newEnvVariables as $key => $newValue) {
        $newValue = str_replace(' ', '_', $newValue);
        // Update the environment variable
        $currentValue = env($key);
        // Replace the existing value with the new value
        if ($currentValue !== false) {
            $str = preg_replace("/$key=" . preg_quote($currentValue, '/') . "/", "$key=" . $newValue, $str);
        } else {
            // If the key doesn't exist, add it to the .env file
            $str .= "\n$key=$newValue";
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
function priceChangeByUom($currentUOMId, $currentUomPrice, $newUOMID)
{
    $newUomInfo = UOM::where('id', $newUOMID)->first();
    $newUomValue = $newUomInfo->value;
    $newUomType = $newUomInfo->unit_type;
    $currentUomInfo = UOM::where('id', $currentUOMId)->first();
    $currentUomType = $currentUomInfo->unit_type;
    $currentUomValue = $currentUomInfo->value;
    $resultPrice = 0;
    if ($currentUomType == 'reference' && $newUomType == 'smaller') {
        $resultPrice = $currentUomPrice / ($newUomInfo->value * $currentUomInfo->value);
    } else if ($currentUomType == 'reference' && $newUomType == 'bigger') {
        $resultPrice = $currentUomPrice * $newUomValue;
    } else if ($currentUomType == 'bigger' && $newUomType == 'reference') {
        $resultPrice = $currentUomPrice / $currentUomValue;
    } else if ($currentUomType == 'bigger' && $newUomType == 'bigger') {
        $resultPrice = $currentUomPrice * ($newUomValue / $currentUomValue);
    } else if ($currentUomType == 'smaller' && $newUomType == 'bigger') {
        $resultPrice = $currentUomPrice * ($currentUomValue * $newUomValue);
    } else if ($currentUomType == 'smaller' && $newUomType == 'smaller') {
        $resultPrice = $currentUomPrice / $newUomValue;
    } else if ($currentUomType == 'smaller' && $newUomType == 'reference') {
        $resultPrice = $currentUomPrice * $currentUomValue;
    } else {
        $resultPrice = $currentUomPrice;
    }
    return $resultPrice;
}


// applied transaction edit day
function checkTxEditable($startDate)
{
    $start_date = new DateTime($startDate);
    $since_start = $start_date->diff(now());
    $transaction_edit_days = getSettingValue('transaction_edit_days');
    if ($since_start->d >= $transaction_edit_days) {
        return false;
    } else {
        return true;
    }
}



// --------------------------------------------------    voucher generator
function productSKU()
{
    $products = new \App\Repositories\Product\ProductRepository();
    $uniqueCount = $products->query()->orderBy('id', 'DESC')->pluck('id')->first() ?? 1;

    return  sprintf('%07d', ($uniqueCount));
}
function serviceSaleVoucher()
{
//    $prefix = getSettingValue('sale_prefix');
    $prefix = 'SS';

    if (hasModule('Service') && isEnableModule('Service')){
        $uniqueCount = \Modules\Service\Entities\ServiceSale::orderBy('id', 'DESC')->select('id')->first()->id ?? 0;
        return generatorHelpers::generateVoucher($prefix, $uniqueCount);
    }else{
        return null;
    }
}

function generateManufacturingOrderNo()
{
    $prefix = 'MF';

    if (hasModule('Manufacturing') && isEnableModule('Manufacturing')){
        $uniqueCount = \Modules\Manufacturing\Entities\ManufacturingOrder::orderBy('id', 'DESC')->select('id')->first()->id ?? 0;
        return generatorHelpers::generateVoucher($prefix, $uniqueCount);
    }else{
        return null;
    }
}

function saleVoucher($uniqueCount)
{
    $prefix = getSettingValue('sale_prefix');
    return generatorHelpers::generateVoucher($prefix, $uniqueCount);
}

function customerCode($uniqueCount)
{
    $prefix = 'C';
    return generatorHelpers::generateVoucher($prefix, $uniqueCount);
}
function purchaseVoucher()
{
    $prefix = getSettingValue('purchase_prefix');
    $uniqueCount = purchases::orderBy('id', 'DESC')->select('id')->first()->id ?? 0;
    return generatorHelpers::generateVoucher($prefix, $uniqueCount);
}
function stockTransferVoucher()
{
    $prefix = getSettingValue('stock_transfer_prefix');
    $uniqueCount = StockTransfer::orderBy('id', 'DESC')->select('id')->first()->id ?? 0;
    return generatorHelpers::generateVoucher($prefix, $uniqueCount);
}
function stockAdjustmentVoucherNo()
{
    $prefix = getSettingValue('stock_adjustment_prefix');
    $uniqueCount = StockAdjustment::orderByDesc('id')->value('id') ?? 0;
    return generatorHelpers::generateVoucher($prefix, $uniqueCount);
}
function expenseVoucherNo($uniqueCount)
{
    $prefix = getSettingValue('expense_prefix');
    return generatorHelpers::generateVoucher($prefix, $uniqueCount);
}

function expenseReportVoucherNo($uniqueCount)
{
    $prefix = getSettingValue('expense_report_prefix');
    return generatorHelpers::generateVoucher($prefix, $uniqueCount);
}


function contactNo($uniqueCount)
{
    // $prefix = getSettingValue('expense_report_prefix');
    return generatorHelpers::generateVoucher('', $uniqueCount);
}
function businessLocationCode()
{
    $location_count = businessLocation::count();
    $prefix = getSettingValue('business_location_prefix');
    return generatorHelpers::generateVoucher($prefix, $location_count);
}

function requestJsonId($requestJson, $key, $value)
{
    $datas = json_decode($requestJson);
    if ($datas) {
        $data = array_map(function ($c) use ($key, $value) {
            return [$key => $c->$value];
        }, $datas);
        return $data;
    }
    return [];
}


function getParentName($parentLocation)
{
    if ($parentLocation) {
        $parent = getParentName($parentLocation->parentLocation);
        $name = $parent . ' / ' . $parentLocation->name;
        return $name;
    } else {
        return null;
    }
}

function arr($array, $key, $seperator = '', $noneVal = '')
{
    if (isset($array[$key])) {
        return $seperator != '' ? $array[$key] . $seperator : $array[$key];
    } else {
        return $noneVal;
    }
}
function businessLocationName($bl)
{
    $id=$bl['id'];
    $appTitle=env('APP_TITLE',"erppos");
    $name=$id."_".$appTitle;
    return Cache::remember("bl_$name", 20000, function () use($bl) {
        $parentName = getParentName($bl['parentLocation']);
        $parent = $parentName ? substr($parentName, 2) . ' / ' : '';
        logger($parent . $bl['name']);
        return $parent . $bl['name'];
    });
}
function childLocationIDs($locationId)
{
    $LocationsIds = businessLocation::where('parent_location_id', $locationId)->select('id')->pluck('id')->toArray();
    $LocationsIds[] = $locationId;
    return $LocationsIds;
}
function getUserAccesssLocation(){
    $accessLocationIds = [];
    $accessLocations = old('access_location_ids', unserialize(Auth::user()->access_location_ids));
    foreach ($accessLocations as $accessLocation) {
        $childLocationIDs = childLocationIDs($accessLocation);
        $accessLocationIds = [...$accessLocationIds, ...$childLocationIDs];
    }
    logger($accessLocationIds);
    return $accessLocationIds;
}
function checkLocationAccessForTx($voucherLocationId){
    $accessUserLocation = getUserAccesssLocation();
    if ($accessUserLocation[0] != 0) {
        $checkAccessLocation = in_array($voucherLocationId, $accessUserLocation);
        if (!$checkAccessLocation) {
            return throw new Exception("Can't Edit this Purchase Voucher. Permission Denied On location");
        }
    }
    return true;
}
function address($address)
{
    // dd($address);
    return
        arr($address, 'address', ',') . "<br>" .
        arr($address, 'city', ',') . arr($address, 'state', ',') . arr($address, 'country', ',') . "<br>" .
        arr($address, 'zip_postal_code', '');
}

function getSystemData($key)
{
    try {
        return systemSetting::where('key', $key)->firstOrFail()->value;
    } catch (\Throwable $th) {
        return null;
    }
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




// format char to print in 48
function printTxtFormat(array $values=[],array $width=[],$break=true)
{
    $splitedValue=[];
    $lengths = [];
    foreach ($values as $key=>$value) {
        $splited=str_split($value, $width[$key]);
        $splitedValue=[...$splitedValue,$splited];
        $lengths=[...$lengths,count($splited)];
    }
    $longestIndex = '';
    $longestArrayLength = 0;

    foreach ($lengths as $index => $arrayLength) {
        if ($arrayLength > $longestArrayLength) {
            $longestIndex = $index;
            $longestArrayLength = $arrayLength;
        }
    }
    $str='';
    foreach ($splitedValue[$longestIndex] as $splitIndex=>$s) {
        $line='';
        foreach ($values as $index=>$value) {
            if($index == 0){
                $line.=isset($splitedValue[$index][$splitIndex]) ? str_pad($splitedValue[$index][$splitIndex], $width[$index], " ") : str_pad(" ", $width[$index], " ");
            }
            elseif($index ==count( $splitedValue)){
                $line.=isset($splitedValue[$index][$splitIndex]) ? str_pad($splitedValue[$index][$splitIndex], $width[$index], " ", STR_PAD_LEFT) : str_pad(" ", $width[$index], " ", STR_PAD_LEFT);
            }
            else{
                $line.=isset($splitedValue[$index][$splitIndex]) ? str_pad($splitedValue[$index][$splitIndex], $width[$index] + 1, " ", STR_PAD_LEFT) : str_pad(" ", $width[$index] + 1, " ", STR_PAD_LEFT);
            }
        }
        if($break){
            $str.=$line."\n";
        }else{
            $str.=$line;
        }
    }
    return $str;
}

function  eightyTxtFormat(...$values)  {
    $width=[22,8,18];
    return printTxtFormat($values,$width);
}
function eighty4Column(...$values){
    $width=[14,10,10,12];
    return printTxtFormat($values,$width);
}

function eighty5Column(...$values){
    $width=[11,8,10,8,9];
    return printTxtFormat($values,$width);
}
function discountTxt($type,$disAmt){
    if($type=='percentage'){
        return formatNumber($disAmt).'%';
    }elseif($type=='foc'){
        return '';
    }
    else{
        return '0';
    }
}
function calPercentage($type,$disAmt,$originalAmt){
    if($type=='percentage'){
        return formatNumber(($originalAmt*$disAmt)/100);
    }elseif($type=='foc'){
        return 0;
    }
    else{
        return formatNumber($disAmt ?? 0);
    }
}
function calPercentageNumber($type, $disAmt, $originalAmt)
{
    if ($type == 'percentage') {
        return ($originalAmt * $disAmt) / 100;
    } elseif ($type == 'foc') {
        return  $disAmt;
    } else {
        return $disAmt;
    }
}
function formatNumber($number) {
    return strpos($number, '.') !== false ? rtrim(rtrim($number, '0'), '.') : $number;
}
function formatNumberv2($number){
    return number_format($number, ($number == round($number) ? 0 : 4),'.',',');
}
function getKitAvailableQty($locationId, $productId)
{
    if (hasModule('ComboKit') && isEnableModule('ComboKit')) {
        return RoMService::getKitAvailableQty($locationId, $productId);
    }
    return $productId;
}

function getConsumeQty($product_id)
{
    try {
        return RoMService::getKitConsumeDetails($product_id);
    } catch (\Throwable $th) {
        return [];
    }
}



function queryFilter($query, $filterData = false, $dateColumn = 'created_at')
{
    if (isset($filterData['from_date']) && isset($filterData['to_date'])) {
        return $query->whereDate($dateColumn, '>=', $filterData['from_date'])
            ->whereDate($dateColumn, '<=', $filterData['to_date']);
    }
    return $query;
}

function purchaeTxData($filterData = false)
{
    $purchase = purchases::query()->where('is_delete', 0)->where("status", 'received');
    return queryFilter($purchase, $filterData, 'received_at');
}
function saleTxData($filterData = false)
{
    $purchase = sales::query()->where('is_delete', 0)->where("status", 'delivered');
    return queryFilter($purchase, $filterData,'sold_at');
}
function isFilter($arr){
  return  !empty($arr) ? $arr : false;
}
//purchase transactions
function totalPurchaseAmount($filterData = false)
{
    return purchaeTxData($filterData)->sum('total_purchase_amount');
}

function totalPurchaseDueAmount($filterData = false)
{
    return purchaeTxData($filterData)->sum('balance_amount');
}

function totalPurchaseAmountWithoutDis($filterData = false)
{
    return purchaeTxData($filterData)->sum(DB::raw('total_line_discount + purchase_amount'));
}

function totalPurchaseDiscountAmt($filterData = false)
{
    return purchaeTxData($filterData)->sum('total_discount_amount');
}


function totalOSTransactionAmount($filterData = false)
{
    $tranactions = openingStocks::query()->where('is_delete', 0);
    $resultPrice = queryFilter($tranactions, $filterData, 'opening_date')
                    ->sum('total_opening_amount');
    return $resultPrice;
}
function totalExpenseAmount($filterData = false)
{
    $tranactions = expenseTransactions::query();
    return queryFilter($tranactions, $filterData)->sum('expense_amount');
}
function totalExpenseDueAmount($filterData = false)
{
    $tranactions = expenseTransactions::query();
    return queryFilter($tranactions, $filterData)->sum('balance_amount');
}




//  sale transactions
function totalSaleAmount($filterData = false)
{
    return saleTxData($filterData)->sum('total_sale_amount');
}

function totalSaleDueAmount($filterData = false)
{
    return saleTxData($filterData)->sum('balance_amount');
}
function totalSaleAmountWithoutDis($filterData = false)
{
    return saleTxData($filterData)->sum('sale_amount');
}

function totalSaleDiscount($filterData = false)
{
    return saleTxData($filterData)->sum(DB::raw('total_item_discount + COALESCE(extra_discount_amount, 0)'));
}
function closingStocks($filterData = false)
{
    return closingStocksCal($filterData) ;
}

function closingStocksCal($filterData = false, $betweenDateRage = false)
{
    $stockHistories = stock_history::select('variation_id', DB::raw('SUM(increase_qty) as totalIncreaseQty'), DB::raw('SUM(decrease_qty) as totalDecreaseQty'))
        ->when(isset($filterData['from_date']) && !$betweenDateRage, function ($q) use ($filterData) {
            $q->whereDate('created_at', '<', $filterData['from_date']);
        })
        ->when(isset($filterData['from_date']) && isset($filterData['to_date']) && $betweenDateRage, function ($q) use ($filterData) {
            $q->whereDate('created_at', '>=', $filterData['from_date'])
                ->whereDate('created_at', '<=', $filterData['to_date']);
        })
        ->groupBy('variation_id')
        ->get()
        ->toArray();

    $avgPrice = CurrentStockBalance::select('variation_id',DB::raw("SUM(ref_uom_price * ref_uom_quantity)/SUM(ref_uom_quantity) as total_price"))
        ->when(isset($filterData['from_date']) && !$betweenDateRage, function ($q) use ($filterData) {
            $q->whereDate('created_at', '<', $filterData['from_date']);
        })
        ->groupBy('variation_id')
        ->get()
        ->keyBy('variation_id')
        ->toArray();
    $totalPrice = 0;
    foreach ($stockHistories as $i => $stockHistory) {
        $totalQTy = $stockHistory['totalIncreaseQty'] - $stockHistory['totalDecreaseQty'];
        // dd($stockHistory['totalIncreaseQty'] , $stockHistory['totalDecreaseQty']);
        $varId = $stockHistory['variation_id'];
        $price = $avgPrice[$varId]['total_price'] ?? 0;
        $totalPrice += $totalQTy * $price;
    }

    return $totalPrice;
}
function osWithFromCs($filterData = false)
{
    $decreasePrice = reportServices::cogsForOs($filterData);
    $IncreasePrice  = purchases::where('is_delete', '!=', '1')
        ->when(isset($filterData['from_date']) && isset($filterData['to_date']), function ($query) use ($filterData) {
            $query->whereDate('received_at', '<', $filterData['from_date']);
        })
        ->where("status", 'received')
        ->sum('total_purchase_amount');
    $IncreaseOsPrice  = openingStocks::where('is_delete', '!=', '1')
        ->when(isset($filterData['from_date']) && isset($filterData['to_date']), function ($query) use ($filterData) {
            $query->whereDate('opening_date', '<', $filterData['from_date']);
        })
        ->sum('total_opening_amount');
    $totalPrice = $IncreasePrice + $IncreaseOsPrice  - $decreasePrice;

    return $totalPrice;
}
function closingStocksCalWithCogs($filterData = false)
{
    $decreasePrice =reportServices::cogsForCs($filterData);

                    // dd($decreasePrice);
    $IncreasePrice  = purchases::where('is_delete', '!=', '1')
        ->when(isset($filterData['from_date']) && isset($filterData['to_date']), function ($query) use ($filterData) {
            $query->whereDate('received_at', '<=', $filterData['to_date']);
        })
        ->where("status", 'received')
        ->sum('total_purchase_amount');

    $IncreaseOsPrice  = openingStocks::where('is_delete', '!=', '1')
        ->when(isset($filterData['from_date']) && isset($filterData['to_date']), function ($query) use ($filterData) {
            $query->whereDate('opening_date','<=', $filterData['to_date']);
        })
        ->sum('total_opening_amount');

    $totalPrice = ($IncreasePrice + $IncreaseOsPrice) - $decreasePrice;






    // dd($IncreaseOsPrice);
    // dd($decreasePrice, $IncreasePrice);
    // dd($totalPrice, $decreasePrice);
    // $avgPrice = CurrentStockBalance::select('variation_id', DB::raw("SUM(ref_uom_price * ref_uom_quantity)/SUM(ref_uom_quantity) as total_price"))
    //     ->when(isset($filterData['from_date']) && !$betweenDateRage, function ($q) use ($filterData) {
    //         $q->whereDate('created_at', '<', $filterData['from_date']);
    //     })
    //     ->groupBy('variation_id')
    //     ->get()
    //     ->keyBy('variation_id')
    //     ->toArray();
    // $totalPrice = 0;
    // foreach ($stockHistories as $i => $stockHistory) {
    //     $totalQTy = $stockHistory['totalIncreaseQty'] - $stockHistory['totalDecreaseQty'];
    //     // dd($stockHistory['totalIncreaseQty'] , $stockHistory['totalDecreaseQty']);
    //     $varId = $stockHistory['variation_id'];
    //     $price = $avgPrice[$varId]['total_price'] ?? 0;
    //     $totalPrice += $totalQTy * $price;
    // }

    return $totalPrice;
}

function avgPriceCalculation($variationId){
    $avgPrice = CurrentStockBalance::select('variation_id',
     DB::raw("SUM(ref_uom_price * ref_uom_quantity)/SUM(ref_uom_quantity) as total_price")
     )
    ->when($variationId,function($query)use($variationId){
        $query->where('variation_id',$variationId);
    })
    ->groupBy('variation_id')
    ->first();
    // dd($avgPrice['total_price']/ $avgPrice['ref_qty']);
    return $avgPrice;
}

function getOptionName($type,$id){
    $result='';
    if($type=='All'){
        return 'All';
    }elseif($type== 'Category'){
        $result= Category::select('id', 'name', 'short_code as uniqCode')
                ->where('id',$id)
                ->first();
    }elseif($type== 'Product'){
        $result = Product::select('id', 'name', 'sku as uniqCode')
            ->where('id', $id)->first();
    }elseif($type== 'Variation'){
        $result = ProductVariation::whereNotNull('variation_template_value_id')
            ->where('product_variations.id', $id)
            ->select(
                'product_variations.id',
                DB::raw("CONCAT(products.name, '-', variation_template_values.name) AS name"),
                'product_variations.variation_sku as uniqCode'
            )
            ->leftJoin('products', 'product_variations.product_id', '=', 'products.id')
            ->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
            ->first();
    }
    // logger(isset($result['uniqCode']));
    $name=isset($result['name'] ) ?$result['name'] :'';
    $uniqueCode=isset($result['uniqCode']) ? ' (' . $result['uniqCode']. ')' : '';
    $text= $name. $uniqueCode ;
    return $text;
}



function getProductNameInfos($varId){
   $variation= ProductVariation::select('id','product_id')->where('id',$varId)
                ->with('product:id,name','variationTemplateValue:id,name')
                ->first()->toArray();

    return [
        'product_name'=>arr($variation['product'],'name'),
        'variation_name'=>arr($variation['variation_template_value'],['name'])
    ];
}
function mnumber($number)
{
    if ($number >= 10000000000) {
        return fprice(round($number / 1000000000, 1)) . 'B';
    } elseif ($number >= 100000000) {
        return fprice(round($number / 1000000, 1)) . 'M';
    } elseif ($number >= 1000000) {
        return fprice(round($number / 1000, 1)) . 'K';
    } else {
        return fprice($number);
    }
}

function getUomName($uom_id)
{
    $uom = UOM::where('id', $uom_id)->first()->name;

    if ($uom){
       return $uom;
    }

    return '';
}

function getTotalCurrentBalance($variation_id)
{
    $totalCurrentStock = CurrentStockBalance::where('variation_id', $variation_id)->sum('current_quantity');
    return intval($totalCurrentStock);
}

function newOrderCount()
{
    $totalCurrentStock = EcommerceOrder::select('viewed_at')->whereNull('viewed_at')->count();
    return intval($totalCurrentStock);
}
function isRead()
{
    $totalCurrentStock = EcommerceOrder::select('viewed_at')->whereNull('viewed_at')->count();
    return intval($totalCurrentStock);
}
function productSummary($campaignId,$userId,$dateFilter){
    return sale_details::query()
    ->select(
    'products.name',
    'uom.short_name as uom',
    'categories.name as category_name',
    'categories.id as category_id',
        DB::raw("SUM(stock_histories.decrease_qty) as totalQty"),
        DB::raw("SUM(total_sale_amount) as totalPrice"),
        DB::raw("SUM('total_sale_amount') as ttp")
    )
    ->leftJoin('sales', 'sale_details.sales_id', '=', 'sales.id')
    ->where('sales.channel_type', '=', 'campaign')
    ->leftJoin('stock_histories', 'stock_histories.transaction_details_id', 'sale_details.id')
    ->leftJoin('products', 'stock_histories.product_id', 'products.id')
    ->leftJoin('uom', 'stock_histories.ref_uom_id', 'uom.id')
    ->where( 'stock_histories.transaction_type', '=','sale')
    ->leftJoin('fscampaign', 'sales.channel_id', 'fscampaign.id')
    ->leftJoin('categories', 'products.category_id', 'categories.id')
    ->leftJoin('business_locations  as outlet', 'fscampaign.business_location_id', '=', 'outlet.id')
    ->leftJoin('receipe_of_material_details', 'receipe_of_material_details.component_variation_id', 'stock_histories.product_id')
    ->where('sale_details.is_delete','=', 0)
    ->where('sales.is_delete','=',0)
    ->where('receipe_of_material_details.id',null)
    ->where('fscampaign.id','=',$campaignId)
    ->where('sales.sold_by','=',$userId)
    ->orderBy('categories.name','ASC')
    ->whereDate('sales.sold_at',$dateFilter)
    ->groupBy('sale_details.variation_id','products.name', 'categories.name','categories.id', 'uom.short_name')
    ->get()->groupBy('category_name');
}


function calBalanceQtyForDesc($increase_qty,$decrease_qty,$balanceQtyBeforePage){
    if ($increase_qty >0){
       return  $balanceQtyBeforePage-=  $increase_qty;
    }elseif ($decrease_qty >0){
       return $balanceQtyBeforePage+=  $decrease_qty;
    }else{
        return $balanceQtyBeforePage;
    }
}

function calBalanceQtyForAsc($increase_qty,$decrease_qty,$balanceQtyBeforePage){
    if ($increase_qty >0){
       return  $balanceQtyBeforePage+=  $increase_qty;
    }elseif ($decrease_qty >0){
       return $balanceQtyBeforePage-=  $decrease_qty;
    }
}


function formatAddress($address)
{
    $postalZipCode = optional($address)->postal_zip_code == 0 ? '' : $address->postal_zip_code;
    $addressLine1 = optional($address)->address_line_1;
    $addressLine2 = optional($address)->address_line_2 == null ? '' : $address->address_line_2;
    $townshipName = optional($address)->township_en_name;

    return implode(', ', array_filter([$postalZipCode, $addressLine1, $addressLine2, $townshipName]));
}
