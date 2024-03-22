<?php

namespace App\Console\Commands;

use App\Models\sale\sales;
use App\Models\Contact\Contact;
use Illuminate\Console\Command;
use App\Models\purchases\purchases;

class payableReceivableCal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:payable-receivable-cal';

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
        $contacts=Contact::get();


        foreach ($contacts as $contact) {
            $purcases=purchases::where('contact_id',$contact['id'])->get();
            $sales=sales::where('contact_id',$contact['id'])->get();

            $result=$this->calPayableReceiveableByCus($purcases,$sales);
            Contact::where('id',$contact['id'])->update([
                "payable_amount"=>$result['payable'],
                "receivable_amount"=>$result['receiveable'],
            ]);
            $this->Info($contact['first_name']);
            $this->info("Payable :". $result['payable']);
            $this->info("Receiveable :". $result['receiveable']);
            $this->Info("\n==========================\n");
        }
    }
    public function calPayableReceiveableByCus($purcases,$sales){
        $payable=0;
        $receiveable=0;
        foreach ($purcases as $purchase) {
            if($purchase->balance_amount  >=0){
                $payable+=$purchase->balance_amount ?? 0;
            }
        }

        foreach ($sales as $sale) {
            if($sale->balance_amount  >=0){
                $receiveable+=$sale->balance_amount ?? 0;
            }
        }
        return [
            'payable'=>$payable,
            'receiveable'=>$receiveable
        ];
    }
    public function payable($purchases){
        $payable=0;
        foreach ($purchases as $purchase) {
            if($purchase->balance_amount  >=0){
                $payable+=$purchase->balance_amount ?? 0;
            }
        }
        return $payable;
    }
    public function receiveable($sales){
        $payable=0;
        foreach ($sales as $sale) {
            if($sale->balance_amount  >=0){
                $payable+=$sale->balance_amount ?? 0;
            }
        }
        return $payable;
    }
}
