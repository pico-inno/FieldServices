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
<h1 class="text-dark fw-bold my-0 fs-3">Add Room Sale</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Room Management</li>
    <li class="breadcrumb-item text-muted">Room Sale</li>
    <li class="breadcrumb-item text-dark">Add Room Sale</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <form action="{{route('room-sale.store')}}" method="POST">
            @csrf
            <!--begin::Card-->
            <div class="card card-p-4 card-flush">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label class="form-label">Transaction Type</label>
                            <select name="transaction_type" id="transactionType" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" data-hide-search="true">
                                <option></option>
                                <option value="registration">Registration</option>
                                <option value="reservation">Reservation</option>
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
                                <option value="{{ $business_location->id }}">{{ $business_location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8 reservation-div" style="display: none;">
                            <label for="" class="form-label">Reservation Code</label>
                            <select name="reservation_id" class="form-select form-select-sm fs-7 transaction_id" data-control="select2" data-placeholder="Please select" onchange="getReservationDetails(this)">
                                <option></option>
                                @foreach($reservations as $reservation)
                                <option value="{{$reservation->id}}">
                                    @if(!empty( $reservation->contact))
                                    {{ $reservation->contact['prefix'] ?? '' }} {{ $reservation->contact['first_name'] ?? '' }} {{ $reservation->contact['middle_name'] ?? '' }} {{ $reservation->contact['last_name'] ?? '' }}
                                    ({{ $reservation->reservation_code }})
                                    @else
                                    {{$reservation->company['company_name'] ?? ''}}  ({{ $reservation->reservation_code }})
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8 registration-div" style="display: none;">
                            <label for="" class="form-label">Registration Code</label>
                            <select name="registration_id" class="form-select form-select-sm fs-7 transaction_id" data-control="select2" data-placeholder="Please select" onchange="getRegistrationDetails(this)">
                                <option></option>
                                @foreach($registrations as $registration)
                                <option value="{{$registration->id}}">
                                    @if(!empty( $registration->patient))
                                    {{ $registration->patient['prefix'] ?? '' }} {{ $registration->patient['first_name'] ?? '' }} {{ $registration->patient['middle_name'] ?? '' }} {{ $registration->patient['last_name'] ?? '' }}
                                    ({{ $registration->registration_code }})
                                    @else
                                    {{$registration->company['company_name'] ?? ''}}  ({{ $registration->registration_code }})
                                    @endif
                                </option>
                                @endforeach
                            </select>
                            {{-- <input type="hidden" name="contact_id" value="{{ $registration->patient['id'] }}"> --}}
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8 reservation-div" style="display: none;">
                            <label class="form-label">Guest</label>
                            <select name="guest_id" id="guest_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select">
                                <option></option>
                            </select>
                        </div>
                         <div class="col-md-3 col-sm-12 mb-8 registration-div" style="display: none;">
                            <label class="form-label">Patient</label>
                            <select name="patient_id" id="patient_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-8 check-in-out-date-div" style="display: none;">
                            <label for="check_in_date" class="required form-label">Checkin Date</label>
                            <input type="text" name="check_in_date" id="check_in_date" class="form-control form-control-sm fs-7" autocomplete="off" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8 check-in-out-date-div" style="display: none;">
                            <label for="check_out_date" class="required form-label">Checkout Date</label>
                            <input type="text" name="check_out_date" id="check_out_date" class="form-control form-control-sm fs-7" autocomplete="off" />
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
                            <tbody class="fw-semibold text-gray-700 x">
                                <tr class="room_sales">
                                    <td>
                                        <select name="room_type_id[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2 room_type" data-control="select2" data-placeholder="Please select">
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
                                        <select name="room_rate_id[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2 room_rate" data-control="select2" data-placeholder="Please select">
                                            <option></option>
                                            @php
                                            $room_rates = \Modules\RoomManagement\Entities\RoomRate::all();
                                            @endphp
                                            {{-- @foreach($room_rates as $room_rate)
                                            <option value="{{ $room_rate->id}}" data-rate-amount="{{ $room_rate->rate_amount }}" data-room-type-id="{{ $room_rate->room_type_id }}">{{ $room_rate->rate_name }}</option>
                                            @endforeach --}}
                                        </select>
                                    </td>
                                    <td>
                                        <select name="room_id[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2 room_select" data-control="select2" data-placeholder="Please select">
                                            <option></option>
                                            @php
                                            $rooms = \Modules\RoomManagement\Entities\Room::all();
                                            @endphp
                                            {{-- @foreach($rooms as $room)
                                            <option value="{{ $room->id}}" data-max-occupancy="{{ $room->max_occupancy }}" data-room-type-id="{{ $room->room_type_id }}">{{ $room->name }}</option>
                                            @endforeach --}}
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="check_in_date[]" class="room_check_in_date form-control form-control-sm rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
                                    </td>
                                    <td>
                                        <input type="text" name="check_out_date[]" class="room_check_out_date form-control form-control-sm rounded-0 fs-7" placeholder="Select date" autocomplete="off" />
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
                                            <input type="number" name="qty[]" class="form-control form-control-sm rounded-0 fs-7" value="1" data-kt-dialer-control="input" placeholder="Amount" readonly />
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
                                        <input type="number" name="room_fees[]" id="rate_amout_bef_discount" class="form-control form-control-sm rounded-0 fs-7" value="" placeholder="0" readonly />
                                    </td>
                                    <td>
                                        <input type="number" name="subtotal[]" id="subtotal" class="form-control form-control-sm rounded-0 fs-7" value="" placeholder="0" readonly />
                                    </td>
                                    <td>
                                        <select name="discount_type[]" class="form-select form-select-sm rounded-0 fs-7 custom-select2" data-control="select2" data-placeholder="Please select">
                                            {{-- <option></option> --}}
                                            <option value="fixed">Fixed</option>
                                            <option value="percentage" selected>Percentage</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="per_item_discount[]" class="form-control form-control-sm rounded-0 fs-7" value="" placeholder="0" />
                                        <input type="hidden" name="total_line_discount_amount[]" class="form-control form-control-sm rounded-0 fs-7" value="" placeholder="0" />
                                    </td>
                                    <td><button type="button" class="btn btn-light-danger btn-sm" id="delete_each_room_row"><i class="fa-solid fa-trash"></i></button></td>
                                </tr>
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
                            <input type="text" name="sale_amount" id="sale_amount" class="form-control form-control-sm fs-7" value="0"  />
                        </div>
                    </div>
                    <div class="row justify-content-end mt-3">
                        <div class="fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Total Item Discount:(-)Ks
                            </span>
                            <input type="text" name="total_item_discount" id="total_item_discount" class="form-control form-control-sm fs-7" value="0" readonly />
                        </div>
                    </div>
                    <div class="row justify-content-end mt-3">
                        <div class="fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Total Sale Amount:(=)Ks
                            </span>
                            <input type="text" name="total_sale_amount" id="total_sale_amount" class="form-control form-control-sm fs-7" value="0" readonly />
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="my-9 fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Paid Amount:(=)Ks
                            </span>
                            <input type="text" name="paid_amount" class="form-control form-control-sm form-control form-control-sm-sm input_number max-w-100px" id="total_paid_amount" value="{{old('balance_amount',0)}}">
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                            <span class="min-w-200px pe-2" for="">
                                Balance Amount:(=)Ks
                            </span>
                            <input type="text" name="balance_amount" class="form-control form-control-sm form-control form-control-sm-sm input_number max-w-100px" id="total_balance_amount" value="{{old('balance_amount',0)}}">
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
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

@endpush
