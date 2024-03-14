<?php

namespace App\Console\Commands;

use App\Models\openingStocks;
use Illuminate\Console\Command;

class FixOsRefNo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fixOsRefNo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To fixed opening stock ref number';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $datas=openingStocks::select('id','opening_stock_voucher_no')->get();
        $bar = $this->output->createProgressBar(count($datas));
        foreach ($datas as $index=>$data) {

           $bar->advance();
           $data->update([
            'opening_stock_voucher_no'=>sprintf('OS-'.'%06d', ($data['id'])),
           ]);
        }
        $bar->finish();
        //
    }
}
