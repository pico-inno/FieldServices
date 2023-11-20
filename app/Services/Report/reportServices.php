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
    public static function grossProfit(){
        $totalPurchaseAmount= purchases::where('is_delete',0)->sum('total_purchase_amount');
        $totalSaleAmount = sales::where('is_delete', 0)->sum('total_sale_amount');
        return $totalSaleAmount- $totalPurchaseAmount;
    }
    public static function netProfit(){
        //outcome
        $totalPurchaseAmount = purchases::where('is_delete', 0)->sum('total_purchase_amount');
        $totalOSAmount = openingStocks::where('is_delete', 0)->sum('total_opening_amount');
        $totalExpenseAmount = expenseTransactions::sum('expense_amount');

        $totalOutcome=(int) $totalPurchaseAmount+ $totalOSAmount + $totalExpenseAmount;

        $totalSaleAmount = sales::where('is_delete', 0)->sum('total_sale_amount');
        $closingStocks = CurrentStockBalance::sum(DB::raw('ref_uom_price * current_quantity'));

        $totalIncomeAmount=$totalSaleAmount+ $closingStocks;
        // dd($totalSaleAmount, $closingStocks);


        // dd( $totalIncomeAmount - $totalOutcome, $totalSaleAmount, $totalPurchaseAmount, $closingStocks);
        return $totalIncomeAmount - $totalOutcome;
    }
}

