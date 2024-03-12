<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class productPriceFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:product-price-fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To Fix Default Purchase Price and Selling Price that are entried as string';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Running......');
            DB::beginTransaction();
            DB::statement('UPDATE product_variations SET default_purchase_price = REPLACE(default_purchase_price, ",", "")');
            DB::statement('UPDATE product_variations SET default_selling_price = REPLACE(default_selling_price, ",", "")');


            $this->info('Successful');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
