<?php

namespace App\Livewire\Payment;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\paymentAccounts;
use Livewire\Attributes\Validate;
use App\Actions\paymentMethods\paymentMethodActions;

class PaymentMethodCreateAction extends Component
{

    use WithFileUploads;
    public $name='';
    public $paymentAccountId='';
    public $note='';
    public $paymentAccounts;
    public $logo=null;
    protected $rules=[
        'name'=>'required',
        'paymentAccountId'=>'required|int',
        'note'=>'max:255',
        'logo' => 'nullable|image|max:1024'
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


            $imageData = file_get_contents($this->logo->getRealPath());

            $pma->create([
                'name'=>$this->name,
                'payment_account_id'=>$this->paymentAccountId,
                'note'=>$this->note,
                'logo'=>$imageData
            ]);
            $this->clearDate();
            $this->dispatch('pm-created-success');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            $this->dispatch('pm-created-fail',message:'Something Wrong');
        }
    }
    public function clearDate() :void{
        $this->paymentAccountId='';
        $this->note='';
        $this->name='';
        $this->logo=null;
    }
}
