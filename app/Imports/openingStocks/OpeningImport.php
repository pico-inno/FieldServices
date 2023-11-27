<?php

namespace App\Imports\openingStocks;

use Exception;
use App\Helpers\UomHelper;
use App\Models\Product\UOM;
use App\Models\Product\Unit;
use App\Models\openingStocks;
use App\Models\Product\UOMSet;
use App\Models\Product\Product;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\openingStockDetails;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Product\ProductVariation;
use Illuminate\Support\Facades\Validator;
use App\Models\Product\VariationTemplates;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Product\VariationTemplateValues;
use App\Http\Controllers\openingStockController;

class OpeningImport implements ToCollection,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $openingStock;

    public function __construct($data)
    {
        $this->openingStock=$data;
    }
    public function collection(Collection $rows)
    {
        // dd(ini_get('max_input_vars'));
        try {
            DB::beginTransaction();
            $chunkedRows=$rows->chunk(100);
            $count=0;
            foreach ($chunkedRows as $index => $rows) {
                if($count>=5){

                    $count = 0;
                    $data= $this->openingStock;
                    $opening_stock_count = openingStocks::count();
                    $this->openingStock=openingStocks::create([
                        'business_location_id' => $data->business_location_id,
                        'opening_stock_voucher_no' => sprintf('OS-' . '%06d', ($opening_stock_count + 1)),
                        'opening_date' => now(),
                        'opening_person' => Auth::user()->id,
                        'total_opening_amount' => $data->total_opening_amount,
                        'note' => $data->note,
                        'created_by' => Auth::user()->id,
                        'updated_at' => null,
                    ]);
                }
                $count++;
                foreach ($rows as  $row) {
                    $row=$row->toArray();
                    if ($row['product_name'] || $row['variation_name'] || $row['product_variation_sku'] || $row['expired_date'] || $row['quantity'] || $row['unit_uom_name'] || $row['price'] || $row['remark']) {
                        Validator::make($row, [
                            'product_variation_sku' => 'required|exists:product_variations,variation_sku',
                            'quantity' => 'required|numeric',
                            'price' => 'required|numeric',
                            'unit_uom_name' => 'required|exists:uom,name',
                            'variation_value' => 'nullable|exists:variation_template_values,name',
                        ], [
                            'product_variation_sku.exists' => 'Imported product is not exists in products list',
                            'unit_uom_name.exists' => "Imported UOM [:value] is not exists in uom set list",
                            'variation_value.exists' => 'Imported variation value is not exists in variation value list'
                        ])->validate();
                        // get uom_set_id
                        $uom_name = $row['unit_uom_name'];
                        $uom = UOM::where('name', $uom_name)->get()->first();
                        $uom_id = $uom->id;

                        // 'product_name' => 'required|exists:products,name',
                        // get product_id
                        $product_name = $row['product_name'];
                        $sku = $row['product_variation_sku'];
                        $productVariation = ProductVariation::where('variation_sku', $sku)->first();
                        $product = Product::where('id', $productVariation->product_id)->select('id', 'has_variation', 'uom_id', 'product_type')->first();

                        if ($product->product_type != 'storable') {
                            return;
                        }
                        $product_id = $product->id;

                        // dd($product_id);
                        $unitCategoryId = $product->uom->unit_category->id;
                        // custom validation for custom rule
                        $customUnitRule = Rule::exists('uom', 'id')->where(function ($query) use ($unitCategoryId) {
                            $query->where('unit_category_id', $unitCategoryId);
                        });
                        // second_match_validator
                        Validator::make([
                            'uom_id' => $uom_id,
                            'variation_name' => $row['variation_name']
                        ], [
                            'uom_id' => [$customUnitRule],
                            'variation_name' => [
                                Rule::requiredIf($product->has_variation == "variable"),
                            ],
                        ], [
                            'uom_id.exists' => 'Make sure units are must be same category that defined in product!'
                        ])->validate();


                        // get product_variation_id
                        if ($product->has_variation != "single") {
                            $variation_name = $row['variation_name'];
                            Validator::make([
                                'variation_name' => $row['variation_name']
                            ], [
                                'variation_name' => 'exists:variation_template_values,name',
                            ])->validate();
                            $variation = VariationTemplateValues::where('name', $variation_name)->first();
                            $variation_value_id = $variation->id;
                            $variation_id = ProductVariation::where('product_id', $product_id)->where('variation_template_value_id', $variation_value_id)->first()->id;

                            $customVariationRule = Rule::exists('product_variation', 'variation_template_value_id')->where(function ($query) use ($product_id) {
                                $query->where('product_id', $product_id);
                            });
                            Validator::make([
                                'variation_id' => $variation_id
                            ], [
                                'variation_id' => [$customVariationRule, 'required']
                            ]);
                        } else {
                            $variation_id = ProductVariation::where('product_id', $product->id)->first()->id;
                        }



                        $expired_date = $row['expired_date'];
                        $quantity = $row['quantity'];
                        $price = $row['price'];
                        $remark = $row['remark'];
                        $refUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($quantity, $uom_id);
                        $smallestQty = $refUomInfo['qtyByReferenceUom'];
                        $refUomId = $refUomInfo['referenceUomId'];
                        $data = [
                            'opening_stock_id' => $this->openingStock->id,
                            'product_id' => $product_id,
                            'variation_id' => $variation_id,
                            'uom_id' => $uom_id,
                            'quantity' => $quantity,
                            'uom_price' => $price,
                            'subtotal' => $price * $quantity,
                            'ref_uom_id' => $refUomId,
                            'ref_uom_price' => $price ?? 0 / $refUomInfo['qtyByReferenceUom'] ?? 1,
                            'remark' => $remark,
                            'created_at' => now(),
                            'created_by' => Auth::user()->id,
                        ];
                        $detailData =  openingStockDetails::create($data);
                        $openingStockController = new openingStockController;
                        $currentStockData = $openingStockController->currentStockBalanceData($detailData, $this->openingStock, 'opening_stock');
                        CurrentStockBalance::create(
                            $currentStockData
                        );
                    }
                }
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage(),$row,'here error');
        }
    }


}
