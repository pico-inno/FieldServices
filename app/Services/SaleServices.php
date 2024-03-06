<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Helpers\UomHelper;
use App\Models\sale\sales;
use App\Models\stock_history;
use App\Models\Product\Product;
use App\Models\lotSerialDetails;
use App\Models\sale\sale_details;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use Illuminate\Support\Facades\Auth;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use Modules\ComboKit\Services\RoMService;
use App\Services\packaging\packagingServices;

class SaleServices
{
    private $setting;
    private $currency;
    private $accounting_method;
    public function __construct()
    {
        $settings = businessSettings::select('lot_control', 'currency_id', 'accounting_method', 'enable_line_discount_for_sale')->with('currency')->first();
        $this->setting = $settings;
        $this->currency = $settings->currency ?? null;
        $this->accounting_method = $settings->accounting_method ?? null;
        ini_set('max_input_vars', 2000000);
    }


    /**
     * create
     *
     * @param  mixed $data
     * @return void
     */
    public function create($data)
    {
        $lastSaleId = sales::orderBy('id', 'DESC')->select('id')->first()->id ?? 0;
        return sales::create([
            'business_location_id' => $data->business_location_id,
            'sales_voucher_no' => saleVoucher($lastSaleId),
            'contact_id' => $data->contact_id,
            'status' => $data->status,
            'sale_amount' => $data->sale_amount,
            'total_item_discount' => $data->total_item_discount,
            'extra_discount_type' => $data->extra_discount_type,
            'extra_discount_amount' => $data->extra_discount_amount,
            'total_sale_amount' => $data->total_sale_amount,
            'paid_amount' => $data->paid_amount,
            'payment_status' => $data->payment_status,
            'pos_register_id' => $data->pos_register_id ?? null,
            'table_id' => $data->table_id,
            'balance_amount' => $data->balance_amount,
            'currency_id' => $data->currency_id,
            'sold_at' => $data->sold_at,
            'sold_by' => Auth::user()->id,
            'created_by' => Auth::user()->id,
            'channel_type'=>$data->channel_type ?? null,
            'channel_id'=>$data->channel_id,
        ]);
    }
    public function update($id,$data){
        $sales = sales::where('id', $id)->first();
        $balanceAmount= $data['total_sale_amount'] -$sales['paid_amount'];
        $saleData = [
            'contact_id' => $data['contact_id'],
            'status' => $data['status'],
            'sale_amount' => $data['sale_amount'],
            'total_item_discount' => $data['total_item_discount'],
            'extra_discount_type' => $data['extra_discount_type'],
            'extra_discount_amount' => $data['extra_discount_amount'],
            'total_sale_amount' => $data['total_sale_amount'],
            'balance_amount' => $balanceAmount,
            'currency_id' => $data['currency_id'],
            'updated_by' => Auth::user()->id ?? $id,
            'sold_at' => $data['sold_at'] ?? now(),
        ];
        if ($data['type'] == 'pos') {
            $saleData['paid_amount'] = $data['paid_amount'];
            $saleData['balance_amount'] = $data['balance_amount'];
        }
        $sales->update($saleData);
        return $sales;
    }

    /**
     * saleDetailCreation
     *
     * @param  mixed $request
     * @param  object $sale_data
     * @param  array $sale_details
     * @param  mixed $resOrderData
     * @return void
     */
    public function saleDetailCreation($request, Object $sale_data, array $sale_details, $resOrderData = null)
    {
        $packaging=new packagingServices();

        $parentSaleItems=[];
        foreach ($sale_details as $key=>$sale_detail) {
            $product = Product::where('id', $sale_detail['product_id'])->select('product_type','id','uom_id')->with('uom')->first();

            //SaleDetailCreate
            $sale_details_data = $this->saleDetailData($request,$sale_data,$sale_detail,$parentSaleItems,$resOrderData);

            $created_sale_details = sale_details::create($sale_details_data);

            // Rom
            $this->createRomTx($created_sale_details,$request->business_location_id);
            //packaging
            $packaging->packagingForTx($sale_detail, $created_sale_details['id'], 'sale');
            if (isset($sale_detail['isParent'])) {
                $parentSaleItems[$sale_detail['isParent']]= $created_sale_details;
            }
            // manage stock transactions
            $this->txManager($request,$sale_data,$created_sale_details,$product,$sale_detail);
        }
        return;
    }

    public function saleDetailData($request,$sale_data,$sale_detail, $parentSaleItems,$resOrderData){
        if (isset($sale_detail['parentUniqueNameId']) && $sale_detail['parentUniqueNameId'] != 'false' && $sale_detail['parentUniqueNameId'] != 'null') {
            if (isset($parentSaleItems[$sale_detail['parentUniqueNameId']])) {
                $parentId = $parentSaleItems[$sale_detail['parentUniqueNameId']]->id;
            } else {
                $parentId = $sale_detail['parentSaleDetailId'];
            }
        }

        $currency_id = $this->currency->id;
        $subtotal= $sale_detail['subtotal'] ?? 0;
        $line_subtotal_discount = $sale_detail['line_subtotal_discount'] ?? 0;
        $subtotal_with_discount_for_pos= $sale_detail['subtotal_with_discount'] ??  $subtotal;
        $subtotal_with_discount= $request->type == 'pos' ?  $subtotal_with_discount_for_pos : $subtotal - $line_subtotal_discount;
        $sale_details_data = [
            'sales_id' => $sale_data->id,
            'product_id' => $sale_detail['product_id'],
            'parent_id' => $parentId ?? null,
            'variation_id' => $sale_detail['variation_id'],
            'uom_id' => $sale_detail['uom_id'],
            'quantity' => $sale_detail['quantity'],
            'uom_price' => $sale_detail['uom_price'] ?? 0,
            'subtotal' =>  $sale_detail['subtotal'] ?? 0,
            'discount_type' => $sale_detail['discount_type'],
            'per_item_discount' => $sale_detail['per_item_discount'],
            'subtotal_with_discount' => $subtotal_with_discount,
            'currency_id' => $request->currency_id ?? $currency_id,
            'price_list_id' => $sale_detail['price_list_id'] == "default_selling_price" ? null :  $sale_detail['price_list_id'],
            'tax_amount' => 0,
            'per_item_tax' => 0,
            'subtotal_with_tax' => $subtotal_with_discount,
            'note' => $sale_detail['item_detail_note'] ?? null,
        ];
        if ($resOrderData) {
            $sale_details_data['rest_order_id'] = $resOrderData ? $resOrderData->id : null;
            $sale_details_data['rest_order_status'] = $resOrderData ? 'order' : null;
        }
        return $sale_details_data;
    }
    public function createRomTx($created_sale_details, $business_location_id){
        if (hasModule('ComboKit') && isEnableModule('ComboKit')) {
            $romCheck = RoMService::isKit($created_sale_details['product_id']);
            if ($romCheck == 'kit') {

                // $quantity= $created_sale_details['quantity'];
                // if($created_sale_details['uom_id'] != $product['uom_id']){
                //     $quantity= UomHelper::changeQtyOnUom($created_sale_details['uom_id'], $product['uom_id'], $quantity);
                // };

                $status=RoMService::createRomTransactions(
                    $created_sale_details['id'],
                    'kit_sale_detail',
                    $business_location_id,
                    $created_sale_details['product_id'],
                    $created_sale_details['variation_id'],
                    $created_sale_details['quantity'],
                    $created_sale_details['uom_id'],
                );
                if($status !='success'){
                    throw new Exception("Out of Stock", 1);
                }
            }
        }
    }

    public function txManager($request, $sale_data,$created_sale_details,$product,$requestSaleDetails){
        $stock = CurrentStockBalance::where('product_id', $created_sale_details['product_id'])
            ->where('business_location_id', $sale_data->business_location_id)
            ->with(['product' => function ($q) {
                $q->select('id', 'name');
            }])
            ->where('variation_id', $created_sale_details['variation_id'])
            ->get()->first();

        $refInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($created_sale_details['quantity'], $created_sale_details['uom_id']);
        $requestQty = $refInfo['qtyByReferenceUom'];
        $refUoMId = $refInfo['referenceUomId'];
        $businessLocation = businessLocation::where('id', $request->business_location_id)->first();

        if ($product) {
            if ($product->product_type != 'storable' && $request->status == 'delivered') {
                $stock_history_data = [
                    'business_location_id' => $sale_data->business_location_id,
                    'product_id' => $created_sale_details['product_id'],
                    'variation_id' => $created_sale_details['variation_id'],
                    'expired_date' => $created_sale_details['expired_date'] ?? null,
                    'transaction_type' => 'sale',
                    'transaction_details_id' => $created_sale_details->id,
                    'increase_qty' => 0,
                    'ref_uom_id' => $refUoMId,
                    'decrease_qty' => $requestQty
                ];
                stock_history::create($stock_history_data);
            } else {
                if ($request->status == 'delivered' && $businessLocation->allow_sale_order == 0) {
                    if(isset($requestSaleDetails['lot_serial_val']) && $requestSaleDetails['lot_serial_val']!=$this->accounting_method && $request->type == 'pos'){
                        $changeQtyStatus = $this->changeStockQtyById($requestQty, $refUoMId, $request->business_location_id, $created_sale_details->toArray(), $requestSaleDetails['lot_serial_val'], $sale_data);
                    }else{
                        $changeQtyStatus = $this->changeStockQty($requestQty, $refUoMId, $request->business_location_id, $created_sale_details->toArray(), $stock, $sale_data);
                    };
                    if ($changeQtyStatus == false) {
                        return throw new Exception('Product Out of Stock!');
                    } else {
                        $datas = $changeQtyStatus;
                        foreach ($datas as $data) {
                            // dd($datas);
                            $sale_uom_qty = UomHelper::changeQtyOnUom($data['ref_uom_id'], $created_sale_details->uom_id, $data['stockQty']);
                            lotSerialDetails::create([
                                'transaction_type' => 'sale',
                                'transaction_detail_id' => $created_sale_details->id,
                                'current_stock_balance_id' => $data['stock_id'],
                                'lot_serial_numbers' => $data['lot_serial_no'],
                                'uom_quantity' => $sale_uom_qty,
                                'uom_id' => $created_sale_details->uom_id,
                                'ref_uom_quantity' => $data['stockQty'],
                            ]);
                        }
                    }
                }
            }
        }
    }
    /**
     * changeStockQty
     *
     * @param  mixed $requestQty must be ref
     * @param  mixed $refUoMId
     * @param  mixed $business_location_id
     * @param  mixed $sale_detail
     * @param  mixed $current_stock
     * @param  mixed $sales
     * @return void
     */
    public function changeStockQty($requestQty,$refUoMId, $business_location_id, $sale_detail,$current_stock = [], $sales)
    {
        $product_id = $sale_detail['product_id'];
        $sale_detail_id = $sale_detail['id'];
        $locationIds= childLocationIDs($business_location_id);
        // check lot control from setting
        $product = Product::where('id', $product_id)->select('product_type')->first();
        if ($product->product_type == 'storable') {
            $variation_id = $sale_detail['variation_id'];
            $totalStocks = CurrentStockBalance::select('id')
                            ->where('product_id', $product_id)
                            ->where('variation_id', $variation_id)
                            ->whereIn('business_location_id', $locationIds)
                            ->where('current_quantity', '>', '0')
                            ->sum('current_quantity');
            if ($requestQty > $totalStocks) {
                return false;
            } else {
                $stocks = CurrentStockBalance::where('product_id', $product_id)
                    ->where('variation_id', $variation_id)
                    ->whereIn('business_location_id', $locationIds)
                    ->where('current_quantity', '>', '0');
                if ($this->accounting_method == 'lifo') {
                    $stocks = $stocks->orderBy('id', 'DESC')->get();
                } else {
                    $stocks = $stocks->get();
                }
                $qtyToRemove = $requestQty;
                $this->createStockHistory($business_location_id,$sale_detail,$requestQty, $refUoMId,$sales);
                // dd($requestQty);
                $data = [];
                foreach ($stocks as  $stock) {
                    $stockQty = $stock->current_quantity;
                    // prepare data for stock history
                    //remove qty from current stock
                    if ($qtyToRemove > $stockQty) {
                        $data[] = [
                            'stockQty' => $stockQty,
                            'batch_no' => $stock['batch_no'],
                            'lot_serial_no' => $stock['lot_serial_no'],
                            'ref_uom_id' => $stock->ref_uom_id,
                            'stock_id' => $stock->id
                        ];
                        $qtyToRemove -= $stockQty;
                        CurrentStockBalance::where('id', $stock->id)->first()->update([
                            'current_quantity' => 0,
                        ]);
                    } else {
                        $leftStockQty = $stockQty - $qtyToRemove;
                        $data[] = [
                            'stockQty' => $qtyToRemove,
                            'batch_no' => $stock['batch_no'],
                            'lot_serial_no' => $stock['lot_serial_no'],
                            'ref_uom_id' => $stock->ref_uom_id,
                            'stock_id' => $stock->id
                        ];
                        $qtyToRemove = 0;
                        CurrentStockBalance::where('id', $stock->id)->first()->update([
                            'current_quantity' => $leftStockQty,
                        ]);
                        break;
                    }
                };
                // dd($data);
                return $data;
            }
        } else {
            dd('here');
            $current_stock_id = $current_stock['id'];
            $product_id = $current_stock['product_id'];
            $variation_id = $current_stock['variation_id'];
            $currentStock = CurrentStockBalance::whereIn('business_location_id', $business_location_id)
                ->where('product_id', $product_id)
                ->where('variation_id', $variation_id)
                ->where('id', $current_stock_id);
            $current_stock_qty =  $currentStock->first()->current_quantity;
            return abort(404, '');
            if ($requestQty > $current_stock_qty) {
                return false;
            } else {
                $left_qty = $current_stock_qty - $requestQty;
                $currentStock->update([
                    'current_quantity' => $left_qty
                ]);
                $currentStockData = $currentStock->get()->first()->toArray();
                stock_history::create([
                    'business_location_id' => $currentStockData['business_location_id'],
                    'product_id' => $currentStockData['product_id'],
                    'variation_id' => $currentStockData['variation_id'],
                    'batch_no' => $currentStockData['batch_no'],
                    'expired_date' => $currentStockData['expired_date'],
                    'transaction_type' => 'sale',
                    'transaction_details_id' => $sale_detail_id,
                    'increase_qty' => 0,
                    'decrease_qty' => $requestQty,
                    'ref_uom_id' => $currentStockData['ref_uom_id'],
                ]);
                return true;
            }
        }
        // }else{
        //     return false;
        // }

    }
    public function changeStockQtyById($requestQty,$refUoMId, $business_location_id, $sale_detail,$current_stock_id, $sales)
    {
        $cbs = CurrentStockBalance::select('id','current_quantity','batch_no','lot_serial_no','ref_uom_id')
                        ->where('id',$current_stock_id)
                        ->first();
        if ($requestQty > $cbs['current_quantity'] && $cbs) {
            return false;
        } else {
            $balanceQty=$cbs['current_quantity']-$requestQty;
            $data =[ [
                'stockQty' => $requestQty,
                'batch_no' => $cbs['batch_no'],
                'lot_serial_no' => $cbs['lot_serial_no'],
                'ref_uom_id' => $cbs['ref_uom_id'],
                'stock_id' => $cbs['id']
            ]];
            $cbs->update([
                'current_quantity'=>$balanceQty
            ]);
            $this->createStockHistory($business_location_id,$sale_detail,$requestQty, $refUoMId,$sales);

            return $data;
        }

    }
    public function createStockHistory($business_location_id,$sale_detail,$reqQty, $refUoMId, $sales){
        $stock_history_data = [
            'business_location_id' => $business_location_id,
            'product_id' => $sale_detail['product_id'],
            'variation_id' => $sale_detail['variation_id'],
            'expired_date' => $sale_detail['expired_date'] ?? null,
            'transaction_type' => 'sale',
            'transaction_details_id' => $sale_detail['id'],
            'increase_qty' => 0,
            'decrease_qty'=> $reqQty,
            'ref_uom_id' => $refUoMId,
            'created_at' => $sales['sold_at'] ?? now(),
        ];
        stock_history::create($stock_history_data);
    }
}
