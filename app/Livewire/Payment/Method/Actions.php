<?php

namespace App\Livewire\Payment\Method;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\paymentMethods;
use App\Models\paymentAccounts;
use App\Actions\paymentMethods\paymentMethodActions;

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
        return view('livewire.payment.method.Actions');
    }

    public function confirmDelete($itemId)
    {
        // Show the SweetAlert confirmation modal
        $this->dispatch('swal-confirm', [
            'type' => 'warning',
            'title' => 'Are you sure?',
            'text' => 'You won\'t be able to revert this!',
            'itemId' => $itemId, // Pass any data you need to handle the deletion
        ]);
    }

    #[On('delete')]
    public function delete($id){
        try {
            if(paymentMethods::where('id',$id)->exists()){
                paymentMethods::where('id',$id)->first()->delete();
                $this->dispatch('pm-updated-success');
            }
        } catch (\Throwable $th) {

            $this->dispatch('pm-updated-fail',message:'Something Wrong');
        }
    }
}
