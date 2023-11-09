<?php

namespace App\Services\purchase;

use purchase;
use Carbon\Carbon;
use App\Helpers\UomHelper;
use App\Models\stock_history;
use App\Models\Product\Product;
use App\Models\CurrentStockBalance;
use App\Models\purchases\purchases;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\ProductVariation;
use App\Repositories\CurrencyRepository;
use App\Repositories\LocationRepository;
use App\Models\purchases\purchase_details;
use App\Services\purchase\purchasingService;
use App\Services\packaging\packagingServices;
use App\Actions\purchase\purchaseDetailActions;
use App\Services\stockhistory\stockHistoryServices;
use App\Repositories\interfaces\CurrencyRepositoryInterface;

class purchaseDetailServices
{

    public  $currency;
    public function __construct()
    {
        $currency = new CurrencyRepository();
        $this->currency = $currency->defaultCurrency();
    }

    public function create(array $purchases_details, $purchase)
    {
        $action = new purchaseDetailActions();
        $packaging = new packagingServices();
        foreach ($purchases_details as $pd) {
            $createdPd = $action->detailCreate($pd, $purchase);
            $packaging->packagingForTx($pd, $createdPd['id'], 'purchase');
        }
    }
    public function update($id,Array $purchasesDatas,$request){

        $befUpdatedPurchaseData = $purchasesDatas['befUpdateData'];
        $purchasesData= $purchasesDatas['updatedData'];

        $location = new LocationRepository();
        $purchaseDetailActions = new purchaseDetailActions();

        $request_purchase_details = $request->purchase_details;
        $businessLocation = $location->find($purchasesData['business_location_id']);

        if ($request_purchase_details) {

            //get old purchase_details
            $requestDetailDataForUpdate = array_filter($request_purchase_details, function ($item) {
                return isset($item['purchase_detail_id']);
            });

            // get old purchase_details ids from client [1,2,]
            $requestDetialIdForUpdate = array_column($requestDetailDataForUpdate, 'purchase_detail_id');
            // dd($requestDetailDataForUpdate, $requestDetialIdForUpdate);
            // update purchase detail's data and related current stock
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

                // remove stock on status
                if ($befUpdatedPurchaseData['status'] == 'received' && $purchasesData['status'] != "received") {
                    $purchaseDetailActions->removeStock($purchase_detail_id);
                }elseif($befUpdatedPurchaseData['status'] != 'received' && $request['status'] == 'received'){
                    $purchaseDetailActions->currentStockBalanceAndStockHistoryCreation($purchase_details, $purchasesData, 'purchase');
                }

                $stock_check = currentStockBalance::where('transaction_detail_id', $purchase_detail_id)->where('transaction_type', 'purchase')->exists();
                if (!$stock_check && $befUpdatedPurchaseData['status'] != 'received' && $request['status'] == 'received') {
                    $purchaseDetailActions->currentStockBalanceAndStockHistoryCreation($purchase_details, $purchasesData, 'purchase');
                }elseif($stock_check && $befUpdatedPurchaseData['status'] == 'received' && $request['status'] == 'received'){
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
                                    "ref_uom_price" => $pd['per_ref_uom_price'],
                                    "ref_uom_quantity" => $requestQty,
                                    "current_quantity" => $currentResultQty >= 0 ? $currentResultQty :  0,
                                ]);
                                stock_history::where('transaction_details_id', $purchase_detail_id)->where('transaction_type', 'purchase')->first()->update([
                                    'increase_qty' => $requestQty,
                                    "business_location_id" => $request->business_location_id,
                                ]);
                            } else {
                                return redirect()->route('purchase_list')->with(['warning' => 'Something wrong on Updating Purchase']);
                            }
                        }
                }else{
                    if ($befUpdatedPurchaseData['status'] == 'received' && $purchasesData['status'] != "received") {
                        $purchaseDetailActions->removeStock($purchase_detail_id);
                    } 
                }

                // purchase details will update last because in update diff qty of stock need to check
                $purchase_details->update($pd);
                // update packaging
                $packagingService = new packagingServices();
                $packagingService->updatePackagingForTx($pd, $purchase_detail_id, 'purchase');
            }




            //------------------------------------------- remove and create --------------------------------------------------------------//
            //get added purchase_details_ids from database
            $fetch_purchase_details = purchase_details::where('purchases_id', $id)->where('is_delete', 0)->select('id')->get()->toArray();
            $get_fetched_purchase_details_id = array_column($fetch_purchase_details, 'id');

            //to remove edited purchase_detais that are already created
            $requestDetailDataForUpdate_id_for_delete = array_diff($get_fetched_purchase_details_id, $requestDetialIdForUpdate); //for delete row
            foreach ($requestDetailDataForUpdate_id_for_delete as $p_id) {
                $purchaseDetailActions->delete($p_id);
            }
            //to create purchase details
            $new_purchase_details = array_filter($request_purchase_details, function ($item) {
                return !isset($item['purchase_detail_id']);
            });
            if (count($new_purchase_details) > 0) {
                $this->create($new_purchase_details, $purchasesData);
            }
        } else {
            $fetch_purchase_details = purchase_details::where('purchases_id', $id)->where('is_delete', 0)->select('id')->get();
            foreach ($fetch_purchase_details as $p) {
                CurrentStockBalance::where('trnasaction_detail_id', $p->id)->where('transaction_type', 'purchase')->delete();
            }
            purchase_details::where('purchases_id', $id)->update([
                'is_delete' => 1,
                'deleted_at' => now(),
                'deleted_by' => Auth::user()->id,
            ]);
        }
    }

    public function changeDefaultPurchasePrice($variation_id, $default_selling_price)
    {
        $variation_product = ProductVariation::where('id', $variation_id)->first();
        if ($variation_product) {
            $variation_product->update(['default_purchase_price' => $default_selling_price]);
        }
    }


}
