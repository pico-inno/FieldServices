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

function roundDown($number,$power){
    return floor($number * $power) / $power;
}


function exchangeCurrency($amount, $fromCurrencyid, $toCurrencyid) {
    if(class_exists(Modules\ExchangeRate\Entities\exchangeRates\exchangeRates::class)){
        $fromCurrency=exchangeRates::where('id',$fromCurrencyid)->first();
        $toCurrency=exchangeRates::where('id',$toCurrencyid)->first();
        if($fromCurrency !=null && $toCurrency != null){
            $inDollar = $amount / $fromCurrency->rate;
            $convertedAmount = $inDollar * $toCurrency->rate ;
            return $convertedAmount;
        }
        return $amount;
    }
}
