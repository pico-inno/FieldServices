<?php

namespace App\View\Components\invoice;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SellLayout extends Component
{
    public $layout;
    public $tabletext;
    public $sale;
    public $location;
    public function __construct($layout,$tabletext,$sale,$location)
    {
        $this->layout = $layout;
        $this->tabletext = $tabletext;
        $this->sale = $sale;
        $this->location = $location;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.invoice.sell-layout', [
            'layout' => $this->layout,
            'table_text' => $this->tabletext,
            'sale' => $this->sale,
            'location' => $this->location
        ]);
    }
}
