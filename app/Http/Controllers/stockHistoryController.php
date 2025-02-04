<?php

namespace App\Http\Controllers;

use DateTime;
use stockout;
use App\Helpers\UomHelper;
use Illuminate\Http\Request;
use App\Models\stock_history;
use Yajra\DataTables\DataTables;
use App\Models\sale\sale_details;
use App\Models\CurrentStockBalance;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use Modules\StockInOut\Entities\StockoutDetail;
use PDO;

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
        $histories = stock_history::query()
                    ->select('stock_histories.*','products.name')
                    ->leftJoin('products', 'stock_histories.product_id', '=', 'products.id')
                    ->orderBy('id','DESC')
                    ->with(
                        'productVariation.variationTemplateValue',
                        'business_location:id,name',
                    )
                    ->when(!hasModule('StockInOut') ,function($q){
                        $q->whereNotIn('stock_histories.transaction_type', ['stock_in', 'stock_out']);
                    })
                    ;
                    // ->get();
        // $openingBalances = [];

        // foreach ($histories as $history) {
        //     $product_id = $history->product_id;
        //     $variation_id = $history->variation_id;
        //     $location_id = $history->business_location_id;


        //     if (!isset($openingBalances[$product_id][$variation_id][$location_id])) {

        //         $openingBalances[$product_id][$variation_id][$location_id] = 0;
        //     }

        //     $increase_qty = $history->increase_qty ?? 0;
        //     $decrease_qty = $history->decrease_qty ?? 0;

        //     $increaseAble = $openingBalances[$product_id][$variation_id][$location_id] + $increase_qty;
        //     $balance_qty = $increaseAble - $decrease_qty;


        //     $openingBalances[$product_id][$variation_id][$location_id] = $balance_qty;


        //     $history->balance_qty = $balance_qty;

        // }





        return DataTables::of($histories)
            ->filter(function ($history) {
                if (rtrim(request('search')['value'])) {
                    $keyword= request('search')['value'];
                    $history->where('products.name', 'like',"%".$keyword."%");
                }else{
                    $history;
                }

            })
            ->filterColumn('from', function ($data, $id) {
                if($id !='all' && rtrim($id)){
                    $data->where("stock_histories.business_location_id", $id);
                }
            })


            ->filter(function ($history) {
                if (rtrim(request('search')['value'])) {
                    $keyword = request('search')['value'];
                    $history->where('products.name', 'like', "%" . $keyword . "%");
                }
            })
            ->addColumn('location',function($history) {
                return $history->business_location->name;
            })
            ->addColumn('product', function ($history) {
                $variation = $history->productVariation;
                if ($variation) {
                    $variationTemplateValue = $variation->variationTemplateValue;
                    $variationName = $variationTemplateValue ? ' (' . $variationTemplateValue->name . ')' : '';
                    $productName = $history->product['name'];
                    return $productName . $variationName;
                }
            })
            ->editColumn('created_at', function ($history) {
                return fdate($history->created_at,false,true);
            })
            ->addColumn('from',function($history){
                if($history->transaction_type=='sale' || $history->transaction_type=='stock_out'){
                    return $history->business_location->name;
                }else{
                    if($history->transaction_type=='purchase'){
                        return  arr($history->purchaseDetail->purchase->supplier,'company_name','','no supplier found');
                    }else if($history->transaction_type=='stock_in'){
                        return  arr($history->stockInDetail->purchaseDetail->purchase->supplier, 'company_name', '', 'no supplier found');
                    }
                }
                return $history->business_location->name;
            })
            ->addColumn('to',function($history){
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
            ->addColumn('increase_qty', function ($history) use($quantityDp) {
                // return "<span class='badge badge-primary'>". $history->increase_qty .' '. $history->uom->short_name ."</span>";
                return  $history->increase_qty >0 ? "<span class='text-success fw-bold'>".number_format( $history->increase_qty,$quantityDp,'.') ."</span>":'-' ;
            })
            ->addColumn('decrease_qty', function ($history) use($quantityDp) {
                // return "<span class='badge badge-danger'>". $history->decrease_qty  . "</span>";
                return $history->decrease_qty >0 ? "<span class='text-danger fw-bold'>". number_format($history->decrease_qty,$quantityDp) ."</span>":'-' ;
            })
            ->addColumn('balance_qty', function ($history) {
                return $history->balance_qty;
            })
            ->addColumn('uom', function ($history) {
                $uom=$history->uom->short_name;
                return "<span class='pe-3'>$uom</span>";
            })

            ->addColumn('reference', function ($history) {

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
                    $html = "";
                    if (hasModule('StockInOut') && isEnableModule('StockInOut')) {
                        $voucherNo=$history->stockInDetail->stockin->stockin_voucher_no;
                        $html = "
                        <span class='text-primary'>$voucherNo</span><br>
                        ";
                    }
                }else if($history->transaction_type=='stock_out'){

                $html = "";
                    if(hasModule('StockInOut') && isEnableModule('StockInOut')){
                        $voucherNo = $history->StockoutDetail->stockOut->stockout_voucher_no;
                        $html = "
                        <span class='text-danger'>$voucherNo</span><br>
                        ";
                    }
                }
                else if($history->transaction_type=='opening_stock'){
                    $voucherNo=$history->openingStockDetail->openingStock->opening_stock_voucher_no;
                    $html = "
                    <span class='text-dark'>$voucherNo</span><br>
                    ";
                }
                else if($history->transaction_type=='transfer'){
                    $voucherNo=$history->StockTransferDetail->stockTransfer->transfer_voucher_no  ?? '';
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
        // ->toJson();

    }
}
