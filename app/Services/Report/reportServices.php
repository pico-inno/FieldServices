<?php

namespace App\Services\Report;

use App\Models\sale\sales;
use App\Models\openingStocks;
use App\Models\expenseReports;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\expenseTransactions;
use App\Models\purchases\purchases;

class reportServices
{
    public static function grossProfit($filterData=''){
        // dd($filterData);
        $totalPurchaseAmount= totalPurchaseAmount($filterData);
        $totalSaleAmount = totalSaleAmount($filterData);
        return $totalSaleAmount- $totalPurchaseAmount;
    }
    public static function netProfit($filterData = ''){
        //outcome

        $totalPurchaseAmount =  totalPurchaseAmount($filterData);
        $totalOSAmount = totalOSAmount($filterData);
        $totalExpenseAmount = totalExpenseAmount();
        $totalSaleAmount = totalSaleAmount($filterData);
        $closingStocks = closingStocks();



        $totalOutcome=(int) $totalPurchaseAmount+ $totalOSAmount + $totalExpenseAmount;
        $totalIncomeAmount=$totalSaleAmount+ $closingStocks;
        // dd($totalSaleAmount, $closingStocks);


        // dd( $totalIncomeAmount - $totalOutcome, $totalSaleAmount, $totalPurchaseAmount, $closingStocks);
        return $totalIncomeAmount - $totalOutcome;
    }
}

