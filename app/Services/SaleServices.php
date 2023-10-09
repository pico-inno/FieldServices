<?php

namespace App\Services;

use App\Helpers\UomHelper;
use App\Models\sale\sales;
use App\Models\stock_history;
use App\Models\Product\Product;
use App\Models\lotSerialDetails;
use App\Models\sale\sale_details;
use Illuminate\Support\Collection;
use App\Models\CurrentStockBalance;
use Illuminate\Support\Facades\Auth;
use App\Models\settings\businessLocation;
use App\Models\settings\businessSettings;
use Carbon\Carbon;

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
            'sold_at' => now(),
            'sold_by' => Auth::user()->id,
            'created_by' => Auth::user()->id,
        ]);
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
        // dd($sale_details);
        $parentSaleItems=[];
        foreach ($sale_details as $key=>$sale_detail) {
            // dd($sale_details);
            $product = Product::where('id', $sale_detail['product_id'])->select('product_type')->first();
            // dd($product);
            $stock = CurrentStockBalance::where('product_id', $sale_detail['product_id'])
            ->where('business_location_id', $sale_data->business_location_id)
                // ->where('id', $sale_detail['stock_id_by_batch_no'])
                ->with(['product' => function ($q) {
                    $q->select('id', 'name');
                }])
                ->where('variation_id', $sale_detail['variation_id'])
                ->get()->first();
            $line_subtotal_discount = $sale_detail['line_subtotal_discount'] ?? 0;
            $currency_id = $this->currency->id;
            $sale_details_data = [
                'sales_id' => $sale_data->id,
                'product_id' => $sale_detail['product_id'],
                'parent_id'=>isset($sale_detail['parentUniqueNameId']) && $sale_detail['parentUniqueNameId'] !='false' ? $sale_detail['parentUniqueNameId']: null,
                'variation_id' => $sale_detail['variation_id'],
                'uom_id' => $sale_detail['uom_id'],
                'quantity' => $sale_detail['quantity'],
                'uom_price' => $sale_detail['uom_price'],
                'subtotal' =>  $sale_detail['subtotal'],
                'discount_type' => $sale_detail['discount_type'],
                'per_item_discount' => $sale_detail['per_item_discount'],
                'subtotal_with_discount' => $request->type != 'pos' ? $sale_detail['subtotal']  - $line_subtotal_discount :  $sale_detail['subtotal_with_discount'] ??  $sale_detail['subtotal'],
                'currency_id' => $request->currency_id ?? $currency_id,
                'price_list_id' => $sale_detail['price_list_id'] == "default_selling_price" ? null :  $sale_detail['price_list_id'],
                'tax_amount' => 0,
                'per_item_tax' => 0,
                'subtotal_with_tax' => $request->type != 'pos' ? $sale_detail['subtotal']  - $line_subtotal_discount :   $sale_detail['subtotal_with_discount'] ??  $sale_detail['subtotal'],
                'note' => $sale_detail['item_detail_note'] ?? null,
            ];
            if ($resOrderData) {
                $sale_details_data['rest_order_id'] = $resOrderData ? $resOrderData->id : null;
                $sale_details_data['rest_order_status'] = $resOrderData ? 'order' : null;
            }
            $created_sale_details = sale_details::create($sale_details_data);
            if (isset($sale_detail['isParent'])) {
                $parentSaleItems[$sale_detail['isParent']]= $created_sale_details;
            }
            $refInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($sale_detail['quantity'], $sale_detail['uom_id']);
            $requestQty = $refInfo['qtyByReferenceUom'];
            $businessLocation = businessLocation::where('id', $request->business_location_id)->first();

            if ($product) {
                if ($product->product_type != 'storable') {
                    $stock_history_data = [
                        'business_location_id' => $sale_data->business_location_id,
                        'product_id' => $sale_detail['product_id'],
                        'variation_id' => $sale_detail['variation_id'],
                        'expired_date' => $sale_detail['expired_date'] ?? null,
                        'transaction_type' => 'sale',
                        'transaction_details_id' => $created_sale_details->id,
                        'increase_qty' => 0,
                        'ref_uom_id' => $refInfo['referenceUomId'],
                        'decrease_qty' => $requestQty
                    ];
                    stock_history::create($stock_history_data);
                } else {
                    if ($request->status == 'delivered' && $businessLocation->allow_sale_order == 0) {
                        $changeQtyStatus = $this->changeStockQty($requestQty, $request->business_location_id, $created_sale_details->toArray(), $stock);
                        if ($changeQtyStatus == false) {
                            return 'outOfStock';
                            // return redirect()->back()->withInput()->with(['warning' => "Out of Stock In " . $stock['product']['name']]);
                        } else {
                            // if ($this->setting->lot_control == "off") {
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
                                ]);
                            }
                            // }
                        }
                    }
                }
            }
        }
        return;
    }

    public function changeStockQty($requestQty, $business_location_id, $sale_detail, $current_stock = [])
    {
        $product_id = $sale_detail['product_id'];
        $sale_detail_id = $sale_detail['id'];
        $locationIds= childLocationIDs($business_location_id);
        // check lot control from setting
        $product = Product::where('id', $product_id)->select('product_type')->first();
        if ($product->product_type == 'storable') {
            $variation_id = $sale_detail['variation_id'];
            $totalStocks = CurrentStockBalance::select('id', 'current_stock_id')
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
                // dd($requestQty);
                $data = [];
                foreach ($stocks as  $stock) {
                    $stockQty = $stock->current_quantity;
                    // prepare data for stock history
                    $stock_history_data = [
                        'business_location_id' => $stock['business_location_id'],
                        'product_id' => $stock['product_id'],
                        'variation_id' => $stock['variation_id'],
                        'expired_date' => $stock['expired_date'],
                        'transaction_type' => 'sale',
                        'transaction_details_id' => $sale_detail_id,
                        'increase_qty' => 0,
                        'ref_uom_id' => $stock['ref_uom_id'],
                    ];

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
                        $stock_history_data['decrease_qty'] = $stockQty;
                        stock_history::create($stock_history_data);
                    } else {
                        $leftStockQty = $stockQty - $qtyToRemove;
                        $data[] = [
                            'stockQty' => $qtyToRemove,
                            'batch_no' => $stock['batch_no'],
                            'lot_serial_no' => $stock['lot_serial_no'],
                            'ref_uom_id' => $stock->ref_uom_id,
                            'stock_id' => $stock->id
                        ];
                        $stock_history_data['decrease_qty'] = $qtyToRemove;
                        $qtyToRemove = 0;
                        CurrentStockBalance::where('id', $stock->id)->first()->update([
                            'current_quantity' => $leftStockQty,
                        ]);
                        stock_history::create($stock_history_data);
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
}
