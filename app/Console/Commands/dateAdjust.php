<?php

namespace App\Console\Commands;

use App\Models\stock_history;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class dateAdjust extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust:date';

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
            DB::update("UPDATE sales SET delivered_at = created_at");
            DB::update("UPDATE sales SET sold_at = created_at");
            $this->info("---------- Finish --------------\n");
            DB::commit();
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
            DB::rollBack();
            //throw $th;
        }
    }
}
