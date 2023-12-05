<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Contact\ContactUtility;
use App\Models\Contact\Contact;
use App\Models\sale\sales;
use App\Models\paymentsTransactions;
use App\Models\settings\businessSettings;
use Yajra\DataTables\Facades\DataTables;
use DateTime;
use Illuminate\Support\Facades\Route;
use Razorpay\Api\Customer;

use function PHPUnit\Framework\isEmpty;

class CustomerController extends Controller
{
    use ContactUtility;

    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:customer')->only(['index', 'show']);
        $this->middleware('canCreate:customer')->only(['create', 'store']);
        $this->middleware('canUpdate:customer')->only(['edit', 'update']);
        $this->middleware('canDelete:customer')->only('destroy');
    }

    public function index(){
        if(request()->ajax()){
            $customers = Contact::whereIn('type', ['Customer', 'Both'])->get();

            return DataTables::of($customers)
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
                    $html = '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary fw-semibold fs-7 " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                            <span class="svg-icon fs-3 rotate-180 ms-3 me-0">
                                <i class="fas fa-angle-down"></i>
                            </span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">';
                            $html .= ' <li><a href="'.route('customers.show', $row->id).'" class="dropdown-item p-2"><i class="fa-solid fa-eye me-3"></i> View</a></li>';
                        if (hasUpdate('customer')){
                            $html .= ' <li><a href="'.route('customers.edit', $row->id).'" class="dropdown-item p-2"><i class="fas fa-pen-to-square me-3"></i> Edit</a></li>';
                        }

                        if (hasDelete('customer')){
                            $html .= '<li>
                                <form id="delete-form-' . $row->id . '" action="contacts/customers/' . $row->id . '" method="POST">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                </form>
                                <button type="button" class="delete-btn dropdown-item p-2 cursor-pointer" data-id="' . $row->id . '"><i class="fas fa-trash me-3"></i> Delete</button>
                            </li>';
                        }

                    $html .= '</ul> </div>';


                    return (hasView('customer') || hasUpdate('customer') || hasDelete('customer') ? $html : 'No Access');
                }

            )
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('App.contact_management.customers.index');
    }

    public function show($id) {
        $contact= Contact::find($id);
        $user = Auth::user();
        // dd($user_id);
        $business = businessSettings::where('id', $user->business_id)->first();
        // dd($business);
        $data = $this->getSalesAndPurchases($id);

        return view('App.contact_management.customers.show')->with(compact('contact', 'data', 'business'));
    }

    public function create(){
        return view('App.contact_management.customers.create');
    }

    public function store(Request $request){
        try{
            // dd($request->all());
            $customer_data =  $request->only([
                'type', 'price_list_id', 'company_name', 'prefix', 'first_name', 'middle_name', 'last_name',
                'email', 'is_active', 'tax_number', 'city', 'state', 'country', 'address_line_1',
                'address_line_2', 'zip_code', 'mobile', 'landline', 'alternate_number', 'pay_term_value',
                'pay_term_type', 'receivable_amount', 'payable_amount', 'credit_limit', 'is_default',
                'shipping_address', 'customer_group_id', 'custom_field_1', 'custom_field_2', 'custom_field_3',
                'custom_field_4', 'custom_field_5', 'custom_field_6', 'custom_field_7', 'custom_field_8',
                'custom_field_9', 'custom_field_10', 'age', 'gender'
            ]);
            // $contactId=$this->contactId();
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

            $customer_data['contact_id'] =  $contactId;

            $dob_value = $request->input('dob');
            $date = DateTime::createFromFormat('d/m/Y', $dob_value);
            $customer_data['dob'] = !empty($dob_value) ? $date->format('Y-m-d') : null;

            $customer_data['business_id'] = 1;
            $customer_data['created_by'] = Auth::user()->id;

            // dd($customer_data);
            Contact::create($customer_data);
            return redirect('/contacts/customers')->with('success','Contact Created Successfully');
        } catch(\Exception $e){
            return redirect()->back()->with('error', 'An error occurred while creating the contact');
        }
    }

    public function contactId($extra=0) {
        $latestContactId = Contact::latest()->first()->id ?? 1;
        $contactId = contactNo($latestContactId+1 + $extra);
        if (!Contact::where('contact_id', $contactId)->exists()) {
            return $this->contactId(1);
        } else {
            return $contactId;
        }
    }
    public function quickCreateCustomer() {
        return view('App.contact_management.customers.quickAddContact');
    }

    public function quickStoreCustomer(Request $request) {
        try{
            // dd($request->all());
            $customer_data =  $request->only([
                'type', 'price_list_id', 'company_name', 'prefix', 'first_name', 'middle_name', 'last_name',
                'email', 'is_active', 'tax_number', 'city', 'state', 'country', 'address_line_1',
                'address_line_2', 'zip_code', 'mobile', 'landline', 'alternate_number', 'pay_term_value',
                'pay_term_type', 'credit_limit', 'is_default',
                'shipping_address', 'customer_group_id', 'custom_field_1', 'custom_field_2', 'custom_field_3',
                'custom_field_4', 'custom_field_5', 'custom_field_6', 'custom_field_7', 'custom_field_8',
                'custom_field_9', 'custom_field_10','age','gender'
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

            $customer_data['contact_id'] =  $contactId;

            $dob_value = $request->input('dob');
            $date = DateTime::createFromFormat('d/m/Y', $dob_value);
            $customer_data['dob'] = !empty($dob_value) ? $date->format('Y-m-d') : null;

            $customer_data['business_id'] = 1;
            $customer_data['created_by'] = Auth::user()->id;

            // dd($customer_data);
            Contact::create($customer_data);

            $createdContact = Contact::latest()->first();

            $newContactId = $createdContact->id;

            $newContactName = '';
            $newCompanyName = '';

            if (!empty($createdContact->prefix)) {
                $newContactName .= $createdContact->prefix . ' ';
            }

            if (!empty($createdContact->first_name)) {
                $newContactName .= $createdContact->first_name . ' ';
            }

            if (!empty($createdContact->middle_name)) {
                $newContactName .= $createdContact->middle_name . ' ';
            }

            if (!empty($createdContact->last_name)) {
                $newContactName .= $createdContact->last_name;
            }

            if(!empty($createdContact->company_name)) {
                $newCompanyName .= $createdContact->company_name;
            }

            $reservationId = $request->input('reservation_id');
            // dd($reservationId);

            $formType = $request->input('form_type');

            if ($formType === 'create') {
                return redirect('/reservation/create')->with([
                    'success' => 'Contact Created Successfully',
                    'newContactId' => $newContactId,
                    'newContactName' => $newContactName,
                    'newCompanyName' => $newCompanyName
                ]);
            } elseif ($formType === 'edit') {
                return redirect()->route('reservation.edit', $reservationId)->with([
                    'success' => 'Contact Created Successfully',
                    'newContactId' => $newContactId,
                    'newContactName' => $newContactName,
                    'newCompanyName' => $newCompanyName,
                    'reservationId' => $reservationId,
                ]);

            } elseif ($formType === 'from_pos') {
                return response()->json([
                    'success' => true,
                    'msg' => 'Contact Created Successfully',
                    'new_contact_id' => $newContactId,
                    'new_contact_name' => $newContactName]);
            } else {
                return redirect()->back()->with('error', 'Invalid form type');
            }

        } catch(\Exception $e){
            return redirect()->back()->with('error', 'An error occurred while creating the contact');
        }
    }

    public function edit($id){
        $customer = Contact::find($id);
        return view('App.contact_management.customers.edit')->with(compact('customer'));
    }

    public function update(Request $request, $id){
        try{
            $customer = Contact::find($id);

            $customer->type = $request['type'];
            $customer->price_list_id = $request['price_list_id'];
            $customer->company_name = $request['company_name'];
            $customer->prefix = $request['prefix'];
            $customer->first_name = $request['first_name'];
            $customer->middle_name = $request['middle_name'];
            $customer->last_name = $request['last_name'];
            $customer->age = $request['age'];
            $customer->gender = $request['gender'];
            $customer->email = $request['email'];
            $customer->contact_id = $request['contact_id'];
            $customer->is_active = $request['is_active'];
            $customer->tax_number = $request['tax_number'];
            $customer->city = $request['city'];
            $customer->state = $request['state'];
            $customer->country = $request['country'];
            $customer->address_line_1 = $request['address_line_1'];
            $customer->address_line_2 = $request['address_line_2'];
            $customer->zip_code = $request['zip_code'];
            $customer->mobile = $request['mobile'];
            $customer->landline = $request['landline'];
            $customer->alternate_number = $request['alternate_number'];
            $customer->pay_term_value = $request['pay_term_value'];
            $customer->pay_term_type = $request['pay_term_type'];
            $customer->receivable_amount = $request['receivable_amount'];
            $customer->payable_amount = $request['payable_amount'];
            $customer->credit_limit = $request['credit_limit'];
            $customer->shipping_address = $request['shipping_address'];
            $customer->customer_group_id = $request['customer_group_id'];
            $customer->custom_field_1 = $request['custom_field_1'];
            $customer->custom_field_2 = $request['custom_field_2'];
            $customer->custom_field_3 = $request['custom_field_3'];
            $customer->custom_field_4 = $request['custom_field_4'];
            $customer->custom_field_5 = $request['custom_field_5'];
            $customer->custom_field_6 = $request['custom_field_6'];
            $customer->custom_field_7 = $request['custom_field_7'];
            $customer->custom_field_8 = $request['custom_field_8'];
            $customer->custom_field_9 = $request['custom_field_9'];
            $customer->custom_field_10 = $request['custom_field_10'];


            $dob = $request['dob'];
            $date = DateTime::createFromFormat('d/m/Y', $dob);
            $customer->dob = !empty($dob) ? $date->format('Y-m-d') : null;
            $customer->business_id = 1;
            $customer->updated_by = Auth::user()->id;

            $customer->update();

            if($request->input('form_type') === "from_pos"){
                return response()->json([
                    'success' => true,
                    'msg' => 'Contact Updated Successfully',
                    'id' => $id,
                ]);
            }
            if(isEmpty($request->input('form_type'))){
                return redirect('/contacts/customers')->with('success','Contact Updated Successfully');
            }
        } catch(\Exception $e){
            return redirect()->back()->with('error', 'An error occurred while updating the contact');
        }
    }

    public function destroy($id)
    {
        $customer = Contact::find($id);

        if($customer){
            $customer->is_delete = true;
            $customer->save();
            $customer->delete();

            return back()->with('success','Contact Deleted Successfully');
        } else{
            return back()->with('error','Contact not found');
        }

    }

    public function getCusForSelect(Request $request){
        $q = $request->q;
        $customers = Contact::where(function ($query) use ($q) {
            if($q!=''){
                $query->where('first_name', 'like', '%' . $q . '%')
                ->orWhere('middle_name', 'like', '%' . $q . '%')
                ->orWhere('last_name', 'like', '%' . $q . '%')
                ->orWhere('mobile', 'like', '%' . $q . '%')
                ->orWhere('contact_id', 'like', '%' . $q . '%');
            }{
                return $query;
            }
        })
        ->whereIn('type', ['Customer', 'Both'])
        ->paginate(20);
        return response()->json($customers, 200);
    }
}
