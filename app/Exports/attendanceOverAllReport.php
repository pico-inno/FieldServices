<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\FieldService\Entities\attendanceRecords;

class attendanceOverAllReport implements FromView, ShouldAutoSize
{

    public $filterData;
    public $withFilter;
    public function __construct($filterData, $withFilter)
    {
        $this->filterData = $filterData;
        $this->withFilter = $withFilter;
    }
    public function query()
    {
        $sortField = $this->filterData['sortField'] ?? 'attendance_records.checkin_datetime';
        $sortAsc = $this->filterData['sortAsc'] ?? 'desc';
        return    attendanceRecords::select(
            'attendance_records.*',
            'business_users.username as employee',
            'fscampaign.name as campaign',
            'pf.first_name as fn',
            'pf.last_name as ln',
            'pf.initname'
        )
        ->leftJoin('business_users', 'business_users.id', '=', 'attendance_records.employee_id')
        ->leftJoin('personal_infos  as pf', 'business_users.personal_info_id', '=', 'pf.id')
        ->leftJoin('fscampaign', 'fscampaign.id', '=', 'attendance_records.campaign_id')
        ->orderBy($sortField, $sortAsc ? 'asc' : 'desc');
    }
    public function view(): View
    {
        $campaignId = $this->filterData['campaignId'];
        $filterDate = $this->filterData['filterDate'];
        $campaignFilterId = $this->filterData['campaignFilterId'];
        $withFilter=$this->withFilter;

        $datas = $this->query()
            ->when($withFilter,function($q) use ($campaignId,$filterDate,$campaignFilterId){
                $q->when(isset($filterDate), function ($query) use ($filterDate) {
                    $query->whereDate('attendance_records.checkin_datetime', '>=', $filterDate[0])
                        ->whereDate('attendance_records.checkin_datetime', '<=', $filterDate[1]);
                })
                ->when($campaignId, function ($query) use ($campaignId) {
                    $query->where('fscampaign.id', $campaignId);
                })
                ->when($campaignFilterId != 'all', function ($query) use ($campaignFilterId) {
                    $query->where('fscampaign.id', '=', $campaignFilterId);
                });
            })
            ->orderBy('attendance_records.id', 'DESC')
            ->get();
        return view('App.fieldService.Export.attendanceOverAll', compact('datas'));
    }
}
