


@extends('App.main.navBar')
@section('styles')
@endsection
@section('registration_icon','active')
@section('hospital_registration_show','active show')
@section('create_registration_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Add Registration</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Hospital</li>
    <li class="breadcrumb-item text-muted">Registration</li>
    <li class="breadcrumb-item text-dark">Add Registration</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('styles')
    {{-- <link rel="stylesheet" href={{asset("customCss/businessSetting.css")}}> --}}
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="hospital_registration">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <form action="{{route('registration_store')}}" method="POST" id="hospital_registration_form">
            @csrf
            <div class="card card-p-4 card-flush">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="registration_type" class="required form-label fs-6 text-gray-600 mb-3">Registration Type</label>
                            <select name="registration_type" id="registration_type" class="form-select form-select-sm" data-control="select2" data-placeholder="Please select Patient Type">
                                <option value="OPD">Outpatient Department (OPD)</option>
                                <option value="IPD">In-patient Department (IPD)</option>
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="opd_check_in_date" class="form-label fs-6 text-gray-600 mb-3">OPD Checkin Date</label>
                            <input type="text" name="opd_check_in_date" id="opd_check_in_date" class="form-control form-control-sm" autocomplete="off"  value="" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="ipd_check_in_date" class="form-label fs-6 text-gray-600 mb-3">IPD Checkin Date</label>
                            <input type="text" name="ipd_check_in_date" id="ipd_check_in_date" class="form-control form-control-sm" value="" autocomplete="off" />
                        </div>
                        <div class=" col-md-3 col-sm-12 mb-8">
                            <label for="check_out_date" class="form-label fs-6 text-gray-600 mb-3">Checkout Date</label>
                            <input type="text" name="check_out_date" id="check_out_date" class="form-control form-control-sm" autocomplete="off" />
                        </div>
                    </div>
                    <div class="row">

                        <div class=" col-md-3 col-sm-12 mb-8 fv-row">
                            <label for="registration_status" class="required form-label fs-6 text-gray-600 mb-3">Registration Status</label>
                            <select name="registration_status" id="registration_status" class="form-select  form-select-sm" data-control="select2" data-hide-search="true" aria-label="Select example">
                                <option value="" selected disabled>Please select</option>
                                <option value="Pending">Pending</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Checkin">Checkin</option>
                                <option value="Checkout">Checkout</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="" class="form-label">
                                Join To
                            </label>
                            <select name="join_id" class="form-select form-select-sm" data-control="select2" data-placeholder="Select Patient">
                                <option value="" disabled selected>Select Patient</option>
                                <option value="daw mya">Daw Mya (VRC-001)</option>
                                <option value="ts">U Kyaw (VRC-002)</option>
                                <option value="3">Ma Khine (VRC-003)</option>
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
                                    <option value="{{ $contact->id }}">{{ $contact->first_name ? "$contact->first_name $contact->middle_name $contact->last_name"  : $contact->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label for="company_id" class="required form-label fs-6 text-gray-600 mb-3">Company</label>
                            <select name="company_id" id="company_id" class="form-select form-select-sm" data-control="select2" data-placeholder="Please select">
                                <option></option>
                                @foreach ($contacts as $contact)
                                    <option value="{{ $contact->id }}">{{ $contact->company_name ?? $contact->first_name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-4 col-sm-12 mb-8">
                            <label class="form-label fs-6 text-gray-600 mb-3">remark</label>
                            <textarea name="remark" class="form-control form-control-sm " id="" cols="20" rows="3"></textarea>
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
                                    <div class="accordion-header pt-5 pb-10 d-flex collapsed" data-bs-toggle="collapse" data-bs-target="#patient_info">
                                        <span class="accordion-icon">
                                            <i class="fa-solid fa-circle-chevron-right"></i>
                                        </span>
                                        <h3 class="fs-6 fw-semibold mb-0 ms-4 text-gray-600">Patient Info (Read Only)</h3>
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Body-->
                                    <div id="patient_info" class="fs-6 collapse  ps-10 " data-bs-parent="#patient">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 mb-8">
                                                <label for="ipd_check_in_date" class="form-label fs-6 text-gray-600 mb-3">Name</label>
                                                <input type="text" name="" id="patient_name" class=" form-control form-control-sm" value="" readonly>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-8">
                                                <label for="ipd_check_in_date" class="form-label fs-6 text-gray-600 mb-3">Father Name</label>
                                                <input type="text" name="" id="patient_father_name" class=" form-control form-control-sm" value="" readonly>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-8">
                                                <label for="ipd_check_in_date" class="form-label fs-6 text-gray-600 mb-3">Gender</label>
                                                <input type="text" name="" id="patient_gender" class=" form-control form-control-sm" value="" readonly>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-8">
                                                <label for="ipd_check_in_date" class="form-label fs-6 text-gray-600 mb-3">DOB</label>
                                                <input type="text" name="" id="patient_DOB" class="patient_name form-control form-control-sm" value="" readonly>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mb-8">
                                                <label for="ipd_check_in_date" class="form-label fs-6 text-gray-600 mb-3">Blood Type</label>
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
                    <div class="row">
                        {{-- <div class="col-md-6 col-sm-12 mb-8">
                            <label for="Agency" class="required form-label fs-6 text-gray-600 mb-3">Agency</label>
                            <select name="agency_id" id="Agency" class="form-select" data-control="select2" data-placeholder="Please select">
                                <option></option>
                                @foreach($contacts as $contact)
                                    <option value="{{$contact->id}}">{{ $contact->first_name ? "$contact->first_name $contact->middle_name $contact->last_name" : $contact->company_name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                    <div class="room_info" style="display: none;">
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
                        <div id="roomRegistrationData" class="room-registration-form mb-3">
                            <!--begin::Form group-->
                            <div class="form-group">
                                <div data-repeater-list="roomRegistrationData">
                                    <div data-repeater-item>
                                        <div class="form-group row">
                                            <div class="col-lg-2 col-sm-12 mb-8 fv-row">
                                                <label class="required form-label fs-6 text-gray-600 mb-3">Room Type</label>
                                                <select name="room_type_id" class="form-select form-select-sm room_type_select room_type " data-kt-repeater="select2" data-placeholder="Please select">
                                                    <option></option>
                                                    @php
                                                        $room_types = \App\Models\RoomManagement\RoomType::all();
                                                    @endphp
                                                    @foreach($room_types as $room_type)
                                                        <option value="{{ $room_type->id}}">{{ $room_type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-sm-12 mb-8">
                                                <div class="row">
                                                    <div class="col-12 mb-3 fv-row">
                                                        <label class="required form-label fs-6 text-gray-600 mb-3">Room Rate</label>
                                                        <select  name="room_rate_id"  class="form-select form-select-sm room_type_select room_rate" data-kt-repeater="select2" data-placeholder="Please select">
                                                            <option></option>
                                                            @php
                                                                $room_rates = \App\Models\RoomManagement\RoomRate::all();
                                                            @endphp
                                                            {{-- @foreach($room_rates as $room_rate)
                                                                <option value="{{ $room_type->id}}">{{ $room_rate->rate_name }}</option>
                                                            @endforeach --}}
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="required form-label fs-6 text-gray-600 mb-3">Room Price</label>
                                                        <input type="text" value="" class="form-control form-control-sm rate_amount_before_discount" name="rate_amount_before_discount" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-sm-12 mb-8 ">
                                                <div class="row g-3">
                                                    <div class="col-12 fv-row">
                                                        <label class="required form-label fs-6 text-gray-600 mb-3">Room</label>
                                                        <select class="form-select  form-select-sm room_select room_id " name="room_id" data-kt-repeater="select2" data-placeholder="Please select" >
                                                            @php
                                                                $rooms = \App\Models\RoomManagement\Room::where('status',"Available")->select('id','room_type_id','status','name')->get();
                                                            @endphp
                                                            {{-- @foreach($rooms as $room)
                                                                <option value="{{ $room->id}}" >{{ $room->name }}</option>
                                                            @endforeach --}}
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class=" form-label fs-6 text-gray-600 mb-3">Qty</label>
                                                        <input type="text" value="" class="form-control form-control-sm " name="" readonly>
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-sm-12 mb-8 ">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label class="form-label fs-6 text-gray-600 mb-3">Discount Type</label>
                                                        <select class="form-select  form-select-sm  discount_type" name="discount_type" data-kt-repeater="select2" data-placeholder="Please select" >

                                                            <option value="fixed">Fixed</option>
                                                            <option value="percentage">Percentage</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                            <label class="form-label fs-6 text-gray-600 mb-3">Discount Amount</label>
                                                            <input type="text" class="form-control form-control-sm discount_amount" name="discount_amount">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-sm-12">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label class="form-label fs-6 text-gray-600 mb-3">Price After Discount</label>
                                                        <input type="text" class="form-control form-control-sm price_after_discount" name="rate_amount_after_discount">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fs-6 text-gray-600 mb-3">remark</label>
                                                        <textarea name="remark" class="form-control form-control-sm" id="" cols="20" rows="3"></textarea>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-sm-12 mb-8">
                                                <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                    <i class="fa-solid fa-trash"></i>
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                        <div class="separator my-10"></div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Form group-->

                            <!--begin::Form group-->
                            <div class="form-group mb-8">
                                <a href="javascript:;" data-repeater-create class="btn btn-smn btn-light-primary">
                                    <i class="fa-solid fa-plus"></i>
                                    Add
                                </a>
                            </div>
                            <!--end::Form group-->
                        </div>
                    </div>

                    <!--end::Repeater-->
                    {{-- <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label for="remark" class="form-label fs-6 text-gray-600 mb-3">Remark</label>
                            <textarea name="remark" id="remark" class="form-control form-control-sm" placeholder="Remark" style="resize: none;"></textarea>
                        </div>
                    </div> --}}
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" data-kt-hosptial-action="submit">Save</button>
                </div>
            </div>
            <!--end::Card-->
        </form>
    </div>
    <!--end::container-->
</div>
<!--end::Content-->

@endsection

@push('scripts')
<script src="assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
<script src="{{asset('customJs/hospital/validation.js')}}"></script>
@include('App.Js.registrationJs')
@endpush
