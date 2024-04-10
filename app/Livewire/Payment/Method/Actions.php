<?php

namespace App\Livewire\Payment\Method;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\paymentMethods;
use App\Models\paymentAccounts;
use App\Actions\paymentMethods\paymentMethodActions;

class Actions extends Component
{
    use WithFileUploads;
    public $name='';
    public $paymentAccountId='';
    public $note='';
    public $paymentAccounts;
    public $paymentMethod;
    public $logo=null;
    public $logoImg=null;
    public $id;
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

    public function updatedLogo($logo)
    {
        // dd($logo);
        if($this->logo->getRealPath()){
            $imageData = file_get_contents($this->logo->getRealPath());
            $this->logoImg = base64_encode($imageData); // Convert to BLOB data
        }
    }
    public function mount(){
        $paymentMethod=paymentMethods::where('id',$this->id)->first();
        $this->name=$paymentMethod['name'];
        $this->paymentAccountId=$paymentMethod['payment_account_id'];
        $this->note=$paymentMethod['note'];
        $this->logo=getImage($paymentMethod['logo']);
        $this->logoImg=getImage($paymentMethod['logo']);
        // dd($this->logo);

        $this->paymentAccounts=paymentAccounts::query()
                                        ->select('id','name')
                                        ->get();


    }
    public function update(paymentMethodActions $pma){

        $this->validate();
        try {
            $imageData =$this->logo? file_get_contents($this->logo->getRealPath()) : null;
            $pma->update($this->id,[
                'name'=>$this->name,
                'payment_account_id'=>$this->paymentAccountId,
                'note'=>$this->note,
                'logo'=>$imageData,
            ]);
            $this->dispatch('pm-updated-success');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            $this->dispatch('pm-updated-fail',message:'Something Wrong');
        }
    }
    public function render()
    {
        return view('livewire.payment.method.Actions');
    }
    public function removeLogo(){
        // dd('here');
       $this->logo=null;
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
