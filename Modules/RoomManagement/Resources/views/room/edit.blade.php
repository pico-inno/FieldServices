@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
@endsection
@section('room_management_icon','active')
@section('room_management_show','active show')
@section('room_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">Edit Room</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Room Management</li>
    <li class="breadcrumb-item text-muted">Room</li>
    <li class="breadcrumb-item text-dark">Edit Room</li>
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
        <form action="{{route('room.update', $room->id)}}" method="POST" id="edit_room_form">
            @csrf
            @method('PUT')
            <!--begin::Card-->
            <div class="card card-p-4 card-flush">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="name" class="required form-label">Name</label>
                            <input type="text" name="name" id="name" value="{{old('name', $room->name)}}" class="form-control form-control-sm fs-7" placeholder="Name" required />
                        </div>
                        <div class="col-md-6 col-sm-12 mb-4">
                            <label for="floor_id" class="required form-label">Floor</label>
                            <select name="floor_id" id="floor_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" required>
                                @php
                                    $floors= \App\Models\settings\Floor::all();
                                @endphp

                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}" {{ old('floor_id', $room->floor_id) == $floor->id ? 'selected' : ''}}>
                                        {{ $floor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="room_type_id" class="required form-label">Room Type</label>
                            <select name="room_type_id" id="room_type_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" required>
                                @php
                                    $room_types = \Modules\RoomManagement\Entities\RoomType::all();
                                @endphp

                                @foreach($room_types as $room_type)
                                    <option value="{{ $room_type->id }}" {{ old('room_type_id', $room->room_type_id) == $room_type->id ? 'selected' : ''}}>
                                        {{ $room_type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-4">
                            <label for="status" class="form-label">Room Status</label>
                            <select name="status" id="status" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Please select" data-hide-search="true">
                                <option value="Available" {{$room->status == 'Available' ? 'selected' : ''}}>Available</option>
                                <option value="Dirty" {{$room->status == 'Dirty' ? 'selected' : ''}}>Dirty</option>
                                <option value="Maintenance" {{$room->status == 'Maintenance' ? 'selected' : ''}}>Maintenance</option>
                                <option value="Out_of_service" {{$room->status == 'Out_of_service' ? 'selected' : ''}}>Out of service</option>
                            </select>                        
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-md-6 col-sm-12">
                            <label for="max_occupancy" class="form-label">Max Occupancy</label>
                            <input type="number" name="max_occupancy" id="max_occupancy" value="{{old('max_occupancy', $room->max_occupancy)}}" class="form-control form-control-sm fs-7" placeholder="Max Occupancy" />
                        </div>
                        <div class="col-md-6 col-sm-12 mt-10 mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="flexCheckCheckedEdit" class="form-check-input"  value="{{ $room->is_active }}" {{ old('is_active', $room->is_active) == 'Active' ? 'checked' : ''}}  />
                                <label class="form-check-label" for="flexCheckCheckedEdit">
                                    Is Active
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-md-6 col-sm-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control form-control-sm fs-7" placeholder="Description" style="resize: none;">{{ old('description', !empty($room->description) ? $room->description : null) }}</textarea>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="custom_field_1" class="form-label">Custom Field 1</label>
                            <input type="text" name="custom_field_1" id="custom_field_1" value="{{old('custom_field_1', $room->custom_field_1)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 1" />
                        </div>
                        <div class="col-md-6 col-sm-12 mb-4">
                            <label for="custom_field_2" class="form-label">Custom Field 2</label>
                            <input type="text" name="custom_field_2" id="custom_field_2" value="{{old('custom_field_2', $room->custom_field_2)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 2" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-6">
                            <label for="custom_field_3" class="form-label">Custom Field 3</label>
                            <input type="text" name="custom_field_3" id="custom_field_3" value="{{old('custom_field_3', $room->custom_field_3)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 3" />
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="custom_field_4" class="form-label">Custom Field 4</label>
                            <input type="text" name="custom_field_4" id="custom_field_4" value="{{old('custom_field_4', $room->custom_field_4)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 4" />
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