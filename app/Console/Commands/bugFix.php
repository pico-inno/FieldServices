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
            ->where('is_delete', 0)
            ->select('id', 'per_item_discount', 'uom_price', 'discount_type', 'subtotal_with_discount', 'subtotal', 'sales_id', 'quantity')
            ->get();

        $bar = $this->output->createProgressBar(count($datas));

        $salesId = [];
        foreach ($datas as  $sd) {
            $bar->advance();
            // $calculation = ($sd->uom_price - calPercentageNumber($sd->discount_type, $sd->per_item_discount ?? 0, $sd->uom_price)) * $sd->quantity;
            sale_details::where('id', $sd->id)->update([
                'uom_price' => $sd->subtotal / $sd->quantity
            ]);
            $sid = $sd->sales_id;
            $salesId[$sid] = $sid;

            if($sd->uom_price != ($sd->subtotal / $sd->quantity)){
                logger([
                    $sd
                ]);
            }
        }

        $bar->finish();


    }
}
