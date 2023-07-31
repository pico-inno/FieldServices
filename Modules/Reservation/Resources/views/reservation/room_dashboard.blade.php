@extends('App.main.navBar')
@section('styles')
<link href="assets/plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('reservation_icon','active')
@section('reservation_show','active show')
@section('room_dashboard_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">Room</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Room</li>
    <li class="breadcrumb-item text-dark">Dashboard</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@php
    $reservations = \Modules\Reservation\Entities\RoomReservation::all();
    $earliestDate = $reservations->min('check_in_date');
    $latestDate = $reservations->max('check_out_date');
@endphp
@section('content')
<!--begin::Content-->
<div class="content flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <!--begin::Card-->
        <div class="card card-p-4 card-flush">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-12 mb-8">
                        <label for="" class="form-label">Room Type</label>
                        <select class="form-select rounded-0" data-control="select2" data-placeholder="All" data-hide-search="true">
                            <option></option>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                            <option value="5">Option 5</option>
                        </select>  
                    </div>
                    <div class="col-md-3 col-sm-12 mb-8">
                        <label for="" class="form-label">Floor</label>
                        <select class="form-select rounded-0" data-control="select2" data-placeholder="All" data-hide-search="true">
                            <option></option>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                            <option value="5">Option 5</option>
                        </select>  
                    </div>
                    <div class="col-md-3 col-sm-12 mb-8">
                        <label for="" class="form-label">Room Status</label>
                        <select class="form-select rounded-0" data-control="select2" data-placeholder="All" data-hide-search="true">
                            <option></option>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                            <option value="5">Option 5</option>
                        </select>  
                    </div>
                    <div class="col-md-3 col-sm-12 mb-8">
                        <label for="" class="form-label">Booking Status</label>
                        <select class="form-select rounded-0" data-control="select2" data-placeholder="All" data-hide-search="true">
                            <option></option>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                            <option value="5">Option 5</option>
                        </select>  
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label for="" class="form-label">Date</label>
                        <input class="form-control rounded-0" placeholder="Select date" id="kt_datepicker_1"/>                   
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->
        <div class="row mt-10">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="kt_docs_vistimeline_group"></div>            

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::container-->
</div>
<!--end::Content-->
@endsection

@push('scripts')
<script src="assets/plugins/custom/vis-timeline/vis-timeline.bundle.js"></script>
<script>
   
    const groups = new vis.DataSet(@json($groups));
    const items = new vis.DataSet(@json($items));
    
    var options = {
        stack: true,
        maxHeight: 640,
        horizontalScroll: false,
        verticalScroll: true,
        zoomKey: "ctrlKey",
        start: new Date("{{ $earliestDate }}"),
        end: new Date("{{ $latestDate }}"),
        orientation: {
            axis: "both",
            item: "top",
        },

    };
    
    const container = document.getElementById('kt_docs_vistimeline_group');
    const timeline = new vis.Timeline(container, options);
    timeline.setGroups(groups);
    timeline.setItems(items);

    $("#kt_datepicker_1").flatpickr();
    
</script>
@endpush