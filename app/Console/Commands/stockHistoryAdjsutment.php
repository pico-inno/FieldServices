<?php

namespace App\Console\Commands;

use App\Helpers\UomHelper;
use App\Models\stock_history;
use Illuminate\Console\Command;
use App\Models\sale\sale_details;
use App\Models\Stock\StockTransferDetail;
use Illuminate\Support\Facades\DB;
use Modules\StockInOut\Entities\StockoutDetail;

class stockHistoryAdjsutment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust:sh-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To Adjust Stock History Data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("---------- Adjusting data --------------\n");
        try {
            DB::beginTransaction();
            $transactions = [
                'sale',
                'stock_out',
                'adjustment',
                'transfer'
            ];
            foreach ($transactions as $tx) {
                $duplicateHistories = stock_history::where('transaction_type', $tx)
                    ->where('decrease_qty', '>', 0)
                    ->get()
                    ->groupBy('transaction_details_id')
                    ->toArray();

                $bar = $this->output->createProgressBar(count($duplicateHistories));
                $bar->start();

                foreach ($duplicateHistories as $dh) {
                    $bar->advance();
                    if (count($dh) > 1) {
                        if ($tx == 'sale') {
                            $sd = sale_details::where('id', $dh[0]['transaction_details_id'])->first();
                            $ref = UomHelper::getReferenceUomInfoByCurrentUnitQty($sd['quantity'], $sd['uom_id']);
                        }
                        if ($tx == 'transfer') {
                            $tf = StockTransferDetail::where('id', $dh[0]['transaction_details_id'])->first();
                            $ref = UomHelper::getReferenceUomInfoByCurrentUnitQty($tf['quantity'], $tf['uom_id']);
                        }
                        if(hasModule('StockInOut') && isEnableModule('StockInOut') && $tx == 'stock_out'){
                            $sod = StockoutDetail::where('id', $dh[0]['transaction_details_id'])->first();
                            $ref = UomHelper::getReferenceUomInfoByCurrentUnitQty($sod['quantity'], $sod['uom_id']);
                        }
                        if ($ref) {
                            stock_history::where('id', $dh[0]['id'])->update(['decrease_qty' => $ref['qtyByReferenceUom']]);
                            stock_history::where('transaction_type', $tx)
                                            ->where('transaction_details_id', $dh[0]['transaction_details_id'])
                                            ->where('decrease_qty', '>', 0)
                                            ->where('increase_qty','<=',0)
                                            ->whereNotIn('id', [$dh[0]['id']])->delete();
                        }
                    }
                }
            }
            $bar->finish();
            DB::commit();
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}
