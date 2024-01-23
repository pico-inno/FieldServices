<?php

namespace App\Livewire\Purchase;

use Livewire\Component;
use Livewire\WithPagination;
use App\Datatables\datatable;
use App\Models\Contact\Contact;
use App\Models\purchases\purchases;
use App\Models\settings\businessLocation;

class PurchaseTable extends Component
{
    use WithPagination, datatable;

    public $businesslocationFilterId = 'all';
    public $customerFilterId = 'all';
    public $statusFilter = 'all';
    public $filterDate;
    public $saleType;

    public function __construct()
    {
        $this->queryString = [
            ...$this->queryString,
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
        $saleType = $this->saleType;
        $businesslocationFilterId = $this->businesslocationFilterId;
        $customerFilterId = $this->customerFilterId;
        $statusFilter = $this->statusFilter;
        $filterDate = $this->filterDate;
        $accessUserLocation = getUserAccesssLocation();

        // permissions
        $hasView = hasView('purchase');
        $hasUpdate = hasUpdate('purchase');
        $hasPrint = hasPrint('purchase');
        $hasDelete = hasDelete('purchase');
        $locations = businessLocation::select('name', 'id', 'parent_location_id')->get();
        $customers = Contact::where('type', 'supplier')->orWhere('type', 'Both')->get();

        $accessUserLocation = getUserAccesssLocation();
        // $purchases = purchases::where('is_delete', 0)
        // ->when($accessUserLocation[0] != 0, function ($query) use ($accessUserLocation) {
        //     $query->whereIn('business_location_id', $accessUserLocation);
        // })
        //     ->with('business_location_id', 'businessLocation', 'supplier')
        //     ->OrderBy('id', 'desc');
        // if ($request->has('from_date') && $request->has('to_date')) {
        //     // dd('her');
        //     $purchases = $purchases->whereDate('received_at', '>=', $request->from_date)
        //         ->whereDate('received_at', '<=', $request->to_date);
        // }
        $purchases = purchases::query()
            ->select(
                'purchases.id',
                'purchases.received_at',
                'purchases.contact_id',
                'purchases.status',
                'purchases.purchase_amount',
                'purchases.total_purchase_amount',
                'purchases.paid_amount',
                'purchases.balance_amount',
                'purchases.business_location_id',
                'purchases.purchase_voucher_no',
                'purchases.currency_id',
                'contacts.company_name',
                'contacts.prefix',
                'contacts.first_name',
                'contacts.last_name',
                'contacts.middle_name',
                'contacts.company_name',
                'business_locations.id as business_locations_id',
                'business_locations.name as location_name',
                'business_locations.invoice_layout'
            )
            ->leftJoin('contacts', 'purchases.contact_id', '=', 'contacts.id')
            ->leftJoin('business_locations', 'purchases.business_location_id', '=', 'business_locations.id')
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->when(rtrim($search), function ($query) use ($search) {
                $query->where("purchases.purchase_voucher_no", 'like', '%' . $search . '%')
                    ->orWhere(function ($subQuery) use ($search) {
                        $subQuery->where("contacts.company_name", 'like', '%' . $search . '%')
                            ->orWhere("contacts.first_name", 'like', '%' . $search . '%')
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
                $query->whereDate('purchases.received_at', '>=', $filterDate[0])
                    ->whereDate('purchases.received_at', '<=', $filterDate[1]);
            })

            ->when($statusFilter != 'all', function ($query) use ($statusFilter) {
                $query->where('purchases.status', '=', $statusFilter);
            })
            ->where('purchases.is_delete', 0)
            ->when($accessUserLocation[0] != 0, function ($query) use ($accessUserLocation) {
                $query->whereIn('business_location_id', $accessUserLocation);
            })
            ->with('currency:id,symbol')
            ->paginate($this->perPage);
        return view('livewire.purchase.purchase-table', compact('purchases', 'locations', 'customers', 'hasView', 'hasUpdate', 'hasPrint', 'hasDelete'));
    }
}
