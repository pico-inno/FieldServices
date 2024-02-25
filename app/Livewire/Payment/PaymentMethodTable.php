<?php

namespace App\Livewire\Payment;

use App\Datatables\datatable;
use App\Models\paymentAccounts;
use App\Models\paymentMethods;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentMethodTable extends Component
{
    use WithPagination,datatable;


    #[On('pm-created-success')]
    #[On('pm-updated-success')]
    public function render()
    {
        $paymentMethods=paymentMethods::query()->with('paymentAccount:id,name')->orderBy('id','DESC')->paginate($this->perPage);
        return view('livewire.payment.payment-method-table',compact('paymentMethods'));
    }
}
