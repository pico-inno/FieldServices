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
        $this->level = $level < 4 ? $level : 1;
        $this->locations = BusinessLocation::select('id', 'name')
            ->where('parent_location_id', $this->locationId)
            ->get()
            ->toArray();
        $this->mainColor = $this->getMainColor();
    }

    public function render()
    {
        return view('components.sub-location-tree-component');
    }

    private function getMainColor()
    {
        $colors = ['primary', 'dark', 'success', 'info', 'warning'];
        return $colors[$this->level - 1] ?? 'success';
    }
}
