<?php

namespace App\View\Components\setting;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * settingRow
 */
class settingRow extends Component
{
    /**
     * Create a new component instance.
     */
    public $firstLabel;
    public $secLabel;
    public $firstFor;
    public $secFor;
    public function __construct($firstLabel='', $secLabel='', $firstFor='', $secFor='')
    {
        $this->firstLabel= $firstLabel;
        $this->secLabel = $secLabel;
        $this->firstFor= $firstFor;
        $this->secFor= $secFor;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.setting.setting-row');
    }
}
