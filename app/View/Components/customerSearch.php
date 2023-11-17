<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class customerSearch extends Component
{
    /**
     * Create a new component instance.
     */
    public $placeholder = '';
    public $name='';
    public $className='';
    public $id = '';
    public function __construct($placeholder='', $name='', $className="",$id="")
    {
        $this->placeholder= $placeholder;
        $this->className= $className;
        $this->name=$name;
        $this->id=$id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        return view('components.customer-search');
    }
}
