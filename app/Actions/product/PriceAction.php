<?php

namespace App\Actions\product;

use App\Models\Product\PriceListDetails;
use App\Models\Product\PriceLists;
use App\Models\Product\ProductVariation;
use App\Models\settings\businessSettings;
use App\Repositories\Product\PriceRepository;
use Illuminate\Support\Facades\DB;

class PriceAction
{
    protected $priceRepository;
    public function __construct(PriceRepository $priceRepository)
    {
        return $this->priceRepository = $priceRepository;
    }
    public function create($request){

        return DB::transaction(function () use ($request){

            $preparedPriceList = $this->preparePriceList($request);
            $preparedPriceList['business_id'] = getSettingsValue('id');
            $createdPriceList = $this->priceRepository->createPriceList($preparedPriceList);


            $hasCreation = $this->hasCreatePricelistDetail($request);
            if($hasCreation){
                $this->insertPricelistDetail($request, $createdPriceList->id);
            }
        });
    }


    private function hasCreatePricelistDetail($request)
    {
        $applyType = $request->apply_type[0];
        $applyValue = $request->apply_value;
        $minQty = $request->min_qty[0];
        $calType = $request->cal_type[0];
        $calValue = $request->cal_val[0];

        if($applyType == null || $applyValue == null || $minQty == null || $calType == null || $calValue == null){
            return false;
        }else{
            return true;
        }
    }

    private function insertPricelistDetail($request, $pricelistId, $isCreating = true)
    {
        if(!$isCreating){
            // Get all pricelist-detail IDs of the pricelist from the database
            $dbPricelistDetailIds = $this->priceRepository->queryPriceListDetails()->where('pricelist_id', $pricelistId)->pluck('id');

            // Find pricelist-detail IDs to delete
            $pricelistDetailsToDelete = array_diff($dbPricelistDetailIds->toArray(), $request->price_list_detail_id);
            if(!empty($pricelistDetailsToDelete)){
                $this->priceRepository->queryPriceListDetails()->whereIn('id', $pricelistDetailsToDelete)->delete();;
//                PriceListDetails::destroy($pricelistDetailsToDelete);
            }
        }

        $pricelistDetails = [];
        foreach($request->apply_type as $index => $value){
            $pricelistData = [
                'pricelist_id' => $pricelistId,
                'applied_type' => $value,
                'applied_value' => $request->apply_value[$index],
                'min_qty' => $request->min_qty[$index],
                'from_date' => $request->start_date[$index],
                'to_date' => $request->end_date[$index],
                'cal_type' => $request->cal_type[$index],
                'cal_value' => $request->cal_val[$index],
                'base_price' => $request->base_price
            ];
            // dd($pricelistData);
            if(!$isCreating){
                $pricelistData['id'] = $request->price_list_detail_id[$index] ?? null;
            }
            $pricelistDetails[] = $pricelistData;
        }

        if($isCreating){
            $this->priceRepository->createPriceListDetails($pricelistDetails);
        }
        if(!$isCreating){
            foreach ($pricelistDetails as $detail) {
                PriceListDetails::updateOrCreate(['id' => $detail['id']], $detail);
                if($detail['applied_type']== 'Product'){
                    $variation=ProductVariation::where('product_id',$detail['applied_value'])->first();
                    $sellingPrice=$detail['cal_type']== 'percentage' ? percentageCalc($variation->default_purchase_price, $detail['cal_value']): $detail['cal_value'];
                    $variation->update([
                        'default_selling_price'=> $sellingPrice,
                    ]);
                }elseif($detail['applied_type']== 'Variation'){
                    $variation = ProductVariation::where('id', $detail['applied_value'])->first();

                    $sellingPrice = $detail['cal_type'] == 'percentage' ? percentageCalc($variation->default_purchase_price, $detail['cal_value']) : $detail['cal_value'];
                    $variation->update([
                        'default_selling_price' => $sellingPrice,
                    ]);
                }
            }
        }
    }


    private function preparePriceList($request)
    {
        return [
            'price_list_type' => $request->price_list_type,
            'currency_id' => $request->currency_id,
            'name' => $request->name,
            'description' => $request->description
        ];
    }
}
