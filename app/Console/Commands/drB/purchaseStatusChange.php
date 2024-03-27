<?php

namespace App\Console\Commands\drB;

use purchase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\purchases\purchases;
use App\Models\purchases\purchase_details;
use App\Actions\purchase\purchaseDetailActions;

class purchaseStatusChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drb2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to change status and store stock';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $Purchases=purchases::where('is_delete',0)->get();
            $this->info('Start Changing');
            $bar = $this->output->createProgressBar(count($Purchases));
            foreach ($Purchases as $purchase) {
                $bar->advance();
               $purchase->where('id',$purchase['id'])->update([
                'status'=>'received'
               ]);
               $purchase['status']='received';

            //    $this->info($purchase['status']);
               $purchaseDetails=purchase_details::where('purchases_id',$purchase['id'])->where('is_delete','!=','1')->get();
               foreach ($purchaseDetails as  $purchaseDetail) {
                // $this->info($purchase);
                    if($purchaseDetail){

                        $csb=new purchaseDetailActions();
                        $csb->currentStockBalanceAndStockHistoryCreation($purchaseDetail, $purchase, 'purchase');
                    }
               }
            }
            $bar->finish();
            $this->info('\n Successfully changed');
            DB::commit();
        } catch (\Throwable $th) {
            logger($th->getMessage());
            DB::rollback();
            $this->error($th->getMessage());
        }
    }
}
