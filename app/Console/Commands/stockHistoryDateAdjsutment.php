<?php

namespace App\Console\Commands;

use App\Models\stock_history;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class stockHistoryDateAdjsutment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust:sh-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Date to updated column';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info("---------- Adjusting Date --------------\n");
        try {
            DB::beginTransaction();
            $sh = stock_history::where('transaction_type', 'purchase')
                ->with('purchaseDetail')
                ->get();
            foreach ($sh as $s) {
                if ($s->purchaseDetail->is_delete == 1) {
                    stock_history::find($s->id)->delete();
                }
            }
            DB::update("UPDATE purchases SET received_at = created_at");
            DB::update("UPDATE sales SET delivered_at = created_at");
            DB::update("UPDATE stock_adjustments SET adjustmented_at= updated_at");
            DB::update("
                UPDATE stock_histories
                LEFT JOIN purchase_details ON stock_histories.transaction_details_id = purchase_details.id
                LEFT JOIN purchases ON purchase_details.purchases_id = purchases.id
                SET stock_histories.created_at = purchases.received_at
                WHERE stock_histories.transaction_type = 'purchase'
            ");

            DB::update("
                UPDATE stock_histories
                LEFT JOIN sale_details ON stock_histories.transaction_details_id = sale_details.id
                LEFT JOIN sales ON sale_details.sales_id = sales.id
                SET stock_histories.created_at = sales.sold_at
                WHERE stock_histories.transaction_type = 'sale'
            ");
            $this->line('Running');
            DB::update("
                UPDATE stock_histories
                LEFT JOIN opening_stock_details ON stock_histories.transaction_details_id = opening_stock_details.id
                LEFT JOIN opening_stocks ON opening_stock_details.opening_stock_id = opening_stocks.id
                SET stock_histories.created_at = opening_stocks.opening_date
                WHERE stock_histories.transaction_type = 'opening_stock'
            ");

            DB::update("
                UPDATE stock_histories
                LEFT JOIN stock_adjustment_details ON stock_histories.transaction_details_id = stock_adjustment_details.id
                LEFT JOIN stock_adjustments ON stock_adjustment_details.adjustment_id = stock_adjustments.id
                SET stock_histories.created_at = stock_adjustments.created_at
                WHERE stock_histories.transaction_type = 'adjustment'
            ");

            DB::update("
                UPDATE stock_histories
                LEFT JOIN transfer_details ON stock_histories.transaction_details_id = transfer_details.id
                LEFT JOIN transfers ON transfer_details.transfer_id = transfers.id
                SET stock_histories.created_at = transfers.created_at
                WHERE stock_histories.transaction_type = 'transfer'
            ");
            if (hasModule('StockInOut') && isEnableModule('StockInOut')){
                DB::raw("
                    UPDATE stock_histories
                    LEFT JOIN stockin_details ON stock_histories.transaction_details_id = stockin_details.id
                    LEFT JOIN stockins ON stockin_details.stockin_id = stockins.id
                    SET stock_histories.created_at = stockins.stockin_date
                    WHERE stock_histories.transaction_type = 'stock_in'
                ");
            }

            $this->info("---------- Finish --------------\n");
            DB::commit();
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
            DB::rollBack();
            //throw $th;
        }
    }
}
