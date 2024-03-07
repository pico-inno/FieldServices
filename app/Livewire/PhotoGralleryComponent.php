<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BusinessUser;
use Livewire\WithPagination;
use App\Models\settings\businessLocation;
use Modules\FieldService\Entities\FsCampaign;
use Modules\FieldService\Entities\ImageGallery;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

class PhotoGralleryComponent extends Component
{
    use WithPagination;
    public $search='';

    public $campaignFilterId='all';
    public $outletFilterId='all';
    public $outletTypeFilter='all';
    public $pgFilterId='all';
    public $employee=[];


    public $outlets;
    public $campaigns;
    public function mount(){
        $this->outlets = businessLocation::select('id', 'name')->get()->toArray();
        $this->campaigns = FsCampaign::select('id', 'name')->get()->toArray();
        $this->employee = BusinessUser::select('username', 'id', 'personal_info_id')
            ->with('personal_info:first_name,last_name,initname,id')->get()->toArray();
    }
    public function render()
    {
        $campaignFilterId = $this->campaignFilterId;
        $outletFilterId=$this->outletFilterId;
        $outletTypeFilter=$this->outletTypeFilter;
        $pgFilterId=$this->pgFilterId;
        $search=rtrim($this->search);
        $gralleries= ImageGallery::with('user.personal_info:id,first_name,last_name,profile_photo','campaign:id,name,business_location_id','campaign.location:name,id')
        ->select('image_gallery.*')
        ->leftJoin('fscampaign', 'image_gallery.campaign_id', 'fscampaign.id')
        ->leftJoin('business_locations  as outlet', 'fscampaign.business_location_id', '=', 'outlet.id')
        ->leftJoin('business_users  as pg', 'image_gallery.created_by', '=', 'pg.id')
        ->orderBy('image_gallery.id','DESC')
        ->when($search !='null' && $search !='',function($q) use($search){
            $q->where('note', 'like', '%' . $search . '%');
        })

        ->when($campaignFilterId != 'all', function ($query) use ($campaignFilterId) {
            $query->where('fscampaign.id','=',$campaignFilterId);
        })

        ->when($outletFilterId != 'all', function ($query) use ($outletFilterId) {
            $query->where('outlet.id','=',$outletFilterId);
        })

        ->when($outletTypeFilter != 'all', function ($query) use ($outletTypeFilter) {
            $query->where('outlet.outlet_type',$outletTypeFilter);
        })

        ->when($pgFilterId != 'all', function ($query) use ($pgFilterId) {
            $query->where('pg.id', '=', $pgFilterId);
        })
        ->paginate(40);

        // dd($gralleries->toArray());
        return view('livewire.photo-grallery-component',compact('gralleries'));
    }
}
