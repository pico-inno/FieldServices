<?php

namespace App\Console\Commands\drB;

use App\Models\openingStocks;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\openingStockDetails;
use App\Models\stock_history;

class osDeleteUltraLoad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osul';

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

            $osds=openingStockDetails::query()
                                    ->select('id','opening_stock_id')
                                    ->where('opening_stock_id',8)
                                    ->where('id','>',3765)
                                    ->where('id','<=',4046)
                                    ->get();
            $bar=$this->output->createProgressBar(count($osds));
            $osUsedList=[];
            foreach ($osds as $key => $osd) {
                $bar->advance();

                $csb=CurrentStockBalance::where('transaction_type', 'opening_stock')->where('transaction_detail_id', $osd->id)->first();
                if($csb && $csb->current_quantity != $csb->ref_uom_quantity){
                    $osUsedList=[...$osUsedList,[
                        'osdId'=>$osd['id'],
                        'current_quantity'=>$osd['current_quantity'],
                        'ref_uom_quantity'=>$osd['ref_uom_quantity'],
                        'differ'=>$osd['ref_uom_quantity']-$osd['current_quantity']
                    ]];
                }else{
                    $csb->update([
                        'current_quantity'=>0
                    ]);
                }
                stock_history::where('transaction_type', 'opening_stock')->where('transaction_details_id', $osd->id)->delete();
                openingStockDetails::where('id', $osd['id'])->update([
                    'is_delete' => 1,
                    'deleted_at' => now()
                ]);
                $subtotal=openingStockDetails::where('opening_stock_id',8)->where('is_delete',0)->sum('subtotal');
                openingStocks::where('id',8)->update([
                    'total_opening_amount'=>$subtotal
                ]);
            }

            $bar->finish();
            $this->table(
                ['os id', 'current_quantity','ref_uom_quantity','differ'],
                $osUsedList
            );
            DB::commit();
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
            DB::rollBack();
        }
    }
}
