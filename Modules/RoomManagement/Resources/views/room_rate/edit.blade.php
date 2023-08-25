@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
@endsection
@section('room_management_icon','active')
@section('room_management_show','active show')
@section('room_rate_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">Edit Room Rate</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Room Management</li>
    <li class="breadcrumb-item text-muted">Room Rate</li>
    <li class="breadcrumb-item text-dark">Edit Room Rate</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    @php
        $currentDate = date('d/m/Y');
    @endphp
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
        <form action="{{route('room-rate.update', $room_rate->id)}}" method="POST" id="edit_room_rate_form">
            @csrf
            @method('PUT')
            <!--begin::Card-->
            <div class="card card-p-4 card-flush">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="room_type_id" class="required form-label">Room Type</label>
                            <select name="room_type_id" id="room_type_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" required>
                                @php
                                    $room_types = \Modules\RoomManagement\Entities\RoomType::all();
                                @endphp

                                @foreach($room_types as $room_type)
                                    <option value="{{ $room_type->id }}" {{ old('room_type_id', $room_rate->room_type_id) == $room_type->id ? 'selected' : ''}}>
                                        {{ $room_type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="rate_name" class="required form-label">Rate Name</label>
                            <input type="text" name="rate_name" id="rate_name" value="{{old('rate_name', $room_rate->rate_name)}}" class="form-control form-control-sm fs-7" placeholder="Rate Name" required />
                        </div>
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="rate_amount" class="form-label">Rate Amount</label>
                            <input type="number" name="rate_amount" id="rate_amount" value="{{old('rate_amount', $room_rate->rate_amount)}}" class="form-control form-control-sm fs-7" placeholder="Rate Amount" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="text" name="start_date" id="start_date" value="{{ old('start_date', !empty($room_rate->start_date) ? date('d/m/Y', strtotime($room_rate->start_date)) : $currentDate) }}" class="form-control form-control-sm fs-7" autocomplete="off" />
                        </div>
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="text" name="end_date" id="end_date" value="{{ old('end_date', !empty($room_rate->end_date) ? date('d/m/Y', strtotime($room_rate->end_date)) : $currentDate) }}" class="form-control form-control-sm fs-7" autocomplete="off" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="booking_rule" class="form-label">Booking Rule</label>
                            <textarea name="booking_rule" id="booking_rule" class="form-control form-control-sm fs-7" placeholder="Booking Rule" style="resize: none;">{{ old('booking_rule', !empty($room_rate->booking_rule) ? $room_rate->booking_rule : null) }}</textarea>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="cancellation_rule" class="form-label">Cancellation Rule</label>
                            <textarea name="cancellation_rule" id="cancellation_rule" class="form-control form-control-sm fs-7" placeholder="Cancellation Rule" style="resize: none;">{{ old('cancellation_rule', !empty($room_rate->cancellation_rule) ? $room_rate->cancellation_rule : null) }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="min_stay" class="form-label">Minimum Stay</label>
                            <input type="number" name="min_stay" id="min_stay" value="{{old('min_stay', $room_rate->min_stay)}}" class="form-control form-control-sm fs-7" placeholder="Minimun Stay" />
                        </div>
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="max_stay" class="form-label">Maximum Stay</label>
                            <input type="number" name="max_stay" id="max_stay" value="{{old('max_stay', $room_rate->max_stay)}}" class="form-control form-control-sm fs-7" placeholder="Maximum Stay" />
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
    <script>
       tempusDominus.extend(tempusDominus.plugins.customDateFormat);

       var datepickerIds = ["start_date", "end_date"];

        datepickerIds.forEach(function(id) {
            new tempusDominus.TempusDominus(document.getElementById(id), {
                 localization: {
                    locale: "en",
                    format: "dd/MM/yyyy", 
                }
            });
        });
    </script>
@endpush