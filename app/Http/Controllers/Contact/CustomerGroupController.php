<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact\CustomerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CustomerGroupController extends Controller
{
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
            $customer_groups = CustomerGroup::all();

            return DataTables::of($customer_groups)
                ->addColumn('action', function($row){
                    $html =  '
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary fw-semibold fs-7 " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                                <span class="svg-icon fs-3 rotate-180 ms-3 me-0">
                                    <i class="fas fa-angle-down"></i>
                                </span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">';

                    if (hasUpdate('customer')){
                        $html .= '<li>
                                    <button type="button" class="edit-btn dropdown-item p-2" data-href="'.route('customer-group.edit', $row->id).'" >
                                        <i class="fas fa-pen-to-square me-3"></i> Edit
                                    </button>
                                </li>';
                    }

                    if (hasDelete('customer')){
                        $html .= '<li>
                                    <form id="delete-form-' . $row->id . '" action="customer-group/' . $row->id . '" method="POST">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
                                    </form>
                                    <button type="button" class="delete-btn dropdown-item p-2 cursor-pointer" data-id="' . $row->id . '"><i class="fas fa-trash me-3"></i> Delete</button>
                                </li>';
                    }

                    $html .= '</ul></div>';

                    return (hasUpdate('customer') && hasDelete('customer') ? $html : 'No Access');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('App.contact_management.customer_group.index');
    }

    public function show(){

    }

    public function create(){
        return view('App.contact_management.customer_group.create');
    }

    public function store(Request $request){
        try{
            $customer_group = $request->only(['name', 'amount', 'price_calculation_type', 'selling_price_group_id']);
            $customer_group['business_id'] = 1;
            $customer_group['created_by'] = Auth::user()->id;
            // dd($customer_group);
            CustomerGroup::create($customer_group);

            return redirect('/customer-group')->with('success','Customer Group Created Successfully');
        } catch(\Exception $e){
            return redirect()->back()->with('error', 'An error occurred while creating the customer group');
        }
    }

    public function edit($id){
        $customer_group = CustomerGroup::find($id);
        return view('App.contact_management.customer_group.edit')->with(compact('customer_group'));
    }

    public function update(Request $request, $id){
        if($request->ajax()){
            try{
                $customer_group = CustomerGroup::find($id);

                $customer_group->name = $request['name'];
                $customer_group->price_calculation_type = $request['price_calculation_type'];

                $customer_group->update();

                return response()->json(['success' => true, 'msg' => 'Customer Group Updated Successfully']);
            } catch(\Exception $e){
                return response()->json(['success' => false, 'msg' => 'An error occurred while updating the customer group']);
            }
        }
    }

    public function destroy($id){
        $customer_group = CustomerGroup::find($id);

        if($customer_group){
            $customer_group->delete();

            return back()->with('success','Customer Group Deleted Successfully');
        } else{
            return back()->with('error','Customer group not found');
        }
    }
}
