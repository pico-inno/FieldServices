<?php

namespace App\Services\product;

use App\Models\Product\AdditionalProduct;
use App\Models\Product\ProductVariation;
use App\repositories\ProductRepository;

class productServices
{
    public function additionalProductsRetrive($product_id){
     $productRepository = new ProductRepository();
     $additionalProducts =  $productRepository->getAllAdditionalProductByProductId($product_id);

     $result = [];
     foreach ($additionalProducts as $additionalProduct){
         $product_variation = $additionalProduct->toArray()['product_variation'];
         $product_data = $product_variation['product'];
         $uoms_data = $additionalProduct->toArray()['uom']['unit_category']['uom_by_category'];

         $data = [
             'id' => $additionalProduct['id'],
             'product_id' => $product_data['id'],
             'variation_id' => $product_variation['id'],
             'quantity' => $additionalProduct['quantity'],
             'uom_id' => $additionalProduct['uom_id'],
             'product_name' => $product_data['name'],
             'variation_name' => isset($product_variation['variation_template_value']) ? $product_variation['variation_template_value']['name'] : '',
             'uoms' => $uoms_data,
         ];

         $result [] = $data;

     }
     return $result;
    }

    /////////
    public function createAdditionalProducts(?array $datas, $nextProduct, $isCreating = true)
    {
        $nextProductId = $nextProduct->id;
        $productVariation = ProductVariation::where('product_id', $nextProductId)->first();

        if (!$isCreating) {
            if ($datas === null) {
                AdditionalProduct::where('primary_product_id', $nextProductId)
                    ->delete();
                return;
            }else{
                $this->updateAdditionalProducts($datas, $nextProductId, $productVariation);
                return;
            }
        }

        $this->createNewAdditionalProducts($datas, $nextProduct, $productVariation);
    }

    private function updateAdditionalProducts(array $datas, $nextProductId, $productVariation)
    {
        $additionalDetailsIds = array_column(array_filter($datas, function ($item) {
            return isset($item['additional_detail_id']);
        }), 'additional_detail_id');

        AdditionalProduct::where('primary_product_id', $nextProductId)
            ->whereNotIn('id', $additionalDetailsIds)
            ->delete();

        foreach ($datas as $data) {
            if (isset($data['additional_detail_id'])) {
                AdditionalProduct::where('primary_product_id', $nextProductId)
                    ->where('id', $data['additional_detail_id'])
                    ->update([
                        'additional_product_variation_id' => $data['variation_id'],
                        'uom_id' => $data['uom_id'],
                        'quantity' => $data['quantity'],
                    ]);
            } else {
                AdditionalProduct::create([
                    'primary_product_id' => $nextProductId,
                    'primary_product_variation_id' => $productVariation->id,
                    'additional_product_variation_id' => $data['variation_id'],
                    'uom_id' => $data['uom_id'],
                    'quantity' => $data['quantity'],
                ]);
            }
        }
    }

    private function createNewAdditionalProducts(array $datas, $nextProduct, $productVariation)
    {
        foreach ($datas as $data) {
            AdditionalProduct::create([
                'primary_product_id' => $nextProduct->id,
                'primary_product_variation_id' => $productVariation->id,
                'additional_product_variation_id' => $data['variation_id'],
                'uom_id' => $data['uom_id'],
                'quantity' => $data['quantity'],
            ]);
        }
    }

}
