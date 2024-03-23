<?php

namespace App\Console\Commands;

use App\Actions\currentStockBalance\currentStockBalanceActions;
use App\Helpers\UomHelper;
use App\Models\CurrentStockBalance;
use App\Models\openingStockDetails;
use App\Models\Product\Product;
use App\Models\Product\ProductVariation;
use Illuminate\Console\Command;

class defaultPurchasePriceToOs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dpToPurchase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Default Purchase Price To Os';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $osds= openingStockDetails::where("is_delete",0)->get();
        foreach ($osds as $osd) {
            $product=ProductVariation::where('id',$osd['variation_id'])
                        ->select("default_purchase_price")->first();
           $uom_price=$product['default_purchase_price'];
           $refUomPrice=UomHelper::getReferenceUomInfoByCurrentUnitQty($osd['quantity'],$osd['uom_id']);
           $this->info($uom_price,$refUomPrice['qtyByReferenceUom']);
        }
    }
}
