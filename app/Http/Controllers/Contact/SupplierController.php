<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Contact\ContactUtility;
use App\Models\Contact\Contact;
use App\Models\settings\businessSettings;
use App\Models\paymentsTransactions;
use App\Models\purchases\purchases;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use DateTime;

class SupplierController extends Controller
{
    use ContactUtility;

    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:supplier')->only(['index', 'show']);
        $this->middleware('canCreate:supplier')->only(['create', 'store']);
        $this->middleware('canUpdate:supplier')->only(['edit', 'update']);
        $this->middleware('canDelete:supplier')->only('destroy');
    }

    public function index()
    {
        if (request()->ajax()) {
            $suppliers = Contact::whereIn('type', ['Supplier', 'Both'])->get();

            return DataTables::of($suppliers)
                ->editColumn('first_name', function($row){
                    return $row->getFullNameAttribute();
                })
                ->editColumn('address_line_1', function($row){
                    return $row->getAddressAttribute();
                })
                ->editColumn('pay_term_value', function($row){
                    return $row->getPayTerm();
                })
                ->addColumn(
                    'action',
                    function($row){

                        $html =  '

                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary fw-semibold fs-7 " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                                <span class="svg-icon fs-3 rotate-180 ms-3 me-0">
                                    <i class="fas fa-angle-down"></i>
                                </span>
                            </button>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">';
                            $html .= ' <li><a href="'.route('suppliers.show', $row->id).'" class="dropdown-item p-2"><i class="fa-solid fa-eye me-3"></i> View</a></li>';

                            if (hasUpdate('supplier')){
                                $html .= '<li><a href="'.route('suppliers.edit', $row->id).'" class="dropdown-item p-2"><i class="fas fa-pen-to-square me-3"></i> Edit</a></li>';
                            }

                            if (hasDelete('supplier')){
                                $html .= '<li>
                                        <form id="delete-form-' . $row->id . '" action="contacts/suppliers/' . $row->id . '" method="POST">
                                            ' . csrf_field() . '
                                            ' . method_field('DELETE') . '
                                        </form>
                                        <button type="button" class="delete-btn dropdown-item p-2 cursor-pointer" data-id="' . $row->id . '"><i class="fas fa-trash me-3"></i> Delete</button>
                                    </li>';
                            }

                        $html .= '</ul></div>';

                       return (hasUpdate('supplier') || hasDelete('supplier') ? $html : 'No Access');
                    }

                )
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('App.contact_management.suppliers.index');
    }

    public function show($id)
    {
        $contact = Contact::find($id);
        $user = Auth::user();
        $business = businessSettings::where('id', $user->business_id)->first();

        $data = $this->getSalesAndPurchases($id);

        return view('App.contact_management.customers.show')->with(compact('contact', 'data', 'business'));
    }

    public function create()
    {
        return view('App.contact_management.suppliers.create');
    }

    public function store(Request $request)
    {
        try{
            // dd($request->all());
            $supplier_data = $request->only([
                'type', 'price_list_id', 'company_name', 'prefix', 'first_name', 'middle_name', 'last_name',
                'email', 'is_active', 'tax_number', 'township', 'city', 'state', 'country', 'address_line_1',
                'address_line_2', 'zip_code', 'mobile', 'landline', 'alternate_number', 'pay_term_value',
                'pay_term_type', 'receivable_amount', 'payable_amount', 'credit_limit', 'is_default',
                'shipping_address', 'customer_group_id', 'custom_field_1', 'custom_field_2', 'custom_field_3',
                'custom_field_4', 'custom_field_5', 'custom_field_6', 'custom_field_7', 'custom_field_8',
                'custom_field_9', 'custom_field_10'
            ]);

            $latestContact = Contact::latest()->first();

            // Check if the latest contact_id is in the format of 'C000X'
            if ($latestContact && preg_match('/^C\d{4}$/i', $latestContact->contact_id)) {
                // Extract the numeric part of the latest Contact ID
                $numericPart = (int)substr($latestContact->contact_id, 1);
                $numericPart++;
                // Generate the Contact ID with leading zeros
                $contactId = 'C' . str_pad($numericPart, 4, '0', STR_PAD_LEFT);
            } else {
                // If latest contact_id is not in the format of 'C000X', find the next available Contact ID
                $contactId = 'C0001';
                while (Contact::where('contact_id', $contactId)->exists()) {
                    // Keep incrementing the numeric part until an available Contact ID is found
                    $numericPart = (int)substr($contactId, 1);
                    $numericPart++;
                    $contactId = 'C' . str_pad($numericPart, 4, '0', STR_PAD_LEFT);
                }
            }

            // Check if the contact_id input is filled
            if (request()->filled('contact_id')) {
                $contactId = request()->input('contact_id');
            }

            $supplier_data['contact_id'] =  $contactId;

            $dob_value = $request->input('dob');
            $date = DateTime::createFromFormat('d/m/Y', $dob_value);
            $supplier_data['dob'] = !empty($dob_value) ? $date->format('Y-m-d') : null;

            $supplier_data['business_id'] = 1;
            $supplier_data['created_by'] = Auth::user()->id;

        // dd($supplier_data);
            Contact::create($supplier_data);
            return redirect('/contacts/suppliers')->with('success','Contact Created Successfully');
        } catch(\Exception $e){
            return redirect()->back()->with('error', 'An error occurred while creating the contact');
        }
    }

    public function edit($id)
    {
        $supplier = Contact::find($id);
        // dd($supplier);
        return view('App.contact_management.suppliers.edit')->with(compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        try{
            $supplier = Contact::find($id);

            $supplier->type = $request['type'];
            $supplier->price_list_id = $request['price_list_id'];
            $supplier->company_name = $request['company_name'];
            $supplier->prefix = $request['prefix'];
            $supplier->first_name = $request['first_name'];
            $supplier->middle_name = $request['middle_name'];
            $supplier->last_name = $request['last_name'];
            $supplier->email = $request['email'];
            $supplier->contact_id = $request['contact_id'];
            $supplier->tax_number = $request['tax_number'];
            $supplier->city = $request['city'];
            $supplier->state = $request['state'];
            $supplier->country = $request['country'];
            $supplier->address_line_1 = $request['address_line_1'];
            $supplier->address_line_2 = $request['address_line_2'];
            $supplier->zip_code = $request['zip_code'];
            $supplier->mobile = $request['mobile'];
            $supplier->landline = $request['landline'];
            $supplier->alternate_number = $request['alternate_number'];
            $supplier->pay_term_value = $request['pay_term_value'];
            $supplier->pay_term_type = $request['pay_term_type'];
            $supplier->receivable_amount = $request['receivable_amount'];
            $supplier->payable_amount = $request['payable_amount'];
            $supplier->credit_limit = $request['credit_limit'];
            $supplier->shipping_address = $request['shipping_address'];
            $supplier->customer_group_id = $request['customer_group_id'];
            $supplier->custom_field_1 = $request['custom_field_1'];
            $supplier->custom_field_2 = $request['custom_field_2'];
            $supplier->custom_field_3 = $request['custom_field_3'];
            $supplier->custom_field_4 = $request['custom_field_4'];
            $supplier->custom_field_5 = $request['custom_field_5'];
            $supplier->custom_field_6 = $request['custom_field_6'];
            $supplier->custom_field_7 = $request['custom_field_7'];
            $supplier->custom_field_8 = $request['custom_field_8'];
            $supplier->custom_field_9 = $request['custom_field_9'];
            $supplier->custom_field_10 = $request['custom_field_10'];
            $dob = $request['dob'];
            $date = DateTime::createFromFormat('d/m/Y', $dob);
            $supplier->dob = !empty($dob) ? $date->format('Y-m-d') : null;
            $supplier->business_id = 1;
            $supplier->updated_by = Auth::user()->id;

            $supplier->update();

            return redirect('/contacts/suppliers')->with('success','Contact Updated Successfully');
        } catch(\Exception $e){
            return redirect()->back()->with('error', 'An error occurred while updating the contact');
        }
    }

    public function destroy($id)
    {
        $supplier = Contact::find($id);

        if($supplier){
            $supplier->is_delete = true;
            $supplier->save();
            $supplier->delete();

            return back()->with('success','Contact Deleted Successfully');
        } else{
            return back()->with('error','Contact not found');
        }

    }
}
