<?php

namespace App\Console\Commands;

use App\Models\sale\sales;
use Illuminate\Console\Command;

class paymentStatusChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:payment-status-checker';

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
            $sales=sales::all();

            $bar = $this->output->createProgressBar(count($sales));
            foreach ($sales as $sale) {
                $bar->advance();
                if ($sale->paid_amount == 0 && $sale->total_sale_amount != 0) {
                    $payment_status = 'due';
                } elseif ($sale->paid_amount >= $sale->total_sale_amount) {
                    $payment_status = 'paid';
                } else {
                    $payment_status = 'partial';
                }
                sales::where('id',$sale->id)->first()->update(['payment_status'=>$payment_status]);
                calcreceiveable($sale->contact_id);
            }
            $bar->finish();

           $this->info('success');
        } catch (\Throwable $th) {
           $this->error($th->getMessage());
        }

    }
}
