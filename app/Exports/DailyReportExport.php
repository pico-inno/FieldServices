<?php

namespace App\Exports;

use App\Models\BusinessUser;
use App\Models\Product\Product;
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
    public function __construct($campaignId)
    {
        $this->campaignId=$campaignId;
    }
    public function view(): View
    {
        $campaignId=$this->campaignId;
        $campaign= FsCampaign::where('id',$campaignId)->with('leader','location')->first();
        $campaignMemberId = json_decode($campaign->campaign_member);
        $leaderId=$campaign['campaign_leader'];
        $PBIds=[$leaderId,...$campaignMemberId ];
        return view('App.openingStock.export.exportWithData',compact('PBIds','campaignId'));
    }
}
