<?php

namespace App\Console\Commands;

use App\Models\lotSerialDetails;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LotSerialTableStockStatusMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:lot-serial-table-stock-status-migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'is_prepare to stock_status of data migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {
            DB::beginTransaction();
            if ($this->confirm('Do you really want to migrate is_prepare column to stock_status column?')) {
                $lotSerialDetails = LotSerialDetails::all();
                $countChanges = 0;
                $bar=$this->output->createProgressBar(count($lotSerialDetails));
                if ($lotSerialDetails->isNotEmpty()) {
                    foreach ($lotSerialDetails as $detail) {
                        $bar->advance();
                        if ($detail->is_prepare === 0) {
                            $detail->stock_status = 'normal';
                            $countChanges++;
                        } elseif ($detail->is_prepare === 1) {
                            $detail->stock_status = 'prepare';
                            $countChanges++;
                        }
                        $detail->save();
                    }
                    $this->info('Data migration is successful. Total ' . $countChanges . ' rows data changed.');
                } else {
                    $this->warn('No need to data migrate. Table is empty.');
                }
                $bar->finish();
            } else {
                $this->info('Operation cancelled.');
            }
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            $this->error($exception->getMessage());
        }
    }
}
