@extends('App.main.navBar')
@section('styles')
@endsection
@section('reservation_icon','active')
@section('reservation_show','active show')
@section('reservation_list_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Reservation</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1 fs-5">
    <li class="breadcrumb-item text-muted">Reservation</li>
    <li class="breadcrumb-item text-dark">Reservation Details</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap mb-5">
                    <div class="flex-grow-1">
                        <div class="row mb-9">
                            <div class="col-9">
                                <!--begin::User-->
                                <div class="d-flex flex-column">
                                    <!--begin::Name-->
                                    <div class="d-flex mb-2">
                                        <a href="#" class="text-gray-700 text-hover-primary fs-4 fw-bold me-1">
                                            @if($currentReservation->contact)
                                            {{$currentReservation->contact->prefix}}
                                            {{$currentReservation->contact->first_name}}
                                            {{$currentReservation->contact->middle_name}}
                                            {{$currentReservation->contact->last_name}}
                                            @elseif($currentReservation->company)
                                            {{$currentReservation->company->company_name}}
                                            @endif
                                        </a>
                                    </div>
                                    <!--end::Name-->
                                    <!--begin::Info-->
                                    <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                        @php
                                        $address = $currentReservation->contact ? $currentReservation->contact->address_line_1 : ($currentReservation->company ? $currentReservation->company->address_line_1 : null);
                                        $email = $currentReservation->contact ? $currentReservation->contact->email : ($currentReservation->company ? $currentReservation->company->email : null);
                                        $mobile = $currentReservation->contact ? $currentReservation->contact->mobile : ($currentReservation->company ? $currentReservation->company->mobile : null);
                                        $alternateNumber = $currentReservation->contact ? $currentReservation->contact->alternate_number : ($currentReservation->company ? $currentReservation->company->alternate_number : null);
                                        $dob = isset($currentReservation->contact) && isset($currentReservation->contact->dob)
                                        ? date('d/m/Y', strtotime($currentReservation->contact->dob))
                                        : (isset($currentReservation->company) && isset($currentReservation->company->dob)
                                        ? date('d/m/Y', strtotime($currentReservation->company->dob))
                                        : null);
                                        $addressLineTwo = $currentReservation->contact ? $currentReservation->contact->address_line_2 : ($currentReservation->company ? $currentReservation->company->address_line_2 : null);
                                        $city = $currentReservation->contact ? $currentReservation->contact->city : ($currentReservation->company ? $currentReservation->company->city : null);
                                        $state = $currentReservation->contact ? $currentReservation->contact->state : ($currentReservation->company ? $currentReservation->company->state : null);
                                        $country = $currentReservation->contact ? $currentReservation->contact->country : ($currentReservation->company ? $currentReservation->company->country : null);
                                        $zipcode = $currentReservation->contact ? $currentReservation->contact->zip_code : ($currentReservation->company ? $currentReservation->company->zip_code : null);
                                        @endphp

                                        @if($address)
                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                            <!--begin::FontAwesome Icon-->
                                            <i class="fa-solid fa-location-dot me-2"></i>
                                            <!--end::FontAwesome Icon-->
                                            {{$address}}
                                        </a>
                                        @endif

                                        @if($email)
                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                            <!--begin::FontAwesome Icon-->
                                            <i class="fa-solid fa-envelope me-2"></i>
                                            <!--end::FontAwesome Icon-->
                                            {{$email}}
                                        </a>
                                        @endif

                                        @if($mobile)
                                        <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                            <!--begin::FontAwesome Icon-->
                                            <i class="fa-solid fa-phone-volume me-2"></i>
                                            <!--end::FontAwesome Icon-->
                                            {{$mobile}}
                                        </a>
                                        @endif
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </div>
                            <!--begin::Actions-->
                            <div class="col-3">
                                <div class="d-flex justify-content-end mb-6">
                                    <a class="btn btn-sm btn-primary me-2" id="edit-reservation-info" data-href="{{route('editReservationInfo',$currentReservation->id)}}">Edit</a>
                                </div>
                            </div>
                            <!--end::Actions-->
                        </div>
                        {{-- <div class="row">
                            <!--begin::Room info-->
                            <div class="col-md-6 col-sm-12 mb-8">
                                <div class="row">
                                    <div class="col-3">
                                        <h6 class="text-gray-700">No. of rooms</h6>
                                        <span class="text-gray-600 ms-10 num-of-room"></span>
                                    </div>
                                    <div class="col-5">
                                        <table id="room_table">
                                            <thead>
                                                <tr>
                                                    <th class="min-w-50px">
                                                        <h6 class="text-gray-700">Room</h6>
                                                    </th>
                                                    <th class="min-w-50px">
                                                        <h6 class="text-gray-700">Room Type</h6>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($reservation->room_reservations as $room_reservation)
                                                <tr>
                                                    <td><span class="text-gray-600">{{$room_reservation->room->name}}</span></td>
                        <td><span class="text-gray-600">{{$room_reservation->room_type->name}}</span></td>
                        </tr>
                        @endforeach
                        </tbody>
                        </table>
                    </div>
                    <div class="col-4">
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        <h6 class="text-gray-700">Room Rate</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservation->room_reservations as $room_reservation)
                                <tr>
                                    <td><span class="text-gray-600">{{$room_reservation->room_rate->rate_name}}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end::Room info-->
            <!--begin::Reservation data-->
            <div class="col-md-6 mb-8">
                <div class="row">
                    <div class="col-4">
                        <h6 class="text-gray-700">Reservation Code</h6>
                        <span class="text-gray-600">{{$reservation->reservation_code}}</span>
                    </div>
                    <div class="col-4">
                        <h6 class="text-gray-700">Checkin Date</h6>
                        <span class="text-gray-600">{{ date_format(date_create($reservation->check_in_date),"Y-M-d   (h:i A)")}}</span>
                    </div>
                    <div class="col-4">
                        <h6 class="text-gray-700">Checkout Date</h6>
                        <span class="text-gray-600">{{ date_format(date_create($reservation->check_out_date),"Y-M-d   (h:i A)")}}</span>
                    </div>
                </div>
            </div>
            <!--end::Reservation data-->
        </div> --}}

    </div>
    <!--end::Info-->
</div>
<!--end::Details-->
<!--begin::Navs-->
<ul id="myTab" class="nav nav-stretch nav-tabs  nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold">
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-4 {{session('updated') == 'roomTab' || session('updated') == 'guestTab' ? '' : 'active'}}" data-bs-toggle="tab" href="#reservationInfoTab">Reservation</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-4 {{session('updated') == 'guestTab' ? 'active' : ''}}" data-bs-toggle="tab" href="#guestInfoTab">Guest</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-4 {{session('updated') == 'roomTab' ? 'active' : ''}}" data-bs-toggle="tab" href="#roomInfoTab">Room</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#billing">Billing</a>
    </li>
</ul>
<!--begin::Navs-->
</div>
</div>
<div class="tab-content">
    <div class="tab-pane fade {{session('updated') == 'roomTab' || session('updated') == 'guestTab' ? '' : ' active show'}}" id="reservationInfoTab" role="tab-panel">
        <div class="d-flex flex-column gap-7 gap-lg-10">
            <div class="row ">
                <div class="col-md-3 col-12 mb-10">
                    <div class="card h-auto">
                        <div class="card-body p-5">
                            <div class="row">

                                @foreach($reservations as $reservation)
                                <div class="col-12">
                                    <label class="btn btn-outline btn-outline-dashed w-100 d-flex flex-stack text-start p-6 mb-5 col-md-12 col-4 nav-item ">
                                        <!--end::Description-->
                                        <div class="d-flex align-items-center me-2">
                                            <!--begin::Radio-->
                                            <div class="form-check form-check-sm form-check-custom form-check-solid form-check-primary me-6">
                                                <input class="form-check-input reservation-checkbox" type="checkbox" name="reservation" id="reservation-checkbox-{{ $reservation->id }}" value="{{ $reservation->id }}" {{ $reservation->id == $currentReservation->id ? 'checked' : '' }} />
                                            </div>
                                            <!--end::Radio-->

                                            <!--begin::Info-->
                                            <div class="flex-grow-1">
                                                <h2 class="d-flex align-items-center fs-7 fw-bold flex-wrap">
                                                    {{$reservation->reservation_code}}<span class="text-gray-600 fs-8 ps-3"></span>
                                                </h2>
                                                @foreach($reservation->room_reservations as $room_reservation)
                                                <div class="fw-semibold fs-7 opacity-50">
                                                    {{$room_reservation->room->name}}
                                                </div>
                                                @endforeach
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Description-->
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 tab-content">
                    <div class="tab-pane  fade show active" id="patient1" role="tab-panel">
                        <div class="d-flex flex-column gap-7 gap-lg-10">
                            <!--begin::Billing History-->
                            <div class="card" id="view1">
                                <div class="card-header">
                                    <!--begin::Card title-->
                                    <div class="card-title m-0">
                                        <h4 class="fw-bold fs-5 m-0">Reservation Details</h4>
                                    </div>
                                    <!--end::Card title-->
                                    <a class="btn btn-primary btn-sm align-self-center add_new_guest" data-href="{{route('addNewGuest', $currentReservation->id)}}" data-reservation-id="{{ $currentReservation->id }}">Checkin</a>
                                    <!--begin::Action-->
                                    <!-- <a href="../../demo7/dist/account/settings.html" class="btn btn-sm btn-primary align-self-center">Edit Reservation</a> -->
                                    <!--end::Action-->
                                </div>
                                <!--begin::Card body-->
                                <div class="card-body p-9">
                                    <!--begin::Row-->
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 fw-semibold text-muted">Reservation Code</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8">
                                            <span class="fw-bold fs-6 text-gray-800" id="reservation-code">{{$currentReservation->reservation_code}}</span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->
                                    <!--begin::Input group-->
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 fw-semibold text-muted">Guest</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8 fv-row">
                                            <span class="fw-semibold text-gray-800 fs-6" id="guest">
                                                @if($currentReservation->contact)
                                                {{$currentReservation->contact->prefix}}
                                                {{$currentReservation->contact->first_name}}
                                                {{$currentReservation->contact->middle_name}}
                                                {{$currentReservation->contact->last_name}}
                                                @elseif($currentReservation->company)
                                                {{$currentReservation->company->company_name}}
                                                @endif
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Input group-->
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 fw-semibold text-muted">Checkin Date</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <span class="fw-bold fs-6 text-gray-800 me-2" id="checkin-date">
                                                {{ date_format(date_create($currentReservation->check_in_date),"Y-M-d   (h:i A)")}}
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 fw-semibold text-muted">Checkout Date</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <span class="fw-bold fs-6 text-gray-800 me-2" id="checkout-date">
                                                {{ date_format(date_create($currentReservation->check_out_date),"Y-M-d   (h:i A)")}}
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 fw-semibold text-muted">Reservation Status</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <span class="fw-bold fs-6 text-gray-800 me-2" id="reservation-status">
                                                {{$currentReservation->reservation_status}}
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="row mb-7">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 fw-semibold text-muted">Booking Confirmed By</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8">
                                            <span class="fw-bold fs-6 text-gray-800" id="booking-confirm-by">
                                                @php
                                                $confirmed_user = \App\Models\BusinessUser::find($currentReservation->booking_confirmed_by);
                                                $confirmed_by = '';
                                                if ($confirmed_user) {
                                                $confirmed_by = $confirmed_user->username;
                                                }
                                                @endphp
                                                {{$confirmed_by}}
                                            </span>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="row mb-10">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 fw-semibold text-muted">Remark</label>
                                        <!--begin::Label-->
                                        <!--begin::Label-->
                                        <div class="col-lg-8">
                                            <span class="fw-semibold fs-6 text-gray-800" id="remark">
                                                @if($currentReservation->remark)
                                                {{$currentReservation->remark}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </div>
                                        <!--begin::Label-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Billing Address-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade {{session('updated') == 'guestTab' ? ' active show' : ''}}" id="guestInfoTab" role="tab-panel">
        <div class="d-flex flex-column gap-7 gap-lg-10">
            <!-- <div class="card">
                        <div class="card-body"> -->
            <div class="row">
                <div class="col-md-3 mb-8">
                    <div class="card h-auto">
                        <div class="card-body p-5">
                            <div class="row">
                                @foreach($reservations as $reservation)
                                <div class="col-12">
                                    <label class="btn btn-outline btn-outline-dashed w-100 d-flex flex-stack text-start p-6 mb-5 col-md-12 col-4 nav-item ">
                                        <!--end::Description-->
                                        <div class="d-flex align-items-center me-2">
                                            <!--begin::Radio-->
                                            <div class="form-check form-check-sm form-check-custom form-check-solid form-check-primary me-6">
                                                <input class="form-check-input reservation-checkbox" type="checkbox" name="reservation" id="reservation-checkbox-{{ $reservation->id }}" value="{{ $reservation->id }}" {{$reservation->id == $currentReservation->id ? 'checked' : ''}} />
                                            </div>
                                            <!--end::Radio-->

                                            <!--begin::Info-->
                                            <div class="flex-grow-1">
                                                <h2 class="d-flex align-items-center fs-7 fw-bold flex-wrap">
                                                    @if($reservation->contact)
                                                    {{$reservation->contact->prefix}}
                                                    {{$reservation->contact->first_name}}
                                                    {{$reservation->contact->middle_name}}
                                                    {{$reservation->contact->last_name}}
                                                    @elseif($reservation->company)
                                                    {{$reservation->company->company_name}}
                                                    @endif
                                                </h2>
                                                <div class="fw-semibold fs-7 opacity-50">
                                                    {{$reservation->reservation_code}}
                                                </div>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Description-->
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card card-p-4 card-flush" id="view2">
                        <div class="card-body">
                            <form action="{{route('updateGuestInfo', $currentReservation->id)}}" method="POST" id="updateGuestForm">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="guest_id" value="{{ !empty($currentReservation->contact) ? $currentReservation->contact->id : '' }}">

                                <div class="row">
                                    <div class="col-md-3 mb-8">
                                        <label for="prefix" class="form-label fs-7">Prefix</label>
                                        <input type="text" name="prefix" id="prefix" class="form-control fs-7" value="{{!empty($currentReservation->contact) ? $currentReservation->contact->prefix : ''}}" placeholder="Mr / Mrs / Miss" />
                                    </div>
                                    <div class="col-md-3 mb-8">
                                        <label for="first_name" class="form-label fs-7">First Name</label>
                                        <input type="text" name="first_name" id="first_name" value="{{!empty($currentReservation->contact) ? $currentReservation->contact->first_name : ''}}" class="form-control fs-7" placeholder="First Name" />
                                    </div>
                                    <div class="col-md-3 mb-8">
                                        <label for="middle_name" class="form-label fs-7">Middle Name</label>
                                        <input type="text" name="middle_name" id="middle_name" value="{{!empty($currentReservation->contact) ? $currentReservation->contact->middle_name : ''}}" class="form-control fs-7" placeholder="Middle Name" />
                                    </div>
                                    <div class="col-md-3 mb-8">
                                        <label for="last_name" class="form-label fs-7">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" value="{{!empty($currentReservation->contact) ? $currentReservation->contact->last_name : ''}}" class="form-control fs-7" placeholder="Last Name" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-8">
                                        <label for="company_name" class="form-label fs-7">Business Name</label>
                                        <input type="text" name="company_name" id="company_name" value="{{!empty($currentReservation->company) ? $currentReservation->company->company_name : ''}}" class="form-control fs-7" placeholder="Business Name" />
                                    </div>
                                    <div class="col-md-3 mb-8">
                                        <label for="mobile" class="form-label fs-7">Mobile</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control fs-7" value="{{$mobile}}" placeholder="Mobile" required />
                                    </div>
                                    <div class="col-md-3 mb-8">
                                        <label for="alternate_number" class="form-label fs-7">Alternate Contact No</label>
                                        <input type="text" name="alternate_number" id="alternate_number" value="{{$alternateNumber}}" class="form-control fs-7" placeholder="Alternate Contact No" />
                                    </div>
                                    <div class="col-md-3 mb-8">
                                        <label for="email" class="form-label fs-7">Email</label>
                                        <input type="text" name="email" id="email" value="{{$email}}" class="form-control fs-7" placeholder="Email" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-8">
                                        <label for="dob" class="form-label fs-7">Date of birth</label>
                                        <input type="text" name="dob" id="dob" value="{{$dob}}" class="form-control fs-7" placeholder="Date of birth" autocomplete="off" />
                                    </div>
                                    <div class="col-md-3 mb-8">
                                        <label for="address_line_1" class="form-label fs-7">Address Line 1</label>
                                        <input type="text" name="address_line_1" id="address_line_1" value="{{$address}}" class="form-control fs-7" placeholder="Address Line 1">
                                    </div>
                                    <div class="col-md-3 mb-8">
                                        <label for="address_line_2" class="form-label fs-7">Address Line 2</label>
                                        <input type="text" name="address_line_2" id="address_line_2" value="{{$addressLineTwo}}" class="form-control fs-7" placeholder="Address Line 2">
                                    </div>
                                    <div class="col-md-3 mb-8">
                                        <label for="city" class="form-label fs-7">City</label>
                                        <input type="text" name="city" id="city" value="{{$city}}" class="form-control fs-7" placeholder="City">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-8">
                                        <label for="state" class="form-label fs-7">State</label>
                                        <input type="text" name="state" id="state" value="{{$state}}" class="form-control fs-7" placeholder="State">
                                    </div>
                                    <div class="col-md-3 mb-8">
                                        <label for="country" class="form-label fs-7">Country</label>
                                        <select name="country" id="country" class="form-select fs-8" aria-label="Select example">
                                            <option value="" selected disabled>Select Country</option>
                                            <option value="Afghanistan">Afghanistan</option>
                                            <option value="Aland Islands">Aland Islands</option>
                                            <option value="Albania">Albania</option>
                                            <option value="Algeria">Algeria</option>
                                            <option value="American Samoa">American Samoa</option>
                                            <option value="Andorra">Andorra</option>
                                            <option value="Angola">Angola</option>
                                            <option value="Anguilla">Anguilla</option>
                                            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                            <option value="Argentina">Argentina</option>
                                            <option value="Armenia">Armenia</option>
                                            <option value="Aruba">Aruba</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Austria">Austria</option>
                                            <option value="Azerbaijan">Azerbaijan</option>
                                            <option value="Bahamas">Bahamas</option>
                                            <option value="Bahrain">Bahrain</option>
                                            <option value="Bangladesh">Bangladesh</option>
                                            <option value="Barbados">Barbados</option>
                                            <option value="Belarus">Belarus</option>
                                            <option value="Belgium">Belgium</option>
                                            <option value="Belize">Belize</option>
                                            <option value="Benin">Benin</option>
                                            <option value="Bermuda">Bermuda</option>
                                            <option value="Bhutan">Bhutan</option>
                                            <option value="Bolivia, Plurinational State of">Bolivia, Plurinational State of</option>
                                            <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                                            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                            <option value="Botswana">Botswana</option>
                                            <option value="Brazil">Brazil</option>
                                            <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                            <option value="Brunei Darussalam">Brunei Darussalam</option>
                                            <option value="Bulgaria">Bulgaria</option>
                                            <option value="Burkina Faso">Burkina Faso</option>
                                            <option value="Burundi">Burundi</option>
                                            <option value="Cambodia">Cambodia</option>
                                            <option value="Cameroon">Cameroon</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Cape Verde">Cape Verde</option>
                                            <option value="Cayman Islands">Cayman Islands</option>
                                            <option value="Central African Republic">Central African Republic</option>
                                            <option value="Chad">Chad</option>
                                            <option value="Chile">Chile</option>
                                            <option value="China">China</option>
                                            <option value="Christmas Island">Christmas Island</option>
                                            <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                            <option value="Colombia">Colombia</option>
                                            <option value="Comoros">Comoros</option>
                                            <option value="Cook Islands">Cook Islands</option>
                                            <option value="Cook Islands">Cook Islands</option>
                                            <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                                            <option value="Croatia">Croatia</option>
                                            <option value="Cuba">Cuba</option>
                                            <option value="Curaçao">Curaçao</option>
                                            <option value="Czech Republic">Czech Republic</option>
                                            <option value="Denmark">Denmark</option>
                                            <option value="Djibouti">Djibouti</option>
                                            <option value="Dominica">Dominica</option>
                                            <option value="Dominican Republic">Dominican Republic</option>
                                            <option value="Ecuador">Ecuador</option>
                                            <option value="Egypt">Egypt</option>
                                            <option value="El Salvador">El Salvador</option>
                                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                                            <option value="Eritrea">Eritrea</option>
                                            <option value="Estonia">Estonia</option>
                                            <option value="Ethiopia">Ethiopia</option>
                                            <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                            <option value="Fiji">Fiji</option>
                                            <option value="Finland">Finland</option>
                                            <option value="France">France</option>
                                            <option value="French Polynesia">French Polynesia</option>
                                            <option value="Gabon">Gabon</option>
                                            <option value="Gambia">Gambia</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Ghana">Ghana</option>
                                            <option value="Gibraltar">Gibraltar</option>
                                            <option value="Greece">Greece</option>
                                            <option value="Greenland">Greenland</option>
                                            <option value="Grenada">Grenada</option>
                                            <option value="Guam">Guam</option>
                                            <option value="Guatemala">Guatemala</option>
                                            <option value="Guernsey">Guernsey</option>
                                            <option value="Guinea">Guinea</option>
                                            <option value="Guinea-Bissau">Guinea-Bissau</option>
                                            <option value="Haiti">Haiti</option>
                                            <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                            <option value="Honduras">Honduras</option>
                                            <option value="Hong Kong">Hong Kong</option>
                                            <option value="Hungary">Hungary</option>
                                            <option value="Iceland">Iceland</option>
                                            <option value="India">India</option>
                                            <option value="Indonesia">Indonesia</option>
                                            <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                            <option value="Iraq">Iraq</option>
                                            <option value="Ireland">Ireland</option>
                                            <option value="Isle of Man">Isle of Man</option>
                                            <option value="Israel">Israel</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Jamaica">Jamaica</option>
                                            <option value="Japan">Japan</option>
                                            <option value="Jersey">Jersey</option>
                                            <option value="Jordan">Jordan</option>
                                            <option value="Kazakhstan">Kazakhstan</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="Kiribati">Kiribati</option>
                                            <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                            <option value="Kuwait">Kuwait</option>
                                            <option value="Kyrgyzstan">Kyrgyzstan</option>
                                            <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                            <option value="Latvia">Latvia</option>
                                            <option value="Lebanon">Lebanon</option>
                                            <option value="Lesotho">Lesotho</option>
                                            <option value="Liberia">Liberia</option>
                                            <option value="Libya">Libya</option>
                                            <option value="Liechtenstein">Liechtenstein</option>
                                            <option value="Lithuania">Lithuania</option>
                                            <option value="Luxembourg">Luxembourg</option>
                                            <option value="Macao">Macao</option>
                                            <option value="Madagascar">Madagascar</option>
                                            <option value="Malawi">Malawi</option>
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Maldives">Maldives</option>
                                            <option value="Mali">Mali</option>
                                            <option value="Malta">Malta</option>
                                            <option value="Marshall Islands">Marshall Islands</option>
                                            <option value="Martinique">Martinique</option>
                                            <option value="Mauritania">Mauritania</option>
                                            <option value="Mauritius">Mauritius</option>
                                            <option value="Mexico">Mexico</option>
                                            <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                            <option value="Moldova, Republic of">Moldova, Republic of</option>
                                            <option value="Monaco">Monaco</option>
                                            <option value="Mongolia">Mongolia</option>
                                            <option value="Montenegro">Montenegro</option>
                                            <option value="Montserrat">Montserrat</option>
                                            <option value="Morocco">Morocco</option>
                                            <option value="Mozambique">Mozambique</option>
                                            <option value="Myanmar">Myanmar</option>
                                            <option value="Namibia">Namibia</option>
                                            <option value="Nauru">Nauru</option>
                                            <option value="Nepal">Nepal</option>
                                            <option value="Netherlands">Netherlands</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Nicaragua">Nicaragua</option>
                                            <option value="Niger">Niger</option>
                                            <option value="Nigeria">Nigeria</option>
                                            <option value="Niue">Niue</option>
                                            <option value="Norfolk Island">Norfolk Island</option>
                                            <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                            <option value="Norway">Norway</option>
                                            <option value="Oman">Oman</option>
                                            <option value="Pakistan">Pakistan</option>
                                            <option value="Palau">Palau</option>
                                            <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                            <option value="Panama">Panama</option>
                                            <option value="Papua New Guinea">Papua New Guinea</option>
                                            <option value="Paraguay">Paraguay</option>
                                            <option value="Peru">Peru</option>
                                            <option value="Philippines">Philippines</option>
                                            <option value="Poland">Poland</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Puerto Rico">Puerto Rico</option>
                                            <option value="Qatar">Qatar</option>
                                            <option value="Romania">Romania</option>
                                            <option value="Russian Federation">Russian Federation</option>
                                            <option value="Rwanda">Rwanda</option>
                                            <option value="Saint Barthélemy">Saint Barthélemy</option>
                                            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                            <option value="Saint Lucia">Saint Lucia</option>
                                            <option value="Saint Martin (French part)">Saint Martin (French part)</option>
                                            <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                            <option value="Samoa">Samoa</option>
                                            <option value="San Marino">San Marino</option>
                                            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                            <option value="Saudi Arabia">Saudi Arabia</option>
                                            <option value="Senegal">Senegal</option>
                                            <option value="Serbia">Serbia</option>
                                            <option value="Seychelles">Seychelles</option>
                                            <option value="Sierra Leone">Sierra Leone</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
                                            <option value="Slovakia">Slovakia</option>
                                            <option value="Slovenia">Slovenia</option>
                                            <option value="Solomon Islands">Solomon Islands</option>
                                            <option value="Somalia">Somalia</option>
                                            <option value="South Africa">South Africa</option>
                                            <option value="South Korea">South Korea</option>
                                            <option value="South Sudan">South Sudan</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Sri Lanka">Sri Lanka</option>
                                            <option value="Sudan">Sudan</option>
                                            <option value="Suriname">Suriname</option>
                                            <option value="Suriname">Suriname</option>
                                            <option value="Sweden">Sweden</option>
                                            <option value="Switzerland">Switzerland</option>
                                            <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                            <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                            <option value="Tajikistan">Tajikistan</option>
                                            <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                            <option value="Thailand">Thailand</option>
                                            <option value="Togo">Togo</option>
                                            <option value="Tokelau">Tokelau</option>
                                            <option value="Tonga">Tonga</option>
                                            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                            <option value="Tunisia">Tunisia</option>
                                            <option value="Turkey">Turkey</option>
                                            <option value="Turkmenistan">Turkmenistan</option>
                                            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                            <option value="Tuvalu">Tuvalu</option>
                                            <option value="Uganda">Uganda</option>
                                            <option value="Ukraine">Ukraine</option>
                                            <option value="United Arab Emirates">United Arab Emirates</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="United States">United States</option>
                                            <option value="Uruguay">Uruguay</option>
                                            <option value="Uzbekistan">Uzbekistan</option>
                                            <option value="Vanuatu">Vanuatu</option>
                                            <option value="Venezuela, Bolivarian Republic of">Venezuela, Bolivarian Republic of</option>
                                            <option value="Vietnam">Vietnam</option>
                                            <option value="Virgin Islands">Virgin Islands</option>
                                            <option value="Yemen">Yemen</option>
                                            <option value="Zambia">Zambia</option>
                                            <option value="Zimbabwe">Zimbabwe</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-8">
                                        <label for="zip_code" class="form-label fs-7">Zip Code</label>
                                        <input type="text" name="zip_code" id="zip_code" value="{{$zipcode}}" class="form-control fs-7" placeholder="Zip Code">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    </div>
                                </div>
                            </form>

                            <hr style="opacity: 0.1;">
                            <h4 class="text-gray-700 mt-8">Documents & note</h4>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-light-primary add-document-note" data-bs-toggle="modal" data-bs-target="#document_and_note_modal">Add</button>
                            </div>
                            <table class="table align-middle rounded table-row-dashed fs-6 g-5 mt-7" id="document_and_note_table">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase">
                                        <th class="min-w-125px">Action</th>
                                        <th class="min-w-125px">Title</th>
                                        <th class="min-w-125px">Document</th>
                                        <th class="min-w-125px">Added By</th>
                                        <th class="min-w-125px">Created At</th>
                                        <th class="min-w-125px">Updated At</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600">
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary fw-semibold fs-7 " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                    <span class="svg-icon fs-3 rotate-180 ms-3 me-0">
                                                        <i class="fas fa-angle-down"></i>
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a href="#" class="dropdown-item p-2"><i class="fa-solid fa-eye me-3"></i> View</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="dropdown-item p-2"><i class="fa-solid fa-pen-to-square me-3"></i> Edit</a>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="delete-btn dropdown-item p-2" data-id="' . $row->id . '">
                                                            <i class="fa-solid fa-trash me-3"></i> Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>Document</td>
                                        <td>Document</td>
                                        <td>admin</td>
                                        <td>2023-7-14</td>
                                        <td>2023-7-15</td>
                                    </tr>
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade {{session('updated') == 'roomTab' ? ' active show' : ''}}" id="roomInfoTab" role="tab-panel">
        <div class="d-flex flex-column gap-7 gap-lg-10">
            <div class="row">
                <div class="col-md-3 mb-8">
                    <div class="card h-auto">
                        <div class="card-body p-5">
                            <div class="row">
                                @foreach($reservations as $reservation)
                                <div class="col-12">
                                    <label class="btn btn-outline btn-outline-dashed w-100 d-flex flex-stack text-start p-6 mb-5 col-md-12 col-4 nav-item ">
                                        <!--end::Description-->
                                        <div class="d-flex align-items-center me-2">
                                            <!--begin::Radio-->
                                            <div class="form-check form-check-sm form-check-custom form-check-solid form-check-primary me-6">
                                                <input class="form-check-input reservation-checkbox" type="checkbox" name="reservation" id="reservation-checkbox-{{ $reservation->id }}" value="{{ $reservation->id }}" {{ $reservation->id == $currentReservation->id ? 'checked' : '' }} />
                                            </div>
                                            <!--end::Radio-->

                                            <!--begin::Info-->
                                            <div class="flex-grow-1">
                                                @foreach($reservation->room_reservations as $room_reservation)
                                                <h2 class="d-flex align-items-center fs-7 fw-bold flex-wrap">
                                                    {{$room_reservation->room->name}}<span class="text-gray-600 fs-8 ps-3"></span>
                                                </h2>
                                                @endforeach
                                                <div class="fw-semibold fs-7 opacity-50">
                                                    {{$reservation->reservation_code}}
                                                </div>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Description-->
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card card-p-4 card-flush">

                        <div class="card-body px-2">
                            <div class="d-flex justify-content-end align-items-center w-100 px-5 mb-7">
                                <a class="btn btn-sm btn-light-info me-2" id="edit-reserved-room" data-href="{{route('editReservedRoom', '__reservationId__')}}"><i class="fa-regular fa-pen-to-square "></i> Edit Reserved Room</a>
                            </div>
                            <div class="table-responsive px-5">
                                <!--begin::Table-->
                                <table class="table table-row-bordered align-middle gy-4" id="roomTable">
                                    <thead class="border-bottom border-gray-200 fs-6 text-gray-400 fw-bold bg-light bg-opacity-75">
                                        <tr class="fs-6 fw-bold">
                                            <td class="min-w-150px ps-5">Room</td>
                                            <td class="min-w-150px">Room Type</td>
                                            <td class="min-w-150px">Check In Date</td>
                                            <td class="min-w-150px">Check Out Date</td>
                                            <td class="min-w-150px">Room Rate</td>
                                            {{-- <td class="min-w-150px">Amount(Before Discount)</td> --}}
                                            <td class="min-w-150px">Remark</td>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        <!--begin::Table row-->
                                        @foreach($currentReservation->room_reservations as $room_reservation)
                                        <tr class="fs-7 fw-semibold" id="row_{{$room_reservation->id}}">
                                            <td class="ps-5 room-name">{{$room_reservation->room->name}}</td>
                                            <td class="room-type">{{$room_reservation->room_type->name}}</td>
                                            <td class="room-check-in-date">{{$room_reservation->room_check_in_date}}</td>
                                            <td class="room-check-out-date">{{$room_reservation->room_check_out_date}}</td>
                                            <td class="room-rate">{{$room_reservation->room_rate->rate_name}}</td>
                                            {{-- <td>{{$room_reservation->room_rate->rate_amount}}</td> --}}
                                            <td class="remark">{{$room_reservation->remark}}</td>
                                        </tr>
                                        <!--end::Table row-->
                                        @endforeach
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="billing" role="tab-panel">
        <div class="d-flex flex-column gap-7 gap-lg-10">
            <div class="row ">
                <div class="col-md-3 mb-10">
                    <div class="card h-auto">
                        <div class="card-body p-5">
                            {{-- <div class="d-flex align-items-center form-check form-check-sm form-check-custom form-check-solid form-check-primary me-6 mb-5">
                                <input class="form-check-input me-3" type="checkbox" name="" id="select-all-checkbox" value=""  />
                                <label for="select-all-checkbox" class="form-label fs-7">Select all</label>
                            </div> --}}
                            <div class="row">
                                @foreach($reservations as $reservation)
                                <div class="col-12">
                                    <label class="btn btn-outline btn-outline-dashed w-100 d-flex flex-stack text-start p-6 mb-5 col-md-12 col-4 nav-item ">
                                        <!--end::Description-->
                                        <div class="d-flex align-items-center me-2">
                                            <!--begin::Radio-->
                                            <div class="form-check form-check-sm form-check-custom form-check-solid form-check-primary me-6">
                                                <input class="form-check-input billing-checkbox" type="checkbox" name="billingcheckbox" id="billing-checkbox-{{ $reservation->id }}" value="{{$reservation->id}}" {{ $reservation->id == $currentReservation->id ? 'checked' : '' }} />
                                            </div>
                                            <!--end::Radio-->

                                            <!--begin::Info-->
                                            <div class="flex-grow-1">
                                                <h2 class="d-flex align-items-center fs-7 fw-bold flex-wrap">
                                                    @if($reservation->contact)
                                                    {{$reservation->contact->prefix}}
                                                    {{$reservation->contact->first_name}}
                                                    {{$reservation->contact->middle_name}}
                                                    {{$reservation->contact->last_name}}
                                                    @elseif($reservation->company)
                                                    {{$reservation->company->company_name}}
                                                    @endif
                                                </h2>
                                                <div class="fw-semibold fs-7 opacity-50">
                                                    {{$reservation->reservation_code}}
                                                </div>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Description-->
                                    </label>
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-9 tab-content">
                    <div class="tab-pane  fade show active" id="patient1" role="tab-panel">
                        <div class="d-flex flex-column gap-7 gap-lg-10">
                            <!--begin::Billing History-->
                            <div class="card">
                                <!--begin::Card header-->
                                <div class="card-header card-header-stretch border-bottom border-gray-200">
                                    <!--begin::Toolbar-->
                                    <div class="card-toolbar m-0">
                                        @php
                                        if($folioInvoiceDetail){
                                        $sales = [];
                                        foreach($folioInvoiceDetail as $detail) {

                                        if($detail->transaction_type == 'room'){
                                        $room_sale_id = $detail->transaction_id;
                                        $room_sales = \Modules\RoomManagement\Entities\RoomSale::where('id',$room_sale_id)->get();
                                        }

                                        if($detail->transaction_type == 'sale'){
                                        $sale_id = $detail->transaction_id;
                                        $sale = \App\Models\sale\sales::where('id', $sale_id)->with('sale_details')->first();
                                        if ($sale) {
                                        $sales[] = $sale;
                                        }
                                        }
                                        }
                                        }

                                        @endphp
                                        <!--begin::Tab nav-->
                                        <ul class="nav nav-stretch nav-line-tabs border-transparent" role="tablist">
                                            <!--begin::Tab nav item-->
                                            <li class="nav-item" role="presentation">
                                                <a id="alltab" class="nav-link fs-6 fw-semibold me-3 active" data-bs-toggle="tab" role="tab" href="#allTab">All</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a id="kt_billing_6months_tab" class="nav-link fs-6 fw-semibold me-3 " data-bs-toggle="tab" role="tab" href="#roomBillingTab">Room</a>
                                            </li>
                                            <!--end::Tab nav item-->
                                            <!--begin::Tab nav item-->
                                            <li class="nav-item" role="presentation">
                                                <a id="kt_billing_1year_tab" class="nav-link fs-6 fw-semibold me-3" data-bs-toggle="tab" role="tab" href="#kt_billing_year">Product</a>
                                            </li>
                                            <!--end::Tab nav item-->
                                            <!--begin::Tab nav item-->
                                            <li class="nav-item" role="presentation">
                                                <a id="kt_billing_alltime_tab" class="nav-link fs- fw-semibold" data-bs-toggle="tab" role="tab" href="#kt_billing_all">Service</a>
                                            </li>
                                            <!--end::Tab nav item-->
                                        </ul>
                                        <!--end::Tab nav-->
                                    </div>
                                    <div class="p-5">
                                        <a class="btn btn-sm btn-success printAllTab" type="button" data-href="{{route('printInvoice', '__reservationId__')}}">
                                            Print All
                                        </a>
                                        <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                                            Discharge
                                        </button>
                                    </div>
                                    <!--end::Toolbar-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Tab Content-->
                                <div class="tab-content mb-5 mt-8 px-2">
                                    <!--begin::Tab panel-->
                                    <div id="allTab" class="card-body p-0 tab-pane fade show active" role="tabpanel" aria-labelledby="allTab">
                                        <!--begin::Table container-->
                                        <div class="table-responsive">
                                            {{-- @php
                                                    foreach($folioInvoiceDetail as $detail) {
                                                    $transaction_type = $detail->transaction_type;
                                                    }
                                                    @endphp --}}
                                            <!--begin::Table-->
                                            <table class="table table-row-bordered align-middle fs-7 gy-4 gs-9" id="allTabTable">
                                                <thead class="border-bottom border-gray-200 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                    <tr class="text-center">
                                                        <th class="min-w-150px">Voucher No.</th>
                                                        <th class="min-w-150px">Product</th>
                                                        <th>Quantity</th>
                                                        <th>UOM</th>
                                                        <th class="min-w-150px">Price</th>
                                                        <th class="min-w-150px">Subtotal</th>
                                                        <th class="min-w-150px">Discount</th>
                                                    </tr>
                                                </thead>

                                                <tbody class="fw-semibold text-gray-600">
                                                    <!--begin::Table row-->
                                                    @if(isset($room_sales))
                                                    @foreach($room_sales as $room_sale)
                                                    @foreach($room_sale->room_sale_details as $room_sale_detail)
                                                    <tr class="text-center">
                                                        <td>{{$room_sale->room_sales_voucher_no}}</td>
                                                        <td>
                                                            <span>
                                                                {{$room_sale_detail->room->name}},
                                                                {{$room_sale_detail->room_type->name}},
                                                                {{$room_sale_detail->room_rate->rate_name}},
                                                            </span><br>
                                                            <span>
                                                                {{$room_sale_detail->check_in_date}},
                                                                {{$room_sale_detail->check_out_date}}
                                                            </span>
                                                        </td>
                                                        <td>{{$room_sale_detail->qty}}</td>
                                                        <td>{{$room_sale_detail->uom_id ?? '-'}}</td>
                                                        <td>{{$room_sale_detail->room_fees}}</td>
                                                        <td>{{$room_sale_detail->subtotal}}</td>
                                                        <td>{{$room_sale_detail->per_item_discount ?? '0.0000'}}</td>
                                                    </tr>
                                                    @endforeach
                                                    @endforeach
                                                    @endif

                                                    @if(isset($sales))
                                                    @foreach($sales as $sale)
                                                    @foreach($sale->sale_details as $sale_detail)
                                                    <tr class="text-center">
                                                        <td>{{$sale->sales_voucher_no}}</td>
                                                        <td>{{$sale_detail->product->name}}</td>
                                                        <td>{{$sale_detail->quantity}}</td>
                                                        <td>{{$sale_detail->uom->name}}</td>
                                                        <td>{{$sale_detail->uom_price}}</td>
                                                        <td>{{$sale_detail->subtotal}}</td>
                                                        <td>{{$sale_detail->per_item_discount ?? '0.0000'}}</td>
                                                    </tr>
                                                    @endforeach
                                                    @endforeach
                                                    @endif
                                                    <!--end::Table row-->
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Table container-->
                                        <div class="row justify-content-end mt-5">
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table align-middle table-row-dashed fw-bold text-gray-700 fs-7" id="allTabTotalAmounts">
                                                        @php
                                                        $saleAmount = 0;
                                                        $totalItemDiscount = 0;
                                                        $totalExtraDiscount = 0;
                                                        $paidAmount = 0;

                                                        // Loop through room_sales data and update the calculated values
                                                        if(isset($room_sales)) {
                                                        foreach($room_sales as $room_sale) {
                                                        $saleAmount += $room_sale->sale_amount;
                                                        $totalItemDiscount += $room_sale->total_item_discount;
                                                        $paidAmount += $room_sale->paid_amount;
                                                        }
                                                        }

                                                        // Loop through sales data and update the calculated values
                                                        if(isset($sales)) {
                                                        foreach($sales as $sale) {
                                                        $saleAmount += $sale->sale_amount;
                                                        $totalItemDiscount += $sale->total_item_discount;
                                                        $totalExtraDiscount += $sale->extra_discount_amount;
                                                        $paidAmount += $sale->paid_amount;
                                                        }
                                                        }

                                                        // Calculate total sale amount and balance amount
                                                        $totalSaleAmount = $saleAmount - ($totalItemDiscount + $totalExtraDiscount);
                                                        $balanceAmount = $totalSaleAmount - $paidAmount;
                                                        @endphp

                                                        <tr>
                                                            <td>Sale Amount</td>
                                                            <td>(=)</td>
                                                            <td>{{ number_format($saleAmount, 4) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Item Discount</td>
                                                            <td>(-)</td>
                                                            <td>{{ number_format($totalItemDiscount, 4) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Extra Discount</td>
                                                            <td>(-)</td>
                                                            <td>{{ number_format($totalExtraDiscount, 4) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Sale Amount</td>
                                                            <td>(=)</td>
                                                            <td>{{ number_format($totalSaleAmount, 4) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Paid Amount</td>
                                                            <td>(-)</td>
                                                            <td>{{ number_format($paidAmount, 4) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Balance Amount</td>
                                                            <td>(=)</td>
                                                            <td>{{ number_format($balanceAmount, 4) }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Tab panel-->
                                    <!--begin::Tab panel-->
                                    <div id="roomBillingTab" class="card-body p-0 tab-pane fade" role="tabpanel" aria-labelledby="roomBillingTab">
                                        <!--begin::Table container-->
                                        <div class="table-responsive">
                                            <!--begin::Table-->
                                            <table class="table table-row-bordered align-middle fs-7 gy-4 gs-9" id="roomBillingTable">
                                                <thead class="border-bottom border-gray-200 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                    <tr class="text-center">
                                                        <th class="min-w-150px">Voucher No.</th>
                                                        <th class="min-w-150px">Room</th>
                                                        <th>Quantity</th>
                                                        <th class="min-w-150px">Price</th>
                                                        <th class="min-w-150px">Subtotal</th>
                                                        <th class="min-w-150px">Discount</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <!--begin::Table row-->
                                                    @if(isset($room_sales))
                                                    @foreach($room_sales as $room_sale)
                                                    @foreach($room_sale->room_sale_details as $room_sale_detail)
                                                    <tr class="text-center">
                                                        <td>{{$room_sale->room_sales_voucher_no}}</td>
                                                        <td>
                                                            <span>
                                                                {{$room_sale_detail->room->name}},
                                                                {{$room_sale_detail->room_type->name}},
                                                                {{$room_sale_detail->room_rate->rate_name}},
                                                            </span><br>
                                                            <span>
                                                                {{$room_sale_detail->check_in_date}},
                                                                {{$room_sale_detail->check_out_date}}
                                                            </span>
                                                        </td>
                                                        <td>{{$room_sale_detail->qty}}</td>
                                                        <td>{{$room_sale_detail->room_fees}}</td>
                                                        <td>{{$room_sale_detail->subtotal}}</td>
                                                        <td>{{$room_sale_detail->per_item_discount ?? '0.0000'}}</td>
                                                    </tr>
                                                    @endforeach
                                                    @endforeach
                                                    @endif
                                                    <!--end::Table row-->
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Table container-->
                                        <div class="row justify-content-end mt-5">
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table align-middle table-row-dashed fw-bold text-gray-700 fs-7" id="roomBillingTotalTable">
                                                        @if(isset($room_sales))
                                                        @foreach($room_sales as $room_sale)
                                                        <tr>
                                                            <td>Sale Amount</td>
                                                            <td>(=)</td>
                                                            <td>{{$room_sale->sale_amount}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Item Discount</td>
                                                            <td>(-)</td>
                                                            <td>{{$room_sale->total_item_discount}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Sale Amount</td>
                                                            <td>(=)</td>
                                                            <td>{{$room_sale->total_sale_amount}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Paid Amount</td>
                                                            <td>(-)</td>
                                                            <td>{{$room_sale->paid_amount}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Balance Amount</td>
                                                            <td>(=)</td>
                                                            <td>{{$room_sale->balance_amount}}</td>
                                                        </tr>
                                                        @endforeach
                                                        @else
                                                        <tr>
                                                            <td>Sale Amount</td>
                                                            <td>(=)</td>
                                                            <td>0.0000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Item Discount</td>
                                                            <td>(-)</td>
                                                            <td>0.0000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Sale Amount</td>
                                                            <td>(=)</td>
                                                            <td>0.0000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Paid Amount</td>
                                                            <td>(-)</td>
                                                            <td>0.0000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Balance Amount</td>
                                                            <td>(=)</td>
                                                            <td>0.0000</td>
                                                        </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Tab panel-->
                                    <!--begin::Tab panel-->
                                    <div id="kt_billing_year" class="card-body p-0 tab-pane fade" role="tabpanel" aria-labelledby="kt_billing_year">
                                        <!--begin::Table container-->
                                        <div class="table-responsive">
                                            <!--begin::Table-->
                                            <table class="table table-row-bordered align-middle fs-7 gy-4 gs-9" id="productBillingTable">
                                                <thead class="border-bottom border-gray-200 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                    <tr class="text-center">
                                                        <td class="min-w-150px">Voucher No.</td>
                                                        <td class="min-w-150px">Product</td>
                                                        <td>Quantity</td>
                                                        <td>UOM</td>
                                                        <td class="min-w-150px">Unit Price</td>
                                                        <td class="min-w-150px">Subtotal</td>
                                                        <td class="min-w-150px">Discount</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    @if(isset($sales))
                                                    @foreach($sales as $sale)
                                                    @foreach($sale->sale_details as $sale_detail)
                                                    <tr class="text-center">
                                                        <td>{{$sale->sales_voucher_no}}</td>
                                                        <td>
                                                            {{$sale_detail->product->name}}
                                                        </td>
                                                        <td>{{$sale_detail->quantity}}</td>
                                                        <td>{{$sale_detail->uom->name}}</td>
                                                        <td>{{$sale_detail->uom_price}}</td>
                                                        <td>{{$sale_detail->subtotal}}</td>
                                                        <td>{{$sale_detail->per_item_discount ?? '0.0000'}}</td>
                                                    </tr>
                                                    @endforeach
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Table container-->
                                        <div class="row justify-content-end mt-5">
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table align-middle table-row-dashed fw-bold text-gray-700 fs-7" id="productBillingTotalTable">
                                                        @if(isset($sales))
                                                        @php
                                                        $saleAmount = 0;
                                                        $totalItemDiscount = 0;
                                                        $totalExtraDiscount = 0;
                                                        $totalSaleAmount = 0;
                                                        $paidAmount = 0;
                                                        $balanceAmount = 0;
                                                        @endphp
                                                        @foreach($sales as $sale)
                                                        @php
                                                        $saleAmount += $sale->sale_amount;
                                                        $totalItemDiscount += $sale->total_item_discount;
                                                        $totalExtraDiscount += $sale->extra_discount_amount;
                                                        $totalSaleAmount += $sale->total_sale_amount;
                                                        $paidAmount += $sale->paid_amount;
                                                        $balanceAmount += $sale->balance_amount;
                                                        @endphp
                                                        @endforeach

                                                        <tr>
                                                            <td>Sale Amount</td>
                                                            <td>(=)</td>
                                                            <td>{{ number_format($saleAmount, 4) }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td>Total Item Discount</td>
                                                            <td>(-)</td>
                                                            <td>{{ number_format($totalItemDiscount, 4) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Extra Discount</td>
                                                            <td>(-)</td>
                                                            <td>{{ number_format($totalExtraDiscount, 4) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Sale Amount</td>
                                                            <td>(=)</td>
                                                            <td>{{ number_format($totalSaleAmount, 4) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Paid Amount</td>
                                                            <td>(-)</td>
                                                            <td>{{ number_format($paidAmount, 4) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Balance Amount</td>
                                                            <td>(=)</td>
                                                            <td>{{ number_format($balanceAmount, 4) }}</td>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <td>Sale Amount</td>
                                                            <td>(=)</td>
                                                            <td>0.0000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Item Discount</td>
                                                            <td>(-)</td>
                                                            <td>0.0000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Extra Discount</td>
                                                            <td>(-)</td>
                                                            <td>0.0000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Sale Amount</td>
                                                            <td>(=)</td>
                                                            <td>0.0000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Paid Amount</td>
                                                            <td>(-)</td>
                                                            <td>0.0000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Balance Amount</td>
                                                            <td>(=)</td>
                                                            <td>0.0000</td>
                                                        </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Tab panel-->
                                    <!--begin::Tab panel-->
                                    <div id="kt_billing_all" class="card-body p-0 tab-pane fade" role="tabpanel" aria-labelledby="kt_billing_all">
                                        <!--begin::Table container-->
                                        <div class="table-responsive">
                                            <!--begin::Table-->
                                            <table class="table table-row-bordered align-middle gy-4 gs-9">
                                                <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                    <tr>
                                                        <td class="min-w-125px">Date</td>
                                                        <td class="min-w-125px">Voucher No.</td>
                                                        <td class="min-w-125px">Service</td>
                                                        <td class="min-w-125px">Qty</td>
                                                        <td class="min-w-125px">Unit Price</td>
                                                        <td class="min-w-125px">Discount</td>
                                                        <td class="min-w-125px">FOC</td>
                                                        <td class="min-w-125px">Amount</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <tr>
                                                        <td>09.06.2023</td>
                                                        <td>
                                                            INV0001
                                                        </td>
                                                        <td>Service</td>
                                                        <td>1</td>
                                                        <td>50000</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>50000</td>
                                                    </tr>
                                                    <tr>
                                                        <td>09.06.2023</td>
                                                        <td>
                                                            INV0001
                                                        </td>
                                                        <td>Service</td>
                                                        <td>1</td>
                                                        <td>50000</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>50000</td>
                                                    </tr>
                                                    <tr>
                                                        <td>09.06.2023</td>
                                                        <td>
                                                            INV0001
                                                        </td>
                                                        <td>Service</td>
                                                        <td>1</td>
                                                        <td>50000</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>50000</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Table container-->
                                    </div>
                                    <!--end::Tab panel-->
                                </div>
                                <!--end::Tab Content-->
                            </div>
                            <!--end::Billing Address-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!--end::container-->

<div class="modal modal-lg fade" tabindex="-1" role="dialog" id="editReservationInfo">
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="editReservedRoom">
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="add_new_guest_modal">
</div>


@include('reservation::reservation.addDocument&Note')
</div>
<!--end::Content-->



<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h3 class="offcanvas-title" id="offcanvasExampleLabel"> Bill Counter</h3>
        <button class="btn btn-light btn-sm" id="kt_drawer_example_basic_close" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fas fa-close fs-4"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="">
            <form>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Room Charge :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Room Charge">
                </div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Sale Charge :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Sale Charge">
                </div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Service Charge :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Service Charge">
                </div>
                <div class="col-12 separator my-6 border-gray-300"></div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Net Amount :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Net Amount">
                </div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Discount :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Discount">
                </div>
                <div class="col-12 separator my-6 border-gray-300"></div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Total Amount :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Total Amount">
                </div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Paid Amount :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Payment">
                </div>
                <div class="col-12 separator my-6 border-gray-300"></div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Balance :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Balance">
                </div>

                <div class="col-12 separator my-6 border-gray-300"></div>
                <div class="col-12 mb-5">
                    <a class="btn btn-sm btn-primary w-100" data-bs-toggle="modal" data-bs-target="#selectPaymentModal">
                        Payment
                    </a>
                </div>
                <div class="col-12">
                    <button class="btn btn-sm btn-success w-100">
                        Print
                    </button>
                </div>

                {{-- <button class="btn btn-sm btn-primary w-100">
                        Check Out
                    </button> --}}
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')

<script src={{asset("assets/plugins/custom/formrepeater/formrepeater.bundle.js")}}></script>
<script>
    var numOfRows = 0;

    var rows = $('#room_table tbody tr').length;
    $('.num-of-room').text(rows);

    var datepickerIds = ["check_out_date", "opd_check_in_date", "ipd_check_in_date"];
    datepickerIds.forEach(function(id) {
        $(`#${id}`).flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    });

    var myDropzone = new Dropzone("#kt_dropzonejs_example_1", {
        url: "/reservation", // Set the url for your upload script location
        paramName: "file", // The name that will be used to transfer the file
        maxFiles: 10,
        maxFilesize: 10, // MB
        addRemoveLinks: true,
        accept: function(file, done) {
            if (file.name == "wow.jpg") {
                done("Naha, you don't.");
            } else {
                done();
            }
        }
    });

    $('.reservation-checkbox').change(function() {

        if ($(this).is(':checked')) {
            $('.reservation-checkbox').not(this).prop('checked', false);

            var reservationId = $(this).val();

            console.log(reservationId);
            fetchReservationDetails(reservationId);

            $('.add_new_guest').data('reservation-id', reservationId);

            $('#updateGuestForm').attr('action', '/reservation/guestinfo/' + reservationId);
            console.log($('#updateGuestForm').attr('action'));
        }
    });

    // $('#select-all-checkbox').change(function() {
    //     // console.log('checked');
    //     var isChecked = $(this).prop('checked');
    //     // console.log(isChecked);

    //     $('.reservation-checkbox').prop('checked', isChecked);
    // });

    var selectedReservations = [];

    $('.billing-checkbox').change(function() {
        var selectedReservations = $('.billing-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        console.log(selectedReservations);

        fetchMultipleReservationDetails(selectedReservations);
    });

    function fetchGuest(reservationId) {
        $.ajax({
            method: 'GET',
            url: `/reservation/guestinfo/${reservationId}`,
            data: {
                reservationId: reservationId
            },
            dataType: 'json',
            success: function(response) {
                $('#guest_id').val(response.reservation.guest_id);
            }
        })
    }

    function fetchMultipleReservationDetails(selectedReservations) {
        $.ajax({
            method: 'GET',
            url: '/reservations/multiple/' + selectedReservations.join(','),
            data: {
                selectedReservations: selectedReservations
            },
            dataType: 'json',
            success: function(response) {
                // console.log(response.reservations);
                // console.log(response.reservations.room_sales);

                // Billing Tab

                // All Tab
                $('#allTabTable tbody').empty();

                if (response.reservations) {
                    response.reservations.forEach(function(reservation) {
                        // console.log(reservation.room_sales);
                        reservation.room_sales.forEach(function(room_sale) {
                            if (room_sale.transaction_type == 'reservation') {
                                room_sale.room_sale_details.forEach(function(detail) {
                                    // console.log(detail);
                                    var row = $('<tr class="text-center"></tr>');

                                    row.append('<td>' + room_sale.room_sales_voucher_no + '</td>');
                                    row.append('<td><span>' +
                                        detail.room.name + ', ' + detail.room_type.name + ', ' + detail.room_rate.rate_name +
                                        '</span><span>' + ', ' + detail.check_in_date + ', ' + detail.check_out_date +
                                        '</span></td>');
                                    row.append('<td>' + detail.qty + '</td>');
                                    row.append('<td>' + '-' + '</td>');
                                    row.append('<td>' + detail.room_fees + '</td>');
                                    row.append('<td>' + detail.subtotal + '</td>');
                                    row.append('<td>' + (detail.per_item_discount ? detail.per_item_discount : '0.0000') + '</td>');

                                    $('#allTabTable tbody').append(row);

                                })
                            }

                        })
                    });
                }

                if (response.sales) {
                    response.sales.forEach(function(item) {
                        // console.log(item.sale_details);
                        item.sale_details.forEach(function(detail) {
                            var row = $('<tr class="text-center"></tr>');
                            row.append('<td>' + item.sales_voucher_no + '</td>');
                            row.append('<td>' + detail.product.name + '</td>');
                            row.append('<td>' + detail.quantity + '</td>');
                            row.append('<td>' + detail.uom.name + '</td>');
                            row.append('<td>' + detail.uom_price + '</td>');
                            row.append('<td>' + detail.subtotal + '</td>');
                            row.append('<td>' + detail.per_item_discount + '</td>');
                            $('#allTabTable tbody').append(row);
                        });
                    });
                }


                $('#allTabTotalAmounts tbody').empty();

                var saleAmount = 0;
                var totalItemDiscount = 0;
                var totalExtraDiscount = 0;
                var totalSaleAmount = 0;
                var paidAmount = 0;
                var balanceAmount = 0;

                if (response.reservations) {
                    response.reservations.forEach(function(reservation) {
                        reservation.room_sales.forEach(function(room_sale) {
                            saleAmount += parseFloat(room_sale.sale_amount) || 0;
                            totalItemDiscount += parseFloat(room_sale.total_item_discount) || 0;
                            totalSaleAmount += parseFloat(room_sale.total_sale_amount) || 0;
                            paidAmount += parseFloat(room_sale.paid_amount) || 0;
                            balanceAmount += parseFloat(room_sale.balance_amount) || 0;
                        });
                    })

                }

                if (response.sales) {
                    response.sales.forEach(function(sale) {
                        saleAmount += parseFloat(sale.sale_amount) || 0;
                        totalItemDiscount += parseFloat(sale.total_item_discount) || 0;
                        totalExtraDiscount += parseFloat(sale.extra_discount_amount) || 0;
                        totalSaleAmount += parseFloat(sale.total_sale_amount) || 0;
                        paidAmount += parseFloat(sale.paid_amount) || 0;
                        balanceAmount += parseFloat(sale.balance_amount) || 0;
                    });
                }
                var totalDiscount = totalItemDiscount + totalExtraDiscount;
                var balanceAmount = totalSaleAmount - paidAmount;

                var saleAmoutRow = $('<tr></tr>');
                saleAmoutRow.append('<td>Sale Amount</td>');
                saleAmoutRow.append('<td>(=)</td>');
                saleAmoutRow.append('<td>' + saleAmount.toFixed(4) + '</td>');

                var totalDiscountRow = $('<tr></tr>');
                totalDiscountRow.append('<td>Total Item Discount</td>');
                totalDiscountRow.append('<td>(-)</td>');
                totalDiscountRow.append('<td>' + totalItemDiscount.toFixed(4) + '</td>');

                var extraDiscountRow = $('<tr></tr>');
                extraDiscountRow.append('<td>Total Extra Discount</td>');
                extraDiscountRow.append('<td>(-)</td>');
                extraDiscountRow.append('<td>' + totalExtraDiscount.toFixed(4) + '</td>');

                var totalSaleAmountRow = $('<tr></tr>');
                totalSaleAmountRow.append('<td>Total Sale Amount</td>');
                totalSaleAmountRow.append('<td>(=)</td>');
                totalSaleAmountRow.append('<td>' + totalSaleAmount.toFixed(4) + '</td>');

                var paidAmountRow = $('<tr></tr>');
                paidAmountRow.append('<td>Paid Amount</td>');
                paidAmountRow.append('<td>(-)</td>');
                paidAmountRow.append('<td>' + paidAmount.toFixed(4) + '</td>');

                var balanceAmountRow = $('<tr></tr>');
                balanceAmountRow.append('<td>Balance Amount</td>');
                balanceAmountRow.append('<td>(=)</td>');
                balanceAmountRow.append('<td>' + balanceAmount.toFixed(4) + '</td>');

                $('#allTabTotalAmounts tbody').append(saleAmoutRow);
                $('#allTabTotalAmounts tbody').append(totalDiscountRow);
                $('#allTabTotalAmounts tbody').append(extraDiscountRow);
                $('#allTabTotalAmounts tbody').append(totalSaleAmountRow);
                $('#allTabTotalAmounts tbody').append(paidAmountRow);
                $('#allTabTotalAmounts tbody').append(balanceAmountRow);

                // Room Tab
                $('#roomBillingTable tbody').empty();
                if (response.reservations) {
                    response.reservations.forEach(function(reservation) {
                        // console.log(reservation.room_sales);
                        reservation.room_sales.forEach(function(room_sale) {
                            if (room_sale.transaction_type == 'reservation') {
                                room_sale.room_sale_details.forEach(function(detail) {
                                    // console.log(detail);
                                    var row = $('<tr class="text-center"></tr>');

                                    row.append('<td>' + room_sale.room_sales_voucher_no + '</td>');
                                    row.append('<td><span>' +
                                        detail.room.name + ', ' + detail.room_type.name + ', ' + detail.room_rate.rate_name +
                                        '</span><span>' + ', ' + detail.check_in_date + ', ' + detail.check_out_date +
                                        '</span></td>');
                                    row.append('<td>' + detail.qty + '</td>');
                                    // row.append('<td>' + '-' + '</td>');
                                    row.append('<td>' + detail.room_fees + '</td>');
                                    row.append('<td>' + detail.subtotal + '</td>');
                                    row.append('<td>' + (detail.per_item_discount ? detail.per_item_discount : '0.0000') + '</td>');

                                    $('#roomBillingTable tbody').append(row);

                                })
                            }
                        })
                    });
                }

                var roomsaleAmount = '0.0000';
                var roomtotalItemDiscount = '0.0000';
                var roompaidAmount = '0.0000';

                $('#roomBillingTotalTable tbody').empty();
                if (response.reservations) {
                    response.reservations.forEach(function(reservation) {
                        reservation.room_sales.forEach(function(room_sale) {
                            roomsaleAmount = (parseFloat(roomsaleAmount) + parseFloat(room_sale.sale_amount)).toFixed(4);
                            roomtotalItemDiscount = (parseFloat(roomtotalItemDiscount) + parseFloat(room_sale.total_item_discount)).toFixed(4);
                            roompaidAmount = (parseFloat(roompaidAmount) + parseFloat(room_sale.paid_amount)).toFixed(4);
                        });
                    })
                }

                var roomtotalSaleAmount = (parseFloat(roomsaleAmount) - parseFloat(roomtotalItemDiscount)).toFixed(4);
                var roombalanceAmount = (parseFloat(roomtotalSaleAmount) - parseFloat(roompaidAmount)).toFixed(4);


                var roomsaleAmoutRow = $('<tr></tr>');
                roomsaleAmoutRow.append('<td>Sale Amount</td>');
                roomsaleAmoutRow.append('<td>(=)</td>');
                roomsaleAmoutRow.append('<td>' + roomsaleAmount + '</td>');

                var roomtotalDiscountRow = $('<tr></tr>');
                roomtotalDiscountRow.append('<td>Total Item Discount</td>');
                roomtotalDiscountRow.append('<td>(-)</td>');
                roomtotalDiscountRow.append('<td>' + roomtotalItemDiscount + '</td>');

                var roomtotalSaleAmountRow = $('<tr></tr>');
                roomtotalSaleAmountRow.append('<td>Total Sale Amount</td>');
                roomtotalSaleAmountRow.append('<td>(=)</td>');
                roomtotalSaleAmountRow.append('<td>' + roomtotalSaleAmount + '</td>');

                var roompaidAmountRow = $('<tr></tr>');
                roompaidAmountRow.append('<td>Paid Amount</td>');
                roompaidAmountRow.append('<td>(-)</td>');
                roompaidAmountRow.append('<td>' + roompaidAmount + '</td>');

                var roombalanceAmountRow = $('<tr></tr>');
                roombalanceAmountRow.append('<td>Balance Amount</td>');
                roombalanceAmountRow.append('<td>(=)</td>');
                roombalanceAmountRow.append('<td>' + roombalanceAmount + '</td>');

                $('#roomBillingTotalTable tbody').append(roomsaleAmoutRow);
                $('#roomBillingTotalTable tbody').append(roomtotalDiscountRow);
                $('#roomBillingTotalTable tbody').append(roomtotalSaleAmountRow);
                $('#roomBillingTotalTable tbody').append(roompaidAmountRow);
                $('#roomBillingTotalTable tbody').append(roombalanceAmountRow);

                // Sale(Product Tab)
                $('#productBillingTable tbody').empty();

                if (response.sales) {
                    // console.log('sales', response.sales);
                    response.sales.forEach(function(item) {
                        // console.log(item.sale_details);
                        item.sale_details.forEach(function(detail) {
                            var row = $('<tr class="text-center"></tr>');
                            row.append('<td>' + item.sales_voucher_no + '</td>');
                            row.append('<td>' + detail.product.name + '</td>');
                            row.append('<td>' + detail.quantity + '</td>');
                            row.append('<td>' + detail.uom.name + '</td>');
                            row.append('<td>' + detail.uom_price + '</td>');
                            row.append('<td>' + detail.subtotal + '</td>');
                            row.append('<td>' + detail.per_item_discount + '</td>');
                            $('#productBillingTable tbody').append(row);
                        });
                    });
                }

                var productsaleAmount = 0;
                var producttotalItemDiscount = 0;
                var producttotalExtraDiscount = 0;
                var producttotalSaleAmount = 0;
                var productpaidAmount = 0;
                var productbalanceAmount = 0;

                $('#productBillingTotalTable tbody').empty();
                if (response.sales) {
                    response.sales.forEach(function(sale) {
                        productsaleAmount += parseFloat(sale.sale_amount) || 0;
                        producttotalItemDiscount += parseFloat(sale.total_item_discount) || 0;
                        producttotalExtraDiscount += parseFloat(sale.extra_discount_amount) || 0;
                        producttotalSaleAmount += parseFloat(sale.total_sale_amount) || 0;
                        productpaidAmount += parseFloat(sale.paid_amount) || 0;
                        productbalanceAmount += parseFloat(sale.balance_amount) || 0;
                    });
                }
                var producttotalDiscount = producttotalItemDiscount + producttotalExtraDiscount;
                var productbalanceAmount = producttotalSaleAmount - productpaidAmount;

                var productsaleAmoutRow = $('<tr></tr>');
                productsaleAmoutRow.append('<td>Sale Amount</td>');
                productsaleAmoutRow.append('<td>(=)</td>');
                productsaleAmoutRow.append('<td>' + (productsaleAmount ? productsaleAmount.toFixed(4) : '0.0000') + '</td>');

                var producttotalDiscountRow = $('<tr></tr>');
                producttotalDiscountRow.append('<td>Total Item Discount</td>');
                producttotalDiscountRow.append('<td>(-)</td>');
                producttotalDiscountRow.append('<td>' + producttotalItemDiscount.toFixed(4) + '</td>');

                var producttotalExtraDiscountRow = $('<tr></tr>');
                producttotalExtraDiscountRow.append('<td>Total Extra Discount</td>');
                producttotalExtraDiscountRow.append('<td>(-)</td>');
                producttotalExtraDiscountRow.append('<td>' + producttotalExtraDiscount.toFixed(4) + '</td>');

                var producttotalSaleAmountRow = $('<tr></tr>');
                producttotalSaleAmountRow.append('<td>Total Sale Amount</td>');
                producttotalSaleAmountRow.append('<td>(=)</td>');
                producttotalSaleAmountRow.append('<td>' + producttotalSaleAmount.toFixed(4) + '</td>');

                var productpaidAmountRow = $('<tr></tr>');
                productpaidAmountRow.append('<td>Paid Amount</td>');
                productpaidAmountRow.append('<td>(-)</td>');
                productpaidAmountRow.append('<td>' + productpaidAmount.toFixed(4) + '</td>');

                var productbalanceAmountRow = $('<tr></tr>');
                productbalanceAmountRow.append('<td>Balance Amount</td>');
                productbalanceAmountRow.append('<td>(=)</td>');
                productbalanceAmountRow.append('<td>' + productbalanceAmount.toFixed(4) + '</td>');

                $('#productBillingTotalTable tbody').append(productsaleAmoutRow);
                $('#productBillingTotalTable tbody').append(producttotalDiscountRow);
                $('#productBillingTotalTable tbody').append(producttotalExtraDiscountRow);
                $('#productBillingTotalTable tbody').append(producttotalSaleAmountRow);
                $('#productBillingTotalTable tbody').append(productpaidAmountRow);
                $('#productBillingTotalTable tbody').append(productbalanceAmountRow);

            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    moment.locale('en-gb');

    function fetchReservationDetails(reservationId) {
        $.ajax({
            method: 'GET',
            url: `/reservations/${reservationId}`,
            data: {
                reservationId: reservationId
            },
            dataType: 'json',
            success: function(response) {
                // Reservation Tab
                $('#reservation-code').text(response.reservation.reservation_code);
                $('#guest').text(function() {
                    var fullName = '';
                    var compantName = '';

                    if (response.reservation.contact) {
                        if (response.reservation.contact.prefix) {
                            fullName += response.reservation.contact.prefix;
                        }

                        if (response.reservation.contact.first_name) {
                            fullName += ' ' + response.reservation.contact.first_name;
                        }

                        if (response.reservation.contact.middle_name) {
                            fullName += ' ' + response.reservation.contact.middle_name;
                        }

                        if (response.reservation.contact.last_name) {
                            fullName += ' ' + response.reservation.contact.last_name;
                        }

                    } else if (response.reservation.company) {
                        fullName = response.reservation.company.company_name;
                    }
                    return fullName;
                });
                const formattedCheckinDate = moment(response.reservation.check_in_date).format('YYYY-MMM-DD (hh:mm A)');
                $('#checkin-date').text(formattedCheckinDate);
                const formattedCheckoutDate = moment(response.reservation.check_out_date).format('YYYY-MMM-DD (hh:mm A)');
                $('#checkout-date').text(formattedCheckoutDate);
                $('#reservation-status').text(response.reservation.reservation_status);
                $('#booking-confirm-by').text(response.reservation.confirmed_by);
                $('#remark').text(response.reservation.remark);

                // Guest Tab
                if (response.reservation.contact) {
                    $('#company_name').val('');
                    $('#prefix').val(response.reservation.contact.prefix);
                    $('#first_name').val(response.reservation.contact.first_name);
                    $('#middle_name').val(response.reservation.contact.middle_name);
                    $('#last_name').val(response.reservation.contact.last_name);
                    $('#mobile').val(response.reservation.contact.mobile);
                    $('#alternate_number').val(response.reservation.contact.alternate_number);
                    $('#email').val(response.reservation.contact.email);
                    var dob = response.reservation.contact ?
                        response.reservation.contact.dob ?
                        formatDate(response.reservation.contact.dob) :
                        null :
                        response.reservation.company ?
                        response.reservation.company.dob ?
                        formatDate(response.reservation.company.dob) :
                        null :
                        null;

                    $('#dob').val(dob);
                    $('#address_line_1').val(response.reservation.contact.address_line_1);
                    $('#address_line_2').val(response.reservation.contact.address_line_2);
                    $('#city').val(response.reservation.contact.city);
                    $('#state').val(response.reservation.contact.state);
                    $('#country').val(response.reservation.contact.country);
                    $('#zip_code').val(response.reservation.contact.zip_code);
                } else if (response.reservation.company) {
                    $('#prefix').val('');
                    $('#first_name').val('');
                    $('#middle_name').val('');
                    $('#last_name').val('');
                    $('#company_name').val(response.reservation.company.company_name);
                    $('#mobile').val(response.reservation.company.mobile);
                    $('#alternate_number').val(response.reservation.company.alternate_number);
                    $('#email').val(response.reservation.company.email);
                    var dob = response.reservation.contact ? response.reservation.contact.dob ? formatDate(response.reservation.contact.dob) : null :
                        response.reservation.company ?
                        response.reservation.company.dob ?
                        formatDate(response.reservation.company.dob) :
                        null :
                        null;

                    $('#dob').val(dob);
                    $('#address_line_1').val(response.reservation.company.address_line_1);
                    $('#address_line_2').val(response.reservation.company.address_line_2);
                    $('#city').val(response.reservation.company.city);
                    $('#state').val(response.reservation.company.state);
                    $('#country').val(response.reservation.company.country);
                    $('#zip_code').val(response.reservation.company.zip_code);
                }

                // Room Tab
                $('#roomTable tbody').empty();

                response.reservation.room_reservations.forEach(function(item) {
                    var row = $('<tr class="fs-7 fw-semibold"></tr>');

                    row.append('<td class="ps-5">' + item.room.name + '</td>');
                    row.append('<td>' + item.room_type.name + '</td>');
                    row.append('<td>' + item.room_check_in_date + '</td>');
                    row.append('<td>' + item.room_check_out_date + '</td>');
                    row.append('<td>' + item.room_rate.rate_name + '</td>');
                    row.append('<td>' + item.remark ? item.remark : '' + '</td>');

                    $('#roomTable tbody').append(row);
                });

            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    function formatDate(dateString) {
        var date = new Date(dateString);
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();

        day = day < 10 ? '0' + day : day;
        month = month < 10 ? '0' + month : month;

        return day + '/' + month + '/' + year;
    }

    $(document).on('click', '#edit-reservation-info', function() {
        $('#editReservationInfo').load($(this).data('href'), function() {
            $(this).modal('show');
        });
    });

    $(document).on('click', '#edit-reserved-room', function() {
        var reservationId = $('.reservation-checkbox:checked').val();
        var editUrl = $(this).data('href').replace('__reservationId__', reservationId);
        $('#editReservedRoom').load(editUrl, function() {
            $(this).modal('show');
        });
    });

    $(document).on('click', '.add_new_guest', function() {
        var reservationId = $(this).data('reservation-id');

        $('#add_new_guest_modal').load('{{ route("addNewGuest", ["id" => ":reservationId"]) }}'
            .replace(':reservationId', reservationId),
            function() {
                $(this).modal('show');
            });
    });

    $("#document_and_note_table").DataTable({
        "order": false,
        "paging": false,
        "info": false,
        "dom": "<'table-responsive'tr>"
    });

    // Print All
    $(document).on('click', '.printAllTab', function(e) {
        e.preventDefault();

        var selectedReservations = $('.billing-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedReservations.length === 0) {
            toastr.error("No reservations selected to print.");
            return;
        }

        // console.log(selectedReservations);

        var editUrl = $(this).data('href').replace('__reservationId__', selectedReservations.join(','));
        // console.log(editUrl);

        $.ajax({
            url: editUrl,
            dataType: 'json',
            method: 'GET',
            success: function(response) {
                var iframe = $('<iframe>', {
                    'height': '0px',
                    'width': '0px',
                    'frameborder': '0',
                    'css': {
                        'display': 'none'
                    }
                }).appendTo('body')[0];

                var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                iframeDoc.open();
                iframeDoc.write(response.html);
                iframeDoc.close();

                // Trigger the print dialog
                iframe.contentWindow.focus();
                setTimeout(() => {
                    iframe.contentWindow.print();
                }, 500);
            },
            error: function(xhr, status, error) {
                console.log('error');
            }
        })
    });


    tempusDominus.extend(tempusDominus.plugins.customDateFormat);

    new tempusDominus.TempusDominus(document.getElementById('dob'), {
        localization: {
            locale: "en",
            format: "dd/MM/yyyy",
        }
    });

    $('#kt_docs_repeater_basic').repeater({
        initEmpty: false,

        defaultValues: {
            'text-input': 'foo'
        },

        show: function() {
            $(this).slideDown();
            var index = $(this).index();
            $(this).find('[data-repeater-delete]').show(); // or use .prop('disabled', false) to enable the button
        },

        hide: function(deleteElement) {
            var index = $(this).index();
            if (index != 0) {
                $(this).slideUp(deleteElement);
            }
            return;
        }
    });
</script>
@endpush