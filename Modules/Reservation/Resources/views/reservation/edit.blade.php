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
</style>
@endsection
@section('reservation_icon','active')
@section('reservation_show','active show')
@section('reservation_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Edit Reservation</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Reservation</li>
    <li class="breadcrumb-item text-dark">Edit Reservation</li>
</ul>
<!--end::Breadcrumb-->
@endsection
@php
$contacts = \App\Models\Contact\Contact::where('type', 'Customer')->get();
$room_reservations = \Modules\Reservation\Entities\RoomReservation::where('reservation_id', $reservation->id)->get();

$currentDate = date('d/m/Y, h:i A');

@endphp

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
        <form action="{{route('reservation.update', $reservation->id)}}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit">

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
                                    <option value="{{$contact->id}}" {{ old('guest_id', $reservation->guest_id) == $contact->id ? 'selected' : ''}}>
                                        {{$contact->prefix}} {{$contact->first_name}} {{$contact->middle_name}} {{$contact->last_name}}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                                <button type="button" class="input-group-text add_contact_button" id="add_contact_button" data-bs-toggle="modal" data-bs-target="#add_contact_modal" data-href="{{url('customers/quickadd')}}" data-form-type="{{ $formType }}" data-reservation-id="{{$reservation->id}}">
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
                                    <option value="{{$contact->id}}" {{ old('company_id', $reservation->company_id) == $contact->id ? 'selected' : ''}}>
                                        {{$contact->company_name}}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                                <button type="button" class="input-group-text add_contact_button" id="add_contact_button" data-bs-toggle="modal" data-bs-target="#add_contact_modal" data-href="{{url('customers/quickadd')}}" data-form-type="{{ $formType }}" data-reservation-id="{{$reservation->id}}">
                                    <i class="fa-solid fa-plus text-primary"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label for="check_in_date" class="required form-label">Checkin Date</label>
                            <input type="text" name="check_in_date" id="check_in_date" value="{{ old('check_in_date', !empty($reservation->check_in_date) ? date('d/m/Y, h:i A', strtotime($reservation->check_in_date)) : $currentDate) }}" class="form-control fs-7" autocomplete="off" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label for="check_out_date" class="required form-label">Checkout Date</label>
                            <input type="text" name="check_out_date" id="check_out_date" value="{{ old('check_out_date', !empty($reservation->check_out_date) ? date('d/m/Y, h:i A', strtotime($reservation->check_out_date)) : $currentDate) }}" class="form-control fs-7" autocomplete="off" />
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label for="reservation_status" class="required form-label">Reservation Status</label>
                            <select name="reservation_status" id="reservation_status" class="form-select fs-7" data-control="select2" data-placeholder="Please select" data-hide-search="true">
                                <option></option>
                                <option value="Pending" {{$reservation->reservation_status == 'Pending' ? 'selected' : ''}}>Pending</option>
                                <option value="Cancelled" {{$reservation->reservation_status == 'Cancelled' ? 'selected' : ''}}>Cancelled</option>
                                <option value="Confirmed" {{$reservation->reservation_status == 'Confirmed' ? 'selected' : ''}}>Confirmed</option>
                                <option value="No_Show" {{$reservation->reservation_status == 'No_Show' ? 'selected' : ''}}>No Show</option>
                                <option value="Checkin" {{$reservation->reservation_status == 'Checkin' ? 'selected' : ''}}>Checkin</option>
                                <option value="Checkout" {{$reservation->reservation_status == 'Checkout' ? 'selected' : ''}}>Checkout</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-8">
                            <label class="form-label">Rooms</label>
                            <input type="number" class="form-control fs-7 room-qty" value="" />
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-md-4 mt-4 mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input flexCheckChecked" name="group_reservation"  value="GroupReserved" id="flexCheckChecked" @if(!empty($reservation->joint_reservation_id)) checked @endif />
                                <label class="form-check-label text-gray-700" for="flexCheckChecked">
                                    Group Reservation
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4 mb-4 reservationSelect" id="reservationSelect" style="display: none;">
                            <label for="parentReservation" class="form-label">Select Parent Reservation:</label>
                            <select id="parentReservation" name="joint_reservation_id" class="form-select fs-7" data-control="select2" data-placeholder="Please select" data-hide-search="true">
                                <option></option>
                                @php
                                    $reservation_edits = \Modules\Reservation\Entities\Reservation::whereNull('joint_reservation_id')->with('contact', 'company')->get();
                                @endphp
                                @foreach($reservation_edits as $reservation_edit)
                                    <option value="{{ $reservation_edit->id }}" @selected($reservation_edit->id==$reservation->joint_reservation_id)>
                                    @if(!empty( $reservation_edit->contact))
                                    {{ $reservation_edit->contact['first_name'] ?? '' }} {{ $reservation_edit->contact['middle_name'] ?? '' }} {{ $reservation_edit->contact['last_name'] ?? '' }}
                                    ({{ $reservation_edit->reservation_code }}) 
                                    @else
                                    {{$reservation_edit->company['company_name'] ?? ''}}  ({{ $reservation_edit->reservation_code }})
                                    @endif       
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="table-responsive mb-4">
                        <table class="table table-row-dashed fs-6 gy-4" id="room_added_table" data-reservation="{{ json_encode($reservation) }}">
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
                                @foreach($reservation->room_reservations as $room_reservation)
                                <tr>
                                    <td>
                                        <select name="room_type_id[]" class="form-select rounded-0 fs-7 custom-select2 room_type" data-control="select2" data-placeholder="Please select">
                                            <option></option>
                                            @php
                                            $room_types = \Modules\RoomManagement\Entities\RoomType::all();
                                            @endphp
                                            @foreach($room_types as $room_type)
                                            <option value="{{ $room_type->id}}" {{ old('$room_reservation->room_type_id ', $room_reservation->room_type_id ) == $room_type->id ? 'selected' : ''}}>
                                                {{ $room_type->name }}
                                            </option>
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
                                            <option value="{{ $room_rate->id}}" data-rate-amount="{{ $room_rate->rate_amount }}" data-room-type-id="{{ $room_rate->room_type_id }}" {{ old('$room_reservation->room_rate_id ', $room_reservation->room_rate_id ) == $room_rate->id ? 'selected' : ''}}>
                                                {{ $room_rate->rate_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="room_id[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                                            <option></option>
                                            @php
                                            $rooms = \Modules\RoomManagement\Entities\Room::all();
                                            @endphp
                                            @foreach($rooms as $room)
                                            <option value="{{ $room->id}}" data-max-occupancy="{{ $room->max_occupancy }}" data-room-type-id="{{ $room->room_type_id }}" data-room-status="{{$room->status}}" {{ old('$room_reservation->room_id ', $room_reservation->room_id ) == $room->id ? 'selected' : ''}}>
                                                {{ $room->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="room_check_in_date[]" value="{{ old('room_check_in_date', !empty($room_reservation->room_check_in_date) ? date('d/m/Y, h:i A', strtotime($room_reservation->room_check_in_date)) : $currentDate) }}" class="room_check_in_date form-control rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
                                    </td>
                                    <td>
                                        <input type="text" name="room_check_out_date[]" value="{{ old('room_check_out_date', !empty($room_reservation->room_check_out_date) ? date('d/m/Y, h:i A', strtotime($room_reservation->room_check_out_date)) : $currentDate) }}" class="room_check_out_date form-control rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
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
                                            <input type="number" name="qty[]" class="form-control rounded-0 fs-7" value="{{ $room_reservation->qty }}" data-kt-dialer-control="input" placeholder="Amount" readonly />
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
                                        <input type="number" id="max_occupancy" class="form-control rounded-0 fs-7 max_occupancy" value="" placeholder="0" readonly />
                                    </td> -->
                                    <td>
                                        <input type="number" name="before_discount_amount[]" id="rate_amout_bef_discount" class="form-control rounded-0 fs-7" value="{{ number_format($room_reservation->before_discount_amount, 0, '', '') }}" placeholder="0" readonly />
                                    </td>
                                    <td>
                                        <select name="discount_type[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                                            <option></option>
                                            <option value="fixed" {{$room_reservation->discount_type == 'fixed' ? 'selected' : ''}}>Fixed</option>
                                            <option value="percentage" {{$room_reservation->discount_type == 'percentage' ? 'selected' : ''}}>Percentage</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="discount_amount[]" class="form-control rounded-0 fs-7" value="{{ number_format($room_reservation->discount_amount, 0, '', '') }}" placeholder="0" />
                                    </td>
                                    <td>
                                        <input type="number" name="after_discount_amount[]" class="form-control rounded-0 fs-7" value="{{ number_format($room_reservation->after_discount_amount, 0, '', '') }}" placeholder="0" readonly />
                                    </td>
                                    <td>
                                        <input type="number" name="subtotal_amount[]" class="form-control rounded-0 fs-7" value="{{ number_format($room_reservation->subtotal_amount, 0, '', '') }}" placeholder="0" readonly />
                                    </td> --}}
                                    <td><button type="button" class="btn btn-light-danger btn-sm" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button></td>
                                </tr>
                                @endforeach
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
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea name="remark" id="remark" class="form-control fs-7" placeholder="Remark" style="resize: none;">{{ old('remark', $reservation->remark ?? null) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </div>
            </div>
            <!--end::Card-->
        </form>
    </div>
    <!--end::container-->
    @include('App.contact_management.customers.quickAddContact')
</div>
<!--end::Content-->
@endsection

@push('scripts')
<script src="{{asset('customJs/reservation.js')}}"></script>

<script>
    
    var reservationData = {!! json_encode($reservation) !!};
    var roomReservations = {!! json_encode($room_reservations) !!};

    setInitialValues();

    function setInitialValues() {
        roomReservations.forEach(function(roomReservation, index) {
            var roomType = roomReservation.room_type_id;
            var roomRate = roomReservation.room_rate_id;
            var room = roomReservation.room_id;

            // Set the selected room type
            $('select[name="room_type_id[]"]').eq(index).val(roomType).trigger('change');

            // Filter room rate options based on the selected room type
            filterRoomRates(index, roomType);

            // Set the selected room rate
            $('select[name="room_rate_id[]"]').eq(index).val(roomRate).trigger('change');

            // Filter room options based on the selected room type
            filterRooms(index, roomType);

            // Set the selected room
            // $('select[name="room_id[]"]').eq(index).val(room).trigger('change');
            var roomSelect = $('select[name="room_id[]"]').find('option:selected').data('max-occupancy');
            // console.log(roomSelect);
            // Set the max occupancy value
            var maxOccupancy = $('select[name="room_id[]"]').eq(index).find('option:selected').data('max-occupancy');
            $('.max_occupancy').eq(index).val(maxOccupancy).trigger('change');

        });
        $('.room-qty').val(roomReservations.length);
    }

    function filterRoomRates(rowIndex, selectedRoomType) {
        $('select[name="room_rate_id[]"]').eq(rowIndex).find('option').each(function() {
            var roomType = $(this).data('room-type-id');

            if (selectedRoomType === "" || roomType == selectedRoomType) {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });
    }

    function filterRooms(rowIndex, selectedRoomType) {
        $('select[name="room_id[]"]').eq(rowIndex).find('option').each(function() {
            var roomType = $(this).data('room-type-id');

            if (selectedRoomType === "" || roomType == selectedRoomType) {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });
    }

    var firstCheckInInput = checkInInputs[0];
    var firstCheckOutInput = checkOutInputs[0];

    var firstRowCheckInPicker = new tempusDominus.TempusDominus(firstCheckInInput, {
        localization: {
            locale: "en",
            format: "dd/MM/yyyy, hh:mm T",
        },
    });

    var firstRowCheckOutPicker = new tempusDominus.TempusDominus(firstCheckOutInput, {
        localization: {
            locale: "en",
            format: "dd/MM/yyyy, hh:mm T",
        },
    });

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
                    <td><button type="button" class="btn btn-light-danger" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button></td>
                </tr>
            `;

        $('#room_added_table tbody').append(newRoomRow);

        // var container = $('.table-responsive');
        // var scrollTo = $('#new_row');
        // container.scrollTop(scrollTo.offset().top - container.offset().top + container.scrollTop());

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

        $('.room-qty').val(numOfRooms);
    });

    if ($('input[name="group_reservation"]').is(':checked')) {
        $('.reservationSelect').show();
    } else {
        $('.reservationSelect').hide();
    }

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