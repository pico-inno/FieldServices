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
        $data = $this->data($request, $product);
        productPackaging::create($data);
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
    public function packagingForTx($data,$txd,$tx_type){
        if ($data['packaging_id'] && $data['packaging_quantity']) {
            $packagingData = $this->packagingDataForTx($data, $txd, $tx_type);
            $createdData = productPackagingTransactions::create($packagingData);
            return $createdData;
        }
    }
    public function packagingDataForTx($data, $txd, $tx_type){
       return [
            'product_packaging_id' => arr($data, 'packaging_id'),
            'quantity' => arr($data, 'packaging_quantity'),

            'packaging_quantity' => arr($data, 'packaging_quantity'),
            'transaction_type' => $tx_type,
            'transaction_details_id' => arr($txd, 'id'),
            'created_at' => Auth::user()->id,

       ];
    }
}
