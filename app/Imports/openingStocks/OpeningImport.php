<?php

namespace App\Imports\openingStocks;

use App\Helpers\UomHelper;
use Exception;
use App\Models\Product\UOM;
use App\Models\Product\Unit;
use App\Models\Product\UOMSet;
use App\Models\Product\Product;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
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
use App\Models\openingStocks;

class OpeningImport implements ToModel,WithHeadingRow
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
    public function model(Array $row)
    {
        if($row['product_name'] || $row['variation_value'] || $row['expired_date'] || $row['quantity']  || $row['price'] || $row['remark']  ){
            Validator::make($row,[
                'product_name'=>'required|exists:products,name',
                'quantity'=>'required|numeric',
                'price'=>'required|numeric',
                'uom_name' => 'required|exists:uom,name',
                'variation_value' => 'nullable|exists:variation_template_values,name',
            ],[
                'product_name.exists'=>'Imported product is not exists in products list',
                'uom_name.exists' => 'Imported UOM [:value] is not exists in uom set list',
                'variation_value.exists' => 'Imported variation value is not exists in variation value list'
            ])->validate();

            // get uom_set_id
            $uom_name = $row['uom_name'];
            $uom=UOM::where('name',$uom_name)->get()->first();
            $uom_id=$uom->id;

            // get product_id
            $product_name=$row['product_name'];
            $product= Product::where('name', $product_name)->select('id', 'product_type','uom_id')->first();
            $product_id = $product->id;
            $unitCategoryId=$product->uom->unit_category->id;
            // custom validation for custom rule
            $customUnitRule = Rule::exists('uom', 'id')->where(function ($query) use ($unitCategoryId) {
                $query->where('unit_category_id', $unitCategoryId);

            });
            // second_match_validator
            Validator::make([
                'uom_id'=>$uom_id,
                'variation_value'=>$row['variation_value']
            ], [
                'uom_id' => [$customUnitRule],
                'variation_value' => [
                    Rule::requiredIf($product->product_type == "variable"),
                ],
            ],[
                'uom_id.exists'=> 'Make sure units are must be same category that defined in product!'
            ])->validate();


            // get product_variation_id
            if($product->product_type!="single"){
                $variation_value=$row['variation_value'];
                $variation_value_id=VariationTemplateValues::where('name', $variation_value)->first()->id;
                $variation_id=ProductVariation::where('product_id',$product_id)->where('variation_template_value_id',$variation_value_id)->first()->id;

                $customVariationRule = Rule::exists('product_variation', 'variation_template_value_id')->where(function ($query) use ($product_id) {
                    $query->where('product_id', $product_id);
                });
                Validator::make([
                    'variation_id'=>$variation_id
                ],[
                    'variation_id'=>[$customVariationRule,'required']
                ]);
            }else{
                $variation_id=ProductVariation::where('product_id',$product->id)->first()->id;
            }



            $expired_date=$row['expired_date'];
            $quantity = $row['quantity'];
            $price = $row['price'];
            $remark = $row['remark'];
            $refUomInfo=UomHelper::getReferenceUomInfoByCurrentUnitQty($quantity,$uom_id);
            $smallestQty = $refUomInfo['qtyByReferenceUom'];
            $refUomId=$refUomInfo['referenceUomId'];
            $data=[
                'opening_stock_id'=>$this->openingStock->id,
                'product_id'=>$product_id,
                'variation_id'=> $variation_id,
                'uom_id'=> $uom_id,
                'quantity'=>$quantity,
                'uom_price'=>$price,
                'subtotal'=>$price * $quantity,
                'ref_uom_id'=>$refUomId,
                'ref_uom_price'=>$price ?? 0 / $refUomInfo['qtyByReferenceUom'] ?? 1,
                'remark'=>$remark,
                'created_at' => now(),
                'created_by'=>Auth::user()->id,
            ];
            $detailData=  openingStockDetails::create($data);
            $openingStockController=new openingStockController;
            $currentStockData=$openingStockController->currentStockBalanceData($detailData,$this->openingStock,'opening_stock');
            CurrentStockBalance::create(
                $currentStockData
            );
            return $detailData;
        }
        return ;
    }


}
