<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use App\Models\settings\businessLocation;

class subLocationTreeComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public $locationId;
    public $level;
    public $locations;
    public $color=[
        'primary',
        'dark',
        'success',
        'info',
        'warning'
    ];
    public function __construct($location,$level=1)
    {
        $this->locationId=$location['id'];
        $this->level=$level<4 ? $level :1;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $locations=businessLocation::select('id','name')
                            ->where('parent_location_id',$this->locationId)->get();
        $this->locations=$locations;
        $mainColor=$this->color[$this->level] ?? 'success';
         $level=$this->level;
        return view('components.sub-location-tree-component',compact('locations','mainColor','level'));
    }
}
