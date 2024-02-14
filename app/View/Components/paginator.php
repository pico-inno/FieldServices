<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class paginator extends Component
{
    /**
     * Create a new component instance.
     */
    public Array $aviablePerPages=[];
    public function __construct(Array $aviablePerPages)
    {
        $this->aviablePerPages=$aviablePerPages;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.paginator');
    }
}
