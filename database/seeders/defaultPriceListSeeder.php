<?php

namespace Database\Seeders;

use App\Models\BusinessUser;
use App\Models\data;
use App\Models\Product\PriceListDetails;
use App\Models\Product\PriceLists;
use App\Models\Product\Product;
use App\Models\Product\ProductVariation;
use App\Models\settings\businessSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class defaultPriceListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (!getData('defaultPriceListId')) {

            $businessId= 1;
            $priceList=PriceLists::create([
                        'name'=>'Default Price',
                        'price_list_type'=> 'product',
                        'business_id'=>$businessId,
                        'currency_id'=>businessSettings::where('id',$businessId)->first()->currency_id ?? 1,
                        'Description'=>'Default Price Lists for all product & variations.This price list work with default selling prices of all product.'
                    ]);
            data::create([
                'key' => 'defaultPriceListId',
                'value' => $priceList->id,
            ]);
            $products=Product::with('productVariations')->get();
            foreach ($products as $product) {
                if($product->has_variation=='single'){
                    PriceListDetails::create([
                        'pricelist_id' => $priceList->id,
                        'applied_type' => 'Product',
                        'applied_value' => $product->id,
                        'min_qty' => '1',
                        'cal_type' => 'fixed',
                        'cal_value' => $product->productVariations[0]->default_selling_price,
                    ]);
                }elseif($product->has_variation == 'variable'){
                    foreach ($product->productVariations as $pv) {
                        PriceListDetails::create([
                            'pricelist_id' => $priceList->id,
                            'applied_type' => 'Variation',
                            'applied_value' => $pv->id,
                            'min_qty' => '1',
                            'cal_type' => 'fixed',
                            'cal_value' => $pv->default_selling_price,
                        ]);
                    }
                }

            }
        }
    }
}
