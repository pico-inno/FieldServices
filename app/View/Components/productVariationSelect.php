<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class productVariationSelect extends Component
{
    /**
     * Create a new component instance.
     */
    public $placeholder = '';
    public $name='';
    public $className='';
    public $parentModalId='';
    public $id = '';
    public $parentModalId='';
    public function __construct($placeholder='', $name='', $className="",$id="",$parentModalId="")
    {
        $this->parentModalId=$parentModalId;
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

        return view('components.productvariationselect');
    }
}
