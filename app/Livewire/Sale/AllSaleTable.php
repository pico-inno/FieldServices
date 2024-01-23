<?php

namespace App\Livewire\Sale;

use Livewire\Component;
use App\Models\sale\sales;
use Livewire\WithPagination;
use App\Datatables\datatable;
use App\Models\Contact\Contact;
use App\Models\settings\businessLocation;

class AllSaleTable extends Component
{
    use WithPagination, datatable;

    public $businesslocationFilterId = 'all';
    public $customerFilterId = 'all';
    public $statusFilter='all';
    public $filterDate;
    public $saleType;

    public function __construct()
    {
        $this->queryString= [...$this->queryString,
            'businesslocationFilterId', 'customerFilterId'
        ];
    }

    public function updated()
    {
        $this->resetPage();
    }
    public function render()
    {
        $search = $this->search;
        $saleType= $this->saleType;
        $businesslocationFilterId = $this->businesslocationFilterId;
        $customerFilterId=$this->customerFilterId;
        $statusFilter=$this->statusFilter;
        $filterDate=$this->filterDate;
        $accessUserLocation = getUserAccesssLocation();

        // permissions
        $hasView=hasView('sell');
        $hasUpdate = hasUpdate('sell');
        $hasPrint = hasPrint('sell');
        $hasDelete = hasDelete('sell');
        $hasHospital= hasModule('HospitalManagement') && isEnableModule('HospitalManagement');
        $locations= businessLocation::select('name', 'id', 'parent_location_id')->get();
        $customers = Contact::where('type', 'Customer')->orWhere('type', 'Both')->get();
        $saleData= sales::query()
                    ->select(
                        'sales.id',
                        'sales.sold_at',
                        'sales.contact_id',
                        'sales.status',
                        'sales.table_id',
                        'sales.sale_amount',
                        'sales.total_sale_amount',
                        'sales.paid_amount',
                        'sales.balance_amount',
                        'sales.business_location_id',
                        'sales.sales_voucher_no',
                        'sales.currency_id',
                        'contacts.company_name',
                        'contacts.first_name',
                        'contacts.last_name',
                        'contacts.middle_name',
                        'contacts.company_name',
                        'business_locations.id as business_locations_id',
                        'business_locations.name as location_name',
                        'business_locations.invoice_layout'
                    )
                    ->leftJoin('contacts', 'sales.contact_id', '=', 'contacts.id')
                    ->leftJoin('business_locations', 'sales.business_location_id', '=', 'business_locations.id')
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->when(rtrim($search), function ($query) use ($search) {
                        $query->where("sales.sales_voucher_no", 'like', '%' . $search . '%')
                            ->orWhere(function ($subQuery) use ($search) {
                                $subQuery->where("contacts.first_name", 'like', '%' . $search . '%')
                                    ->orWhere("contacts.last_name", 'like', '%' . $search . '%')
                                    ->orWhere("contacts.middle_name", 'like', '%' . $search . '%')
                                    ->orWhere("contacts.company_name", 'like', '%' . $search . '%');
                            })
                            ->orWhere(function ($subQuery) use ($search) {
                                $subQuery->where("business_locations.name", 'like', '%' . $search . '%');
                            });
                    })
                    ->when($businesslocationFilterId != 'all', function ($query) use ($businesslocationFilterId) {
                        $query->where('business_locations.id', '=', $businesslocationFilterId);
                    })
                    ->when($customerFilterId != 'all', function ($query) use ($customerFilterId) {
                        $query->where('contacts.id', '=', $customerFilterId);
                    })
                    ->when(isset($filterDate), function ($query) use ($filterDate) {
                        $query->whereDate('sales.sold_at', '>=', $filterDate[0])
                                ->whereDate('sales.sold_at', '<=', $filterDate[1]);
                    })

                    ->when($statusFilter != 'all', function ($query) use ($statusFilter) {
                        $query->where('sales.status', '=', $statusFilter);
                    })
                    ->where('sales.is_delete', 0)
                    ->when($accessUserLocation[0] != 0, function ($query) use ($accessUserLocation) {
                        $query->whereIn('business_location_id', $accessUserLocation);
                    })
                    ->when($saleType == 'sales', function ($query){
                            $query->whereNull('pos_register_id');
                    })
                    ->when($saleType == 'posSales', function ($query) {
                            $query->whereNotNull('pos_register_id');
                    })
                    ->with('currency:id,symbol')
                    ->paginate($this->perPage);
        return view('livewire.sale.all-sale-table',compact('saleData','locations', 'hasHospital', 'customers', 'hasView', 'hasUpdate', 'hasPrint','hasDelete'));
    }
}
