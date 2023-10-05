<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use App\Models\settings\businessLocation;

class locationselect extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $locations = businessLocation::all();
        return view('components.locationselect',compact('locations'));
    }
}
