<?php

namespace App\Services\packaging;

use Exception;
use App\Models\productPackaging;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\ProductVariation;
use App\Models\productPackagingTransactions;

class packagingServices
{

    public function create($request, $product)  {
        $productVariations = ProductVariation::where('product_id', $product['id'])->select('id')->get();
        foreach ($productVariations as $variation) {
            $request['variation_id'] = $variation['id'];
            $data = $this->data($request, $product);
            productPackaging::create($data);
        }
    }
    public function createWithBulk($requests, $product){
        foreach ($requests  as $request) {
            $productVariations = ProductVariation::where('product_id', $product['id'])->select('id')->get();
            foreach ($productVariations as $variation) {
                $request['variation_id']=$variation['id'];
                $data = $this->data($request, $product);
                productPackaging::create($data);
            }
        }
    }
    public function update($requests, $product)
    {
        $requestForPackaging = array_filter($requests, function ($item) {
            return isset($item['packaging_id']);
        });
        $requestPackagingIds = array_column($requestForPackaging, 'packaging_id');
        $ids = productPackaging::where('product_id', $product->id)->pluck('id');
        $diffIds = array_diff($ids->toArray(), $requestPackagingIds);
        foreach ($diffIds as $key => $id) {
            productPackaging::where('id', $id)->first()->delete();
        }

        foreach ($requests  as $request) {
            if(productPackaging::where('id', arr($request, 'packaging_id'))->exists()){
                productPackaging::where('id',$request['packaging_id'])
                ->first()
                ->update($this->dataForUpdate($request));
            }else{
                // dd($request);
                $this->create($request,$product);
            }
        }



    }
    public function  data($data,$product) {
        return [
            'packaging_name'=>$data['packaging_name'],
            'product_id' => $product['id'],
            'product_variation_id'=> $data['variation_id'],
            'quantity'=> $data['packaging_quantity'],
            'uom_id' =>$data['packaging_uom_id'],
            'package_barcode'=>'',
            'for_purchase'=> isset($data['for_purchase'][0])? 1 : 0,
            'for_sale'=> isset($data['for_sale'][0])  ? 1 : 0,
            'created_by'=>Auth::user()->id,
            'created_at' => now(),
        ];
    }
    public function  dataForUpdate($data)
    {
        return [
            'packaging_name' => $data['packaging_name'],
            'quantity' => $data['packaging_quantity'],
            'uom_id' => $data['packaging_uom_id'],
            'package_barcode' => '',
            'for_purchase' => isset($data['for_purchase'][0]) ? 1 : 0,
            'for_sale' => isset($data['for_sale'][0])  ? 1 : 0,
            'updated_at'=>Auth::user()->id,
            'updated_by' => now(),

        ];
    }
    /**
     * record packagign for transactions
     *
     * @param  array $data
     * @param  transaction_detail_data $txd
     * @param  transaction_type $tx_type
     * @return void
     */
    public function packagingForTx($data, $txd_id,$tx_type){
        if(isset($data['packaging_id']) && isset($data['packaging_quantity'])){
            if ($data['packaging_id'] && $data['packaging_quantity']) {
                $packagingData = $this->packagingDataForTx($data, $txd_id, $tx_type);
                $createdData = productPackagingTransactions::create($packagingData);
                return $createdData;
            }
        }
    }
    /**
     * packagingDataForTx
     *
     * @param  array $data packaging data
     * @param  array|collection $txd_id transaction detail id
     * @param  string $tx_type type of transaction
     * @return void
     */
    public function packagingDataForTx($data, $txd_id, $tx_type){
       return [
            'product_packaging_id' => arr($data, 'packaging_id'),
            'quantity' => arr($data, 'packaging_quantity'),

            'packaging_quantity' => arr($data, 'packaging_quantity'),
            'transaction_type' => $tx_type,
            'transaction_details_id' => $txd_id,
            'created_at' => now(),
            'created_by' => Auth::user()->id,

       ];
    }

    public function updatePackagingForTx($data, $txd_id,$type)
    {
        if (isset($data['packaging_id']) && isset($data['packaging_quantity'])) {
            $packagingQry=productPackagingTransactions::where('transaction_details_id', $txd_id);
            if($packagingQry->exists()){
                return $packagingQry->where('transaction_type',$type)
                        ->first()
                        ->update([
                            'product_packaging_id' => arr($data, 'packaging_id'),
                            'quantity' => arr($data, 'packaging_quantity'),
                            'packaging_quantity' => arr($data, 'packaging_quantity'),
                            'updated_by' => Auth::user()->id,
                            'updated_at' => now(),
                        ]);
            }else{
                $this->packagingForTx($data,$txd_id,$type);
            }

        }
        return 0;
    }

    public function deletePackagingForTx($txd_id,$type){
        return productPackagingTransactions::where('transaction_details_id',$txd_id)->where('transaction_type',$type)
                ->first()->update([
                    'is_delete'=>1,
                    'deleted_at'=>now(),
                    'deleted_by'=>Auth::user()->id,
                ]);
    }
}
