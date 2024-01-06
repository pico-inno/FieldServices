<?php

namespace App\Http\Controllers;

use App\Models\printers;
use App\Models\BusinessUser;
use App\Models\InvoiceLayout;
use App\Models\posRegisters;
use Illuminate\Http\Request;
use App\Models\paymentAccounts;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class posRegistrationController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
       // pos
    public function list(){
        $usePaymentAccount=getSettingsValue('use_paymentAccount');
        return view('App.restaurants.pos.list',compact('usePaymentAccount'));
    }
    public function dataForList(){
        $posRegisters=posRegisters::get();
        $dataTable= DataTables::of($posRegisters)
        ->addColumn('checkbox',function($posRegister){
            return
            '
                <div class="form-check form-check-sm form-check-custom ">
                    <input class="form-check-input" type="checkbox" data-checked="delete" value='.$posRegister->id.' />
                </div>
            ';
        })
        ->addColumn('action', function ($posRegister) {
            $html = '
                <div class="dropdown ">
                    <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="exchangeRateDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <div class="z-3">
                    <ul class="dropdown-menu z-10 p-5 " aria-labelledby="exchangeRateDropDown" role="menu">';
                    $html.='<a class="dropdown-item cursor-pointer" href="'.route('pos.sessionCheck',$posRegister->id).'">Open</a>';
                    $html.='<a class="dropdown-item cursor-pointer openModal" data-href="'.route('posEdit',$posRegister->id).'">Edit</a>';
                    $html.='<a class="dropdown-item cursor-pointer" id="delete" data-id="'.$posRegister->id.'"  data-kt-exchangeRate-table="delete_row" data-href="'.route('posDestory',$posRegister->id).'">Delete</a>';
                    // $html .= $editBtn;
                $html .= '</ul></div></div>';
                return $html;
        })
        ->editColumn('employee',function($posRegister){
            $employee_ids=json_decode($posRegister->employee_id);
            if($employee_ids){
                $employees=BusinessUser::whereIn('id',$employee_ids)->select('username')->get();
                $employeeText='';
                foreach ($employees as $key=>$employee) {
                    $seperator=$key!= 0 ?',':'';
                    $employeeText.=$seperator.$employee->username;
                }
                return $employeeText;
            }
            return '';
        })

        ->editColumn('printer',function($posRegister){
            if($posRegister->printer_id == 0){
                return  'Browser Base Printing' ;
            }
            return $posRegister->printer->name ?? '';
        });
        if (getSettingsValue('use_paymentAccount')){
            $dataTable->editColumn('paymentAccount',function($posRegister){

                    $paymentAccountIds=json_decode($posRegister->payment_account_id);
                    if($paymentAccountIds){
                        $paymentAccounts=paymentAccounts::whereIn('id',$paymentAccountIds)->select('name','account_number')->get();
                        $accountText='';
                        foreach ($paymentAccounts as $key=>$account) {
                            $seperator=$key!= 0 ?',':'';
                            $accountText.=$seperator.$account->name.'('.$account->account_number.')';
                        }
                        return $accountText;
                    }
                return "";

            });
        }
        return  $dataTable->rawColumns(['checkbox','action'])
        ->make('true');
    }
    public function create() {
        $employee=BusinessUser::where('is_active',1)->get();
        $paymentAccounts=paymentAccounts::get();
        $printers=printers::all();
        $layouts = InvoiceLayout::all();
        return view('App.restaurants.pos.create',compact('employee','layouts','paymentAccounts','printers'));
    }
    public function store(Request $request){

        try {
            DB::beginTransaction();
            $jsonEmloyeeId=$this->requestJsonId($request->employee_id);
            $jsonPaymentAccountId=$this->requestJsonId($request->payment_account_id);
            posRegisters::create([
                'name'=>$request->name,
                'employee_id'=>$jsonEmloyeeId,
                'payment_account_id'=>$jsonPaymentAccountId,
                'use_for_res'=>$request->use_for_res ? 1 :0,
                'printer_id'=>$request->printer_id,
                'description'=>$request->description,
                'invoice_layout_id' => $request->layout_id
            ]);
            DB::commit();
            return back()->with(['success'=>'successfully created']);
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            return back()->with(['warning'=>'something is wrong !']);
        }
    }
    public function edit($id) {
        $employee=BusinessUser::where('is_active',1)->get();
        $paymentAccounts=paymentAccounts::get();
        $registeredPos=posRegisters::where('id',$id)->first();
        $printers=printers::get();
        $layouts = InvoiceLayout::all();
        // dd($registeredPos);/
        // for employee
        $employee_ids=json_decode($registeredPos->employee_id);
        $employeeText = '';
        if($employee_ids){
            $employees = BusinessUser::whereIn('id', $employee_ids)->get();
            if ($employees) {
                foreach ($employees as $key => $e) {
                    $seperator = $key == 0 ? '' : ',';
                    $employeeText .= $seperator . $e->username;
                }
            }
        }

        // for payment Account
        $paymentAccountIds=json_decode($registeredPos->payment_account_id);
        $accountText='';
        if($paymentAccountIds){
            $paymentAccountsQuery=paymentAccounts::whereIn('id',$paymentAccountIds);
            $paymentAccountsCheck=$paymentAccountsQuery->exists();
            if($paymentAccountsCheck){
                    $paymentAccountsById=$paymentAccountsQuery->get();
                    foreach ($paymentAccountsById as $key=>$a) {
                        $accountNumber= $a->account_number ?'('. $a->account_number. ')' : "";
                        $seperator=$key==0 ? '' :',';
                        $accountText.=$seperator.$a->name.$accountNumber;
                    }
            }
        }

        return view('App.restaurants.pos.edit',compact('employee','paymentAccounts','registeredPos','employeeText','accountText','printers', 'layouts'));
    }
    public function update($id,Request $request){

        try {
            DB::beginTransaction();
            $jsonEmloyeeId=$this->requestJsonId($request->employee_id);
            $jsonPaymentAccountId=$this->requestJsonId($request->payment_account_id);
            posRegisters::where('id',$id)->update([
                'name'=>$request->name,
                'employee_id'=>$jsonEmloyeeId,
                'payment_account_id'=>$jsonPaymentAccountId,
                'use_for_res'=>$request->use_for_res ? 1 :0,
            // 'status'=>$request->status,
                'printer_id'=>$request->printer_id,
                'description'=>$request->description,
                'invoice_layout_id' => $request->layout_id
            ]);
            DB::commit();
            return back()->with(['success'=>'successfully updated']);
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            return back()->with(['warning'=>'something is wrong !']);
        }
    }
    public function destory(Request $request){
        try {
            $idForDelete=$request->idForDelete;
            foreach($idForDelete as $id){
                posRegisters::where('id',$id)->first()->delete();
            }
            return response()->json([
                'status'=>'200',
                'success'=>'successfully deleted'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error'=>'500',
                'message'=>'Something Went wrong'
            ], 200);
        }
    }
    // this function is change request json data that with nested data to only id json data
    protected function requestJsonId($requestJson){
        $categories=json_decode($requestJson);
        if($categories){
            $id=array_map(function($c){
                return $c->id;
            },$categories);
            $idJson=json_encode($id);
            return $idJson;
        }
        return false;
    }
}
