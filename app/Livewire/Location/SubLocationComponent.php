<?php

namespace App\Livewire\Location;

use App\Models\settings\businessLocation;
use Livewire\Component;

class SubLocationComponent extends Component
{
    public $level;
    public $location;
    public $mainColor;
    public function mount()
    {
        $this->mainColor = $this->getMainColor();
    }

    private function getMainColor()
    {
        $level=$this->level <= 4 ?$this->level: 1;
        $colors = [ 'warning','dark', 'success', 'info','primary'];
        return $colors[$level - 1] ?? 'success';
    }
    public function render()
    {
        return view('livewire.location.sub-location-component');
    }
}
