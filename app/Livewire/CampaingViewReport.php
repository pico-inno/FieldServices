<?php

namespace App\Livewire;

use Livewire\Component;

class CampaingViewReport extends Component
{
    public $campaign_id;
    public $currentTab='report';
    public function changeTab($tab){
        $this->currentTab=$tab;
    }
    public function render()
    {
        return view('livewire.campaing-view-report');
    }
}
