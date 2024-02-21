<?php

namespace App\Livewire\FieldService;

use Livewire\Component;
use Livewire\WithPagination;
use App\Datatables\datatable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\attendanceOverAllReport;
use App\Models\settings\businessLocation;
use Modules\FieldService\Entities\FsCampaign;
use Modules\FieldService\Entities\attendanceRecords;

class AttendanceList extends Component
{
    use WithPagination,datatable;
    public $campaign_id=null;
    public $attendanceFilterDate;
    public $campaignFilterId='all';
    public $campaigns;
    public $dataLoading=false;
    public $locations;
    public $outletFilterId='all';
    public $outletTypeFilter='all';
    public function __construct()
    {
        $this->queryString= [...$this->queryString,
            'campaign_id', 'campaignFilterId'
        ];
    }
    public function updated()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->campaigns = FsCampaign::select('id', 'name')->get();
        $this->locations = businessLocation::select('name', 'id', 'parent_location_id')->get();
    }

    public function export($withFilter = true)
    {
        return Excel::download(new attendanceOverAllReport([
            'filterDate' => $this->attendanceFilterDate,
            'campaignFilterId' => $this->campaignFilterId,
            'sordField' => $this->sortField,
            'sortAsc' => $this->sortAsc,
            'campaignId' => $this->campaign_id
        ], $withFilter), 'attendanceOverAllReport.xlsx');
    }
    public function render()
    {
        $search=$this->search;
        $attendanceFilterDate=$this->attendanceFilterDate;
        $campaignId = $this->campaign_id;
        $campaignFilterId=$this->campaignFilterId;
        $outletFilterId=$this->outletFilterId;
        $outletTypeFilter=$this->outletTypeFilter;
        $attendanceRecords = attendanceRecords::select('attendance_records.*', 'business_users.username as employee', 'fscampaign.name as campaign',
            'pf.first_name as fn',
            'pf.last_name as ln',
            'pf.initname')
        ->leftJoin('business_users', 'business_users.id', '=', 'attendance_records.employee_id')
        ->leftJoin('personal_infos  as pf', 'business_users.personal_info_id', '=', 'pf.id')
        ->leftJoin('fscampaign', 'fscampaign.id', '=', 'attendance_records.campaign_id')
        ->leftJoin('business_locations  as outlet', 'fscampaign.business_location_id', '=', 'outlet.id')
        ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
        ->when(rtrim($search), function ($query) use ($search) {
            $query->where(function($query) use($search){
                $query->orWhereRaw("CONCAT(pf.first_name,' ', pf.last_name ) LIKE ?", ['%' . $search . '%'])
                ->orWhereRaw("CONCAT(pf.last_name,' ', pf.first_name) LIKE ?", ['%'. $search . '%']);
            });
        })
        ->when(isset($attendanceFilterDate), function ($query) use ($attendanceFilterDate) {
            $query->whereDate('attendance_records.checkin_datetime', '>=', $attendanceFilterDate[0])
                ->whereDate('attendance_records.checkin_datetime', '<=', $attendanceFilterDate[1]);
        })
        ->when($campaignId, function ($query) use ($campaignId) {
            $query->where('fscampaign.id', $campaignId);
        })

        ->when($campaignFilterId != 'all', function ($query) use ($campaignFilterId) {
            $query->where('fscampaign.id', '=', $campaignFilterId);
        })

        ->when($outletFilterId != 'all', function ($query) use ($outletFilterId) {
            $lids=childLocationIDs($outletFilterId);
            $query->whereIn('outlet.id', $lids);
        })
        ->when($outletTypeFilter != 'all', function ($query) use ($outletTypeFilter) {
            $query->where('outlet.outlet_type', '=', $outletTypeFilter);
        })
        ->orderBy('attendance_records.id', 'DESC');
        $attendanceRecords =  $attendanceRecords->paginate(15);
        return view('livewire.fieldservice.attendance-list', compact('attendanceRecords'));
    }
}
