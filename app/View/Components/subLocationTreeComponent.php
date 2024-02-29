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
        $this->locations=businessLocation::select('id','name')
                            ->where('parent_location_id',$this->locationId)->get()->toArray();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $locations=$this->locations;
        $mainColor=$this->color[$this->level] ?? 'success';
         $level=$this->level;
        return view('components.subLocationTreeComponent',compact('locations','mainColor','level'));
    }
}
