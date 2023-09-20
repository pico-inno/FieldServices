<?php

namespace App\Imports\priceList;

use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Product\PriceLists;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Product\PriceListDetails;
use App\Models\Product\ProductVariation;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Product\VariationTemplateValues;


class priceListImport implements ToCollection, WithHeadingRow
{
    // protected $priceList;
    protected $pricelistDetails;
    public function __construct()
    {
        // $this->priceList = $priceList;
    }
    public function collection(Collection $rows)
    {
        $pricelistDetails=[];
        foreach ($rows as $row) {
            $pricelistData=[];
            try {
                if (
                    (
                        $row['category'] ||
                        $row['product'] ||
                        $row['variation']
                    ) &&
                    $row['min_quantity'] &&
                    $row['fix_or_percentage'] &&
                    $row['value']
                ) {
                    if (
                        ($row['category'] && $row['product'] && $row['variation']) ||
                        ($row['product'] && $row['variation']) ||
                        ($row['category'] && $row['variation'])
                        || $row['variation']
                        ) {
                        $appliedType = "Variation";
                        $product = Product::where('name', $row['product'])->firstOrFail();
                        $productVariationTemplate = VariationTemplateValues::where('name', $row['variation'])->firstOrFail();
                        $product_with_variations = ProductVariation::whereNotNull('variation_template_value_id')
                                                    ->where('product_id',$product->id)
                                                    ->where('variation_template_value_id', $productVariationTemplate->id)
                                                    ->select('id', 'product_id', 'variation_template_value_id')
                                                    ->firstOrFail();
                        // dd($product_with_variations);
                        $appliedValue = $product_with_variations->id;
                        // $appliedType = "Product";
                    } elseif (($row['category'] && $row['product'])||$row['product'])
                    {
                        $appliedType = "Product";
                        $product = Product::where('name', $row['product'])->firstOrFail();
                        $appliedValue = $product->id;
                    } elseif ($row['category']) {
                        $appliedType = "Category";
                        $category = Category::where('name', $row['category'])->firstOrFail();
                        $appliedValue = $category->id;
                    }

                    $pricelistData = [
                        'detail_id' => isset($row['idplease_dont_touch']) ? $row['idplease_dont_touch']:'',
                        'min_qty' => $row['min_quantity'],
                        'applied_type' => $appliedType,
                        'applied_value' => $appliedValue,
                        'min_qty' => $row['min_quantity'],
                        'cal_type' => $row['fix_or_percentage'],
                        'cal_value' => $row['value'],
                        'start_date' => $row['start_date'],
                        'end_date' => $row['end_date'],
                    ];
                    $pricelistDetails=[...$pricelistDetails,$pricelistData];
                }
            } catch (\Throwable $th) {
                throw new \Exception($th->getMessage());
            }
        }
        return $this->pricelistDetails=$pricelistDetails;
    }
    public function getProcessedData()
    {
        return $this->pricelistDetails;
    }

}
