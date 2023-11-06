<?php

namespace App\Services\product;

use App\Models\Product\AdditionalProduct;
use App\Models\Product\ProductVariation;

class productServices
{


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
