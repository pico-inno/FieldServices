@extends('App.main.navBar')
@section('styles')
<style>
    .custom-select2 {
        width: 135px;
    }

    #delete_room_row {
        cursor: not-allowed;
        opacity: 0.5;
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
        /* Firefox */
    }
</style>
@endsection
@section('reservation_icon','active')
@section('reservation_show','active show')
@section('reservation_add_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Add Reservation</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Reservation</li>
    <li class="breadcrumb-item text-dark">Add Reservation</li>
</ul>
<!--end::Breadcrumb-->
@endsection


@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        @if(session('error'))
        <div class="alert alert-dismissible bg-light-danger d-flex align-items-center flex-sm-row p-5 mb-10">
            <span class="text-danger">{{ session('error') }}</span>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        @endif
        @php
            $newContactId = session('newContactId');
            $newContactName = session('newContactName');
            $newCompanyName = session('newCompanyName');
        @endphp
        <form action="{{route('reservation.store')}}" method="POST">
            @csrf

            <!--begin::Card-->
            <div class="card card-p-4 card-flush">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label class="form-label">Guest</label>
                            <div class="input-group flex-nowrap">
                                <select name="guest_id" class="form-select rounded-end-0 fs-7" data-control="select2" data-placeholder="Please select" data-allow-clear="true">
                                    <option></option>
                                    @foreach($contacts as $contact)
                                    @if(empty($contact->company_name))
                                    <option value="{{$contact->id}}">
                                        {{$contact->prefix}} {{$contact->first_name}} {{$contact->middle_name}} {{$contact->last_name}}
                                    </option>
                                    @endif
                                    @endforeach
                                </select> 
                                <button type="button" class="input-group-text add_contact_button" id="add_contact_button" data-bs-toggle="modal" data-bs-target="#add_contact_modal" data-href="{{url('customers/quickadd')}}" data-form-type="{{ $formType }}">
                                    <i class="fa-solid fa-plus text-primary"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label class="form-label">Company</label>
                            <div class="input-group flex-nowrap">
                                <select name="company_id" class="form-select rounded-end-0 fs-7" data-control="select2" data-placeholder="Please select" data-allow-clear="true">
                                    <option></option>
                                    @foreach($contacts as $contact)
                                    @if(!empty($contact->company_name))
                                    <option value="{{$contact->id}}">
                                        {{$contact->company_name}}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                                <button type="button" class="input-group-text add_contact_button" data-bs-toggle="modal" data-bs-target="#add_contact_modal" data-href="{{url('customers/quickadd')}}" data-form-type="{{ $formType }}">
                                    <i class="fa-solid fa-plus text-primary"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label for="check_in_date" class="required form-label">Checkin Date</label>
                            <input type="text" name="check_in_date" id="check_in_date" class="form-control fs-7" autocomplete="off" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label for="check_out_date" class="required form-label">Checkout Date</label>
                            <input type="text" name="check_out_date" id="check_out_date" class="form-control fs-7" autocomplete="off" />
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label for="reservation_status" class="required form-label">Reservation Status</label>
                            <select name="reservation_status" id="reservation_status" class="form-select fs-7" data-control="select2" data-placeholder="Please select" data-hide-search="true" required>
                                <option value=""></option>
                                <option value="Pending">Pending</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="No_Show">No Show</option>
                                <option value="Checkin">Checkin</option>
                                <option value="Checkout">Checkout</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label class="form-label">Rooms</label>
                            <input type="number" class="form-control fs-7 room-qty" value="1" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mt-4 mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input flexCheckChecked" name="group_reservation"  value="GroupReserved" id="flexCheckChecked" />
                                <label class="form-check-label text-gray-700" for="flexCheckChecked">
                                    Group Reservation
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4 mb-4 reservationSelect" id="reservationSelect" style="display: none;">
                            <label for="parentReservation" class="form-label">Select Parent Reservation:</label>
                            <select id="parentReservation" name="joint_reservation_id" class="form-select fs-7" data-control="select2" data-placeholder="Please select" data-hide-search="true">
                                <option></option>
                                @foreach($reservations as $reservation)
                                <option value="{{$reservation->id}}">
                                    @if(!empty( $reservation->contact))
                                    {{ $reservation->contact['first_name'] ?? '' }} {{ $reservation->contact['middle_name'] ?? '' }} {{ $reservation->contact['last_name'] ?? '' }}
                                    ({{ $reservation->reservation_code }}) 
                                    @else
                                    {{$reservation->company['company_name'] ?? ''}}  ({{ $reservation->reservation_code }})
                                    @endif                       
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive mb-4">
                        <table class="table table-row-dashed fs-6 gy-4" id="room_added_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-600">
                                    <th>Room Type</th>
                                    <th>Room Rate</th>
                                    <th>Room</th>
                                    <th class="min-w-150px">Checkin Date</th>
                                    <th class="min-w-150px">Checkout Date</th>
                                    {{-- <th class="min-w-125px">Quantity</th>
                                    <th class="min-w-125px">Amount(Before discount)</th>
                                    <th>Discount Type</th>
                                    <th class="min-w-125px">Discount Amount</th>
                                    <th class="min-w-125px">Amount(Inc. discount)</th>
                                    <th class="min-w-125px">Subtotal</th> --}}
                                    <th><i class="fa-solid fa-trash text-danger"></i></th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-700">
                                <tr>
                                    <td>
                                        <select name="room_type_id[]" class="form-select rounded-0 fs-7 custom-select2 room_type" data-control="select2" data-placeholder="Please select">
                                            <option></option>
                                            @php
                                            $room_types = \Modules\RoomManagement\Entities\RoomType::all();
                                            @endphp
                                            @foreach($room_types as $room_type)
                                            <option value="{{ $room_type->id}}">{{ $room_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="room_rate_id[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                                            <option></option>
                                            @php
                                            $room_rates = \Modules\RoomManagement\Entities\RoomRate::all();
                                            @endphp
                                            @foreach($room_rates as $room_rate)
                                            <option value="{{ $room_rate->id}}" data-rate-amount="{{ $room_rate->rate_amount }}" data-room-type-id="{{ $room_rate->room_type_id }}">{{ $room_rate->rate_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="room_id[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                                            <option></option>
                                            @php
                                            $rooms = \Modules\RoomManagement\Entities\Room::where('status', 'Available')->get();
                                            @endphp
                                            @foreach($rooms as $room)
                                            <option value="{{ $room->id}}" data-max-occupancy="{{ $room->max_occupancy }}" data-room-type-id="{{ $room->room_type_id }}" data-room-status="{{$room->status}}">{{ $room->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="room_check_in_date[]" class="room_check_in_date form-control rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
                                    </td>
                                    <td>
                                        <input type="text" name="room_check_out_date[]" class="room_check_out_date form-control rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
                                    </td>
                                    {{-- <td>
                                        <!--begin::Dialer-->
                                        <div class="input-group w-md-150px w-sm-300px" data-kt-dialer="true" data-kt-dialer-min="1" data-kt-dialer-max="50000" data-kt-dialer-step="1">

                                            <!--begin::Decrease control-->
                                            <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" data-kt-dialer-control="decrease">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <!--end::Decrease control-->

                                            <!--begin::Input control-->
                                            <input type="number" name="qty[]" class="form-control rounded-0 fs-7" value="1" data-kt-dialer-control="input" placeholder="Amount" readonly />
                                            <!--end::Input control-->

                                            <!--begin::Increase control-->
                                            <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" data-kt-dialer-control="increase">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                            <!--end::Increase control-->
                                        </div>
                                        <!--end::Dialer-->
                                        <!-- <input type="number" name="qty[]" id="total-room-qty" class="form-control rounded-0 fs-7" value="1" /> -->
                                    </td>
                                    <!-- <td>
                                        <input type="number" id="max_occupancy" class="form-control rounded-0 fs-7" value="" placeholder="0" readonly />
                                    </td> -->
                                    <td>
                                        <input type="number" name="before_discount_amount[]" id="rate_amout_bef_discount" class="form-control rounded-0 fs-7" value="" placeholder="0" readonly />
                                    </td>
                                    <td>
                                        <select name="discount_type[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                                            <option></option>
                                            <option value="fixed">Fixed</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="discount_amount[]" class="form-control rounded-0 fs-7" value="" placeholder="0" />
                                    </td>
                                    <td>
                                        <input type="number" name="after_discount_amount[]" class="form-control rounded-0 fs-7" value="" placeholder="0" readonly />
                                    </td>
                                    <td>
                                        <input type="number" name="subtotal_amount[]" class="form-control rounded-0 fs-7" value="" placeholder="0" readonly />
                                    </td> --}}
                                    <td><button type="button" class="btn btn-light-danger btn-sm" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                            <!--end::Table body-->
                        </table>
                    </div>
                    {{-- <br>
                    <div class="row">
                        <div class="d-flex justify-content-end">
                            <span class="fw-bold fs-6 me-2">Total Amount:</span>
                            <span id="total_amount" class="fs-6"> 0</span>
                        </div>
                    </div> --}}
                    <div class="row mb-8">
                        <div class="d-flex">
                            <button type="button" class="btn btn-light-primary btn-sm me-3" id="add_room_row"><i class="fa-solid fa-plus"></i></button>
                            <button type="button" class="btn btn-light-danger btn-sm disable delete_room_row" id="delete_room_row"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="mb-7 mt-3 col-12 col-md-3">
                            <label class="form-label fs-6 fw-semibold" for="">
                                Discount Type
                            </label>
                            <select name="" id="total_discount_type" class="form-select form-select-sm" data-control="select2">
                                <option value="fixed">Fixed</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-3">
                            <label class="form-label fs-6 fw-semibold" for="">
                                Discount Amount
                            </label>
                            <input type="text" class="form-control input_number form-control-sm" id="total_discount_amount" name="" value="0">
                        </div>
                        <div class="fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-end align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Discount :(=)Ks
                            </span>
                            <input type="text" class="form-control form-control-sm input_number max-w-100px" id="discount" disabled>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="my-15 fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Total Sale Amount:(=)Ks
                            </span>
                            <input type="text" class="form-control form-control-sm input_number max-w-100px" name="roomSale[total_sale_amount]" id="total_sale_amount" value="{{old('balance_amount',0)}}">
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="my-15 fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Paid Amount:(=)Ks
                            </span>
                            <input type="text" class="form-control form-control-sm input_number max-w-100px" name="roomSale[paid_amount]" id="total_paid_amount" value="{{old('balance_amount',0)}}">
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="my-15 fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Balance Amount:(=)Ks
                            </span>
                            <input type="text" class="form-control form-control-sm input_number max-w-100px" name="roomSale[balance_amount]" id="total_balance_amount" value="{{old('balance_amount',0)}}">
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea name="remark" id="remark" class="form-control fs-7" placeholder="Remark" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="form_type" value="{{$formType}}">

                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </div>
            <!--end::Card-->
        </form>
    </div>
    <!--end::container-->
    @include('App.contact_management.customers.quickAddContact')

    <!-- <div class="modal fade" tabindex="-1" role="dialog" id="add_contact_modal">
   </div> -->
</div>
<!--end::Content-->
@endsection

@push('scripts') 

<script src="{{asset('customJs/reservation.js')}}"></script>
<script>
    $(document).on('click', '#add_room_row', function() {
        // console.log('click');
        var newRoomRow = `
            <tr id="new_row">
                <td>
                    <select name="room_type_id[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                        <option></option>
                        @php
                            $room_types = \Modules\RoomManagement\Entities\RoomType::all();
                        @endphp
                        @foreach($room_types as $room_type)
                            <option value="{{ $room_type->id}}">{{ $room_type->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="room_rate_id[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                        <option></option>
                        @php
                            $room_rates = \Modules\RoomManagement\Entities\RoomRate::all();
                        @endphp
                        @foreach($room_rates as $room_rate)
                            <option value="{{ $room_rate->id}}" data-rate-amount="{{ $room_rate->rate_amount }}" data-room-type-id="{{ $room_rate->room_type_id }}">{{ $room_rate->rate_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="room_id[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                        <option></option>
                        @php
                            $rooms = \Modules\RoomManagement\Entities\Room::where('status', 'Available')->get();
                        @endphp
                        @foreach($rooms as $room)
                            <option value="{{ $room->id}}" data-max-occupancy="{{ $room->max_occupancy }}" data-room-type-id="{{ $room->room_type_id }}" data-room-status="{{$room->status}}">{{ $room->name }}</option>
                        @endforeach       
                    </select>
                </td>
                <td>
                    <input type="text" name="room_check_in_date[]" class="room_check_in_date form-control rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
                </td>
                <td>
                    <input type="text" name="room_check_out_date[]" class="room_check_out_date form-control rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
                </td>
                <td><button type="button" class="btn btn-light-danger btn-sm" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button></td>
            </tr>
        `;

        $('#room_added_table tbody').append(newRoomRow);

        // var container = $('.table-responsive');
        // var scrollTo = $('#new_row');
        // container.scrollTop(scrollTo.offset().top - container.offset().top + container.scrollTop());

        // Initialize the date pickers for the new row
        new tempusDominus.TempusDominus(checkInInputs[checkInInputs.length - 1], {
            localization: {
                locale: "en",
                format: "dd/MM/yyyy, hh:mm T",
            }
        });

        new tempusDominus.TempusDominus(checkOutInputs[checkOutInputs.length - 1], {
            localization: {
                locale: "en",
                format: "dd/MM/yyyy, hh:mm T",
            }
        });

        $('#room_added_table tbody select[data-control="select2"]').select2();

        initializeDialer();

        if ($('#room_added_table tbody tr').length > 1) {
            $('#delete_room_row').removeClass('disable');
            $('#delete_room_row').css({
                'cursor': 'pointer',
                'opacity': 1
            });
        }

        var numOfRooms = $('#room_added_table tbody tr').length;
        // console.log(numOfRooms);

        $('.room-qty').val(numOfRooms);
    });
    
    $(document).on('change', 'input[name="group_reservation"]', function(e) {
        console.log('checked');
        if ($(this).is(':checked')) {
            $('.reservationSelect').show();
        } else {
            $('.reservationSelect').hide();
        }
    });

    var newContactId = '{{ $newContactId ?? "" }}';
    var newContactName = '{{ $newContactName ?? "" }}';
    var newCompanyName = '{{ $newCompanyName ?? ""}}';

    var option = $('<option>');

    if (newContactName) {
        var selectElement = $('select[name="guest_id"]');
        
        if (selectElement.find('option[value="' + newContactId + '"]').length === 0) {
            option.val(newContactId).text(newContactName).attr('selected', 'selected');
            selectElement.append(option).val(newContactId).trigger('change');
        } else {
            selectElement.val(newContactId).trigger('change');
        }

    } else if (newCompanyName) {
        var selectElement = $('select[name="company_id"]');
        
        if (selectElement.find('option[value="' + newContactId + '"]').length === 0) {
            option.val(newContactId).text(newCompanyName).attr('selected', 'selected');
            selectElement.append(option).val(newContactId).trigger('change');
        } else {
            selectElement.val(newContactId).trigger('change');
        }

    }
             
</script>

@endpush