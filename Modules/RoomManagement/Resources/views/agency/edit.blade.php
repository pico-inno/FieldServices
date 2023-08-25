@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
@endsection
@section('room_management_icon','active')
@section('room_management_show','active show')
@section('agency_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-2">Edit Agency</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Room Management</li>
    <li class="breadcrumb-item text-muted">Agency</li>
    <li class="breadcrumb-item text-dark">Edit Agency</li>
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
                        <div class="col-6">
                            <label for="name" class="required form-label">Name</label>
                            <input type="text" class="form-control" placeholder="Name" />
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-6">
                            <label for="guest_id" class="required form-label">Contact Person</label>
                            <select class="form-select" aria-label="Select example">
                                <option value="" selected disabled>Please select</option>
                                <option value="1">Aye Aye</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" placeholder="Phone" />
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" placeholder="Email" />
                        </div>
                        <div class="col-6">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="" id="" class="form-control" placeholder="Address" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" placeholder="City" />
                        </div>
                        <div class="col-6">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" placeholder="State" />
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-6">
                            <label for="zip" class="form-label">Zip</label>
                            <input type="text" class="form-control" placeholder="Zip" />
                        </div>
                        <div class="col-6">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" placeholder="Country" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="comments" class="form-label">Comments</label>
                            <textarea name="" id="" class="form-control" placeholder="Comments" style="resize: none;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
            <!--end::Card-->
        </form>
    </div>
    <!--end::container-->
</div>
<!--end::Content-->
@endsection