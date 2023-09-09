<?php

namespace App\Http\Controllers;

use App\Models\CurrentStockBalance;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use DateTime;
use Illuminate\Http\Request;
use App\Models\stock_history;
use Yajra\DataTables\DataTables;

class stockHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index(){
        $locations=businessLocation::where('is_active',1)->get();
        return view('App.stockHistory.index',compact('locations'));
    }

    public function historyList()
    {
        $currencyDp=getSettingValue('currency_decimal_places');
        $quantityDp=getSettingValue('quantity_decimal_places');
        $histories = stock_history::with('productVariation')->get();

        $openingBalances = [];

        foreach ($histories as $history) {
            $product_id = $history->product_id;
            $variation_id = $history->variation_id;
            $location_id = $history->business_location_id;


            if (!isset($openingBalances[$product_id][$variation_id][$location_id])) {

                $openingBalances[$product_id][$variation_id][$location_id] = 0;
            }

            $increase_qty = $history->increase_qty ?? 0;
            $decrease_qty = $history->decrease_qty ?? 0;

            $increaseAble = $openingBalances[$product_id][$variation_id][$location_id] + $increase_qty;
            $balance_qty = $increaseAble - $decrease_qty;


            $openingBalances[$product_id][$variation_id][$location_id] = $balance_qty;


            $history->balance_qty = $balance_qty;

        }





        return DataTables::of($histories)
            ->editColumn('location',function($history) {
                return $history->business_location->name;
            })
            ->editColumn('product', function ($history) {
                $variation=$history->productVariation->variationTemplateValue;
                $variationName=$variation ? ' ('.$variation->name.')':'';
                $productName=$history->product['name'];
                return $productName . $variationName ;
            })
            ->editColumn('date', function ($history) {
                if($history->transaction_type=='sale'){
                    $created_at=  $history->saleDetail->sale->created_at;
                }else if($history->transaction_type=='purchase'){
                    $created_at=  $history->purchaseDetail->created_at;
                }else if($history->transaction_type=='stock_out'){
                    $created_at=  $history->StockoutDetail->created_at;
                }else if($history->transaction_type=='stock_in'){
                    $created_at=  $history->stockInDetail->created_at;
                }else if($history->transaction_type=='opening_stock'){
                    $created_at=  $history->openingStockDetail->created_at;
                }else if($history->transaction_type=='adjustment'){
                    $created_at=  $history->adjustmentDetail->created_at;
                }else if($history->transaction_type=='transfer'){
                    $created_at=  $history->StockTransferDetail->created_at;
                }
                if($created_at){
                    $dateTime = DateTime::createFromFormat("Y-m-d H:i:s",$created_at);
                    $formattedDate = $dateTime->format("m-d-Y " );
                    $formattedTime = $dateTime->format(" h:i A " );
                    return $formattedDate.'<br>'.'('.$formattedTime.')';
                }
                return '-';
            })
            ->editColumn('from',function($history){
                if($history->transaction_type=='sale' || $history->transaction_type=='stock_out'){
                    return $history->business_location->name;
                }else{
                    if($history->transaction_type=='purchase'){
                        return  $history->purchaseDetail->purchase->supplier->company_name;
                    }else if($history->transaction_type=='stock_in'){
                        return  $history->stockInDetail->purchaseDetail->purchase->supplier->company_name;
                    }
                }
                return $history->business_location->name;
            })
            ->editColumn('to',function($history){
                if($history->transaction_type=='sale' ){
                    $customer=$history->saleDetail->sale->customer;
                    return  $customer ? $customer['first_name'] : '';
                }else{
                    if($history->transaction_type=='purchase' || $history->transaction_type=='stock_in'){
                            return $history->business_location->name;
                    }
                }
                return $history->business_location->name;
            })
            ->editColumn('increase_qty', function ($history) use($quantityDp) {
                // return "<span class='badge badge-primary'>". $history->increase_qty .' '. $history->uom->short_name ."</span>";
                return  $history->increase_qty >0 ? "<span class='text-success fw-bold'>".number_format( $history->increase_qty,$quantityDp,'.') ."</span>":'-' ;
            })
            ->editColumn('decrease_qty', function ($history) use($quantityDp) {
                // return "<span class='badge badge-danger'>". $history->decrease_qty  . "</span>";
                return $history->decrease_qty >0 ? "<span class='text-danger fw-bold'>". number_format($history->decrease_qty,$quantityDp) ."</span>":'-' ;
            })
            ->editColumn('balance_qty', function ($history) {
                return $history->balance_qty;
            })
            ->editColumn('uom', function ($history) {
                $uom=$history->uom->short_name;
                return "<span class='pe-3'>$uom</span>";
            })

            ->editColumn('reference', function ($history) {

                $html = '-';
                if($history->transaction_type=='sale'){
                    $voucherNo=$history->saleDetail->sale->sales_voucher_no;
                    $html = "
                    <span class='text-info'>$voucherNo</span><br>
                    ";
                }else if($history->transaction_type=='purchase'){
                    $voucherNo=$history->purchaseDetail->purchase->purchase_voucher_no;
                    $html = "
                    <span class='text-success'>$voucherNo</span><br>
                    ";
                }else if($history->transaction_type=='stock_in'){
                    $voucherNo=$history->stockInDetail->stockin->stockin_voucher_no;
                    $html = "
                    <span class='text-primary'>$voucherNo</span><br>
                    ";
                }else if($history->transaction_type=='stock_out'){
                    $voucherNo=$history->StockoutDetail->stockOut->stockout_voucher_no;
                    $html = "
                    <span class='text-danger'>$voucherNo</span><br>
                    ";
                }
                else if($history->transaction_type=='opening_stock'){
                    $voucherNo=$history->openingStockDetail->openingStock->opening_stock_voucher_no;
                    $html = "
                    <span class='text-dark'>$voucherNo</span><br>
                    ";
                }
                else if($history->transaction_type=='transfer'){
                    $voucherNo=$history->StockTransferDetail->stockTransfer->transfer_voucher_no;
                    $html = "
                    <span class='text-info'>$voucherNo</span><br>
                    ";
                }

                else if($history->transaction_type=='adjustment'){
                    $voucherNo=$history->adjustmentDetail->stockAdjustment->adjustment_voucher_no;
                    $html = "
                    <span class='text-warning'>$voucherNo</span><br>
                    ";
                }
                return $html;
            })
            // ->editColumn('transaction_type', function ($history) {
            //     $html = '';
            //     if ($history->transaction_type == 'purchase') {
            //         $html = "<span class='badge badge-success'> $history->transaction_type </span>";
            //     } elseif ($history->transaction_type == 'sale') {
            //         $html = "<span class='badge badge-primary'> $history->transaction_type</span>";
            //     } elseif ($history->transaction_type == 'transfer') {
            //         $html = "<span class='badge badge-warning'>$history->transaction_type</span>";
            //     } elseif ($history->transaction_type == 'stock_in') {
            //         $html = "<span class='badge badge-info'>$history->transaction_type</span>";
            //     } elseif ($history->transaction_type == 'stock_out') {
            //         $html = "<span class='badge badge-primary'>$history->transaction_type</span>";
            //     }
            //     return $html;
            //     // return $purchase->supplier['company_name'] ?? $purchase->supplier['first_name'];
            // })
            ->rawColumns(['increase_qty','decrease_qty','transaction_type','uom','date','reference','balance_qty'])
            ->make(true);

    }
}
