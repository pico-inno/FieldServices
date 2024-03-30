<?php

namespace App\Console\Commands\drB;

use App\Models\sale\sales;
use App\Models\openingStocks;
use App\Models\stock_history;
use Illuminate\Console\Command;
use App\Models\lotSerialDetails;
use App\Models\CurrentStockBalance;
use App\Models\openingStockDetails;
use App\Models\purchases\purchases;
use App\Models\Stock\StockTransfer;

class dataReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drb1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to reset status to order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Start Cleaning....');

            openingStocks::truncate();

            openingStockDetails::truncate();
            CurrentStockBalance::truncate();
            lotSerialDetails::truncate();
            stock_history::truncate();

            purchases::where("status",'Received')->update([
                "status"=>"order",
            ]);

            sales::where("status",'delivered')->update([
                "status"=>"order",
            ]);

            StockTransfer::where("status",'completed')->update([
                "status"=>"pending",
            ]);

        $this->info('Successfully clean');
    }
}
