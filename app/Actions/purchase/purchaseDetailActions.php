<?php

namespace App\Actions\purchase;

use App\Helpers\UomHelper;
use App\Models\Product\Product;
use App\Models\CurrentStockBalance;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\ProductVariation;
use App\Repositories\CurrencyRepository;
use App\Repositories\LocationRepository;
use App\Models\settings\businessLocation;
use App\Models\purchases\purchase_details;
use App\Models\stock_history;
use App\Services\packaging\packagingServices;
use App\Services\stockhistory\stockHistoryServices;
use App\Repositories\interfaces\CurrencyRepositoryInterface;

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
        $default_selling_price = priceChangeByUom($pd['purchase_uom_id'], $pd['uom_price'], $product['purchase_uom_id']);
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
        $this->updateDefaultPurchasePrice($pd['variation_id'], $default_selling_price);
        $this->currentStockBalanceAndStockHistoryCreation($pd, $purchase, 'purchase');
        return $pd;
    }



    public function updateDefaultPurchasePrice($variation_id, $default_selling_price)
    {
        $variation_product = ProductVariation::where('id', $variation_id)->first();
        if ($variation_product) {
            $variation_product->update(['default_purchase_price' => $default_selling_price]);
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
            $stockHistoryServices->create($data, $purchase_detail_data->id, $data['ref_uom_quantity'], 'purchase', 'increase');
        }
    }
    protected function currentStockBalanceData($purchase_detail_data, $purchase, $type)
    {
        $referencUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($purchase_detail_data['quantity'], $purchase_detail_data['purchase_uom_id']);
        $batchNo = UomHelper::generateBatchNo($purchase_detail_data['variation_id']);
        $per_ref_uom_price_by_default_currency = exchangeCurrency($purchase_detail_data['per_ref_uom_price'], $purchase['currency_id'], $this->currency->id) ?? 0;
        return [
            "business_location_id" => $purchase['business_location_id'],
            "product_id" => $purchase_detail_data['product_id'],
            "variation_id" => $purchase_detail_data['variation_id'],
            "transaction_type" => $type,
            "transaction_detail_id" => $purchase_detail_data['id'],
            "batch_no" => $purchase_detail_data['batch_no'],
            "expired_date" => $purchase_detail_data['expired_date'],
            'batch_no' => $batchNo,
            "ref_uom_id" => $referencUomInfo['referenceUomId'],
            "ref_uom_quantity" => $referencUomInfo['qtyByReferenceUom'],
            "ref_uom_price" => $per_ref_uom_price_by_default_currency,
            "current_quantity" => $referencUomInfo['qtyByReferenceUom'],
            'currency_id' => $purchase->currency_id,
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
    }
    public function removeStock($detailId,$type="purchase"){
        CurrentStockBalance::where('transaction_detail_id', $detailId)->where('transaction_type', $type)->delete();
        stock_history::where('transaction_details_id', $detailId)->where('transaction_type', $type)->delete();
    }
}
