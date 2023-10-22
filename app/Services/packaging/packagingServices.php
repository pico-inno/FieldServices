<?php

namespace App\Services\packaging;

use App\Models\productPackaging;
use App\Models\Product\ProductVariation;

class packagingServices
{

    public function create($request, $product)  {
        $data = $this->data($request, $product);
        productPackaging::create($data);
    }
    public function createWithBulk($requests, $product){
        foreach ($requests  as $request) {
            $data=$this->data($request,$product);
            productPackaging::create($data);
        }
    }
    public function  data($data,$product) {
        $productVariation = ProductVariation::where('product_id', $product['id'])->select('id')->first();
        return [
            'packaging_name'=>$data['packaging_name'],
            'product_id' => $product['id'],
            'product_variation_id'=> $productVariation['id'],
            'quantity'=> $data['packaging_quantity'],
            'uom_id' =>$data['packaging_uom_id'],
            'package_barcode'=>'',
            'for_purchase'=> isset($data['for_purchase'][0])? 1 : 0,
            'for_sale'=> isset($data['for_sale'][0])  ? 1 : 0,
        ];
    }
}
