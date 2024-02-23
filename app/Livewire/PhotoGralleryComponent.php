<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\FieldService\Entities\ImageGallery;

class PhotoGralleryComponent extends Component
{
    use WithPagination;
    public function mount(){

    }
    public function render()
    {

        $gralleries= ImageGallery::where('campaign_id',9)
        ->with('user.personal_info:id,first_name,last_name,profile_photo')
        ->orderBy('id','DESC')->paginate(40);
        // dd($gralleries->toArray());
        return view('livewire.photo-grallery-component',compact('gralleries'));
    }
}
