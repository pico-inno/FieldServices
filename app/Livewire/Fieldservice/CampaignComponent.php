<?php

namespace App\Livewire\FieldService;

use App\Datatables\datatable;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Modules\FieldService\Entities\FsCampaign;

class CampaignComponent extends Component
{
    use WithPagination,datatable;
    public $search;

    public function updated()
    {
        $this->resetPage();
    }
    public $hasUpdate=true;
    public $hasDelete = true;
    public $hasCreate=true;
    public $hasCampaignReport=true;
    public function mount(){
        $this->hasUpdate = hasUpdate('campaign');
        $this->hasDelete = hasDelete('campaign');
        $this->hasCreate = hasCreate('campaign');
        $this->hasCampaignReport = hasView('campaign report');
    }
    public function render()
    {
        $search=$this->search;
        $checkIsAdmin=$this->hasUpdate && $this->hasDelete && $this->hasCreate;
        $hasCampaignReport=$this->hasCampaignReport;
        $AllCampaigns = FsCampaign::query()
            ->select('fscampaign.*', 'business_locations.name as bln')
            ->orderBy('fscampaign.id', 'DESC')
            ->leftJoin('business_locations', 'fscampaign.business_location_id','=', 'business_locations.id')
            ->where('fscampaign.name', 'like', '%' . $search . '%')
            ->orWhere('business_locations.name', 'like', '%' . $search . '%')
            ->when(!$checkIsAdmin,function($query)  {
                $query->whereJsonContains('campaign_member',[ Auth::user()->id,'all'])
                ->orWhere('campaign_leader', Auth::user()->id);
            })
            ->paginate($this->perPage);
        return view('livewire.fieldservice.campaign-component',compact('AllCampaigns','hasCampaignReport'));
    }
}
