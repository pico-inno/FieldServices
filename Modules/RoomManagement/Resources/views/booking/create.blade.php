@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
@endsection
@section('room_management_icon','active')
@section('room_management_show','active show')
@section('booking_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-2">Add Booking</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Room Management</li>
    <li class="breadcrumb-item text-muted">Booking</li>
    <li class="breadcrumb-item text-dark">Add Booking</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <form action="" method="">
            <!--begin::Card-->
            <div class="card card-p-4 card-flush" id="listAgency">
                <div class="card-body">
                    <div class="row mb-6">
                        <div class="col-4">
                            <label for="guest_id" class="required form-label">Guest</label>
                            <select class="form-select" aria-label="Select example">
                                <option value="" selected disabled>Please select</option>
                                <option value="1">Aye Aye</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="agency_id" class="required form-label">Agency</label>
                            <select class="form-select" aria-label="Select example">
                                <option value="" selected disabled>Please select</option>
                                <option value="1">Elite</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="room_type_id" class="required form-label">Room Type</label>
                            <select class="form-select" aria-label="Select example">
                                <option value="" selected disabled>Please select</option>
                                <option value="1">Double</option>
                            </select>                        
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-4">
                            <label class="form-label">Booking Date</label>  
                            <input type="text" class="form-control" name="datepicker_input" id="booking_date"  />
                        </div>
                        <div class="col-4">
                            <label for="status" class="required form-label">Booking Status</label>
                            <select class="form-select" aria-label="Select example">
                                <option value="" selected disabled>Please select</option>
                                <option value="Pending">Pending</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Checkin">Checkin</option>
                                <option value="Checkout">Checkout</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="No_Show">No Show</option>
                            </select>       
                        </div>      
                        <div class="col-4">
                            <label class="form-label">Arrival Date</label>  
                            <input type="text" class="form-control" name="datepicker_input1" id="arrival_date"  />
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-4">
                            <label class="form-label">Checkin Date</label>  
                            <input type="text" class="form-control" name="datepicker_input" id="checkin_date"  />
                        </div>      
                        <div class="col-4">
                            <label class="form-label">Checkout Date</label>  
                            <input type="text" class="form-control" name="datepicker_input1" id="checkout_date"  />
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-4">
                            <label for="number_of_guests" class="form-label">Number of guests</label>
                            <input type="number" class="form-control" placeholder="Number of guests" />
                        </div>
                        <div class="col-4">
                            <label for="number_of_rooms" class="form-label">Number of rooms</label>
                            <input type="number" class="form-control" placeholder="Number of rooms" />
                        </div>
                        <div class="col-4">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input type="number" class="form-control" placeholder="Total Amount" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label for="comments" class="form-label">Comments</label>
                            <textarea name="" id="" class="form-control" placeholder="Comments" style="resize: none;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Save</button>
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
    <script>
       tempusDominus.extend(tempusDominus.plugins.customDateFormat);

       var datepickerIds = ["checkin_date", "checkout_date","booking_date","arrival_date"];

        datepickerIds.forEach(function(id) {
            new tempusDominus.TempusDominus(document.getElementById(id), {
                 localization: {
                    locale: "en",
                    format: "dd/MM/yyyy, hh:mm T", 
                }
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
    </script>
@endpush