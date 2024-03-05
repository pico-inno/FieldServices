@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('products_menu_link', 'active')

@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">Product List</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Product</li>
        <li class="breadcrumb-item text-dark">Product List</li>
    </ul>
<!--end::Breadcrumb-->
@endsection

@section('styles')
@livewireStyles
<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <!--begin::Content-->
    <livewire:product-list/>
    <!--end::Content-->
    <div class="modal fade" tabindex="-1" id="locationSelect">
        <div class="modal-dialog w-md-600px modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Select Location</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="col-12">
                        <form action="" id="locationAsssignForm">
                            <select class="form-select form-select-solid" data-control="select2" id="locationSelect2" data-close-on-select="false"
                                data-placeholder="Select an option" data-allow-clear="true" multiple="multiple">
                                <option></option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light">Close</button>
                    <button type="button" class="btn btn-primary" id="locationAssignChanges">Save</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" id="locationRemove">
        <div class="modal-dialog w-md-600px modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Select Location To Remove Product</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="col-12">
                        <form action="" id="locationRemoveAsssignForm">
                            <select class="form-select form-select-solid" data-control="select2" id="locationRemoveSelect2"
                                data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true"
                                multiple="multiple">
                                <option></option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light">Close</button>
                    <button type="button" class="btn btn-danger" id="locationRemoveChanges">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@livewireScripts


@endpush

