<?php

namespace App\Livewire\Fieldservice;

use Livewire\Component;

class ImageGralleryComponent extends Component
{
    public $campaign_id;
    public function render()
    {
        return view('livewire.fieldservice.image-grallery-component');
    }
}
