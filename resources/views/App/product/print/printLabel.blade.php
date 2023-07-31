@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('print_labels_menu_link', 'active')


@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Print Label</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Products</li>
        <li class="breadcrumb-item text-dark">Print Label</li>
    </ul>
<!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <form id="kt_ecommerce_add_category_form" class="form d-flex flex-column flex-lg-row" data-kt-redirect="../../demo7/dist/apps/ecommerce/catalog/categories.html">

                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin::General options-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>
                                    Print Label <i class="fas fa-info-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
									title="Add products -> Choose informations to show in Labels -> Select Barcode Setting -> Preview Labels -> Print"></i>
                                </h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="fv-row">
                                <h3 class="">Add products to generate Labels</h3>
                                <div class="row my-5">
                                    <div class="d-flex align-items-center position-relative my-1 col-sm-12 col-md-8 col-md-offset-2 mx-auto">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <input type="text" class="form-control form-control-solid ps-14" placeholder="Enter products name to print labels" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-10 col-md-offset-1 mx-auto">
                                        <div class="table-responsive">
                                            <table class="table table-bordered border-dark">
                                                <thead>
                                                    <tr class="fw-bold fs-6 text-gray-800">
                                                        <th class="fs-30 fw-bold">Products</th>
                                                        <th class="fs-30 fw-bold">No. of labels</th>
                                                        <th class="fs-30 fw-bold">Lot Number</th>
                                                        <th class="fs-30 fw-bold">EXP Date</th>
                                                        <th class="fs-30 fw-bold">Packing Date</th>
                                                        <th class="fs-30 fw-bold">Selling Price Group</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::General options-->
                    <!--begin::Automation-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Information to show in Labels</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Input group-->
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-check-custom form-check-solid my-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="product_name" checked="checked" />
                                        <label class="form-check-label fw-bold cursor-pointer" for="product_name">
                                            Product Name
                                        </label>
                                    </div>
                                    <!--begin::Input group-->
                                    <div class="input-group mb-5">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">Size</span>
                                        <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-check-custom form-check-solid my-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="product_variation" checked="checked" />
                                        <label class="form-check-label fw-bold cursor-pointer" for="product_variation">
                                            Product Variation (recommended)
                                        </label>
                                    </div>
                                    <!--begin::Input group-->
                                    <div class="input-group mb-5">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">Size</span>
                                        <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-check-custom form-check-solid my-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="product_price" checked="checked" />
                                        <label class="form-check-label fw-bold cursor-pointer" for="product_price">
                                            Product Price
                                        </label>
                                    </div>
                                    <!--begin::Input group-->
                                    <div class="input-group  mb-5">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">Size</span>
                                        <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-check-custom form-check-solid my-3">
                                        <label class="form-check-label fw-bold cursor-pointer" >
                                            Show Price:
                                        </label>
                                    </div>
                                    <!--begin::Input group-->
                                    <div class="input-group input-group-sm flex-nowrap mb-5">
                                        <span class="input-group-text"><i class="fa fa-info"></i></span>
                                        <div class="overflow-hidden flex-grow-1">
                                            <select class="form-select rounded-0" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                                <option value="1">Inc. tax</option>
                                                <option value="0">Exc. tax</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-check-custom form-check-solid my-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="business_name" checked="checked" />
                                        <label class="form-check-label fw-bold cursor-pointer" for="business_name">
                                            Business name
                                        </label>
                                    </div>
                                    <!--begin::Input group-->
                                    <div class="input-group mb-5">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">Size</span>
                                        <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-check-custom form-check-solid my-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="print_packing_date" checked="checked" />
                                        <label class="form-check-label fw-bold cursor-pointer" for="print_packing_date">
                                            Print packing date
                                        </label>
                                    </div>
                                    <!--begin::Input group-->
                                    <div class="input-group mb-5">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">Size</span>
                                        <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-check-custom form-check-solid my-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="print_lot_number" checked="checked" />
                                        <label class="form-check-label fw-bold cursor-pointer" for="print_lot_number">
                                            Print lot number
                                        </label>
                                    </div>
                                    <!--begin::Input group-->
                                    <div class="input-group mb-5">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">Size</span>
                                        <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-check-custom form-check-solid my-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="print_expiry_date" checked="checked" />
                                        <label class="form-check-label fw-bold cursor-pointer" for="print_expiry_date">
                                            Print expiry date
                                        </label>
                                    </div>
                                    <!--begin::Input group-->
                                    <div class="input-group mb-5">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">Size</span>
                                        <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                            </div>
                            <!--end::Input group-->
                            <hr>
                            <div class="row">
                                <div class="col-md-6 col-offset-md-6 col-sm-12">
                                    <h3 class="fw-bold">Barcode setting:</h3>
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text" >
                                            <i class="fa fa-cog"></i>
                                        </span>
                                        <div class="overflow-hidden flex-grow-1">
                                            <select class="form-select rounded-0" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                                <option>Please Select</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8"></div>
                                <div class="col-md-4 text-end">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" type="button">Preview</button>
                                      </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::Automation-->
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <a href="{{ url('/category') }}"  class="btn btn-light me-5">Cancel</a>
                        <!--end::Button-->
                        <!--begin::Button-->
                        {{-- <button type="submit" class="btn btn-primary">
                            Save
                        </button> --}}
                        <!--end::Button-->
                    </div>
                </div>
                <!--end::Main column-->
            </form>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
   <script src="assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
   <script src="assets/js/custom/apps/ecommerce/catalog/save-category.js"></script>
@endpush
