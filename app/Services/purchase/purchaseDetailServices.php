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

            // update purchase detail's data and related current stock
            $purchaseDetailActions->detailUpdate($request,$businessLocation,$requestDetailDataForUpdate,$befUpdatedPurchaseData,$purchasesData);


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
                stock_history::where('transaction_type', 'purchase')->where('transaction_details_id', $p->id)->delete();
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
