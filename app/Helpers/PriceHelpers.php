<?php

use Modules\ExchangeRate\Entities\exchangeRates;

function DiscAmountCal($originalPrice,$discType,$disAmount)
{
    if ($discType == 'fixed') {
        return $disAmount;
    } elseif ($discType == 'percentage') {
        return $originalPrice * ($disAmount / 100);
    }
    return false;
}


function percentageCalc($whoelNumer, $percentage)
{
     return $whoelNumer+ ($whoelNumer * ($percentage / 100));
}
function roundDown($number,$power){
    return floor($number * $power) / $power;
}


function exchangeCurrency($amount, $fromCurrencyid, $toCurrencyid) {
    if(hasModule('ExchangeRate') && isEnableModule('ExchangeRate')){
        $fromCurrency=exchangeRates::where('id',$fromCurrencyid)->first();
        $toCurrency=exchangeRates::where('id',$toCurrencyid)->first();
        if($fromCurrency !=null && $toCurrency != null){
            $inDollar = $amount / $fromCurrency->rate;
            $convertedAmount = $inDollar * $toCurrency->rate ;
            return $convertedAmount;
        }
        return $amount;
    }
    return $amount;
}
