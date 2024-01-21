<?php

namespace App\View\Components\datatable;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class sortIcon extends Component
{
    /**
     * Create a new component instance.
     */
    public $field;
    public $sortField;
    public $sortAsc;
    public function __construct($field, $sortField, $sortAsc)
    {
        $this->field=$field;
        $this->sortField= $sortField;
        $this->sortAsc= $sortAsc;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datatable.sort-icon');
    }
}
