<?php

namespace App\View\Components;

use Closure;
use App\Models\sale\sales;
use App\Models\BusinessUser;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Modules\FieldService\Entities\FsCampaign;
use Modules\FieldService\Entities\attendanceRecords;

class campaingInfo extends Component
{
    /**
     * Create a new component instance.
     */
    public $id;
    public function __construct($id)
    {
        $this->id=$id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
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
        dd('here');
        return view('Components.campaing-info',compact('attendanceCount','campaign','campaignUsernames','totalExpense'));
    }
}
