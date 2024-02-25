<?php

namespace App\Livewire\Payment;

use Livewire\Component;
use App\Models\paymentAccounts;
use Livewire\Attributes\Validate;
use App\Actions\paymentMethods\paymentMethodActions;

class PaymentMethodCreateAction extends Component
{

    public $name='';
    public $paymentAccountId='';
    public $note='';
    public $paymentAccounts;
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
        $this->paymentAccounts=paymentAccounts::query()
                                        ->select('id','name')
                                        ->get();

    }
    public function render()
    {
        return view('livewire.payment.payment-method-create-action');
    }
    public function create(paymentMethodActions $pma){

        $this->validate();
        try {
            $pma->create([
                'name'=>$this->name,
                'payment_account_id'=>$this->paymentAccountId,
                'note'=>$this->note,
            ]);
            $this->clearDate();
            $this->dispatch('pm-created-success');
        } catch (\Throwable $th) {
            $this->dispatch('pm-created-fail',message:'Something Wrong');
        }
    }
    public function clearDate() :void{
        $this->paymentAccountId='';
        $this->note='';
        $this->name='';
    }
}
