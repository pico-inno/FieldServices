<?php

namespace App\Console\Commands;

use App\Models\sale\sale_details;
use Illuminate\Console\Command;

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

        $datas = sale_details::where('per_item_discount', '!=', '0.0000')->select('id','per_item_discount', 'discount_type', 'subtotal_with_discount', 'subtotal', 'sales_id', 'quantity')->get();

        $bar = $this->output->createProgressBar(count($datas));
        $bar->start();
        foreach ($datas as  $sd) {
            $bar->advance();
            $calculation= ($sd->subtotal - calPercentageNumber($sd->discount_type, $sd->per_item_discount, $sd->subtotal)) * $sd->quantity;
            $this->info($calculation. $sd->discount_type.'--'. $sd->id);
            sale_details::where('id', $sd->id)->update([
                'subtotal_with_discount'=> $calculation,
                'subtotal_with_tax' => $calculation,
            ]);
        }

        $bar->finish();
    }
}
