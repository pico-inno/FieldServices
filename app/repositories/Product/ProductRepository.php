<?php

namespace App\Repositories\Product;

use App\Models\Product\AdditionalProduct;
use App\Models\Product\Product;
use App\Models\Product\ProductVariation;
use App\Models\Product\ProductVariationsTemplates;
use App\Models\Product\VariationTemplateValues;
use App\Models\Product\VariationValue;
use App\Models\productPackaging;

class ProductRepository
{
    public function query()
    {
        return Product::query();
    }
    public function queryVariation()
    {
        return ProductVariation::query();
    }
    public function queryVariationsTemplates()
    {
        return ProductVariationsTemplates::query();
    }
    public function queryPackaging()
    {
        return productPackaging::query();
    }
    public function queryAdditional()
    {
        return AdditionalProduct::query();
    }

    public function getCountWithTrashed()
    {
        return Product::withTrashed()->count();
    }
    public function getAll()
    {
        return Product::all();
    }

    public function getById($id)
    {
        return Product::find($id);
    }

    //Create Section

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function createVariation(array $data)
    {
        return ProductVariation::create($data);
    }

    public function insertVariation(array $data)
    {
        return  ProductVariation::insert($data);
    }

    public function createVariationTemplate(array $data)
    {
        return ProductVariationsTemplates::create($data);
    }

    public function createPackaging(array $data)
    {
        return productPackaging::create($data);
    }

    public function insertPackaging(array $data)
    {
        return productPackaging::insert($data);
    }

    public function createAdditionalProduct(array $data)
    {
        return AdditionalProduct::created($data);
    }

    //Create Section

    public function update($id, array $data)
    {
        return Product::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        Product::where('id', $id)->update(['deleted_by' => auth()->id()]);
        return Product::destroy($id);
    }

    public function getProductVariationIdsByVariationTemplateId($variationTemplateId)
    {
        return ProductVariation::whereHas('variationTemplateValue', function ($query) use ($variationTemplateId) {
            $query->where('variation_template_id', $variationTemplateId);
        })->pluck('id')->toArray();
    }

    public function getVariationByProductIdWithRelationships($product_id, $relations = [])
    {
        return ProductVariation::with($relations)->where('product_id', $product_id)->get();
    }

    public function getPackagingByProductIdWithRelationships($product_id, $relations = [])
    {
        return productPackaging::where('product_id', $product_id)->with($relations)->get();
    }

    public function getAllAdditionalProductByProductId($product_id)
    {
        return AdditionalProduct::where('primary_product_id', $product_id)
            ->with([
                'productVariation' => function ($query) {
                    $query->select('id', 'product_id', 'variation_template_value_id')
                        ->with([
                            'variationTemplateValue' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'product' => function ($query) {
                                $query->select('id', 'name', 'uom_id');
                            }
                        ]);
                },
                'uom' => function ($query) {
                    $query->select('id', 'name', 'short_name', 'unit_category_id')
                        ->with(['unit_category' => function ($query) {
                            $query->select('id', 'name')->with('uomByCategory');
                        }]);
                }
            ])
            ->get();
    }

    public function getVariationTemplateValueId($product_id)
    {
        return VariationValue::where('product_id', $product_id)
            ->distinct('variation_template_value_id')
            ->pluck('variation_template_value_id');
    }
    public function getVariationTemplateId($variationTemplateValuesId)
    {
        return  VariationTemplateValues::whereIn('id', $variationTemplateValuesId)
            ->distinct('variation_template_id')
            ->pluck('variation_template_id');
    }
}
