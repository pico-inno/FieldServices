<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class nobSelect extends Component
{
    public $class = '';
    public $id = '';
    public $name = '';
    public $value = '';
    public $placeholder = '';
    public $attr = '';
    public function __construct($class = '', $name = '', $value = '', $id = '', $placeholder = '', $attr = '')
    {
        $this->class = $class;
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->attr = $attr;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.nob-select');
    }
}
