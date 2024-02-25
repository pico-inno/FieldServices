<?php

namespace App\Livewire\Payment\Method;

use Livewire\Component;
use App\Models\paymentAccounts;
use App\Actions\paymentMethods\paymentMethodActions;
use App\Models\paymentMethods;

class Actions extends Component
{
    public $name='';
    public $paymentAccountId='';
    public $note='';
    public $paymentAccounts;
    public $paymentMethod;
    public $id;
    protected $rules=[
        'name'=>'required',
        'paymentAccountId'=>'required|int',
        'note'=>'max:255'
    ];
    protected $messages=[
        'paymentAccountId.required'=>'Payment Account Is Required!'
    ];
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function mount(){
        $paymentMethod=paymentMethods::where('id',$this->id)->first();
        $this->name=$paymentMethod['name'];
        $this->paymentAccountId=$paymentMethod['payment_account_id'];
        $this->note=$paymentMethod['note'];


        $this->paymentAccounts=paymentAccounts::query()
                                        ->select('id','name')
                                        ->get();


    }
    public function update(paymentMethodActions $pma){

        $this->validate();
        try {
            $pma->update($this->id,[
                'name'=>$this->name,
                'payment_account_id'=>$this->paymentAccountId,
                'note'=>$this->note,
            ]);
            $this->dispatch('pm-updated-success');
        } catch (\Throwable $th) {
            logger($th->getMessage());
            $this->dispatch('pm-updated-fail',message:'Something Wrong');
        }
    }
    public function render()
    {
        return view('livewire.payment.method.actions');
    }
}
