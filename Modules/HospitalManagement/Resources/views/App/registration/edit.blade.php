@extends('App.main.navBar')
@section('styles')
@endsection
@section('registration_icon','active')
@section('hospital_registration_show','active show')
{{-- @section('create_registration_active_show','active') --}}

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-2">Edit Registration</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Hospital</li>
    <li class="breadcrumb-item text-muted">Registration</li>
    <li class="breadcrumb-item text-dark">Edit Registration</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="hospital_registration">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <form action="{{route('registration_update',$data->id)}}" method="POST" id="hospital_registration_form">
            @csrf
            <div class="card card-p-4 card-flush mb-5">
                <div class="card-body">
                    <div class="row mb-8">
                        <h5 class="text-gray-700">
                            Registration Code : {{$data->registration_code}}
                        </h5>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="registration_type" class="required form-label fs-6 text-gray-600 mb-3">Registration Type</label>
                            <select name="registration_type" id="registration_type" class="form-select form-select-sm" data-control="select2" data-placeholder="Please select Patient Type">
                                <option value="OPD" @selected($data->registration_type=='OPD')>Outpatient Department (OPD)</option>
                                <option value="IPD" @selected($data->registration_type=='IPD')>In-patient Department (IPD)</option>
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-12 mb-8 opd_check_in">
                            <label for="opd_check_in_date" class="form-label fs-6 text-gray-600 mb-3">OPD Checkin Date</label>
                            <input type="text" name="opd_check_in_date"  data-flat-date="true"  id="opd_check_in_date" class="form-control form-control-sm opd_check_in_date" autocomplete="off"  value="{{$data->opd_check_in_date}}" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8 ipd_check_in"  style="display: none;">
                            <label for="ipd_check_in_date" class="form-label fs-6 text-gray-600 mb-3">IPD Checkin Date</label>
                            <input type="text" name="ipd_check_in_date"  data-flat-date="true"  id="ipd_check_in_date" class="form-control form-control-sm ipd_check_in_date" value="{{$data->ipd_check_in_date}}" autocomplete="off" />
                        </div>
                        <div class=" col-md-3 col-sm-12 mb-8">
                            <label for="check_out_date" class="form-label fs-6 text-gray-600 mb-3">Checkout Date</label>
                            <input type="text" name="check_out_date"  data-flat-date="true"  id="check_out_date" class="form-control form-control-sm check_out_date" value="{{$data->check_out_date}}" autocomplete="off" />
                        </div>
                        <div class=" col-md-3 col-sm-12 mb-8 fv-row">
                            <label for="registration_status" class="required form-label fs-6 text-gray-600 mb-3">Registration Status</label>
                            <select name="registration_status" id="registration_status" class="form-select  form-select-sm" data-control="select2" data-hide-search="true" aria-label="Select example">
                                <option value="" selected disabled>Please select</option>
                                <option value="Pending" @selected($data->registration_status=="pending") >Pending</option>
                                <option value="Cancelled" @selected($data->registration_status=="cancelled")>Cancelled</option>
                                <option value="Confirmed" @selected($data->registration_status=="confirmed")>Confirmed</option>
                                <option value="Checkin" @selected($data->registration_status=="checkin")>Checkin</option>
                                <option value="Checkout" @selected($data->registration_status=="checkout")>Checkout</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="" class="form-label">
                                Join To
                            </label>
                            <select name="joint_id" class="form-select form-select-sm" data-control="select2" data-allow-clear="true">
                                <option  disabled selected>Select Patient</option>
                                @foreach ($registeredPatients as $rp)
                                    <option value="{{$rp->id}}" @selected($rp->id==$data->joint_registration_id)>{{$rp->patient['prefix']}} {{$rp->patient['first_name']  }} {{$rp->patient['middle_name']}} {{$rp->patient['last_name']}} ({{$rp->registration_code}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="d-flex justify-content-center align-items-center mb-10">
                        <div class="col-2 fs-4 fw-light  text-primary-emphasis">
                            Patient Info
                        </div>
                        <div class="separator   border-primary-subtle col-10"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 mb-8 fv-row">
                            <label for="patient_id" class="required form-label fs-6 text-gray-600 mb-3">Patient</label>
                            <select name="patient_id" id="patient_id" class="form-select form-select-sm" data-control="select2" data-placeholder="Please select" data-allow-clear="true">
                                <option></option>
                                @foreach ($contacts as $contact)
                                    <option value="{{ $contact->id }}" @selected($data->patient_id==$contact->id)>{{ $contact->first_name ? "$contact->first_name $contact->middle_name $contact->last_name" : $contact->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label for="company_id" class="required form-label fs-6 text-gray-600 mb-3">Company</label>
                            <select name="company_id" id="company_id" class="form-select form-select-sm" data-control="select2" data-placeholder="Please select">
                                <option></option>
                                @foreach ($contacts as $contact)
                                    <option value="{{ $contact->id }}"  @selected($data->company_id==$contact->id)>{{ $contact->company_name ?? $contact->first_name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-4 col-sm-12 mb-8">
                            <label class="form-label mb-3">remark</label>
                            <textarea name="remark" class="form-control form-control-sm " id="" cols="20" rows="3">{{$data->remark}}</textarea>
                            {{-- <input type="number" name="max_occupancy" id="max_occupancy" class="form-control form-control-sm" value="" placeholder="0" readonly /> --}}
                        </div>
                    </div>


                    <div class="row mb-5">
                        <div class="col-md-12 ">
                            <!--begin::Accordion-->
                            <div class="accordion accordion-icon-toggle" id="patient">
                                <!--begin::Item-->
                                <div class="mx-5">
                                    <!--begin::Header-->
                                    <div class="accordion-header pt-5 pb-10 d-flex " data-bs-toggle="collapse" data-bs-target="#patient_info">
                                        <span class="accordion-icon">
                                            <i class="fa-solid fa-circle-chevron-right"></i>
                                        </span>
                                        <h3 class="fs-6 fw-semibold mb-0 ms-4 text-gray-600">Patient Info (Read Only)</h3>
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Body-->
                                    <div id="patient_info" class="fs-6   ps-10  show" data-bs-parent="#patient">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 mb-8">
                                                <label  class="form-label mb-3">Name</label>
                                                <input type="text" name="" id="patient_name" class=" form-control form-control-sm" value="{{$data->patient['first_name']}}" readonly>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-8">
                                                <label  class="form-label mb-3">Father Name</label>
                                                <input type="text" name="" id="patient_father_name" class=" form-control form-control-sm" value="" readonly>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-8">
                                                <label  class="form-label mb-3">Gender</label>
                                                <input type="text" name="" id="patient_gender" class=" form-control form-control-sm" value="" readonly>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-8">
                                                <label  class="form-label mb-3">DOB</label>
                                                <input type="text" name="" id="patient_DOB" class="patient_name form-control form-control-sm" value="{{$data->patient['dob']}}" readonly>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-8">
                                                <label  class="form-label mb-3">Blood Type</label>
                                                <input type="text" name="" id="patient_blood_type" class="patient_name form-control form-control-sm" value="AB" readonly>
                                            </div>
                                        </div>

                                    </div>
                                    <!--end::Body-->
                                </div>
                            </div>
                            <!--end::Accordion-->
                       </div>
                    </div>
                </div>

            </div>
            @if($data->registration_type=="IPD")
                <div  class="card card-p-4 card-flush  border  border-primary-subtle  border-top-1 border-left-0 border-right-0 border-bottom-0 room_info">
                    <div class="card-body">
                        <div>
                            <div class="d-flex justify-content-center align-items-center mb-5 ">
                                    <div class="col-2 fs-4 fw-light  text-primary-emphasis ">
                                        Rooms Info
                                    </div>
                                    <div class="separator   border-primary-subtle col-10"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 mb-8 " >
                                    <label class="form-label fs-6 text-gray-600 mb-3">Rooms</label>
                                    <input type="number" class="form-control form-control-sm room-qty" value="1"/>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table table-row-dashed fs-6 gy-4" id="room_table">
                                    <!--begin::Table head-->
                                    <thead>
                                        <!--begin::Table row-->
                                        <tr class="text-start text-gray-600 fs-6 fw-bold">
                                            <th class="min-w-100px">Room Type</th>
                                            <th class="min-w-100px">Room Rate</th>
                                            <th class="min-w-100px">Room</th>
                                            <th class="min-w-150px">Date</th>
                                            <th class="text-center "><i class="fa-solid fa-trash text-danger"></i></th>
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fw-semibold text-gray-700">
                                        @foreach ($roomRegistrationDatas as $key=>$roomData)
                                            <tr class=" input_group">
                                                <input type="hidden" name="roomRegistrationData[{{$key}}][room_sale_detail_id]" value="{{$roomData['id']}}">
                                                <td class="fv-row">
                                                    <select  name="roomRegistrationData[{{$key}}][room_type_id]" class="form-select form-select-sm room_type_select room_type " data-control="select2" data-placeholder="Please select">
                                                        <option></option>
                                                        @php
                                                            $room_types = \App\Models\RoomManagement\RoomType::all();
                                                        @endphp
                                                        @foreach($room_types as $room_type)
                                                            <option value="{{ $room_type->id}}" @selected($roomData['room_type_id']==$room_type->id)>{{ $room_type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td  class="fv-row">
                                                    <select name="roomRegistrationData[{{$key}}][room_rate_id]"  class="form-select form-select-sm  room_rate"  data-control="select2" data-placeholder="Please select">
                                                        <option></option>
                                                            @php
                                                                $room_rates = \App\Models\RoomManagement\RoomRate::all();
                                                                $room_rates_by_type = \App\Models\RoomManagement\RoomRate::where('room_type_id',$roomData['room_type_id'])->get();
                                                            @endphp
                                                            @foreach($room_rates_by_type as $room_rate)
                                                                <option value="{{ $room_rate->id}}" @selected($room_rate->id==$roomData['room_rate_id'])>{{ $room_rate->rate_name }}</option>
                                                            @endforeach
                                                    </select>
                                                </td>
                                                <td  class="fv-row">
                                                    <select name="roomRegistrationData[{{$key}}][room_id]" class="form-select  fs-7 custom-select2 room_select" data-control="select2" data-placeholder="Please select">
                                                        <option></option>
                                                        @php
                                                            $rooms = \App\Models\RoomManagement\Room::where('status',"Available")->select('id','room_type_id','status','name')->get();
                                                            $rooms_by_type = \App\Models\RoomManagement\Room::where('room_type_id',$roomData['room_type_id'])->where('status',"Available")->select('id','room_type_id','status','name')->get();
                                                        @endphp
                                                        @foreach($rooms_by_type as $room)
                                                            <option value="{{ $room->id}}"  @selected($room->id==$roomData['room_id'])>{{ $room->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td  class="fv-row">
                                                    <div class="d-flex flex-column">
                                                        <input type="text" name="roomRegistrationData[{{$key}}][check_in_date]"  data-flat-date="true"  id="edit_check_in_date" class="form-control form-control-sm mb-3 edit_check_in_date" placeholder="Check In Date" autocomplete="off"  value="{{$roomData['check_in_date']}}" />
                                                        <input type="text" name="roomRegistrationData[{{$key}}][check_out_date]" data-flat-date="true"  id="edit_check_out_date" class="form-control form-control-sm" placeholder="Check Out Date" autocomplete="off"  value="{{$roomData['check_out_date']}}" />
                                                    </div>
                                                </td>
                                                <td class="text-end fv-row">
                                                    <button type="button" class="btn btn-light-danger" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                            </div>
                            <div class="row mt-5">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-sm btn-light-primary me-3" id="add_room_row"><i class="fa-solid fa-plus"></i></button>
                                    <button type="button" class="btn btn-sm btn-light-danger disable" id="delete_room_row"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div  style="display: none;" class="card card-p-4 card-flush  border  border-primary-subtle  border-top-1 border-left-0 border-right-0 border-bottom-0 room_info">
                    <div class="card-body">
                        <div>
                            <div class="d-flex justify-content-center align-items-center mb-5 ">
                                    <div class="col-2 fs-4 fw-light  text-primary-emphasis ">
                                        Rooms Info
                                    </div>
                                    <div class="separator   border-primary-subtle col-10"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 mb-8 " >
                                    <label class="form-label fs-6 text-gray-600 mb-3">Rooms</label>
                                    <input type="number" class="form-control form-control-sm room-qty" value="1"/>
                                </div>
                                {{-- <div class="col-md-6 col-sm-12 mb-8 fv-row">
                                    <label for="registration_status" class="required form-label fs-6 text-gray-600 mb-3">Registration Status</label>
                                    <select name="registration_status" id="registration_status" class="form-select" data-control="select2" data-hide-search="true" aria-label="Select example">
                                        <option value="" selected disabled>Please select</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Confirmed">Confirmed</option>
                                        <option value="Checkin">Checkin</option>
                                        <option value="Checkout">Checkout</option>
                                    </select>
                                </div> --}}
                            </div>
                            <div class="table-responsive">
                                <table class="table table-row-dashed fs-6 gy-4" id="room_table">
                                    <!--begin::Table head-->
                                    <thead>
                                        <!--begin::Table row-->
                                        <tr class="text-start text-gray-600 fs-6 fw-bold">
                                            <th class="min-w-100px">Room Type</th>
                                            <th class="min-w-100px">Room Rate</th>
                                            <th class="min-w-100px">Room</th>
                                            <th class="min-w-125px"> Amount <br>(before discount)</th>
                                            <th class="min-w-100px">Discount Type</th>
                                            <th class="min-w-125px">Discount Amount</th>
                                            <th class="min-w-125px"> Amount(After discount)</th>
                                            <th class="min-w-150px">Date</th>
                                            <th class="min-w-100px">Qty</th>
                                            <th class="min-w-100px">Sub total amount</th>
                                            <th class="text-center "><i class="fa-solid fa-trash text-danger"></i></th>
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fw-semibold text-gray-700">
                                        <tr class=" input_group">
                                            <td class="fv-row">
                                                <select  name="roomRegistrationData[0][room_type_id]" class="form-select form-select-sm room_type_select room_type " data-control="select2" data-placeholder="Please select">
                                                    <option></option>
                                                    @php
                                                        $room_types = \App\Models\RoomManagement\RoomType::all();
                                                    @endphp
                                                    @foreach($room_types as $room_type)
                                                    <option value="{{ $room_type->id}}">{{ $room_type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td  class="fv-row">
                                                <select name="roomRegistrationData[0][room_rate_id]"  class="form-select form-select-sm  room_rate"  data-control="select2" data-placeholder="Please select">
                                                    <option></option>
                                                    @php
                                                        $room_rates = \App\Models\RoomManagement\RoomRate::all();
                                                    @endphp
                                                </select>
                                            </td>
                                            <td  class="fv-row">
                                                <select name="roomRegistrationData[0][room_id]" class="form-select  fs-7 custom-select2 room_select" data-control="select2" data-placeholder="Please select">
                                                    <option></option>
                                                    @php
                                                        $rooms = \App\Models\RoomManagement\Room::where('status',"Available")->select('id','room_type_id','status','name')->get();
                                                    @endphp
                                                </select>
                                            </td>
                                            <td  class="fv-row">
                                                <input type="number" name="roomRegistrationData[0][before_discount_amount]" id="rate_amout_bef_discount" class="form-control  fs-7 before_discount_amount" value="" placeholder="0"  />
                                            </td>
                                            <td  class="fv-row">
                                                <select name="roomRegistrationData[0][discount_type]" class="form-select  fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                                                    <option></option>
                                                    <option value="fixed">Fixed</option>
                                                    <option value="percentage">Percentage</option>
                                                </select>
                                            </td>
                                            <td  class="fv-row">
                                                <input type="number" name="roomRegistrationData[0][discount_amount]" class="form-control  fs-7" value="" placeholder="0" />
                                            </td>
                                            <td  class="fv-row">
                                                <input type="number" name="roomRegistrationData[0][after_discount_amount]" id="" class="form-control  fs-7 after_discount_amount" value="" placeholder="0"  />
                                            </td>
                                            <td  class="fv-row">
                                                <div class="d-flex flex-column">
                                                    <input type="text" name="roomRegistrationData[0][check_in_date]" id="edit_check_in_date" class="form-control form-control-sm mb-3 edit_check_in_date" placeholder="Check In Date" autocomplete="off"  value="" />
                                                    <input type="text" name="roomRegistrationData[0][check_out_date]" id="edit_check_out_date" class="form-control form-control-sm edit_check_out_date" placeholder="Check Out Date" autocomplete="off"  value="" />
                                                </div>
                                            </td>
                                            <td  class="fv-row">
                                                <input type="number" name="roomRegistrationData[0][qty]" class="form-control  fs-7 qty" value="" placeholder="0"  />
                                            </td>
                                            <td  class="fv-row">
                                                <input type="number"  name="roomRegistrationData[0][subtotal_amount]" class="form-control  fs-7" value="" placeholder="0"  />
                                            </td>
                                            <td class="text-end fv-row">
                                                <button type="button" class="btn btn-light-danger" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                            </div>
                            <div class="row mt-5">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-sm btn-light-primary me-3" id="add_room_row"><i class="fa-solid fa-plus"></i></button>
                                    <button type="button" class="btn btn-sm btn-light-danger disable" id="delete_room_row"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                            <div class="payment mt-10">
                                <div class="row">
                                    <div class="my-15 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-center">
                                        <span class="min-w-200px" for="">
                                                Total Amount:(=)Ks
                                        </span>
                                        <input type="text" class="form-control form-control-sm input_number max-w-100px" name="roomSale[total_amount]" id="total_amount" value="{{old('total_amount',0)}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-7 mt-3 col-12 col-md-4">
                                        <label class="form-label fs-6 fw-semibold" for="">
                                            Discount Type
                                        </label>
                                        <select name="roomSale[discount_type]" id="total_discount_type" class="form-select form-select-sm" data-control="select2">
                                            <option value="fixed" @selected(old('total_discount_type')=="fixed")>Fixed</option>
                                            <option value="percentage" @selected(old('total_discount_type')=="percentage")>Percentage</option>
                                        </select>
                                    </div>
                                    <div class="mb-7 mt-3 col-12 col-md-4">
                                        <label class="form-label fs-6 fw-semibold" for="">
                                            Discount Amount
                                        </label>
                                        <input type="text" class="form-control input_number form-control-sm" id="total_discount_amount"  name="roomSale[discount_amount]" value="{{old('total_discount_amount',0)}}">
                                    </div>
                                    <div class="fs-6 fw-semibold col-12 col-md-4 d-flex justify-content-between align-items-center">
                                        <span class="min-w-200px pe-2" for="">
                                                Discount :(=)Ks
                                        </span>
                                        <input type="text" class="form-control form-control-sm  input_number max-w-100px" id="discount" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="my-15 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-center">
                                        <span class="min-w-200px pe-2" for="">
                                                Total Sale Amount:(=)Ks
                                        </span>
                                        <input type="text" class="form-control form-control-sm  input_number max-w-100px" name="roomSale[total_sale_amount]" id="balance_amount" value="{{old('balance_amount',0)}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="my-15 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-center">
                                        <span class="min-w-200px pe-2" for="">
                                                Paid Amount:(=)Ks
                                        </span>
                                        <input type="text" class="form-control form-control-sm  input_number max-w-100px" name="roomSale[paid_amount]" id="balance_amount" value="{{old('balance_amount',0)}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="my-15 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-center">
                                        <span class="min-w-200px pe-2" for="">
                                                Balance Amount:(=)Ks
                                        </span>
                                        <input type="text" class="form-control form-control-sm  input_number max-w-100px" name="roomSale[balance_amount]" id="balance_amount" value="{{old('balance_amount',0)}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
            <div class="card-footer d-flex justify-content-center mt-5">
                <button type="submit" class="btn btn-primary" data-kt-hosptial-action="submit">Save</button>
            </div>
            <!--end::Card-->
        </form>
    </div>
    <!--end::container-->
</div>
<!--end::Content-->
@endsection

@push('scripts')
<script src="{{asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js')}}"></script>
<script src="{{asset('customJs/hospital/validation.js')}}"></script>

@include('hospitalmanagement::App.Js.registrationJs')
@include('hospitalmanagement::App.Js.roomRepeaterJs')
@endpush
