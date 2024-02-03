<?php

namespace App\Livewire;

use Livewire\Component;
use Modules\Ecommerce\Entities\EcommerceOrder;

class NewEcommerceOrderCount extends Component
{
    public function render()
    {
        return view('livewire.new-ecommerce-order-count', [
            'count' => EcommerceOrder::select('viewed_at')->whereNull('viewed_at')->count(),
        ]);
    }
}
