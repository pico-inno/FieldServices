<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\sale\sales;
use App\Models\BusinessUser;
use Modules\FieldService\Entities\FsCampaign;
use Modules\FieldService\Entities\attendanceRecords;

class CampaignInfo extends Component
{
    public $id;
    public function render()
    {
        $id=$this->id;
        $attendanceCount=attendanceRecords::where('campaign_id',$id)->count();
        $campaign= FsCampaign::where('id',$id)->with('leader','location')->first();
        $campaignMemberId = json_decode($campaign->campaign_member);
        $campaignUsernames = '';
        $campaignUsers=[];
        if ($campaignMemberId) {
            $campaignUsers = BusinessUser::whereIn('id', $campaignMemberId)->get();
            if ($campaignUsers) {
                foreach ($campaignUsers as $key => $e) {
                    $seperator = $key == 0 ? '' : ' , ';
                    $campaignUsernames .= $seperator . $e->username;
                }
            }
        }
        $totalExpense=sales::where('channel_id',$id)->where('channel_type','campaign')->sum('total_sale_amount');
        return view('livewire.campaign-info',[
            'attendanceCount'=>$attendanceCount,
            'campaign'=>$campaign,
            'campaignUsernames'=>$campaignUsernames,
            'totalExpense'=>$totalExpense,
        ]);
    }
}
