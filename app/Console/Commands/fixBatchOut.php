<?php

namespace App\Console\Commands;

use App\Helpers\UomHelper;
use App\Models\lotSerialDetails;
use App\Models\sale\sale_details;
use Illuminate\Console\Command;

class fixBatchOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-batch-out';

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
        $sds=sale_details::select('id','quantity','uom_id')->get();

        foreach ($sds as $sd) {
            $qtyByRefUom = UomHelper::getReferenceUomInfoByCurrentUnitQty($sd['quantity'], $sd['uom_id'])['qtyByReferenceUom'];
           $ls=lotSerialDetails::where('transaction_detail_id',$sd->id)
                            ->where('transaction_type','sale')
                            ->first();
            if($ls){
                $ls->update([
                    'uom_quantity'=>$sd['quantity'],
                    'ref_uom_quantity'=>$qtyByRefUom,
                ]);
            }

        }
    }
}
