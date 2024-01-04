<?php

namespace App\View\Components\invoice;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PurchaseLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public $layout;
    public $tabletext;
    public $purchase;
    public $location;
    public function __construct($layout,$tabletext,$purchase,$location)
    {
        $this->layout = $layout;
        $this->purchase = $purchase;
        $this->tabletext = $tabletext;
        $this->location = $location;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.invoice.purchase-layout', [
            'layout' => $this->layout,
            'table_text' => $this->tabletext,
            'purchase' => $this->purchase,
            'location' => $this->location
        ]);
    }
}
