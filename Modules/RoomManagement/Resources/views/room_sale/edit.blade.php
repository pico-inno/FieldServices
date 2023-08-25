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
@section('room_management_icon','active')
@section('room_management_show','active show')
@section('room_sale_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">Edit Room Sale</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Room Management</li>
    <li class="breadcrumb-item text-muted">Room Sale</li>
    <li class="breadcrumb-item text-dark">Edit Room Sale</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@php
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
        <form action="{{route('room-sale.update', $room_sale->id)}}" method="POST">
            @csrf
            @method('PUT')
            <!--begin::Card-->
            <div class="card card-p-4 card-flush">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label class="form-label">Transaction Type</label>
                            <select name="transaction_type" id="transactionType" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" data-hide-search="true" disabled>
                                <option></option>
                                <option value="registration" {{$room_sale->transaction_type == 'registration' ? 'selected' : ''}}>Registration</option>
                                <option value="reservation" {{$room_sale->transaction_type == 'reservation' ? 'selected' : ''}}>Reservation</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label class="form-label">Business Location</label>
                            <select name="business_location_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Select Location">
                                <option></option>
                                @php
                                $business_locations = \App\Models\settings\businessLocation::all();
                                @endphp

                                @foreach($business_locations as $business_location)
                                <option value="{{ $business_location->id }}" {{ old('$room_sale->business_location_id ', $room_sale->business_location_id ) == $business_location->id ? 'selected' : ''}}>
                                    {{ $business_location->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @if ($room_sale->transaction_type=="reservation")
                            <div class="col-md-3 col-sm-12 mb-8 reservation-div">
                                <label for="" class="form-label">Reservation Code</label>
                                <select name="reservation_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" onchange="getReservationDetails(this)" disabled>
                                    <option></option>
                                    @foreach($reservations as $reservation)
                                        <option value="{{$reservation->id}}" @selected($reservation->id==$room_sale->transaction_id)>
                                            @if(!empty( $reservation->contact))
                                            {{ $reservation->contact->prefix ?? '' }} {{ $reservation->contact->first_name ?? '' }} {{ $reservation->contact->middle_name ?? '' }} {{ $reservation->contact->last_name ?? '' }}
                                            ({{ $reservation->reservation_code }})
                                            @else
                                            {{$reservation->company['company_name'] ?? ''}}  ({{ $reservation->reservation_code }})
                                            @endif
                                        </option>
                                    {{-- <!-- <option value="{{$reservation->id}}">{{$reservation->reservation_code}}</option> --> --}}
                                    @endforeach
                                </select>
                            </div>
                        @elseif($room_sale->transaction_type=="registration")
                            <div class="col-md-3 col-sm-12 mb-8 reservation-div">
                                <label for="" class="form-label">Registration Code</label>
                                <select name="registration_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" onchange="getReservationDetails(this)" disabled>
                                    <option></option>
                                    @foreach($registrations as $registration)

                                        <option value="{{$registration->id}}" @selected($registration->id==$room_sale->transaction_id)>

                                                {{ $registration->patient['prefix'] ?? '' }} {{ $registration->patient['first_name'] ?? '' }} {{ $registration->patient['middle_name'] ?? '' }} {{ $registration->patient['last_name'] ?? '' }}
                                                ({{ $registration->registration_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @php
                            $contacts = \App\Models\Contact\Contact::where('type', 'Customer')->get();
                        @endphp
                        @if($room_sale->transaction_type == 'reservation')
                        <div class="col-md-3 col-sm-12 mb-8 reservation-div">
                            <label class="form-label">Guest</label>
                            <select name="guest_id" id="guest_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select">
                                <option></option>
                                @foreach($contacts as $contact)
                                @php
                                    $displayText = $contact->company_name ? $contact->company_name : $contact->prefix . ' ' . $contact->first_name . ' ' . $contact->middle_name . ' ' . $contact->last_name;
                                    $selected = ($contact->id == $room_sale->contact_id) ? 'selected' : '';
                                @endphp
                                <option value="{{$contact->id}}" {{$selected}}>{{$displayText}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if($room_sale->transaction_type == 'registration')
                         <div class="col-md-3 col-sm-12 mb-8 registration-div">
                            <label class="form-label">Patient</label>
                            <select name="patient_id" id="patient_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select">
                                <option></option>
                                @foreach($contacts as $contact)
                                @php
                                    $displayText = $contact->company_name ? $contact->company_name : $contact->prefix . ' ' . $contact->first_name . ' ' . $contact->middle_name . ' ' . $contact->last_name;
                                    $selected = ($contact->id == $room_sale->contact_id) ? 'selected' : '';
                                @endphp
                                <option value="{{$contact->id}}" {{$selected}}>{{$displayText}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-8 check-in-out-date-div">
                            <label for="check_in_date" class="required form-label">Checkin Date</label>
                            <input type="text" id="check_in_date" value="{{ old('check_in_date', !empty($room_sale->reservation->check_in_date) ? date('d/m/Y, h:i A', strtotime($room_sale->reservation->check_in_date)) : $currentDate) }}" class="form-control form-control-sm fs-7" autocomplete="off" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8 check-in-out-date-div">
                            <label for="check_out_date" class="required form-label">Checkout Date</label>
                            <input type="text" id="check_out_date" value="{{ old('check_out_date', !empty($room_sale->reservation->check_out_date) ? date('d/m/Y, h:i A', strtotime($room_sale->reservation->check_out_date)) : $currentDate) }}" class="form-control form-control-sm fs-7" autocomplete="off" />
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
                                    <th class="min-w-125px">Qty</th>
                                    <th class="min-w-125px">UOM</th>
                                    <th class="min-w-125px">Room Fees</th>
                                    <th class="min-w-125px">Subtotal</th>
                                    <th>Discount Type</th>
                                    <th class="min-w-125px">Per Item Discount</th>
                                    <th><i class="fa-solid fa-trash text-danger"></i></th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-700">
                                @foreach($room_sale->room_sale_details as $room_sale_detail)
                                <tr class="room_sales">
                                    <td>
                                        <select name="room_type_id[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2 room_type" data-control="select2" data-placeholder="Please select" >
                                            <option></option>
                                            @php
                                            $room_types = \Modules\RoomManagement\Entities\RoomType::all();
                                            @endphp
                                            @foreach($room_types as $room_type)
                                            <option value="{{ $room_type->id}}" {{ old('$room_sale_detail->room_type_id ', $room_sale_detail->room_type_id ) == $room_type->id ? 'selected' : ''}}>{{ $room_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="room_rate_id[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2 room_rate" data-control="select2" data-placeholder="Please select" >
                                            <option></option>
                                            @php
                                            $room_rates = \Modules\RoomManagement\Entities\RoomRate::all();
                                            $room_rates_by_types = \Modules\RoomManagement\Entities\RoomRate::where('room_type_id',$room_sale_detail->room_type_id)->get();
                                            @endphp
                                            @foreach($room_rates_by_types as $room_rate)
                                            <option value="{{ $room_rate->id}}" data-rate-amount="{{ $room_rate->rate_amount }}" data-room-type-id="{{ $room_rate->room_type_id }}" {{ old('$room_sale_detail->room_rate_id ', $room_sale_detail->room_rate_id ) == $room_rate->id ? 'selected' : ''}}>{{ $room_rate->rate_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="room_id[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2 room_select" data-control="select2" data-placeholder="Please select" >
                                            <option></option>
                                            @php
                                            $rooms = \Modules\RoomManagement\Entities\Room::all();
                                            $rooms_by_type = \Modules\RoomManagement\Entities\Room::where('room_type_id',$room_sale_detail->room_type_id)->get();
                                            @endphp
                                            @foreach($rooms_by_type as $room)
                                            <option value="{{ $room->id}}" data-max-occupancy="{{ $room->max_occupancy }}" data-room-type-id="{{ $room->room_type_id }}" {{ old('$room_sale_detail->room_id ', $room_sale_detail->room_id ) == $room->id ? 'selected' : ''}}>{{ $room->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="check_in_date[]" value="{{ old('check_in_date', !empty($room_sale_detail->check_in_date) ? date('d/m/Y, h:i A', strtotime($room_sale_detail->check_in_date)) : $currentDate) }}" class="room_check_in_date form-control form-control-sm rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
                                    </td>
                                    <td>
                                        <input type="text" name="check_out_date[]" value="{{ old('check_out_date', !empty($room_sale_detail->check_out_date) ? date('d/m/Y, h:i A', strtotime($room_sale_detail->check_out_date)) : $currentDate) }}" class="room_check_out_date form-control form-control-sm rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
                                    </td>
                                    <td>
                                        <!--begin::Dialer-->
                                        <div class="input-group w-md-150px w-sm-300px" data-kt-dialer="true" data-kt-dialer-min="1" data-kt-dialer-max="50000" data-kt-dialer-step="1">

                                            <!--begin::Decrease control-->
                                            <button class="btn btn-icon btn-outline btn-active-color-primary btn-sm" type="button" data-kt-dialer-control="decrease">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <!--end::Decrease control-->

                                            <!--begin::Input control-->
                                            <input type="number" name="qty[]" value="{{ old('qty', $room_sale_detail->qty) }}" class="form-control form-control-sm rounded-0 fs-7" value="1" data-kt-dialer-control="input" placeholder="Amount" readonly />
                                            <!--end::Input control-->

                                            <!--begin::Increase control-->
                                            <button class="btn btn-icon btn-outline btn-active-color-primary btn-sm" type="button" data-kt-dialer-control="increase">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                            <!--end::Increase control-->
                                        </div>
                                        <!--end::Dialer-->
                                        <!-- <input type="number" name="qty[]" id="total-room-qty" class="form-control form-control-sm rounded-0 fs-7" value="1" /> -->
                                    </td>
                                    <!-- <td>
                                        <input type="number" id="max_occupancy" class="form-control form-control-sm rounded-0 fs-7" value="" placeholder="0" readonly />
                                    </td> -->
                                    <td>
                                        <select name="uoms[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                                            <option></option>
                                            <option class="day">Day</option>
                                        </select>
                                        <input type="hidden" name="currencies[]">
                                    </td>
                                    <td>
                                        <input type="number" name="room_fees[]" id="rate_amout_bef_discount" class="form-control form-control-sm rounded-0 fs-7"  placeholder="0" value="{{ old('room_fees', number_format($room_sale_detail->room_fees, 0, '', '')) }}" readonly />
                                    </td>
                                    <td>
                                        <input type="number" name="subtotal[]" id="subtotal" class="form-control form-control-sm rounded-0 fs-7" placeholder="0" readonly value="{{ old('room_fees', number_format($room_sale_detail->subtotal, 0, '', '')) }}" />
                                    </td>
                                    <td>
                                        <select name="discount_type[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                                            {{-- <option></option> --}}
                                            <option value="fixed" @selected($room_sale_detail->discount_type=='fixed')>Fixed</option>
                                            <option value="percentage" @selected($room_sale_detail->discount_type=='percentage') >Percentage</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="per_item_discount[]" class="form-control form-control-sm rounded-0 fs-7"  value="{{ old('room_fees', number_format($room_sale_detail->per_item_discount, 0, '', '')) }}" placeholder="0" />
                                        <input type="hidden" name="total_line_discount_amount[]" class="form-control form-control-sm rounded-0 fs-7" value="{{ old('room_fees', $room_sale_detail->per_item_discount)}}" placeholder="0" />
                                    </td>
                                    <td><button type="button" class="btn btn-light-danger btn-sm" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                    </div>
                    <br>
                    <div class="row mb-8">
                        <div class="d-flex">
                            <button type="button" class="btn btn-light-primary btn-sm me-3" id="add_room_row"><i class="fa-solid fa-plus"></i></button>
                            <button type="button" class="btn btn-light-danger btn-sm disable delete_room_row" id="delete_room_row"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Sale Amount:(=)Ks
                            </span>

                            <input type="text" name="sale_amount" id="sale_amount" class="form-control form-control-sm fs-7" value="{{$room_sale->sale_amount}}"  />
                        </div>
                    </div>
                    <div class="row justify-content-end mt-3">
                        <div class="fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Total Item Discount:(-)Ks
                            </span>
                            <input type="text" name="total_item_discount" id="total_item_discount" class="form-control form-control-sm fs-7" value="{{$room_sale->total_item_discount}}" readonly />
                        </div>
                    </div>
                    <div class="row justify-content-end mt-3">
                        <div class="fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Total Sale Amount:(=)Ks
                            </span>
                            <input type="text" name="total_sale_amount" id="total_sale_amount" class="form-control form-control-sm fs-7"  value="{{$room_sale->total_sale_amount}}" readonly />
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="my-9 fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Paid Amount:(=)Ks
                            </span>
                            <input type="text" name="paid_amount" class="form-control form-control-sm form-control form-control-sm-sm input_number max-w-100px" id="total_paid_amount" value="{{$room_sale->paid_amount}}">
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Balance Amount:(=)Ks
                            </span>
                            <input type="text" name="balance_amount" class="form-control form-control-sm form-control form-control-sm-sm input_number max-w-100px" id="total_balance_amount" value="{{$room_sale->balance_amount}}">
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
</div>
<!--end::Content-->
@endsection

@push('scripts')
<script src="{{asset('customJs/roomsale.js')}}"></script>
@include('roommanagement::room_sale.bladeJs.roomSaleJs')
<script>

    // var uniqueIndex= $('#room_added_table tbody tr').length ?? 0;
    // // console.log(uniqueIndex);
    // $(document).on('click', '#add_room_row', function() {
    //     // console.log('click');
    //     var newRoomRow = `
    //             <tr id="new_row">
    //                 <td>
    //                     <select name="room_type_id[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2 room_type" data-control="select2" data-placeholder="Please select">
    //                         <option></option>
    //                         @php
    //                             $room_types = \Modules\RoomManagement\Entities\RoomType::all();
    //                         @endphp
    //                         @foreach($room_types as $room_type)
    //                             <option value="{{ $room_type->id}}">{{ $room_type->name }}</option>
    //                         @endforeach
    //                     </select>
    //                 </td>
    //                 <td>
    //                     <select name="room_rate_id[]" class="form-select rounded-0 fs-7 custom-select2 room_rate" data-control="select2" data-placeholder="Please select">
    //                         <option></option>
    //                         @php
    //                             $room_rates = \Modules\RoomManagement\Entities\RoomRate::all();
    //                         @endphp
    //                         @foreach($room_rates as $room_rate)
    //                             <option value="{{ $room_rate->id}}" data-rate-amount="{{ $room_rate->rate_amount }}" data-room-type-id="{{ $room_rate->room_type_id }}">{{ $room_rate->rate_name }}</option>
    //                         @endforeach
    //                     </select>
    //                 </td>
    //                 <td>
    //                     <select name="room_id[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
    //                         <option></option>
    //                         @php
    //                             $rooms = \Modules\RoomManagement\Entities\Room::all();
    //                         @endphp
    //                         @foreach($rooms as $room)
    //                             <option value="{{ $room->id}}" data-max-occupancy="{{ $room->max_occupancy }}" data-room-type-id="{{ $room->room_type_id }}">{{ $room->name }}</option>
    //                         @endforeach
    //                     </select>
    //                 </td>
    //                 <td>
    //                     <input type="text" name="check_in_date[]" class="room_check_in_date form-control form-control-sm rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
    //                 </td>
    //                 <td>
    //                     <input type="text" name="check_out_date[]" class="room_check_out_date form-control rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
    //                 </td>
    //                 <td>
    //                     <!--begin::Dialer-->
    //                     <div class="input-group w-md-150px w-sm-300px" data-kt-dialer="true" data-kt-dialer-min="1" data-kt-dialer-max="50000" data-kt-dialer-step="1">

    //                         <!--begin::Decrease control-->
    //                         <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" data-kt-dialer-control="decrease">
    //                             <i class="fa-solid fa-minus"></i>
    //                         </button>
    //                         <!--end::Decrease control-->

    //                         <!--begin::Input control-->
    //                         <input type="number" name="qty[]" class="form-control rounded-0 fs-7" value="1" min="1" max="5000" data-kt-dialer-control="input" placeholder="Amount" readonly />
    //                         <!--end::Input control-->

    //                         <!--begin::Increase control-->
    //                         <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" data-kt-dialer-control="increase">
    //                             <i class="fa-solid fa-plus"></i>
    //                         </button>
    //                         <!--end::Increase control-->
    //                     </div>
    //                     <!--end::Dialer-->
    //                 </td>
    //                 <td>
    //                     <select name="uoms[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
    //                         <option></option>
    //                         <option class="day">Day</option>
    //                     </select>
    //                     <input type="hidden" name="currencies[]">
    //                 </td>
    //                 <td>
    //                     <input type="number" name="room_fees[]" id="rate_amout_bef_discount" class="form-control rounded-0 fs-7" value="" placeholder="0" readonly />
    //                 </td>
    //                 <td>
    //                     <input type="number" name="subtotal[]" id="subtotal" class="form-control rounded-0 fs-7" value="" placeholder="0" readonly />
    //                 </td>
    //                 <td>
    //                     <select name="discount_type[]" class="form-select rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
    //                         {{-- <option></option> --}}
    //                         <option value="fixed">Fixed</option>
    //                         <option value="percentage" selected>Percentage</option>
    //                     </select>
    //                 </td>
    //                 <td>
    //                     <input type="number" name="per_item_discount[]" class="form-control rounded-0 fs-7" value="" placeholder="0" />
    //                     <input type="hidden" name="total_line_discount_amount[]" class="form-control rounded-0 fs-7" value="" placeholder="0" />
    //                 </td>
    //                 <td><button type="button" class="btn btn-light-danger btn-sm" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button></td>
    //             </tr>
    //         `;

    //     $('#room_added_table tbody').append(newRoomRow);

    //     // var container = $('.table-responsive');
    //     // var scrollTo = $('#new_row');
    //     // container.scrollTop(scrollTo.offset().top - container.offset().top + container.scrollTop());

    //     new tempusDominus.TempusDominus(checkInInputs[checkInInputs.length - 1], {
    //         localization: {
    //             locale: "en",
    //             format: "dd/MM/yyyy, hh:mm T",
    //         }
    //     });

    //     new tempusDominus.TempusDominus(checkOutInputs[checkOutInputs.length - 1], {
    //         localization: {
    //             locale: "en",
    //             format: "dd/MM/yyyy, hh:mm T",
    //         }
    //     });


    //     $('#room_added_table tbody select[data-control="select2"]').select2();

    //     initializeDialer();

    //     if ($('#room_added_table tbody tr').length > 1) {
    //         $('#delete_room_row').removeClass('disable');
    //         $('#delete_room_row').css({
    //             'cursor': 'pointer',
    //             'opacity': 1
    //         });
    //     }

    //     var numOfRooms = $('#room_added_table tbody tr').length;

    //     $('.room-qty').val(numOfRooms);
    //     uniqueIndex++;
    // });


    // function getReservationDetails(selectElement) {
    //     console.log({!! $reservations !!});

    //     var reservationId = selectElement.value;
    //     if (reservationId) {
    //         var selectedReservation = {!! $reservations !!}.find(reservation => reservation.id == reservationId);
    //         if (selectedReservation) {
    //             $('#guest_id').empty();

    //             var fullName = '';
    //             if(selectedReservation.contact){
    //                 if (selectedReservation.contact.prefix) {
    //                     fullName += selectedReservation.contact.prefix;
    //                 }
    //                 if (selectedReservation.contact.first_name) {
    //                     fullName += ' ' + selectedReservation.contact.first_name;
    //                 }
    //                 if (selectedReservation.contact.middle_name) {
    //                     fullName += ' ' + selectedReservation.contact.middle_name;
    //                 }
    //                 if (selectedReservation.contact.last_name) {
    //                     fullName += ' ' + selectedReservation.contact.last_name;
    //                 }
    //             }

    //             var companyName = '';
    //             if(selectedReservation.company) {
    //                 if(selectedReservation.company.company_name) {
    //                     companyName += selectedReservation.company.company_name;
    //                 }
    //             }

    //             var option = $('<option>');

    //             if (fullName) {
    //                 option.val(selectedReservation.guest_id).text(fullName).prop('selected', true);
    //             } else if (companyName) {
    //                 option.val(selectedReservation.company_id).text(companyName).prop('selected', true);
    //             }

    //             // var option = $('<option>').val(selectedReservation.company_id).text(companyName).prop('selected', true);

    //             // Append the new option to the select element
    //             $('#guest_id').append(option).trigger('change');
    //             $('#check_in_date').val(selectedReservation.check_in_date);
    //             $('#check_out_date').val(selectedReservation.check_out_date);

    //             var roomTypeId = selectedReservation.room_reservations[0].room_type_id;
    //             var roomTypeName = selectedReservation.room_reservations[0].room_type.name;


    //             $('select[name="room_type_id[]"]').empty();

    //             var roomTypeOption = $('<option>').val(roomTypeId).text(roomTypeName).prop('selected', true);
    //             $('select[name="room_type_id[]"]').append(roomTypeOption).trigger('change');

    //             var roomRateId = selectedReservation.room_reservations[0].room_rate_id;
    //             var roomRateName = selectedReservation.room_reservations[0].room_rate.rate_name;
    //             $('select[name="room_rate_id[]"]').val(roomRateId).trigger('change');

    //              var roomId = selectedReservation.room_reservations[0].room_id;
    //             var roomName = selectedReservation.room_reservations[0].room.name;

    //             $('select[name="room_id[]"]').empty();

    //             var roomOption = $('<option>').val(roomId).text(roomName).prop('selected', true);
    //             $('select[name="room_id[]"]').append(roomOption).trigger('change');

    //             $('input[name="check_in_date[]"]').val(selectedReservation.room_reservations[0].room_check_in_date);
    //             $('input[name="check_out_date[]"]').val(selectedReservation.room_reservations[0].room_check_out_date);

    //             // Set before_discount_amount based on room_rate_id selection
    //             var selectedRateOption = $('select[name="room_rate_id[]"]').find('option:selected');
    //             var rateAmount = parseInt(selectedRateOption.data('rate-amount'));
    //             if (rateAmount) {
    //                 $('#rate_amout_bef_discount').val(rateAmount);
    //                 $('#rate_amount_after_discount').val(rateAmount);
    //                 $('#amount_inc_tax').val(rateAmount);
    //                 update_sale_amount();
    //                 total_sale_amount();
    //                 balance_amount();
    //             } else {
    //                 $('#rate_amout_bef_discount').val('');
    //             }
    //         }
    //     }
    // }


</script>
@endpush
