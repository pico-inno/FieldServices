<?php

namespace App\Livewire\Moudle\Ecommerce;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Configuration extends Component
{
    use WithFileUploads;

    public $imageUrl ;
    public $mainBannerImage;
    public $fileName = 'Upload banner image';


    public function mount()
    {
        $this->loadDefaultBanner();
    }

    public function loadDefaultBanner()
    {
        $defaultUrl = 'https://www.oviond.com/wp-content/uploads/2023/07/E_Commerce_Is_Here_To_Stay_d01ee22aa5_a710eeaf67.png';
        $path = 'ecommerce/banner-image/default.jpg';

        if (Storage::exists($path)) {
            $this->imageUrl = Storage::url($path);
        } else {
            $this->imageUrl = $defaultUrl;
        }
    }

    public function preview()
    {
        $this->validate(['mainBannerImage' => 'image|mimes:jpg,jpeg']);
        $this->imageUrl = $this->mainBannerImage->temporaryUrl();
    }

    public function updateConfiguration()
    {
        $this->validate(['mainBannerImage' => 'image|mimes:jpg,jpeg']);

        $path = 'ecommerce/banner-image/default.jpg';

        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        $filename = 'default.' . $this->mainBannerImage->getClientOriginalExtension();
        $path = $this->mainBannerImage->storeAs('ecommerce/banner-image', $filename);

        $this->imageUrl = $path;

    }


    public function render()
    {
        return view('livewire.module.ecommerce.configuration');
    }


    public function updatedMainBannerImage()
    {
        if ($this->mainBannerImage) {
            $this->fileName = $this->mainBannerImage->getClientOriginalName();
        } else {
            $this->fileName = 'Upload banner image';
        }
    }
}
