<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\settings\BusinessLocation;

class SubLocationTreeComponent extends Component
{
    public $locationId;
    public $level;
    public $locations;
    public $mainColor;
    public function __construct($location, $level = 1)
    {
        $this->locationId = $location['id'];
        $this->level = $level <= 4 ? $level : 1;
        $this->locations = BusinessLocation::select('id', 'name')
            ->where('parent_location_id', $this->locationId)
            ->get()
            ->toArray();
        $this->mainColor = $this->getMainColor();
    }

    private function getMainColor()
    {
        $colors = [ 'dark', 'success', 'info', 'warning','primary'];
        return $colors[$this->level - 1] ?? 'success';
    }

    public function render()
    {
        $locations=$this->locations;
        $locations=$this->locations;
        return view('components.sub-location-tree-component',compact('locations'));
    }

}
