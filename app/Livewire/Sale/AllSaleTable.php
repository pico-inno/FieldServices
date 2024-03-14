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
    public $paymentStatusFilter='all';
    public $filterDate;
    public $saleType;

    public function __construct()
    {
        $this->queryString= [...$this->queryString,
            'businesslocationFilterId', 'customerFilterId','filterDate'
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
        $paymentStatusFilter=$this->paymentStatusFilter;
        $accessUserLocation = getUserAccesssLocation();

        // permissions
        $hasView=hasView('sell');
        $hasUpdate = hasUpdate('sell');
        $hasPrint = hasPrint('sell');
        $hasDelete = hasDelete('sell');
        $hasHospital= hasModule('HospitalManagement') && isEnableModule('HospitalManagement');
        $hasReservation= hasModule('Reservation') && isEnableModule('Reservation');
        $locations= businessLocation::select('name', 'id', 'parent_location_id')->get();
        $customers = Contact::where('type', 'Customer')->orWhere('type', 'Both')->get();
        $pedningCount=0;
        $orderCount=0;
        if($saleType=='ecommerce'){
            $pedningCount=sales::where('status','pending')
                        ->where('channel_type','ecommerce')
                        ->where('is_delete',0)
                        ->count();

            $orderCount=sales::where('status','order')
                        ->where('channel_type','ecommerce')
                        ->where('is_delete',0)
                        ->count();
        }
        $saleData= sales::query()
                    ->select(
                        'sales.id',
                        'sales.sold_at',
                        'sales.contact_id',
                        'sales.channel_type',
                        'sales.status',
                        'sales.table_id',
                        'sales.sale_amount',
                        'sales.total_sale_amount',
                        'sales.payment_status',
                        'sales.paid_amount',
                        'sales.balance_amount',
                        'sales.business_location_id',
                        'sales.sales_voucher_no',
                        'sales.currency_id',
                        'sales.sold_by',
                        'contacts.company_name',
                        'contacts.prefix',
                        'contacts.first_name',
                        'contacts.last_name',
                        'contacts.middle_name',
                        'contacts.company_name',
                        'business_locations.id as business_locations_id',
                        'business_locations.name as location_name',
                        'business_locations.invoice_layout',

                    )
                    ->where('sales.is_delete', 0)
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

                    ->when($paymentStatusFilter != 'all', function ($query) use ($paymentStatusFilter) {
                        $query->where('sales.payment_status', '=', $paymentStatusFilter);
                    })
                    ->when($statusFilter != 'all', function ($query) use ($statusFilter) {
                        $query->where('sales.status', '=', $statusFilter);
                    })
                    ->when($accessUserLocation[0] != 0, function ($query) use ($accessUserLocation) {
                        $query->whereIn('business_location_id', $accessUserLocation);
                    })
                    ->when($saleType == 'sales', function ($query){
                            $query->whereNull('pos_register_id')->where('channel_type','sale');
                    })
                    ->when($saleType == 'ecommerce', function ($query) {
                            $query->where('channel_type','ecommerce')
                                    ->selectRaw('ecommerce_orders.viewed_at as isRead')
                                    ->join('ecommerce_orders','sales.id','=','ecommerce_orders.sale_id');
                    })
                    ->with('currency:id,symbol','soldBy:id,username')
                    ->paginate($this->perPage);
        return view('livewire.sale.all-sale-table',compact('saleData','locations', 'hasHospital', 'customers', 'hasView', 'hasUpdate', 'hasPrint','hasDelete','saleType','pedningCount','orderCount','hasReservation'));
    }
}
