<?php

namespace App\Console\Commands;

use App\Models\systemSetting;
use App\Models\Product\Product;
use Illuminate\Console\Command;
use App\Models\Product\PriceLists;
use App\Models\Product\PriceListDetails;

class defaultPriceListUpdater extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-default-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $defCusId=getSystemData('defaultPriceListId');
        if($defCusId){
            $priceList = PriceLists::where('id',$defCusId)->first();
            $products = Product::with('productVariations')->get();
            foreach ($products as $product) {
                if ($product->has_variation == 'single') {
                    $checkExist=PriceListDetails::where('pricelist_id',$priceList->id)
                                    ->where('applied_type','Product')
                                    ->where('applied_value',$product->id)
                                    ->exists();
                    if($checkExist){
                        PriceListDetails::where('pricelist_id',$priceList->id)
                                        ->where('applied_type','Product')
                                        ->where('applied_value',$product->id)
                                        ->update([
                                            'cal_value' => $product->productVariations[0]->default_selling_price
                                        ]);
                    }else{
                        PriceListDetails::create([
                            'pricelist_id' => $priceList->id,
                            'applied_type' => 'Product',
                            'applied_value' => $product->id,
                            'min_qty' => '1',
                            'cal_type' => 'fixed',
                            'cal_value' => $product->productVariations[0]->default_selling_price,
                        ]);
                    }
                } elseif ($product->has_variation == 'variable') {
                    foreach ($product->productVariations as $pv) {
                        $checkExist=PriceListDetails::where('pricelist_id',$priceList->id)
                                    ->where('applied_type','Variation')
                                    ->where('applied_value',$pv->id)
                                    ->exists();
                        if($checkExist){
                            PriceListDetails::where('pricelist_id',$priceList->id)
                                    ->where('applied_type','Variation')
                                    ->where('applied_value',$pv->id)
                                    ->update([
                                        'cal_value' => $pv->default_selling_price,
                                    ]);
                        }else{
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
            $this->info('Successfully Updated');
        }
    }
}
