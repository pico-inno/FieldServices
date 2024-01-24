<?php

namespace App\Console\Commands;

use App\Helpers\UomHelper;
use Illuminate\Console\Command;
use App\Models\lotSerialDetails;

class addrefqty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refqty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Ref: Qty to LotSerial Detail';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lsid = lotSerialDetails::get();

        $bar = $this->output->createProgressBar(count($lsid));
        foreach ($lsid as $ls) {
            $bar->advance();
            $qtyByRefUom = UomHelper::getReferenceUomInfoByCurrentUnitQty($ls['uom_quantity'], $ls['uom_id'])['qtyByReferenceUom'];
            $ls->update([
                'ref_uom_quantity' => $qtyByRefUom,
            ]);
        }
        $bar->finish();
    }
}
