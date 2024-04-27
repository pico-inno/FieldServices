<?php

namespace App\Exports;

use App\Models\BusinessUser;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\FieldService\Entities\FsCampaign;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DailyReportExport implements FromView,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $campaignId;
    public $dateFilter;
    public function __construct($campaignId,$dateFilter)
    {
        $this->campaignId=$campaignId;
        $this->dateFilter=$dateFilter;
    }
    public function view(): View
    {

        $campaignId=$this->campaignId;
        $dateFilter=$this->dateFilter;
        $campaign= FsCampaign::where('id',$campaignId)->with('leader','location')->first();
        $PBIds = json_decode($campaign->campaign_member);
        $leaderId=$campaign['campaign_leader'];
        $PBIds=array_unique($PBIds);
        if (!in_array($leaderId, $PBIds)) {
            if($PBIds[0] == 'all'){
               $PBIds= BusinessUser::get()->pluck('id');
            }
            $PBIds = [...$PBIds,$leaderId];
        }

        // dd($PBIds,$campaignId,$fromDate,$toDate);
        return view('App.fieldService.Export.dailyExportReport',compact('PBIds','campaignId','dateFilter'));
    }
}
