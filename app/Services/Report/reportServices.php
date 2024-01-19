<?php

namespace App\Services\Report;

use App\Helpers\UomHelper;
use App\Models\Product\Product;
use App\Models\sale\sales;
use App\Models\openingStocks;
use App\Models\expenseReports;
use App\Models\Stock\StockAdjustment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\expenseTransactions;
use App\Models\purchases\purchases;
use App\Models\sale\sale_details;

class reportServices
{
    //Adjustment
    public static function adjustmentSummary($filterData){
        $reportService = new reportServices();
        return $reportService->adjustmentSummaryFilterProcess($filterData);
    }

    public static function adjustmentDetails($filterData){
        $reportService = new reportServices();
        return $reportService->adjustmentDetailsFilterProcess($filterData);
    }
    //Adjustment
    public static function grossProfit($filterData=false){
            $totalSaleAmount = totalSaleAmount($filterData);
            $cogs=0;
            $sales= saleTxData($filterData)->select('id')
                    ->with('sale_details:id,sales_id,quantity,uom_id,variation_id', 'sale_details.uom:id,unit_category_id',
                    'sale_details.uom.unit_category:id,name',
                    'sale_details.uom.unit_category.uomByCategory:id,name,unit_category_id,unit_type')
                    ->get();
            $avgPriceContainer=[];
            foreach ($sales as $sale) {
                foreach ($sale->sale_details as $sd) {
                    $uoms=$sd->uom->unit_category->toArray()['uom_by_category'] ?? [];
                    $refUomQty=0;
                    foreach ($uoms as $uom) {
                        if($uom['unit_type']== 'reference'){
                            $refUomQty = UomHelper::changeQtyOnUom($sd->uom_id,$uom['id'],$sd->quantity);
                            break;
                        }
                    }
                    if(!isset($avgPriceContainer[$sd->variation_id])){
                        $avgPrice = avgPriceCalculation($sd->variation_id, $filterData);
                        $avgPriceContainer[$sd->variation_id] = $avgPrice;
                    }else{
                        $avgPrice= $avgPriceContainer[$sd->variation_id];
                    }
                    $cogs+= $avgPrice->total_price * $refUomQty;
                }
            }
            $result= $totalSaleAmount-$cogs;
        return $result;
    }
    //Adjustment
    public static function grossProfit2($filterData = false)
    {
        $totalSaleAmount = totalSaleAmount($filterData);
        $cogs = 0;
        $sales = sale_details::query()
        ->select(
        //     'sale_details.id as detial_id', 'sale_details.product_id','sale_details.quantity',
        // 'lot_serial_details.current_stock_balance_id',
        //     'lot_serial_details.uom_quantity',
        //     'current_stock_balance.ref_uom_price',
            DB::raw('sale_details.subtotal_with_discount-(lot_serial_details.uom_quantity * current_stock_balance.ref_uom_price) as profit'))
        ->leftJoin('lot_serial_details', 'lot_serial_details.transaction_detail_id','=', 'sale_details.id')
        ->leftJoin('current_stock_balance', 'lot_serial_details.current_stock_balance_id','=', 'current_stock_balance.id')
        ->where('lot_serial_details.transaction_type','=','sale')
        // ->with(
        //     'sale_details:id,sales_id',
        //     'sale_details.lotSerialDetail',
        //     'sale_details.lotSerialDetail.current_stock_balance:id,ref_uom_price',
        // )
        ->orderBy('sale_details.id','DESC')
        ->get()
            ->sum('profit');
        // $avgPriceContainer = [];
        // foreach ($sales as $sale) {
        //     foreach ($sale->sale_details as $sd) {
        //         $uoms = $sd->uom->unit_category->toArray()['uom_by_category'] ?? [];
        //         $refUomQty = 0;
        //         foreach ($uoms as $uom) {
        //             if ($uom['unit_type'] == 'reference') {
        //                 $refUomQty = UomHelper::changeQtyOnUom($sd->uom_id, $uom['id'], $sd->quantity);
        //                 break;
        //             }
        //         }
        //         if (!isset($avgPriceContainer[$sd->variation_id])) {
        //             $avgPrice = avgPriceCalculation($sd->variation_id, $filterData);
        //             $avgPriceContainer[$sd->variation_id] = $avgPrice;
        //         } else {
        //             $avgPrice = $avgPriceContainer[$sd->variation_id];
        //         }
        //         $cogs += $avgPrice->total_price * $refUomQty;
        //     }
        // }
        // $result = $totalSaleAmount - $cogs;
        return $sales;
    }
    public static function netProfit($filterData = ''){
        //outcome

        // $totalPurchaseAmount =  totalPurchaseAmount($filterData);
        // if (!$filterData) {
        //     $totalOSAmount = totalOSTransactionAmount($filterData);
        // } else {
        //     $totalOSAmount = totalOSTransactionAmount($filterData) ;
        // }
        $totalExpenseAmount = totalExpenseAmount();
        // $totalSaleAmount =(int) totalSaleAmount($filterData);
        // $closingStocks = closingStocksCal($filterData);



        // $totalOutcome=(int) $totalPurchaseAmount+ $totalOSAmount + $totalExpenseAmount;
        // $totalIncomeAmount=$totalSaleAmount + $closingStocks;

        // dd($totalOutcome, $totalIncomeAmount);
        // dd($totalIncomeAmount, $totalOutcome);
        // dd($totalSaleAmount, $closingStocks);
        $grossProfit=self::grossProfit($filterData );
        return  $grossProfit - $totalExpenseAmount;
    }



    private function adjustmentSummaryFilterProcess($filterData)
    {
        $dateRange = $filterData['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();

        $stockAdjustment = StockAdjustment::where('is_delete', 0)
            ->with(['businessLocation:id,name', 'createdPerson'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($filterData['filter_status'] !== 'all') {
            $status = '';

            switch ($filterData['filter_status']) {
                case 'pending':
                    $status = 'pending';
                    break;
                case 'completed':
                    $status = 'completed';
                    break;
            }

            $stockAdjustment->where('status', $status);
        }

        if ($filterData['filter_locations'] != 0) {
            $locationId = childLocationIDs($filterData['filter_locations']);
            $stockAdjustment->whereIn('business_location', $locationId);
        }

        $stockAdjustmentData = $stockAdjustment->get();

        foreach ($stockAdjustmentData as $data) {
            $data->adjustment_date = fDate($data->created_at);
        }

        return $stockAdjustmentData;
    }

    private function adjustmentDetailsFilterProcess($filterData)
    {
        $filterProduct = $filterData['filter_product'];
        $filterAdjType = $filterData['filter_adj_type'];
        $dateRange = $filterData['filter_date'];
        $dates = explode(' - ', $dateRange);

        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();

        $query = StockAdjustment::where('is_delete', 0)
            ->with('adjustmentDetails')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($filterData['filter_locations'] != 0) {
            $query->where('business_location', $filterData['filter_locations']);
        }

        $adjustmentDetails = $query->get()->pluck('adjustmentDetails')->flatten()->filter(function ($stockDetail) use ($filterAdjType) {
            return $filterAdjType == 'all' || $stockDetail['adjustment_type'] == $filterAdjType;
        });

        $productIds = $adjustmentDetails->pluck('product_id')->unique()->toArray();

        $finalProduct = Product::select('id', 'name', 'product_code', 'sku', 'product_type', 'brand_id', 'category_id')
            ->with(['category:id,name', 'brand:id,name', 'productVariations' => function ($query) {
                $query->select('id', 'product_id', 'variation_template_value_id', 'default_purchase_price', 'default_selling_price', 'variation_sku')
                    ->with(['variationTemplateValue' => function ($query) {
                        $query->select('id', 'name', 'variation_template_id')
                            ->with(['variationTemplate:id,name']);
                    }]);
            }, 'uom' => function ($q) {
                $q->with(['unit_category' => function ($q) {
                    $q->with('uomByCategory');
                }]);
            }
            ])
            ->whereIn('id', $productIds);

        if ($filterProduct != 0) {
            $finalProduct->where('id', $filterProduct);
        }

//        if ($filterCategory != 0) {
//            $finalProduct->where('category_id', $filterCategory);
//        }
//
//        if ($filterBrand != 0) {
//            $finalProduct->where('brand_id', $filterBrand);
//        }
//
        $finalProduct = $finalProduct->get()->toArray();

        $result = [];

        foreach ($adjustmentDetails as $stockDetail) {
            $productId = $stockDetail['product_id'];
            $variationId = $stockDetail['variation_id'];

            foreach ($finalProduct as $product) {
                if ($product['id'] == $productId) {
                    $variations = $product['product_variations'];

                    foreach ($variations as $variation) {
                        if ($variation['id'] == $variationId) {

                            $variationProduct = [
                                'id' => $product['id'],
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'variation_sku' => $product['product_type'] == 'variable' ? $variation['variation_sku'] : "",
                                'category_name' => $product['category']['name'] ?? '',
                                'brand_name' => $product['brand']['name'] ?? '',
                                'uom_short_name' => $stockDetail['uom']['short_name'],
                                'uom_name' => $stockDetail['uom']['name'],
                                'adjustment_type' => $stockDetail['adjustment_type'],
                                'balance_quantity' => number_format($stockDetail['balance_quantity'], 2),
                                'gnd_quantity' => number_format($stockDetail['gnd_quantity'], 2),
                                'adj_quantity' => number_format($stockDetail['adj_quantity'], 2),
                                'uom_price' => number_format($stockDetail['uom_price'], 2),
                                'subtotal' => number_format($stockDetail['subtotal'], 2),
                                'variation_template_name' => $variation['variation_template_value']['variation_template']['name'] ?? '',
                                'variation_value_name' => $variation['variation_template_value']['name'] ?? '',
                                'currency_name' => settings('currency')->currency->symbol,
                            ];

                            $result[] = $variationProduct;
                        }
                    }
                }
            }

        }

        return $result;
    }
}

