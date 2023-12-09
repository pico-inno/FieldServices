<?php

namespace App\Console\Commands;

use App\Helpers\UomHelper;
use App\Models\CurrentStockBalance;
use App\Models\openingStockDetails;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\error;

class adjustOsRefPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust:os-price';

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
        try {
            DB::beginTransaction();
            $osds = openingStockDetails::select('id', 'uom_id', 'uom_price', 'quantity')->whereNot('is_delete',1)->get();
            $bar = $this->output->createProgressBar(count($osds));
            $bar->start();
            foreach ($osds as $osd) {
                $bar->advance();
                $data = $this->dataForOpeningStockDetails($osd);
                $osd->where('id', $osd->id)->first()->update($data);
                CurrentStockBalance::where('transaction_detail_id', $osd->id)
                ->where('transaction_type', 'opening_stock')
                ->first()
                ->update($data);
            }

            $bar->finish();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            error($th->getMessage());
            //throw $th;
        }
    }

    public function dataForOpeningStockDetails($detail)
    {
        $referencteUom = UomHelper::getReferenceUomInfoByCurrentUnitQty($detail['quantity'], $detail['uom_id']);
        $per_ref_uom_price = priceChangeByUom($detail['uom_id'], $detail['uom_price'], $referencteUom['referenceUomId']);
        return [
            'ref_uom_price' => $per_ref_uom_price,
        ];
    }
}
