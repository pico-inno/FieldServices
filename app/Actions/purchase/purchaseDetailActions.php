<?php

namespace App\Actions\purchase;

use App\Helpers\UomHelper;
use App\Models\Product\Product;
use App\Models\CurrentStockBalance;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\ProductVariation;
use App\Repositories\CurrencyRepository;
use App\Models\settings\businessLocation;
use App\Models\purchases\purchase_details;
use App\Models\stock_history;
use App\Services\packaging\packagingServices;
use App\Services\stockhistory\stockHistoryServices;

class purchaseDetailActions
{

    public  $currency;
    public function __construct()
    {
        $currency = new CurrencyRepository();
        $this->currency = $currency->defaultCurrency();
    }
    public function detailCreate($pd, $purchase)
    {
        $product = Product::where('id', $pd['product_id'])->select('purchase_uom_id')->first();
        $referencteUom = UomHelper::getReferenceUomInfoByCurrentUnitQty($pd['quantity'], $pd['purchase_uom_id']);
        $per_ref_uom_price = priceChangeByUom($pd['purchase_uom_id'], $pd['uom_price'], $referencteUom['referenceUomId']);
        $newDefaultPrice = priceChangeByUom($pd['purchase_uom_id'], $pd['uom_price'], $product['purchase_uom_id']);
        $pd['purchases_id'] = $purchase->id;
        $pd['subtotal'] = $pd['uom_price'] * $pd['quantity'];
        $pd['subtotal_with_discount'] = $pd['subtotal_with_discount'];
        $pd['currency_id'] = $purchase->currency_id;
        $pd['expense'] = $pd['per_item_expense'] * $pd['quantity'];
        $pd['ref_uom_id'] = $referencteUom['referenceUomId'];
        $pd['per_ref_uom_price'] = $per_ref_uom_price;
        $pd['batch_no'] = UomHelper::generateBatchNo($pd['variation_id']);
        $pd['per_item_tax'] = 0;
        $pd['tax_amount'] = 0;
        $pd['subtotal_wit_tax'] = $pd['per_item_expense'] * $pd['quantity'] + 0;
        $pd['created_by'] = Auth::user()->id;
        $pd['purchased_by'] = Auth::user()->id;
        $pd['updated_by'] = Auth::user()->id;
        $pd['deleted_by'] = Auth::user()->id;
        $pd['is_delete'] = 0;
        $pd = purchase_details::create($pd);
        $this->updateDefaultPurchasePrice($pd['variation_id'], $newDefaultPrice);
        $this->currentStockBalanceAndStockHistoryCreation($pd, $purchase, 'purchase');
        return $pd;
    }
    public function detailUpdate($request, $businessLocation, $requestDetailDataForUpdate,$befUpdatedPurchaseData, $purchasesData){
        foreach ($requestDetailDataForUpdate as $pd) {
            $purchase_detail_id = $pd['purchase_detail_id'];
            $purchase_details = purchase_details::where('id', $purchase_detail_id)->where('is_delete', 0)->first();

            $product = Product::where('id', $pd['product_id'])->select('purchase_uom_id')->first();
            $referencUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($pd['quantity'], $pd['purchase_uom_id']);
            $requestQty = $referencUomInfo['qtyByReferenceUom'];
            $referencteUom = $referencUomInfo['referenceUomId'];

            // change default purchase price
            $per_ref_uom_price = priceChangeByUom($pd['purchase_uom_id'], $pd['uom_price'], $referencteUom);
            $default_selling_price = priceChangeByUom($pd['purchase_uom_id'], $pd['uom_price'], $product['purchase_uom_id']);
            $this->changeDefaultPurchasePrice($pd['variation_id'], $default_selling_price);

            $pd['subtotal'] = $pd['uom_price'] * $pd['quantity'];
            $pd['subtotal_with_discount'] = $pd['subtotal_with_discount'];
            $pd['expense'] = $pd['per_item_expense'] * $pd['quantity'];
            $pd['ref_uom_id'] = $referencteUom;
            $pd['per_item_tax'] = 0;
            $pd['tax_amount'] = 0;
            $pd['subtotal_wit_tax'] = $pd['per_item_expense'] * $pd['quantity'] + 0;
            $pd['per_ref_uom_price'] = $per_ref_uom_price;
            $pd['updated_by'] = Auth::user()->id;
            $pd['updated_at'] = now();

            // purchase details will update last because in update diff qty of stock need to check
            // dd($purchase_details);
            // remove stock on status
            if ($befUpdatedPurchaseData['status'] == 'received' && $purchasesData['status'] != "received") {
                $this->deleteCSBSH($purchase_detail_id);
            } elseif ($befUpdatedPurchaseData['status'] != 'received' && $request['status'] == 'received') {
                $this->currentStockBalanceAndStockHistoryCreation($pd, $purchasesData, 'purchase');
            }

            $stock_check = currentStockBalance::where('transaction_detail_id', $purchase_detail_id)->where('transaction_type', 'purchase')->exists();
            if (!$stock_check && $befUpdatedPurchaseData['status'] != 'received' && $request['status'] == 'received') {
                $this->currentStockBalanceAndStockHistoryCreation($pd, $purchasesData, 'purchase');
            } elseif ($stock_check && $befUpdatedPurchaseData['status'] == 'received' && $request['status'] == 'received') {
                $currentStock = currentStockBalance::where('transaction_detail_id', $purchase_detail_id)->where('transaction_type', 'purchase');

                $purchased_quantity = (int) $currentStock->get()->first()->ref_uom_quantity;
                $current_qty_from_db = (int)  $currentStock->get()->first()->current_quantity;
                $diff_qty = $purchased_quantity - $current_qty_from_db;
                $currentResultQty = $requestQty - $diff_qty;
                if ($request->status == 'received') {
                    if ($businessLocation->allow_purchase_order == 0) {
                        $currentStock->first()->update([
                            "business_location_id" => $request->business_location_id,
                            "ref_uom_id" => $referencteUom,
                            "batch_no" => $request->batch_no,
                            "ref_uom_price" => $per_ref_uom_price,
                            "ref_uom_quantity" => $requestQty,
                            "current_quantity" => $currentResultQty >= 0 ? $currentResultQty :  0,
                            "created_at" => $request->received_at
                        ]);
                        stock_history::where('transaction_details_id', $purchase_detail_id)->where('transaction_type', 'purchase')->first()->update([
                            'increase_qty' => $requestQty,
                            "business_location_id" => $request->business_location_id,
                            "created_at"=> $request->received_at
                        ]);
                    } else {
                        return redirect()->route('purchase_list')->with(['warning' => 'Something wrong on Updating Purchase']);
                    }
                }
            } else {
                if ($befUpdatedPurchaseData['status'] == 'received' && $purchasesData['status'] != "received") {
                    $this->deleteCSBSH($purchase_detail_id);
                }
            }

            $purchase_details->update($pd);

            // update packaging
            $packagingService = new packagingServices();
            $packagingService->updatePackagingForTx($pd, $purchase_detail_id, 'purchase');
        }
    }



    public function updateDefaultPurchasePrice($variation_id, $newDefaultPrice)
    {
        $variation_product = ProductVariation::where('id', $variation_id)->first();
        if ($variation_product) {
            $variation_product->update(['default_purchase_price' => $newDefaultPrice]);
        }
    }



    public function currentStockBalanceAndStockHistoryCreation($purchase_detail_data, $purchase, $type)
    {
        $stockHistoryServices = new stockHistoryServices();
        $data = $this->currentStockBalanceData($purchase_detail_data, $purchase, $type);
        $businessLocation = businessLocation::where('id', $data['business_location_id'])->first();
        if ($purchase->status == 'received') {
            if ($businessLocation->allow_purchase_order == 1) {
                return;
            }
            CurrentStockBalance::create($data);
            $stockHistoryServices->create($data, $data['transaction_detail_id'], $data['ref_uom_quantity'], $purchase['received_at'],'purchase', 'increase');
        }
    }
    protected function currentStockBalanceData($purchase_detail_data, $purchase, $type)
    {
        $referencUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($purchase_detail_data['quantity'], $purchase_detail_data['purchase_uom_id']);
        $batchNo = UomHelper::generateBatchNo($purchase_detail_data['variation_id']);
        $per_ref_uom_price_by_default_currency = exchangeCurrency($purchase_detail_data['per_ref_uom_price'], $purchase['currency_id'], $this->currency->id) ?? 0;
        $purchaseDetailId= $purchase_detail_data['purchase_detail_id'] ?? $purchase_detail_data['id'];
        return [
            "business_location_id" => $purchase['business_location_id'],
            "product_id" => $purchase_detail_data['product_id'],
            "variation_id" => $purchase_detail_data['variation_id'],
            "transaction_type" => $type,
            "transaction_detail_id" =>$purchaseDetailId,
            "expired_date" => arr($purchase_detail_data, 'expired_date','',null),
            'batch_no' => $batchNo,
            "ref_uom_id" => $referencUomInfo['referenceUomId'],
            "ref_uom_quantity" => $referencUomInfo['qtyByReferenceUom'],
            "ref_uom_price" => $per_ref_uom_price_by_default_currency,
            "current_quantity" => $referencUomInfo['qtyByReferenceUom'],
            "currency_id" => $purchase->currency_id,
            "created_at" => $purchase->received_at,
            "lot_serial_type"=>'lot',
            "lot_serial_no"=>'LP-'.$purchaseDetailId
        ];
    }

    public function changeDefaultPurchasePrice($variation_id, $default_selling_price)
    {
        $variation_product = ProductVariation::where('id', $variation_id)->first();
        if ($variation_product) {
            $variation_product->update(['default_purchase_price' => $default_selling_price]);
        }
    }

    public function delete($detialId)  {
        purchase_details::where('id', $detialId)->update([
            'is_delete' => 1,
            'deleted_at' => now(),
            'deleted_by' => Auth::user()->id,
        ]);
        CurrentStockBalance::where('transaction_detail_id', $detialId)->where('transaction_type', 'purchase')->delete();
        stock_history::where('transaction_type', 'purchase')->where('transaction_details_id', $detialId)->delete();
    }

    // deleteStock
    public function deleteCSBSH($detailId,$type="purchase"){
        CurrentStockBalance::where('transaction_detail_id', $detailId)->where('transaction_type', $type)->delete();
        stock_history::where('transaction_details_id', $detailId)->where('transaction_type', $type)->delete();
    }
}
