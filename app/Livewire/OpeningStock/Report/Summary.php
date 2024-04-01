<?php

namespace App\Livewire\OpeningStock\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Datatables\datatable;
use App\Models\openingStocks;
use App\Exports\osSummaryExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\settings\businessLocation;

class Summary extends Component
{
    use WithPagination,datatable;
    public $businesslocationFilterId ='all';
    public $filterDate;
    public function export($withFilter=true){
        $query=$this->query();
        return Excel::download(new osSummaryExport($query), 'openingStockSummary.xlsx');
    }
    public function query(){
        $businesslocationFilterId = $this->businesslocationFilterId;
        $filterDate=$this->filterDate;
        $keyword=rtrim($this->search);
        return $openingStocks=openingStocks::query()
        ->select('opening_stocks.*','business_users.username','business_locations.name as locationName')
        ->where('opening_stocks.is_delete',0)
        ->leftJoin('business_users','business_users.id','opening_stocks.opening_person')
        ->leftJoin('business_locations','business_locations.id','opening_stocks.business_location_id')
        ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
        ->when($keyword,function($query)use($keyword){
            $query->where("opening_stocks.opening_stock_voucher_no", 'like', '%' . $keyword );
        })
        ->when($businesslocationFilterId !='all',function($q) use($businesslocationFilterId){
            $q->where('business_locations.id', '=', $businesslocationFilterId);
        })
        ->when(isset($filterDate), function ($query) use ($filterDate) {
            $query->whereDate('opening_stocks.opening_date', '>=', $filterDate[0])
                    ->whereDate('opening_stocks.opening_date', '<=', $filterDate[1]);
        });
    }
    public function render()
    {


        $locations= businessLocation::select('name', 'id', 'parent_location_id')->get();
        $openingStocks= $this->query()->paginate($this->perPage);
        return view('livewire.OpeningStock.report.summary-table',compact('openingStocks','locations'));
    }
}
