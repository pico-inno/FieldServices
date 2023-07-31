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
            <div class="card card-p-4 card-flush">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-8">
                            <label for="registration_type" class="required form-label">Registration Type</label>
                            <select name="registration_type" id="registration_type" class="form-select" data-control="select2" data-placeholder="Please select Patient Type">
                                <option value="OPD" @selected($data->registration_type=='OPD')>Outpatient Department (OPD)</option>
                                <option value="IPD" @selected($data->registration_type=='IPD')>In-patient Department (IPD)</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-8 fv-row">
                            <label for="patient_id" class="required form-label">Patient</label>
                            <select name="patient_id" id="patient_id" class="form-select" data-control="select2" data-placeholder="Please select" data-allow-clear="true">
                                <option></option>
                                @foreach ($contacts as $contact)
                                    <option value="{{ $contact->id }}" @selected($data->patient_id==$contact->id)>{{ $contact->first_name ? "$contact->first_name $contact->middle_name $contact->last_name" : $contact->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-12 ">
                            <!--begin::Accordion-->
                            <div class="accordion accordion-icon-toggle" id="patient">
                                <!--begin::Item-->
                                <div class="mx-5">
                                    <!--begin::Header-->
                                    <div class="accordion-header pt-5 pb-10 d-flex" data-bs-toggle="collapse" data-bs-target="#patient_info">
                                        <span class="accordion-icon">
                                            <i class="fa-solid fa-circle-chevron-right"></i>
                                        </span>
                                        <h3 class="fs-4 fw-semibold mb-0 ms-4 text-gray-600">Patient Info (Read Only)</h3>
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Body-->
                                    <div id="patient_info" class="fs-6   ps-10 show" data-bs-parent="#patient">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 mb-8">
                                                <label for="ipd_check_in_date" class="form-label">Name </label>
                                                <input type="text" name="" id="patient_name" class=" form-control" value="{{$data->patient['first_name']}}" readonly>
                                            </div>
                                            <div class="col-md-6 col-sm-12 mb-8">
                                                <label for="ipd_check_in_date" class="form-label">Father Name</label>
                                                <input type="text" name="" id="patient_father_name" class=" form-control" value="" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 mb-8">
                                                <label for="ipd_check_in_date" class="form-label">Gender</label>
                                                <input type="text" name="" id="patient_gender" class=" form-control" value="" readonly>
                                            </div>
                                            <div class="col-md-6 col-sm-12 mb-8">
                                                <label for="ipd_check_in_date" class="form-label">DOB</label>
                                                <input type="text" name="" id="patient_DOB" class="patient_name form-control" value="{{$data->patient['dob']}}" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 mb-8">
                                                <label for="ipd_check_in_date" class="form-label">Blood Type</label>
                                                <input type="text" name="" id="patient_blood_type" class="patient_name form-control" value="AB" readonly>
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
                        <div class="col-md-6 col-sm-12 mb-8">
                            <label for="company_id" class="required form-label">Company</label>
                            <select name="company_id" id="company_id" class="form-select" data-control="select2" data-placeholder="Please select">
                                <option></option>
                                @foreach ($contacts as $contact)
                                    <option value="{{ $contact->id }}"  @selected($data->company_id==$contact->id)>{{ $contact->company_name ?? $contact->first_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-8 fv-row">
                            <label for="registration_status" class="required form-label">Registration Status</label>
                            <select name="registration_status" id="registration_status" class="form-select" data-control="select2" data-hide-search="true" aria-label="Select example">
                                <option value="" selected disabled>Please select</option>
                                <option value="Pending" @selected($data->registration_status=="pending") >Pending</option>
                                <option value="Cancelled" @selected($data->registration_status=="cancelled")>Cancelled</option>
                                <option value="Confirmed" @selected($data->registration_status=="confirmed")>Confirmed</option>
                                <option value="Checkin" @selected($data->registration_status=="checkin")>Checkin</option>
                                <option value="Checkout" @selected($data->registration_status=="checkout")>Checkout</option>
                            </select>
                        </div>
                        {{-- <div class="col-md-6 col-sm-12 mb-8">
                            <label for="Agency" class="required form-label">Agency</label>
                            <select name="agency_id" id="Agency" class="form-select" data-control="select2" data-placeholder="Please select">
                                <option></option>
                                @foreach($contacts as $contact)
                                    <option value="{{$contact->id}}"  @selected($data->agency_id==$contact->id)>{{ $contact->first_name ? "$contact->first_name $contact->middle_name $contact->last_name" : $contact->company_name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-8">
                            <label for="opd_check_in_date" class="form-label">OPD Checkin Date</label>
                            <input type="text" name="opd_check_in_date" id="opd_check_in_date" class="form-control" autocomplete="off"  value="{{$data->opd_check_in_date}}" />
                        </div>
                        <div class="col-md-6 col-sm-12 mb-8">
                            <label for="ipd_check_in_date" class="form-label">IPD Checkin Date</label>
                            <input type="text" name="ipd_check_in_date" id="ipd_check_in_date" class="form-control" value="{{now()}}" autocomplete="off" value="{{$data->ipd_check_in_date}}" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-8">
                            <label for="check_out_date" class="form-label">Checkout Date</label>
                            <input type="text" name="check_out_date" id="check_out_date" class="form-control" autocomplete="off" value="{{$data->check_out_date}}" />
                        </div>
                        {{-- <div class="col-md-6 col-sm-12 mb-8 fv-row">
                            <label for="registration_status" class="required form-label">Registration Status</label>
                            <select name="registration_status" id="registration_status" class="form-select" data-control="select2" data-hide-search="true" aria-label="Select example">
                                <option value="" selected disabled>Please select</option>
                                <option value="Pending" @selected($data->registration_status=="pending") >Pending</option>
                                <option value="Cancelled" @selected($data->registration_status=="cancelled")>Cancelled</option>
                                <option value="Confirmed" @selected($data->registration_status=="confirmed")>Confirmed</option>
                                <option value="Checkin" @selected($data->registration_status=="checkin")>Checkin</option>
                                <option value="Checkout" @selected($data->registration_status=="checkout")>Checkout</option>
                            </select>
                        </div> --}}
                    </div>

                    <div class="separator my-5 mb-10 border-gray-230"></div>
                    <div id="roomRegistrationData" class="booking-form mb-3" style="{{$data->registration_type=="OPD"?'display: none;':''}};">
                        <!--begin::Form group-->
                            <div class="form-group">
                                    <div data-repeater-list="roomRegistrationData">
                                        @foreach ($roomDatas as $roomData)
                                            <div data-repeater-item>
                                                <div class="form-group row">
                                                    <div class="col-md-3 col-sm-12 mb-8">
                                                        <label class="required form-label">Room Type</label>
                                                        <select name="room_type_id" class="form-select room_type_select" data-kt-repeater="select2" data-placeholder="Please select">
                                                            <option></option>
                                                            @php
                                                                $room_types = \App\Models\RoomManagement\RoomType::all();
                                                            @endphp
                                                            @foreach($room_types as $room_type)
                                                                <option value="{{ $room_type->id}}" @selected($roomData->room_type_id==$room_type->id)>{{ $room_type->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="room_data_id" value="{{$roomData->id}}">
                                                    </div>
                                                    <div class="col-md-3 col-sm-12 mb-8 ">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <label class="form-label">Room</label>
                                                                <select class="form-select room_select" name="room_id" data-kt-repeater="select2" data-placeholder="Please select" >
                                                                    <option></option>
                                                                    @php
                                                                        $rooms = \App\Models\RoomManagement\Room::all();
                                                                    @endphp
                                                                    @foreach($rooms as $room)
                                                                        <option value="{{ $room->id}}"   @selected($roomData->room_id==$room->id)>{{ $room->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-12">
                                                                <label for="form-label">Qty</label>
                                                                <input type="number" name="room_quantity" class="form-control" value="{{$roomData->room_quantity}}" placeholder="1"  />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-12 mb-8">
                                                        <label class="form-label">remark</label>
                                                        <textarea name="remark" class="form-control " id="" cols="20" rows="3">{{$roomData->remark}}</textarea>
                                                        {{-- <input type="number" name="max_occupancy" id="max_occupancy" class="form-control" value="" placeholder="0" readonly /> --}}
                                                    </div>
                                                    <div class="col-md-3 col-sm-12 mb-8">
                                                        <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                            <i class="fa-solid fa-trash"></i>
                                                            Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if (count($roomDatas)==0)
                                            <div data-repeater-item>
                                                <div class="form-group row">
                                                    <div class="col-md-3 col-sm-12 mb-8">
                                                        <label class="required form-label">Room Type</label>
                                                        <select name="room_type_id" class="form-select room_type_select" data-kt-repeater="select2" data-placeholder="Please select">
                                                            <option></option>
                                                            @php
                                                                $room_types = \App\Models\RoomManagement\RoomType::all();
                                                            @endphp
                                                            @foreach($room_types as $room_type)
                                                                <option value="{{ $room_type->id}}">{{ $room_type->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 col-sm-12 mb-8 ">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <label class="form-label">Room</label>
                                                                <select class="form-select room_select" name="room_id" data-kt-repeater="select2" data-placeholder="Please select" onchange="updateMaxOccupancy(this)">
                                                                    <option></option>
                                                                    @php
                                                                        $rooms = \App\Models\RoomManagement\Room::all();
                                                                    @endphp
                                                                    @foreach($rooms as $room)
                                                                        <option value="{{ $room->id}}" data-max-occupancy="{{ $room->max_occupancy }}">{{ $room->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-12">
                                                                <label for="form-label">Qty</label>
                                                                <input type="number" name="room_quantity" id="max_occupancy" class="form-control" value="1" placeholder="1"  />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-12 mb-8">
                                                        <label class="form-label">remark</label>
                                                        <textarea name="remark" class="form-control " id="" cols="20" rows="3"></textarea>
                                                        {{-- <input type="number" name="max_occupancy" id="max_occupancy" class="form-control" value="" placeholder="0" readonly /> --}}
                                                    </div>
                                                    <div class="col-md-3 col-sm-12 mb-8">
                                                        <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                            <i class="fa-solid fa-trash"></i>
                                                            Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                            </div>

                        <!--end::Form group-->

                        <!--begin::Form group-->
                        <div class="form-group mb-8">
                            <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                <i class="fa-solid fa-plus"></i>
                                Add
                            </a>
                        </div>
                        <!--end::Form group-->
                    </div>
                    <!--end::Repeater-->
                    {{-- <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea name="remark" id="remark" class="form-control" placeholder="Remark" style="resize: none;">{{$data->remark}}</textarea>
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
<script src="{{asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js')}}"></script>
<script src="{{asset('customJs/hospital/validation.js')}}"></script>
<script>
    let patients=@json($contacts);
    $('#patient_id').on('change', function() {
        if(this.value != '' && this.value!= undefined ){
            $('#patient_info').addClass('show')
            $('.accordion-header').removeClass('collapsed')
            let selectedPatient=patients.filter((patient)=>{
                return patient.id==this.value;
            })
            let p=selectedPatient[0];
            $('#patient_name').val(p.first_name);
            $('#patient_DOB').val(p.dob);
        }
    });

    $('#registration_type').on('change',function(){
        if(this.value==='OPD'){
            $('.booking-form').hide();
        }else{
            $('.booking-form').show();

        };
    })

    $('#roomRegistrationData').repeater({
        initEmpty: false,

        defaultValues: {
            'text-input': 'foo'
        },

        show: function () {
            $(this).slideDown();

            // Re-init select2
            $(this).find('[data-kt-repeater="select2"]').select2();
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        },

        ready: function(){
            // Init select2
            $('[data-kt-repeater="select2"]').select2();
        }
    });

    updateMaxOccupancy($('#roomRegistrationData [name=room_id]'));

    $('#roomRegistrationData').on('click', '[data-repeater-create]', function() {
        setTimeout(function() {
            var newRepeater = $('#roomRegistrationData [data-repeater-item]:last-child');

            // Update onchange event for room selection in the new repeater
            newRepeater.find('[name=room_id]').on('select2:select', function() {
                updateMaxOccupancy($(this));
            });

            updateMaxOccupancy(newRepeater.find('[name=room_id]'));
        }, 100);
    });

    function updateMaxOccupancy(selectElement) {
        var maxOccupancyInput = $(selectElement).closest('[data-repeater-item]').find('#max_occupancy');
        var selectedOption = $(selectElement).find('option:selected');
        var maxOccupancy = selectedOption.data('max-occupancy');
        // console.log(maxOccupancy);
        maxOccupancyInput.val(maxOccupancy).trigger('change');
    }

    // Get the "Add" button element
    var addButton = $('[data-repeater-create]');
    // Get the "Delete" button element
    var deleteButton = $('[data-repeater-delete]');
    // Get the "Add" button element from group-booking-form

    addButton.on('click', function() {

        // Get the number of rows in the repeater
        var numRows = $('[data-repeater-item]').length;
        $('.room-qty').val(numRows);
        console.log('Number of rows: ' + numRows);
    });

    $(document).on('click', '[data-repeater-delete]', function() {
        var numItems = $('[data-repeater-item]').length - 1;
        $('.room-qty').val(numItems);
        console.log('Number of del rows: ' + numItems);
    });

    // function updateMaxOccupancy() {
    //     // Get the selected room's max occupancy value
    //     var maxOccupancy = $('.room option:selected').data('max-occupancy');
    //     // console.log(maxOccupancy);
    //     // Set the max occupancy input box value
    //     $('.max_occupancy').val(maxOccupancy);
    // }

    // deleteButton.on('click', function() {
    //     // var numRows = $(['data-repeater-item']).length;
    //     var repeaterList = $(this).closest('[data-repeater-list]');

    //     var currentRowCount = repeaterList.find('[data-repeater-item]').length;
    //     // console.log(repeaterList.attr('data-repeater-list', 'kt_docs_repeater_basic_' + currentRowCount));


    //     console.log('No of rows: ' + currentRowCount);
    // })

    tempusDominus.extend(tempusDominus.plugins.customDateFormat);

    var datepickerIds = [ "check_out_date","opd_check_in_date","ipd_check_in_date"];

    datepickerIds.forEach(function(id) {
        $(`#${id}`).flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    });

    datepickerIds.forEach(function(id) {
        var currentDate = new Date();
        var datepicker = document.getElementById(id);
        datepicker.placeholder = currentDate.toLocaleString();

        var options = {
            autoClose: true
        };
    });

    $('#group-booking').on('click', function() {
        console.log('click');
        $('.group-booking-form').toggleClass('hide');
        $('.simple-booking-form').toggleClass('hide');
    });

    // Check or unchecked group booking checkbox and show and hide room col and num of rooms col
    // const groupBooking = document.getElementById('group-booking');
    // const roomColumn = document.querySelector('.room-col');
    // const numOfRoomColumn = document.querySelector('.number-of-room-col');
    // groupBooking.addEventListener('change', function() {
    //     numOfRoomColumn.classList.toggle('hide');
    //     roomColumn.classList.toggle('hide');
    // })

    // add new room row when clicked Add Room button
    let roomRow = document.getElementById('room-row');
    let addRoomRows = document.querySelectorAll('.add-room-row');
    let newroom = `
        <div class="row">
            <div class="col-md-3 col-sm-12 mb-8">

                <select name="room_type_id" id="room_type_id" class="form-select" aria-label="Select example">
                    <option value="" selected disabled>Please select</option>
                    <option value="1">Double</option>
                </select>
            </div>
            <div class="col-md-4 col-sm-12 mb-8 room-col">

                <select name="room_id" id="room_id" class="form-select" aria-label="Select example">
                    <option value="" selected disabled>Please select</option>
                    <option value="1">Double</option>
                </select>
            </div>
            <div class="col-md-4 col-sm-12 mb-8 number-of-room-col hide">

                <input type="number" class="form-control" placeholder="No. of rooms" value="" />
            </div>
            <div class="col-md-4 col-sm-12 mb-8">

                <input type="number" class="form-control" value="" />
            </div>

                <div class="col-md-4 col-sm-12 d-flex mb-8 buttons-col">
                    <button type="button" class="btn btn-primary btn-sm me-4 add-room-row">Add Room</button>
                    <button type="button" class="btn btn-danger btn-sm delete-room-row">Cancel</button>
                </div>

        </div>
    `;



</script>
@endpush
