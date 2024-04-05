<?php

namespace App\Console\Commands;

use App\Models\lotSerialDetails;
use App\Models\Product\ProductVariation;
use App\Models\Product\VariationValue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProductVariationOldToNew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:product-variation-old-to-new';

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
        try {
            DB::beginTransaction();
            if ($this->confirm('Do you really want to migrate product variation to variation value?')) {
                $productVariations = ProductVariation::whereNotNull('variation_template_value_id')
                    ->get();
                $countChanges = 0;
                $bar=$this->output->createProgressBar(count($productVariations));
                if ($productVariations->isNotEmpty()) {
                    foreach ($productVariations as $detail) {
                        $bar->advance();

                        VariationValue::create([
                            'product_id' => $detail->product_id,
                            'product_variation_id' => $detail->id,
                            'variation_template_value_id' => $detail->variation_template_value_id,
                        ]);
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
