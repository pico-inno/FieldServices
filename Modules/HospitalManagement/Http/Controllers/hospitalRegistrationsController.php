<?php

namespace Modules\HospitalManagement\Http\Controllers;

use App\Models\roomSales;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Contact\Contact;
use App\Models\hospitalRoomSales;
use Illuminate\Support\Facades\DB;
use Modules\RoomManagement\Entities\Room;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\hospitalRoomSaleDetails;
use Modules\RoomManagement\Entities\RoomRate;
use Yajra\DataTables\Facades\DataTables;
use Modules\RoomManagement\Entities\RoomType;
use Modules\HospitalManagement\Entities\hospitalFolioInvoices;
use Modules\HospitalManagement\Entities\hospitalRegistrations;
use Modules\HospitalManagement\Entities\hospitalRoomRegistrations;
use Modules\HospitalManagement\Entities\hospitalFolioInvoiceDetails;

class hospitalRegistrationsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index()
    {

         return view('hospitalmanagement::App.registration.index');
    }


    // data for datatable
    public function data()
    {
        $datas=hospitalRegistrations::with('patient','company')
                                    ->where('is_delete',0)
                                    ->orderBy('id','DESC')
                                    ->get();
        return DataTables::of($datas)
            ->addColumn('checkbox',function($data){
                return
                '
                    <div class="form-check form-check-sm form-check-custom ">
                        <input class="form-check-input" type="checkbox" data-checked="delete" value='.$data->id.' />
                    </div>
                ';
            })
            ->editColumn('registration_code', function ($data) {
                return $data->joint_registration_id? $data->registration_code.'<span><i class="fa-solid fa-link ps-2 p-1 fs-8 text-success"></i></span>': $data->registration_code;
            })
            ->editColumn('company', function($data){
                return $data->company['company_name']??$data->company['first_name'] ?? '';
            })
            ->addColumn('action', function ($data) {
                $count=hospitalRegistrations::where('joint_registration_id', $data->id)->count();
                $text=$data->joint_registration_id?"edit join": 'Join To';
                $joinTo= $count=='0' ?'<a type="button" class="dropdown-item p-2 edit-unit fw-bold joinRegistration" data-href="' . route('joinRegistraionModal', $data->id) . '">'.$text.'</a>' :'';
                return '
                    <div class="dropdown ">
                        <button class="btn  btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="purchaseDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <div class="z-3" style="z-index:999px;">
                            <ul class="dropdown-menu z-10 p-5 " aria-labelledby="purchaseDropDown" role="menu">
                                <a class="dropdown-item p-2  px-3 view_detail"   type="button" href="'.route('registration_view',$data->id). '">
                                    view
                                </a>
                                ' . $joinTo . '
                                <a href="'.route('registration_edit',$data->id).' " class="dropdown-item p-2 edit-unit bg-active-primary text-primary" >Edit</a>
                                <a href="'.route('room-sale.create',['type'=>'registration','id'=>$data->id]).' " class="dropdown-item p-2 edit-unit " >Room Sale</a>
                                <a  class="dropdown-item p-2  cursor-pointer bg-active-danger text-dark"  data-id="'.$data->id.'" data-kt-room-data="delete_row">Delete</a>
                            </ul>
                        </div>
                    </div>
                ';
            })
            ->rawColumns(['action', 'checkbox', 'registration_code'])
            ->make(true);
    }

    // create and store
    public function create()
    {
        $contacts = Contact::where('type', 'Customer')->get();
        $registeredPatients=hospitalRegistrations::select('id', 'registration_code','patient_id')->whereNull('joint_registration_id')->with('patient')->get();
         return view('hospitalmanagement::App.registration.create',compact('contacts', 'registeredPatients'));
    }


    public function store(Request $request)
    {
        request()->validate(
            [
                'patient_id'=>'required',
                'registration_type'=>'required',
                'registration_status'=>'required'
            ]
        );
        DB::beginTransaction();
        try {
            $registrationData=$this->hospitalRegisterationData($request);
            $registeredData=hospitalRegistrations::create($registrationData);
            if($request->registration_type==="IPD"){
                $roomRegistrationDatas=$request->roomRegistrationData;
                $this->hospitalRoomRegistration($roomRegistrationDatas, $registeredData);
            };
            $this->folioInoviceCreation($registeredData,$registeredData->joint_registration_id);
            DB::commit();
            return redirect()->route('registration_list')->with(['success'=>'Successfully Registered']);
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->route('registration_list')->with(['warning'=>'Something is wrong while registering']);
        }
    }

    private function hospitalRoomRegistration($roomRegistrationDatas, $registeredData){
      foreach ($roomRegistrationDatas as $roomRegistrationData) {
        // dd($roomRegistrationData);
            $data = [
                'registration_id' => $registeredData->id,
                'patient_id' => $registeredData->patient_id,
                'room_type_id' => $roomRegistrationData['room_type_id'],
                'room_rate_id' => $roomRegistrationData['room_rate_id'],
                'room_id' => $roomRegistrationData['room_id'],
                'check_in_date' => $roomRegistrationData['check_in_date'],
                'check_out_date' => $roomRegistrationData['check_out_date'],
                'created_at' => now(),
                'created_by' => Auth::user()->id,
            ];
            hospitalRoomRegistrations::create($data);
      }
    }



    public function edit($id)
    {

        $contacts = Contact::where('type', 'Customer')->get();
        $data = hospitalRegistrations::where('id', $id)->with('patient', 'company', 'agency')
                ->where('is_delete', 0)->get()->first();
        $roomRegistrationDatas=[];
       if($data->registration_type=="IPD"){
            $roomRegistrationDatas = hospitalRoomRegistrations::where('registration_id', $id)
                ->where('is_delete', 0)->get()->toArray();
       }
        $registeredPatients = hospitalRegistrations::select('id', 'registration_code', 'patient_id')->whereNull('joint_registration_id')->where('id','!=',$id)->with('patient')->get();
         return view('hospitalmanagement::App.registration.edit', compact('contacts', 'data', 'roomRegistrationDatas', 'registeredPatients'));
    }



    public function update($id, Request $request)
    {
        request()->validate(
            [
                'patient_id' => 'required',
                'registration_type' => 'required',
                'registration_status' => 'required'
            ]
        );
        $roomRegistrationData = $request->roomRegistrationData;
        DB::beginTransaction();
        try {
            $roomSaleDataFromReq = $request->roomSale;

            $this->updateRegistration($request,$id);
            $this->updateRoomRegistration($id,$roomRegistrationData, $request);
            // $this->updateRoomSales($id,$roomSaleDataFromReq);
            // $this->updateRoomSaleDetail($roomRegistrationData, $request, $id);
            DB::commit();
            return redirect()->route('registration_list')->with(['success' => 'Successfully Registered']);
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->route('registration_list')->with(['warning' => 'Something is wrong while registering']);
        }
    }
    private function updateRoomRegistration($id, $roomRegistrationData,$request){
        DB::beginTransaction();
        try {
            if ($roomRegistrationData) {
                //get old rooms
                $old_room_data = array_filter($roomRegistrationData, function ($item) {
                    return isset($item['room_data_id']);
                });
                // get old rooms ids from client [1,2,]
                $old_room_data_ids = array_column($old_room_data, 'room_data_id');
                // update rooms  data
                foreach ($old_room_data as $data) {
                    $room_data_id = $data['room_data_id'];
                    unset($data["room_data_id"]);
                    $data['updated_by'] = Auth::user()->id;
                    $roomUpdate = hospitalRoomRegistrations::where('id', $room_data_id)->first();
                    $roomUpdate->update($data);
                    $roomUpdate->update([
                        'check_in_date' => $request->registration_type == 'IPD' ? $request->ipd_check_in_date : $request->opd_check_in_date,
                        'check_out_date' => $request->check_out_date,
                    ]);
                }

                //get added purchase_details_ids from database
                $fetch_room_data = hospitalRoomRegistrations::where('registration_id', $id)->select('id')->get()->toArray();
                $get_fetched_hostipal_room_id = array_column($fetch_room_data, 'id');

                //to remove edited rooms that are already created
                $old_room_id_for_delete = array_diff($get_fetched_hostipal_room_id, $old_room_data_ids); //for delete row
                foreach ($old_room_id_for_delete as $p_id) {
                    hospitalRoomRegistrations::where('id', $p_id)->update([
                        'is_delete' => 1,
                        'deleted_at' => now(),
                        'deleted_by' => Auth::user()->id,
                    ]);
                }

                // //to create purchase details
                $new_room_data = array_filter($roomRegistrationData, function ($item) {
                    return !isset($item['room_data_id']);
                });
                if (count($new_room_data) > 0) {
                    $this->hospitalRoomRegistration($new_room_data,$request);
                }
            } else {
                hospitalRoomRegistrations::where('registration_id', $id)->where('is_delete', 0)->update([
                    'is_delete' => 1,
                    'deleted_at' => now(),
                    'deleted_by' => Auth::user()->id,
                ]);
            }

            DB::commit();
            return redirect()->route('registration_list')->with(['success' => 'Successfully Registered']);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
            return redirect()->route('registration_list')->with(['warning' => 'Something is wrong while registering']);
        }
        dd($request->toArray());
    }


    public function view($id)
    {
        $contacts = Contact::where('type', 'Customer')->get();
        $data=hospitalRegistrations::where('id',$id)->with('patient','company','agency','booking_by', 'jointRegistration')
                                    ->where('is_delete',0)->get()->first();
        if($data->registration_type=="IPD"){
            $roomDatas = hospitalRoomRegistrations::where('registration_id', $id)->with('room_type', 'room', 'rate')
            ->where('is_delete', 0)->get();
        }else{
            $roomDatas=[];
        }
        $folio=hospitalFolioInvoices::with('folioInvoiceDetail')->where('registration_id',$id)->first();
         return view('hospitalmanagement::App.registration.view',compact('data','roomDatas', 'folio'));
    }



    public function destory($id)
    {
        hospitalRegistrations::where('id',$id)->first()->update([
            'is_delete'=>1,
            'deleted_by'=>Auth::user()->id,
            'deleted_at'=>now()
        ]);
        hospitalRoomSales::where('registration_id', $id)->where('is_delete',0)->update([
            'is_delete'=>1,
            'deleted_at'=>now(),
            'deleted_by'=>Auth::user()->id,
        ]);
        $data=[
            'success'=>'Successfully Deleted'
        ];
        return response()->json($data, 200);
    }




    public function roomStatusChange($room_id)
    {
        $room = Room::where('id', $room_id);
        $rooStatus = $room->first()->status;
        if ($rooStatus === "Available") {
            $room->first()->update([
                'status' => 'Out_of_service'
            ]);
        } else {
            return ;
        }
    }





    // registration data
    private function hospitalRegisterationData($request)
    {

        $registrationCount = hospitalRegistrations::orderBy('id','Desc')->select('id')->first()->id ?? 0;
        return [
            'joint_registration_id' => $request->joint_id,
            'registration_code' => sprintf('VRC-' . '%06d', ($registrationCount + 1)),
            'registration_type' => $request->registration_type,
            'patient_id' => $request->patient_id,
            'company_id' => $request->company_id,
            'opd_check_in_date' => $request->registration_type=="OPD" ? date('Y-m-d H:i', strtotime($request->opd_check_in_date)) : null,
            'ipd_check_in_date' => $request->registration_type=="IPD" ? date('Y-m-d H:i', strtotime($request->ipd_check_in_date)) : null,
            'check_out_date' => date('Y-m-d H:i', strtotime($request->check_out_date)),
            'registration_status' => $request->registration_status,
            'remark' => $request->remark,
            'created_at' => now(),
            'created_by' => Auth::user()->id,
            'booking_confirmed_at' => $request->registration_status == 'Confirmed' ? now() : null,
            'booking_confirmed_by' => $request->registration_status == 'Confirmed' ? Auth::user()->id : null,
        ];
    }


    private function updateRegistration($request,$id)
    {
        $registrationData = [
            'joint_registration_id' => $request->joint_id,
            'registration_type' => $request->registration_type,
            'patient_id' => $request->patient_id,
            'company_id' => $request->company_id,
            'opd_check_in_date' => $request->registration_type==="OPD" ? date('Y-m-d H:i', strtotime($request->opd_check_in_date)) : null ,
            'ipd_check_in_date' => $request->registration_type === "IPD" ? date('Y-m-d H:i', strtotime($request->ipd_check_in_date)) : null,
            'check_out_date' => date('Y-m-d H:i', strtotime($request->check_out_date)),
            'registration_status' => $request->registration_status,
            'remark' => $request->remark,
            'updated_at' => now(),
            'updated_by' => Auth::user()->id,
        ];

        if ($request->registration_status == "Confirmed") {
            $confirmInfo = hospitalRegistrations::where('id', $id)->select('id', 'booking_confirmed_at', 'booking_confirmed_by')->where('id', $id)->first()->toArray();
            if (!$confirmInfo['booking_confirmed_at'] && !$confirmInfo['booking_confirmed_by']) {
                $registrationData['booking_confirmed_at'] = now();
                $registrationData['booking_confirmed_by'] = Auth::user()->id;
            }
        }
        hospitalRegistrations::where('id', $id)->first()->update($registrationData);
        return $registrationData;
    }



    //------------------------------------quick add/update----------------------

    // for timelime
    public function timeLine()
    {
        $rooms=Room::select('id','name', 'room_type_id')->get();
        $room_rates= RoomType::select('id','name')->get();
        // $registration=hospitalRoomSaleDetails::select('id','room_type_id','room_id','check_in_date','check_out_date', 'registration_id')->with('registration')->get();
         return view('hospitalmanagement::App.registration.timeLine',compact('rooms', 'room_rates'));
    }


    public function joinRegistraion($id)
    {
        $data=hospitalRegistrations::select('id','registration_code','joint_registration_id')->find($id);
        $registeredPatients = hospitalRegistrations::select('id', 'registration_code', 'patient_id')->where('id','!=',$id)->whereNull('joint_registration_id')->with('patient')->get();
         return view('hospitalmanagement::App.registration.Modals.joinToModal',compact('id','data', 'registeredPatients'));
    }


    public function updateJoinRegistraion(Request $request)
    {
        $idToJoin=$request->idToJoin;
        $childRegistration=$request->childRegistration;
        try {
            hospitalRegistrations::where('id', $childRegistration)->first()->update([
                'joint_registration_id' => $idToJoin
            ]);
            // $this->folioInoviceEdit($childRegistration, $idToJoin);
            return response()->json(['success' => true, 'msg' => ' Successfully Joined Registration']);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'msg' => ' Something Wrong While joinning registration']);
        }
    }

    public function registrationInfoEdit($id) {
        $registrationInfo = hospitalRegistrations::find($id);
        $contacts = Contact::where('type', 'Customer')->get();
        $Businesscontacts = Contact::where('type', 'supplier')->get();
        $registeredPatientsForJoin = hospitalRegistrations::select('id', 'registration_code', 'patient_id')->whereNull('joint_registration_id')->where('id', '!=', $id)->with('patient')->get();
         return view('hospitalmanagement::App.registration.Modals.editRegistrationInfo',compact('registrationInfo', 'registeredPatientsForJoin','contacts', 'Businesscontacts'));

    }
    public function registrationInfoUpdate($id,Request $request){
       $data= $this->updateRegistration($request,$id);
       if($data){
            return back()->with(['success'=>"successfull Updated"]);
       }
    }
    public function registrationRoomEdit($id) {

        $contacts = Contact::where('type', 'Customer')->get();

        $roomDatas = hospitalRoomRegistrations::where('registration_id', $id)->with('room_type', 'room', 'rate')
        ->where('is_delete', 0)->get();
         return view('hospitalmanagement::App.registration.Modals.editRegisteredRoom',compact('roomDatas','contacts','id', 'roomDatas'));
    }

    public function quickRoomDataUpdate($id, Request $request)
    {
        $registrationData=hospitalRegistrations::where('id',$id)->first();
        $roomRegistrationData= $request->roomRegistrationData;

        // dd($roomRegistrationData);
        $this->updateRoomRegistration($id, $roomRegistrationData, $registrationData);

        // $roomSaleDataFromReq = $request->roomSale;

        // $this->updateRoomSales($id, $roomSaleDataFromReq);
        // $this->updateRoomSaleDetail($roomRegistrationData, $registrationData,$id);
        return back()->with(['success'=>'successfully update room data','updated'=>'roomRegister']);
    }
    // public function oldRegistredRoomInfoUpdate($old_room_data_from_request){
    //     // update rooms  data
    //         foreach ($old_room_data_from_request as $data) {
    //             $room_sale_detail_id = $data['room_sale_detail_id'];
    //             unset($data["room_sale_detail_id"]);
    //             $data['updated_by'] = Auth::user()->id;
    //             $roomSoldData = hospitalRoomSaleDetails::where('id', $room_sale_detail_id)->first();
    //             if ($roomSoldData->room_id != $data['room_id']) {
    //                 Room::where('id', $roomSoldData->room_id)->first()->update([
    //                     'status' => 'Available'
    //                 ]);
    //             }
    //             $this->roomStatusChange($data['room_id']);
    //             $roomSoldData->update($data);
    //         }
    // }

    // private function hospitalRoomSaleDetailsCreation($roomSaleDetailsData,$registeredData,$roomSale)
    // {
    //     foreach ($roomSaleDetailsData as $data) {
    //         $this->roomStatusChange($data['room_id']);
    //         $roomData = [
    //             'room_sales_id'=> $roomSale->id,
    //             'room_type_id' => $data['room_type_id'],
    //             'room_id' => $data['room_id'],
    //             'patient_id' => $registeredData->patient_id,
    //             'room_rate_id' => $data['room_rate_id'],
    //             'check_in_date' => $data['check_in_date'],
    //             'check_out_date' => $data['check_out_date'],
    //             'before_discount_amount' => $data['before_discount_amount'],
    //             'discount_type' => $data['discount_type'],
    //             'discount_amount' => $data['discount_amount'],
    //             'after_discount_amount' => $data['after_discount_amount'],
    //             'amount_inc_tax' => $data['amount_inc_tax'] ?? 0,
    //             'qty' => $data['qty'],
    //             'created_at' => now(),
    //             'created_by' => Auth::user()->id,
    //         ];
    //         hospitalRoomSaleDetails::create($roomData);

    //     }

    // }
    // private function updateRoomSaleDetail($roomRegistrationData, $request, $id)
    // {
    //     $roomSalesData=hospitalRoomSales::where('registration_id',$id)->first();
    //     $roomSaleId=$roomSalesData->id;
    //     if ($roomRegistrationData) {
    //         //get old rooms

    //         $old_room_data_from_request = array_filter($roomRegistrationData, function ($item) {
    //             return isset($item['room_sale_detail_id']);
    //         });
    //         $this->oldRegistredRoomInfoUpdate($old_room_data_from_request, $request);
    //         // diff between room id from db and new id
    //         $fetch_room_data = hospitalRoomSaleDetails::where('room_sales_id', $roomSaleId)->select('id')->get()->toArray();
    //         $get_fetched_hostipal_room_id = array_column($fetch_room_data, 'id');


    //         // get old rooms ids from client
    //         $old_room_data_ids_from_request = array_column($old_room_data_from_request, 'room_sale_detail_id');
    //         //to remove edited rooms that are already created
    //         $old_room_id_for_delete = array_diff($get_fetched_hostipal_room_id, $old_room_data_ids_from_request); //for delete row
    //         foreach ($old_room_id_for_delete as $p_id) {
    //             hospitalRoomSaleDetails::where('id', $p_id)->update([
    //                 'is_delete' => 1,
    //                 'deleted_at' => now(),
    //                 'deleted_by' => Auth::user()->id,
    //             ]);
    //         }

    //         // //to create room sales details
    //         if ($request->registration_type === "IPD") {
    //             $new_room_data_from_request = array_filter($roomRegistrationData, function ($item) {
    //                 return !isset($item['room_sale_detail_id']);
    //             });
    //             if (count($new_room_data_from_request) > 0) {
    //                 $this->hospitalRoomSaleDetailsCreation($new_room_data_from_request, $request, $roomSalesData);
    //             }
    //         }
    //     } else {
    //         hospitalRoomSaleDetails::where('room_sales_id', $roomSaleId)->where('is_delete', 0)->update([
    //             'is_delete' => 1,
    //             'deleted_at' => now(),
    //             'deleted_by' => Auth::user()->id,
    //         ]);
    //     }
    // }

    // private function roomSaleCreation($roomSaleData, $registeredData){
    //     $lastRoomSaleId = hospitalRoomSales::orderBy('id', 'DESC')->first()->id ?? 0;
    //     $roomSale = hospitalRoomSales::create([
    //         'registration_id' => $registeredData->id,
    //         'room_sales_voucher_no' => sprintf('RS-' . '%06d', ($lastRoomSaleId + 1)),
    //         'business_location_id' => $roomSaleData['business_location_id'] ?? null,
    //         'contact_id' => $registeredData->patient_id,
    //         'total_amount' => $roomSaleData['total_amount'],
    //         'discount_type' => $roomSaleData['discount_type'],
    //         'discount_amount' => $roomSaleData['discount_amount'],
    //         'total_sale_amount' => $roomSaleData['total_sale_amount'],
    //         'paid_amount' => $roomSaleData['paid_amount'],
    //         'balance_amount' => $roomSaleData['balance_amount'],
    //         'created_at' => Carbon::now(),
    //         'created_by' => Auth::user()->id,
    //     ]);
    //     // $this->folioInvoiceFirstUpdate([
    //     //     'total_amount'=> $roomSaleData['total_sale_amount'],
    //     //     'paid_amount' => $roomSaleData['paid_amount'],
    //     // ],$registeredData->id);
    //     // $this->folioDetailStore($registeredData->id, [
    //     //     'transaction_type' => 'room',
    //     //     'transaction_id' => $roomSale->id,
    //     // ]);
    //     return $roomSale;
    // }

    private function folioInoviceCreation($registeredData,$joinId){
        $last_folio_id=hospitalFolioInvoices::OrderBy('id','DESC')->get()->last()->id??0;
        $joint_folio_invoice_id=$joinId ? hospitalFolioInvoices::where('registration_id', $joinId)->select('id')->first()->id : null;
        $data=[
            'folio_invoice_code'=> sprintf('FI-' . '%06d', ($last_folio_id + 1)),
            'registration_id'=>$registeredData->id,
            'joint_folio_invoice_id'=> $joint_folio_invoice_id,
            'created_at'=>now(),
            'created_by'=>Auth::user()->id,
        ];
        return hospitalFolioInvoices::create($data);
    }


    // private function folioInoviceEdit($registeredid,$newRegistrationJoinId){
    //     $folio = hospitalFolioInvoices::where('registration_id', $registeredid)->select('id')->first();
    //     $newJoinId= hospitalFolioInvoices::where('registration_id', $newRegistrationJoinId)->select('id')->first()->id;
    //     $folio->update([
    //         'joint_folio_invoice_id'=> $newJoinId ?? null
    //     ]);
    // }


    // private function folioDetailStore($registration_id,Array $data){

    //     $folio_id=hospitalFolioInvoices::where('registration_id', $registration_id)->select('id')->first()->id;
    //     $data['folio_invoice_id']=$folio_id;
    //     $data['created_at']=now();
    //     $data['created_by']=Auth::user()->id;
    //     // dd($data);
    //     hospitalFolioInvoiceDetails::create($data);

    // }

    // private function updateRoomSales($registrationId,$roomSaleDataFromReq){
    //     $roomSale=hospitalRoomSales::where('registration_id', $registrationId)->first();
    //     $roomSaleDataFromReq['update_at']=now();
    //     $roomSaleDataFromReq['update_by']=Auth::user()->id;
    //     $roomSale->update($roomSaleDataFromReq);
    // }

    public function getJoinedFolioDatas(Request $request){
        $registration_id=$request['registration_id'];
        $folio=hospitalFolioInvoices::where('registration_id',$registration_id)->first();
        $folioDetails=hospitalFolioInvoiceDetails::where('folio_invoice_id',$folio->id)->get();
        $htmlForAllTabs="";
        $htmlForRoomSale="";


        foreach ($folioDetails as $key=>$fd) {
                $sale='';
                $voucher_no='';
                $type='';
                if($fd->transaction_type=='sale'){
                    $fd->load('sales');
                    $sale=$fd->sales;
                    $voucher_no=$sale->sales_voucher_no;
                    $type="sale";
                    $saleHtml="";
                    $saleDetailHtml="";
                    $saleDetails=$sale->sale_details;
                    $extraDiscountType = $sale['extra_discount_type'] == "fixed" ? 'ks' : '%';
                    $extraDiscount = round($sale['extra_discount_amount'], 2) . $extraDiscountType;
                    foreach ($saleDetails as  $sd) {
                        $product=$sd->product;
                        $variationName=$sd->productVariation->variationTemplateValue->name ?? '';
                        $variation = $variationName ? '('. $variationName .')' : '';
                        $uom=$sd->uom;
                        $DiscountType = $sd->discount_type == "fixed" ? 'ks' : '%';
                        $Discount = round($sd->per_item_discount, 2) . $DiscountType;
                        $saleDetailHtml.= '
                                <tr>
                                    <td class="min-w-150px text-end">
                                        '. $product['name'].'
                                        '. $variation .'
                                    </td>
                                    <td class="min-w-150px text-end">'. $sd->quantity.'</td>
                                    <td class="min-w-150px text-end">'.$uom->name.'</td>
                                    <td class="min-w-150px text-end">'.$sd->uom_price.'</td>
                                    <td class="min-w-150px text-end">'.$sd->subtotal.'</td>
                                </tr>
                        ';
                    }
                    $saleArray=$sale->toArray();


                      $saleHtml.= '
                            <tr class="server_tab_' . $registration_id . '">
                                <td class="appendChildFromSer_'.$registration_id.' cursor-pointer" data-id="s_s'.$sale->id.$fd->id.'" >
                                    <i class="fa-solid fa-circle-plus fs-3 text-primary d-block fa-icon"></i>
                                </td>
                                <td>' . $saleArray["sales_voucher_no"].'</td>
                                <td class=" text-end">'. $saleArray['sale_amount'].'</td>
                                <td class=" text-end"> <a  class="btn btn-sm btn-light btn-active-light-primary">'. round($saleArray['total_item_discount'], 2).'</a></td>
                                <td class="min-w-150px text-end ">
                                '. $extraDiscount.'
                                </td>
                                <td class=" text-end">'. $saleArray['total_sale_amount'].'</td>
                                <td class=" text-end">'. $saleArray['paid_amount'].'</td>
                                <td class=" text-end">'. $saleArray['balance_amount'].'</td>
                                <td class=" text-center">'.date_format(date_create($sale->sold_at),"d-m-y h:i A").'</td>
                                <td class=" text-end"> '. $sale->sold->username. '</td>
                            </tr>
                            <tr  class="server_tab_' . $registration_id . '" id="s_s'.$sale->id.$fd->id.'" style="display: none;">
                                <td colspan="7">
                                    <table class="table table-row-bordered ">
                                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                            <tr>
                                                <td class="text-end">Product</td>
                                                <td class=" text-end">quantity</td>
                                                <td class=" text-end">uom </td>
                                                <td class=" text-end">uom price</td>
                                                <td class=" text-end">Subtotal</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           ' . $saleDetailHtml.'
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        ';

                }elseif($fd->transaction_type=="room"){
                    $fd->load('roomSales');
                    $sale=$fd->roomSales;
                    $voucher_no=$sale->room_sales_voucher_no;
                    $type="room sale";
                    $roomSaleDetails=$sale->room_sale_details;
                    $roomSaleHtml="";

                    foreach ($roomSaleDetails as $rsd) {
                        $roomType=$rsd->room_type;
                        $roomRate = $rsd->room_rate;
                        $Room = $rsd->room;
                        $DiscountType = $rsd->discount_type == "fixed" ? 'ks' : '%';
                        $Discount = round($rsd->per_item_discount, 2) . $DiscountType;
                        $roomSaleHtml.= '
                            <tbody>
                                <tr>
                                    <td class="min-w-150px text-end">'. $roomType['name'].'</td>
                                    <td class="min-w-150px text-end">'. $roomRate['rate_name'] .'</td>
                                    <td class="min-w-150px text-end">'.$Room['name']. '</td>
                                    <td class="min-w-150px text-end ">' . $rsd->room_fees . '</td>
                                    <td class="min-w-150px text-end ">'.$rsd->subtotal.'</td>
                                    <td class="min-w-150px text-center ">'.$Discount.'</td>
                                    <td class="min-w-150px text-center">'. date_format(date_create($rsd->check_in_date), "d-m-y h:i A"). '</td>
                                    <td class="min-w-175px text-center">'. date_format(date_create($rsd->check_out_date), "d-m-y h:i A").'</td>
                                </tr>
                            </tbody>
                        ';
                    }

                    // dd($sale->toArray());

                    $htmlForRoomSale.= '
                        <tr class="server_tab_'.$registration_id . '">
                            <td class="appendChildFromSer_'.$registration_id.' cursor-pointer" data-id="rs_s'.$sale->id.$fd->id.'" >
                                <i class="fa-solid fa-circle-plus fs-3 text-primary d-block fa-icon"></i>
                            </td>
                            <td>'. $sale->room_sales_voucher_no .'</td>
                            <td class=" text-end">'.$sale->sale_amount.'</td>
                            <td class=" text-end"> <a  class="btn btn-sm btn-light btn-active-light-primary"> '.round($sale->total_item_discount,2).' </a></td>
                            <td class=" text-end">'.$sale->total_sale_amount.'</td>
                            <td class=" text-end">'.$sale->paid_amount.'</td>
                            <td class=" text-end">'.$sale->balance_amount. '</td>
                        </tr>
                        <tr  class="server_tab_'.$registration_id.'" id="rs_s'.$sale->id.$fd->id.'" style="display: none;">
                            <td colspan="7">
                                <table class="table table-row-bordered ">
                                    <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                        <tr>
                                            <td class="text-end">Room Rate</td>
                                            <td class=" text-end">Room Type</td>
                                            <td class=" text-end">Room </td>
                                            <td class=" text-end">Room Fees</td>
                                            <td class=" text-end">Subtotal</td>
                                            <td class=" text-center">Discount</td>
                                            <td class="text-center">Check In</td>
                                            <td class="text-center">Check Out</td>
                                        </tr>
                                    </thead>
                                    '. $roomSaleHtml .'
                                </table>
                            </td>
                        </tr>

                    ';



                };


                // FOR ALL FOLIO TAB
                $extraDiscountType= $sale['extra_discount_type'] == "fixed" ? 'ks' : '%';
                $extraDiscount= round($sale['extra_discount_amount'], 2). $extraDiscountType;
                $htmlForAllTabs.= '
                    <tr class="server_tab_'.$registration_id.' ">
                        <td></td>
                        <td>'.$voucher_no. '</td>
                        <td>' . $type . '</td>
                        <td class="text-end">'.$sale->sale_amount . '</td>
                        <td class="text-end">
                            <a  class="btn btn-sm btn-light btn-active-light-primary">' . round($sale['total_item_discount'], 2) . '</a>
                        </td>
                        <td class="min-w-150px text-end ">
                           ' .$extraDiscount. '
                        </td>
                        <td class="text-end">' . $sale->total_sale_amount . '</td>
                        <td class="text-end">' . $sale->paid_amount . '</td>
                        <td class="text-end">' . $sale->balance_amount . '</td>
                    </tr>
                ';


        }
        // dd($htmlForRoomSale);
        $data=[
            'ForAllTab'=> $htmlForAllTabs,
            'ForRoomSaleTab'=>$htmlForRoomSale ?? '',
            'ForSaleTab'=>$saleHtml ?? '',
        ];
        return response()->json($data, 200);
    }


}

// @php
//     $folioDetailsForRoom=App\Models\hospitalFolioInvoiceDetails::with('sales')
//     ->where('folio_invoice_id',$folio->id)
//     ->where('transaction_type','sale')->get();
//     $totalSaleAmount=0;
//     $saleAmount=0;
//     $totalItemDiscount=0;
//     $totalExtraDiscount=0;
// @endphp
// @foreach ($folioDetailsForRoom as $index=>$fd)
// @php
//     $sale=$fd->toArray()['sales'];

//     $totalSaleAmount+=$sale['total_sale_amount'];
//     $saleAmount+=$sale['sale_amount'];
//     $totalItemDiscount+=$sale['total_item_discount'];
//     $totalExtraDiscount+=DiscAmountCal($sale['sale_amount'],$sale['extra_discount_type'],$sale['extra_discount_amount'] ?? 0);
// @endphp
// <tr>
//     <td class="appendChildRow cursor-pointer" data-id="rs-{{$index}}" >
//         <i class="fa-solid fa-circle-plus fs-3 text-primary d-block fa-icon"></i>
//     </td>
//     <td>{{$sale['sales_voucher_no']}}</td>
//     <td class=" text-end">{{$sale['sale_amount']}}</td>
//     <td class=" text-end"> <a  class="btn btn-sm btn-light btn-active-light-primary">{{round($sale['total_item_discount'],2)}} </a></td>
//     <td class="min-w-150px text-end ">
//         {{round($sale['extra_discount_amount'],2)}} {{$sale['extra_discount_type']=="fixed"?'ks':'%'}}
//     </td>
//     <td class=" text-end">{{$sale['total_sale_amount']}}</td>
//     <td class=" text-end">{{$sale['paid_amount']}}</td>
//     <td class=" text-end">{{$sale['balance_amount']}}</td>
//     <td class=" text-center"> {{date_format(date_create($fd->sales->sold_at),"d-m-y h:i A")}}</td>
//     <td class=" text-end"> {{$fd->sales->sold->username}}</td>
// </tr>
// <tr id="rs-{{$index}}" style="display: none;">
//     <td colspan="7">
//         <table class="table table-row-bordered ">
//             <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
//                 <tr>
//                     <td class="text-end">Product</td>
//                     <td class=" text-end">quantity</td>
//                     <td class=" text-end">uom </td>
//                     <td class=" text-end">uom price</td>
//                     <td class=" text-end">Subtotal</td>
//                     {{-- <td class=" text-end">Room Fees</td>
//                     <td class=" text-end">Subtotal</td>
//                     <td class=" text-center">Discount</td> --}}
//                 </tr>
//             </thead>
//             <tbody>
//                 @php
//                 $saleDetails=$sale['sale_details'];
//                 @endphp
//                 @foreach ($saleDetails as $sd)
//                 @php
//                 $variation=$sd['product_variation']['variation_template_value']
//                 @endphp
//                 <tr>
//                     {{-- <td>here</td> --}}
//                     <td class="min-w-150px text-end">
//                         {{$sd['product']['name']}}
//                         @if ( $variation)
//                         ({{$variation['name']}})
//                         @endif
//                     </td>
//                     <td class="min-w-150px text-end">{{$sd['quantity']}}</td>
//                     <td class="min-w-150px text-end">{{$sd['uom']['name']}}</td>
//                     <td class="min-w-150px text-end">{{$sd['uom_price']}}</td>
//                     <td class="min-w-150px text-end">{{$sd['subtotal']}}</td>
//                     {{-- <td class="min-w-150px text-end ">{{$sd['room_fees']}}</td>
//                     <td class="min-w-150px text-end ">{{$sd['subtotal']}}</td>
//                     <td class="min-w-150px text-center ">{{round($sd['per_item_discount'],2)}} {{$rsd['discount_type']=="fixed"?'ks':'%'}}</td> --}}
//                 </tr>
//                 @endforeach
//             </tbody>
//         </table>
//     </td>
// </tr>
// @endforeach



                            // <tr id="rs-{{$index}}" style="display: none;">
                            //     <td colspan="7">
                            //         <table class="table table-row-bordered ">
                            //             <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                            //                 <tr>
                            //                     <td class="text-end">Product</td>
                            //                     <td class=" text-end">quantity</td>
                            //                     <td class=" text-end">uom </td>
                            //                     <td class=" text-end">uom price</td>
                            //                     <td class=" text-end">Subtotal</td>
                            //                     {{-- <td class=" text-end">Room Fees</td>
                            //                     <td class=" text-end">Subtotal</td>
                            //                     <td class=" text-center">Discount</td> --}}
                            //                 </tr>
                            //             </thead>
                            //             <tbody>
                            //                 @php
                            //                 $saleDetails=$sale['sale_details'];
                            //                 @endphp
                            //                 @foreach ($saleDetails as $sd)
                            //                 @php
                            //                 $variation=$sd['product_variation']['variation_template_value']
                            //                 @endphp
                            //                 <tr>
                            //                     {{-- <td>here</td> --}}
                            //                     <td class="min-w-150px text-end">
                            //                         {{$sd['product']['name']}}
                            //                         @if ( $variation)
                            //                         ({{$variation['name']}})
                            //                         @endif
                            //                     </td>
                            //                     <td class="min-w-150px text-end">{{$sd['quantity']}}</td>
                            //                     <td class="min-w-150px text-end">{{$sd['uom']['name']}}</td>
                            //                     <td class="min-w-150px text-end">{{$sd['uom_price']}}</td>
                            //                     <td class="min-w-150px text-end">{{$sd['subtotal']}}</td>
                            //                     {{-- <td class="min-w-150px text-end ">{{$sd['room_fees']}}</td>
                            //                     <td class="min-w-150px text-end ">{{$sd['subtotal']}}</td>
                            //                     <td class="min-w-150px text-center ">{{round($sd['per_item_discount'],2)}} {{$rsd['discount_type']=="fixed"?'ks':'%'}}</td> --}}
                            //                 </tr>
                            //                 @endforeach
                            //             </tbody>
                            //         </table>
                            //     </td>
                            // </tr>
