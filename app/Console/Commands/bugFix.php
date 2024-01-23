<?php

namespace App\Console\Commands;

use App\Models\sale\sales;
use Illuminate\Console\Command;
use App\Models\sale\sale_details;
use Illuminate\Support\Facades\DB;

class bugFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bug-fix';

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

        $datas = sale_details::query()
        // where('per_item_discount', '!=', '0.0000')
        ->where('is_delete',0)
        ->select('id','per_item_discount', 'uom_price', 'discount_type', 'subtotal_with_discount', 'subtotal', 'sales_id', 'quantity')
        ->get();

        $bar = $this->output->createProgressBar(count($datas));

        $salesId=[];
        $bar->start();
        foreach ($datas as  $sd) {
            $bar->advance();
            $calculation= ($sd->uom_price - calPercentageNumber($sd->discount_type, $sd->per_item_discount ?? 0, $sd->uom_price)) * $sd->quantity;
            sale_details::where('id', $sd->id)->update([
                'subtotal'=>$sd->uom_price*$sd->quantity,
                'subtotal_with_discount'=> $calculation,
                'subtotal_with_tax' => $calculation,
            ]);
            $sid= $sd->sales_id;
            $salesId[$sid]= $sid;
        }

        $bar->finish();

        $newBar = $this->output->createProgressBar(count($salesId));
        $newBar->start();
        foreach ($salesId as  $id) {

            $newBar->advance();
            $sales = sales::where('sales.id', $id)
            ->select('sales.*',
                DB::raw('SUM(sale_details.subtotal_with_discount) as sale_details_sum_subtotal_with_discount'),
                DB::raw('SUM(sale_details.subtotal) as sale_details_sum_subtotal'),
            )
            ->where('sales.is_delete',0)
            ->with('sale_details')
            ->leftJoin('sale_details', 'sale_details.id','=','sales.id')
            ->where('sale_details.is_delete', 0)
            // ->withSum('sale_details', 'subtotal_with_discount')
            // ->withSum('sale_details', 'subtotal')
            ->first();
            $updatedPrice = $sales->sale_details_sum_subtotal_with_discount;
            $totalItemDiscount = $sales->sale_details_sum_subtotal - $sales->sale_details_sum_subtotal_with_discount;
            $extraDiscount = calPercentageNumber($sales->extra_discount_type, $sales->extra_discount_amount, $updatedPrice);
            $totalSaleAmt = $updatedPrice - $extraDiscount;

            $balance_amount = $sales->balance_amount == 0 ? 0: $sales->paid_amount - $totalSaleAmt;
            $sales->update([
                'sale_amount' => $sales->sale_details_sum_subtotal,
                'total_item_discount' => $totalItemDiscount,
                'total_sale_amount' => $totalSaleAmt,
                'balance_amount'=> $balance_amount
            ]);
        }
        $newBar->finish();
    }
}
