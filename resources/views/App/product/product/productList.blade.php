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
<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Form-->
            <div class="collapse" id="productFilter">
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Filters</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-md-3 mb-5">
                                    <label class="h5" for="">Product Type:</label>
                                    <select id="product_type_filter" class="form-select form-select-sm" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                        <option value="all" selected>All</option>
                                        @foreach($product_types as $product_type)
                                            <option value="{{$product_type}}">{{$product_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-5">
                                    <label class="h5" for="">Category:</label>
                                    <select id="category_filter" class="form-select form-select-sm" data-control="select2" data-hide-search="false" data-placeholder="Select an option">
                                        <option value="all">All</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category}}">{{$category}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-5">
                                    <label class="h5" for="">Brand:</label>
                                    <select id="brand_filter" class="form-select form-select-sm" data-control="select2" data-hide-search="false" data-placeholder="Select an option">
                                        <option value="all">All</option>
                                        @foreach($brands as $brand)
                                            <option value="{{$brand}}">{{$brand}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-5">
                                    <label class="h5" for="">Generic:</label>
                                    <select id="generic_filter" class="form-select form-select-sm" data-control="select2" data-hide-search="false" data-placeholder="Select an option">
                                        @if($generics->count() > 0)
                                            <option value="all">All</option>
                                            @foreach($generics as $generic)
                                                <option value="{{$generic}}">{{$generic}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-3 mb-5">
                                    <label class="h5" for="">Manufacture:</label>
                                    <select id="manufacture_filter" class="form-select form-select-sm" data-control="select2" data-hide-search="false" data-placeholder="Select an option">
                                        @if($manufactures->count() > 0)
                                            <option value="all">All</option>
                                            @foreach($manufactures as $manufacture)
                                                <option value="{{$manufacture}}">{{$manufacture}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-3 mb-5">
                                    <label class="h5" for="">Location:</label>
                                    <select id="location_filter" class="form-select form-select-sm" data-control="select2" data-hide-search="false" data-placeholder="Select an option">
                                        @if($locations->count() > 0)
                                            <option value="all">All</option>
                                            @foreach($locations as $location)
                                                <option value="{{$location->name}}">{{$location->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                            </div>

                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckChecked" />
                                <label class="form-check-label" for="flexCheckChecked">
                                    <strong class="">Not for selling</strong>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row" data-kt-redirect="../../demo7/dist/apps/ecommerce/catalog/products.html"> --}}
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin:::Tabs-->
                    <!-- <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">

                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general">All Produts</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_advanced">Stock Report</a>
                        </li>

                    </ul> -->
                    <!--end:::Tabs-->
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade show active" id="remove_kt_ecommerce_add_product_general" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::General options-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>All Products</h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    {{-- <div class="card-body pt-0">
                                        <div class="card  card-flush"> --}}
                                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                                <div class="card-title">
                                                    <!--begin::Search-->
                                                    <div class="d-flex align-items-center position-relative my-1">
                                                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <input type="text" id="search" data-kt-filter="search" class="form-control form-control-sm form-control-solid w-250px ps-14" placeholder="Search Report" />
                                                    </div>
                                                    <!--end::Search-->
                                                    <!--begin::Export buttons-->
                                                    <div id="kt_datatable_example_1_export" class="d-none"></div>
                                                    <!--end::Export buttons-->
                                                </div>
                                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                                    <button type="button" class="btn btn-light-primary collapsed btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#productFilter" aria-expanded="false" aria-controls="productFilter">
                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                                                        <span class="svg-icon svg-icon-2">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->Filter
                                                    </button>
                                                    @if(hasExport('product'))
                                                    <!--begin::Export dropdown-->
                                                    <button type="button" class="btn btn-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        Export Products List
                                                    </button>
                                                    <!--begin::Menu-->
                                                    <div id="kt_datatable_example_export_menu" class="btn-sm menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        {{-- <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-kt-export="copy">
                                                            Copy to clipboard
                                                            </a>
                                                        </div> --}}
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route('export-productlist') }}" class="menu-link px-3" data-kt-export="excel">
                                                            Export as Excel
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        {{-- <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-kt-export="csv">
                                                            Export as CSV
                                                            </a>
                                                        </div> --}}
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        {{-- <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-kt-export="pdf">
                                                            Export as PDF
                                                            </a>
                                                        </div> --}}
                                                        <!--end::Menu item-->
                                                    </div>
                                                    <!--end::Menu-->
                                                    <!--end::Export dropdown-->

                                                    <!--begin::Hide default export buttons-->
                                                    <div id="kt_datatable_example_buttons" class="d-none"></div>
                                                    <!--end::Hide default export buttons-->
                                                    @endif
                                                    @if(hasCreate('product'))
                                                    <a href="{{ url('product/add') }}" class="text-light btn btn-primary btn-sm">Add Product</a>
                                                        @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                {{-- <img src="{{ asset('/storage/product-image/1680624705_anime-girl.jpg') }}" alt="image" />	 --}}
                                                <div class="table-responsive">
                                                    <table class="table border-1 Datatable-tb align-middle  rounded table-row-dashed fs-6 g-5" id="kt_datatable_example" >
                                                        <!--begin::Table head-->
                                                        <thead>
                                                            <!--begin::Table row-->
                                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                                {{-- <th class="w-10px pe-2">
                                                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_products_table .form-check-input" value="1" />
                                                                    </div>
                                                                </th> --}}
                                                                <th></th>
                                                                <th></th>
                                                                <th class="text-start min-w-100px">{{ __('product/product.action') }}</th>
                                                                <th class="min-w-150px">{{ __('product/product.product') }}</th>
                                                                {{-- <th class="text-start min-w-150px">
                                                                    Business Location <i class="fas fa-exclamation-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                                    title="Product will be available only in this business locations"></i>
                                                                </th> --}}
                                                                <th class="text-start min-w-150px">{{ __('product/product.sku') }}</th>
                                                                <th class="text-start min-w-150px">{{ __('product/product.assign_location') }}</th>
                                                                <th class="text-start min-w-150px">{{ __('product/product.purchase_price') }}</th>
                                                                <th class="text-start min-w-100px">{{ __('product/product.sell_price') }}</th>
                                                                {{-- <th class="text-start min-w-150px">Current Stock</th> --}}
                                                                <th class="text-start min-w-100px">{{ __('product/product.product_type') }}</th>
                                                                <th class="text-start min-w-150px">{{ __('product/product.category') }}</th>
                                                                <th class="text-start min-w-100px">{{ __('product/product.brand') }}</th>
                                                                <th class="text-start min-w-100px">{{ __('product/product.generic') }}</th>
                                                                <th class="text-start min-w-100px">{{ __('product/product.manufacturer') }}</th>
                                                                <th class="text-start min-w-150px">{{ __('product/product.custom_field_1') }}</th>
                                                                <th class="text-start min-w-150px">{{ __('product/product.custom_field_2') }}</th>
                                                                <th class="text-start min-w-150px">{{ __('product/product.custom_field_3') }}</th>
                                                                <th class="text-start min-w-150px">{{ __('product/product.custom_field_4') }}</th>
                                                            </tr>
                                                            <!--end::Table row-->
                                                        </thead>
                                                        <!--end::Table head-->
                                                        <!--begin::Table body-->
                                                        <tbody class="fw-semibold text-gray-600">

                                                        </tbody>
                                                        <!--end::Table body-->
                                                    </table>
                                                    {{-- <span>
                                                        <button class="btn btn-danger btn-sm p-1">Delete Selected</button>
                                                    </span>
                                                    <span>
                                                        <button class="btn btn-success btn-sm p-1">Add to location</button>
                                                    </span>
                                                    <span>
                                                        <button class="btn btn-primary btn-sm p-1">Remove from location</button>
                                                    </span>
                                                    <span>
                                                        <button class="btn btn-warning btn-sm p-1">Deactive Selected</button>
                                                    </span>

                                                    <i class="fas fa-info-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                    title="Deactived products will not be available for purchase or sell"></i> --}}
                                                    <div class="d-flex gap-4">
                                                        <button class="btn btn-primary btn-sm" id="assignBtn">Assign Selected Products To location</button>
                                                        <button class="btn btn-warning btn-sm" id="removeAssignBtn">Removed Selected Products From location</button>
                                                    </div>
                                                </div>

                                            {{-- </div>
                                        </div> --}}
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::General options-->
                            </div>
                        </div>
                        <!--end::Tab pane-->
                        <!--begin::Tab pane-->
                        {{-- Stock --}}
                        <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::Inventory-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Stock Report</h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <div class="card  card-flush">
                                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                                <div class="card-title">
                                                    <!--begin::Search-->
                                                    <div class="d-flex align-items-center position-relative my-1">
                                                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <input type="text" data-kt-filter="search-stock" class="form-control form-control-solid w-250px ps-14" placeholder="Search Product" />
                                                    </div>
                                                    <!--end::Search-->
                                                    <!--begin::Export buttons-->
                                                    <div id="kt_datatable_example_1_export" class="d-none"></div>
                                                    <!--end::Export buttons-->
                                                </div>
                                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                                    <button type="button" class="btn btn-light-primary me-3 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#productFilter" aria-expanded="false" aria-controls="productFilter">
                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                                                        <span class="svg-icon svg-icon-2">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->Filter
                                                    </button>
                                                    @if(hasExport('product'))
                                                    <!--begin::Export dropdown-->
                                                    <!-- <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        Export Report
                                                    </button> -->
                                                    <!--begin::Menu-->
                                                    <div id="kt_datatable_example_export_menu_stock" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-kt-export-stock="copy">
                                                            Copy to clipboard
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-kt-export-stock="excel">
                                                            Export as Excel
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-kt-export-stock="csv">
                                                            Export as CSVa
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-kt-export-stock="pdf">
                                                            Export as PDF
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                    </div>
                                                    <!--end::Menu-->
                                                    <!--end::Export dropdown-->

                                                    <!--begin::Hide default export buttons-->
                                                    <div id="kt_datatable_example_buttons_stock" class="d-none"></div>
                                                    <!--end::Hide default export buttons-->
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table align-middle  rounded table-row-dashed fs-6 g-5" >
                                                        <!--begin::Table head-->
                                                        <thead>
                                                            <!--begin::Table row-->
                                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                                <th>SKU</th>
                                                                <th>Product & Variation</th>
                                                                <th>Batch No</th>
                                                                <th>Lot No</th>
                                                                <th>Location</th>
                                                                <th>Category</th>
                                                                <th>Brand</th>

                                                                <th>
                                                                    <span id="stock-qty-header">
                                                                        Purchase Qty
                                                                    </span>
                                                                </th>
                                                                <th class="text-center">
                                                                 <span id="stock-qty-header1">
                                                                        Current Qty
                                                                 </span>
                                                                </th>
                                                                <th>UOM</th>

                                                            </tr>
                                                            <!--end::Table row-->
                                                        </thead>
                                                        <!--end::Table head-->
                                                        <!--begin::Table body-->
                                                        <tbody class="fw-semibold text-gray-600">

                                                        </tbody>

                                                        <!--end::Table body-->
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Inventory-->
                            </div>
                        </div>
                        <!--end::Tab pane-->
                    </div>
                    <!--end::Tab content-->
                </div>
                <!--end::Main column-->
            {{-- </form> --}}
            <!--end::Form-->
        </div>
        <!--end::Container-->
    </div>
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
    <script src="{{ asset('customJs/toastrAlert/alert.js') }}"></script>
    <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!-- <script src="{{ asset('customJs/product/productListExport.js') }}"></script> -->
    <script>
        const modal = new bootstrap.Modal($('#locationSelect'));
        const removeModal = new bootstrap.Modal($('#locationRemove'));
        // assign product to location
        let locations=@json($locations ?? []);
        const transformedObject = Object.fromEntries(locations.map(({ id, name }) => [id, name]));
        var options=locations.map((l)=>{
            return {'id':l.id,'text':l.name}
        })
        $('#assignBtn').click(()=>{
            let checkBoxs=document.querySelectorAll('[data-checked="assign"]');
            let checkCount=0;
            let productIds=[];
            checkBoxs.forEach(c => {
                if (c.checked) {
                    checkCount++;
                    let productId=$(c).val();
                    productIds=[...productIds,productId];
                }
            });
            if(checkCount <1){
                Swal.fire({
                    title: 'Please Select The Products',
                    // text: "You want to delete it!",
                    icon: "warning",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                })
            }else{
                $('#locationSelect2').select2({data:options});
                modal.show();
                $(document).off().on('click','#locationAssignChanges',async function(){
                    let locationIds=$('#locationSelect2').val();
                    console.log(locationIds);
                    if(locationIds.length > 0){
                        $('#locationAssignChanges').prop('disabled', true).text('loading.....');
                        await assign(locationIds,productIds);
                        $('#locationAssignChanges').prop('disabled', false).text('Save');
                        $('#locationSelect2').val('');
                        modal.hide();
                    }

                })
            }
        })
        function assign(locationIds,productIds){
            return new Promise((resolve, reject)=>{
                $.ajax({
                    url:'/location-product/store',
                    type: 'GET',
                    data:{
                        locationIds,
                        productIds,
                    },
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // },
                    success: function(s) {
                        Swal.fire({
                            title: 'Successfully Assigned',
                            icon: "success",
                            confirmButton:true,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        })
                        resolve();
                    },
                    error:function(){
                        resolve();
                    }
                })
            })
        }
        function remove(locationIds,productIds){
            return new Promise((resolve, reject)=>{
                $.ajax({
                    url:'/location-product/remove',
                    type: 'GET',
                    data:{
                        locationIds,
                        productIds,
                    },
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // },
                    success: function(s) {
                        Swal.fire({
                            title: 'Successfully Removed',
                            icon: "success",
                            confirmButton:true,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        })
                        resolve();
                    },
                    error:function(){
                        resolve();
                    }
                })
            })
        }
        $('#removeAssignBtn').click(()=>{
            let checkBoxs=document.querySelectorAll('[data-checked="assign"]');
            let checkCount=0;
            let productIds=[];
            checkBoxs.forEach(c => {
                if (c.checked) {
                    checkCount++;
                    let productId=$(c).val();
                    productIds=[...productIds,productId];
                }
            });
            if(checkCount <1){
                Swal.fire({
                    title: 'Please Select The Products',
                    // text: "You want to delete it!",
                    icon: "warning",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                })
            }else{
                $('#locationRemoveSelect2').select2({data:options});
                removeModal.show();
                $(document).off('click').on('click','#locationRemoveChanges',async function(){
                    let locationIds=$('#locationRemoveSelect2').val();
                    if(locationIds.length > 0){
                        $('#locationRemoveChanges').prop('disabled', true).text('loading.....');
                        await remove(locationIds,productIds);
                        $('#locationRemoveChanges').prop('disabled', false).text('Save');
                        $('#locationRemoveSelect2').val('');
                        removeModal.hide();
                    }

                })

            }
        })

        var table;
        $(document).ready(function () {

            function disablePagination() {
                // Store the current pagination state
                var currentPage = table.page();

                // Disable pagination
                table.page('all').draw('page');

                // Revert to the original page after the export is complete
                table.one('draw.dt', function () {
                    table.page(currentPage).draw('page');
                });
            }

            var initDatatable = function (){
                table = $('.Datatable-tb').DataTable({
                processing: true,
                paging:true,
                serverSide: true,
                ajax: {
                    url: '/product-datas',
                    data: function (d) {
                        d.length = $('.Datatable-tb').DataTable().page.len();
                    },
                },

                columns: [
                    {
                    data: 'check_box',
                    name: 'check_box',
                    render: function(data, type, full, meta){
                        console.log(data);
                        return `
                            <div class="form-check form-check-sm form-check-custom ">
                                <input class="form-check-input" type="checkbox" data-checked="assign" value="${data}" />
                            </div>
                        `;
                        }
                    },
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": `
                            <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px">
                                <i id="toggle" class="fas fa-plus"></i>
                            </button>
                        `
                    },

                    {
                        data: 'action',
                        name: 'action',
                        render: function(data, type, full, meta){

                            let updatePermission = <?php echo hasUpdate('product') ? 'true' : 'false'; ?>;
                            let deletePermission = <?php echo hasDelete('product') ? 'true' : 'false'; ?>;

                            return `
                                <div class="dropdown">
                                 <button class="btn btn-sm btn-light btn-active-light-primary fw-semibold fs-7  dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                     Actions
                                 </button>
                                 <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                          ${updatePermission ? `<li><a href="/product/edit/${data}" class="dropdown-item p-2 edit-brand px-3" >
                                        <i class="fas fa-pen-to-square me-3"></i>{{ __('product/product.edit') }}</a>
                                    </li>` : ''}
                                            ${deletePermission ? ` <li><div class="dropdown-item p-2 product-delete-confirm cursor-pointer px-3" data-id="${data}" >
                                        <i class="fas fa-trash me-3"></i>{{ __('product/product.delete') }}</div>
                                    </li>` : ''}
                                </ul>
                                </div>
                                `;
                        }

                    },
                    {
                        data: 'product',
                        name: 'product.name',
                        render: function(data, type, full, meta) {

                            return `
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px">
                                    ${data.image ? `<span class="symbol-label" style="background-image:url(/storage/product-image/${data.image});"></span>` : `<span class="symbol-label"></span>` }
                                </div>
                                <div class="ms-3 ${data.deleted_variation == 'deleted' ? 'text-danger' : ''}">${data.name}</div>
                            </div>
                            `;
                        }
                    },
                    {
                        data: 'sku',
                        name: 'sku',
                    },
                    {
                        data: 'assign_location',
                        name: 'assign_location',
                    },
                    {
                        data: 'purchase_price',
                        name: 'purchase_price',
                        render: function(data) {
                            if(data.has_variation === "single"){
                                return data.purchase_prices;
                            }
                            if(data.has_variation === "variable"){
                                return '';
                            }
                        }
                    },
                    {
                        data: 'selling_price',
                        name: 'selling_price',
                        render: function(data) {
                            if(data.has_variation === "single"){
                                return data.selling_prices;
                            }
                            if(data.has_variation === "variable"){
                                return '';
                            }
                        }
                    },
                    {
                        data: 'product_type',
                        name: 'product_type'
                    },
                    {
                        data: 'category',
                        name: 'category',

                    },

                    {
                        data: 'brand',
                        name: 'brand'
                    },

                    {
                        data: 'generic',
                        name: 'generic'
                    },
                    {
                        data: 'manufacturer',
                        name: 'manufacturer'
                    },
                    {
                        data: 'product_custom_field1',
                        name: 'product_custom_field1'
                    },
                    {
                        data: 'product_custom_field2',
                        name: 'product_custom_field2'
                    },
                    {
                        data: 'product_custom_field3',
                        name: 'product_custom_field3'
                    },
                    {
                        data: 'product_custom_field4',
                        name: 'product_custom_field4'
                    }

                ]
            });

            disablePagination();
            };

            initDatatable();
            // Search
            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#location_filter').on('change', function() {
                let value = this.value;
                if (value === 'all') {
                    value = '';
                }
                table.column(5).search(value).draw();
            });

            $('#product_type_filter').on('change', function() {
                let value = this.value;
                if (value === 'all') {
                    value = '';
                }
                table.column(8).search(value).draw();
            });

            $('#category_filter').on('change', function () {
                let value = this.value;
                if (value === 'all') {
                    value = '';
                }

                table.column('category:name').search(value).draw();
            });




            $('#brand_filter').on('change', function() {
                let value = this.value;
                if (value === 'all') {
                    value = '';
                }
                table.column(10).search(value).draw();
            });

            $('#generic_filter').on('change', function() {
                let value = this.value;
                if (value === 'all') {
                    value = '';
                }
                table.column(11).search(value).draw();
            });

            $('#manufacture_filter').on('change', function() {
                let value = this.value;
                if (value === 'all') {
                    value = '';
                }
                table.column(12).search(value).draw();
            });


            // Product - DELETE
            $(document).on('click', '.product-delete-confirm', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete it!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/product/delete/' + id,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                _method: 'DELETE',
                            },
                            success: function(response) {
                                if(response.message){
                                    success(response.message);
                                }

                                if(response.error){
                                    Swal.fire({
                                        text: response.error,
                                        icon: "error",
                                        buttonsStyling: false,
                                        showCancelButton: false,
                                        confirmButtonText: "Ok",
                                        cancelButtonText: "Delete",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    });
                                }
                                table.ajax.reload();
                            }
                        })
                    }
                });

            });

            // Variation - DELETE
            $(document).on('click', '.variation-delete-confirm', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete it!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/product-variation/delete/' + id,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                _method: 'DELETE',
                            },
                            success: function(response) {
                                if (response.message) {
                                    success(response.message);
                                }

                                table.ajax.reload();
                            }

                        })
                    }
                });

            });

            // Sub Table for Variation
            $('.Datatable-tb tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );

                // for single product type
                if(row.data().has_variation === "single"){
                    return;
                }

                // for '+' and '-' click button
                let toggle = tr.find('#toggle');

                if ( row.child.isShown() ) {
                    toggle.removeClass('fa-minus').addClass('fa-plus');

                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');

                }else {
                    toggle.removeClass('fa-plus').addClass('fa-minus');
                    // Open this row
                    let purchasePrices = row.data().purchase_price.purchase_prices; // outpout is array [10, 20, 30]

                    let childRows = '';

                    $.each(purchasePrices, function(index, item){
                        childRows += format(row.data(), index, item);
                    })

                    row.child(childRows).show();
                    tr.addClass('shown');
                }
            });

             /* Formatting function for row details - modify as you need */
            function format ( data,index, purchase_price ) {
                // begin: for category conditions
                    let category;
                    if(data.category.parentCategory && data.category.subCategory){
                        category = data.category.parentCategory + " ," + data.category.subCategory;
                    }
                    if(data.category.parentCategory && !data.category.subCategory){
                        category = data.category.parentCategory;
                    }
                    if(!data.category.parentCategory && data.category.subCategory){
                        category = data.category.subCategory;
                    }
                    if(category === undefined){
                        category = '';
                    }
                // end: for category conditions
                let variation_name = data.purchase_price.variation_name[index] != 'deleted' ? data.purchase_price.variation_name[index] : data.purchase_price.deleted_variation_name[index];
                let selling_price = data.selling_price.selling_prices[index];
                let variation_id = data.product_variations[index].id;
                let cssClass = data.purchase_price.variation_name[index] == 'deleted' ? 'text-danger' : 'text-gray-500';
                return `
                    <table class="table" >
                        <tr>
                            <td class="w-50px"></td>
                            <td class="text-start w-100px text-gray-500">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-active-light-primary fw-semibold fs-7  dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><div class="dropdown-item p-2 variation-delete-confirm cursor-pointer" data-id="${variation_id}" >Delete</div></li>
                                    </ul>
                                </div>
                            </td>
                            <td class="w-150px ${cssClass}">${data.name} | ${variation_name}</td>
                            <td class="text-start w-150px text-gray-500">Ks ${purchase_price}</td>
                            <td class="text-start w-100px text-gray-500">Ks ${selling_price}</td>
                            <td class="text-start w-100px text-gray-500">${data.has_variation}</td>
                            <td class="text-start w-150px text-gray-500">${category}</td>
                            <td class="text-start w-100px text-gray-500">${data.brand !== null ? data.brand : ''}</td>
                            <td class="text-start w-100px text-gray-500"></td>
                            <td class="text-start w-100px text-gray-500"></td>
                            <td class="text-start w-150px text-gray-500"></td>
                            <td class="text-start w-150px text-gray-500"></td>
                            <td class="text-start w-150px text-gray-500"></td>
                            <td class="text-start w-150px text-gray-500"></td>
                        </tr>
                    </table>
                    `;
            }


            var exportButtons = () => {
                        const documentTitle = 'Product List';



                        var buttons = new $.fn.dataTable.Buttons(table, {
                            buttons: [
                                {
                                    extend: 'copyHtml5',
                                    title: documentTitle,
                                    exportOptions: {
                                        page: 'all', // Export all pages
                                        search: 'none' // Exclude search filter from export
                                    },


                                },
                                {
                                    extend: 'excelHtml5',
                                    title: documentTitle,
                                    exportOptions: {
                                        page: 'all', // Export all pages
                                        search: 'none' // Exclude search filter from export
                                    },

                                },
                                {
                                    extend: 'csvHtml5',
                                    title: documentTitle,
                                    exportOptions: {
                                modifier: {
                                    page: 'all', // Export all pages
                                    search: 'none' // Exclude search filter from export
                                }
                            }
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: documentTitle,
                                    exportOptions: {
                                modifier: {
                                    page: 'all', // Export all pages
                                    search: 'none' // Exclude search filter from export
                                }
                            }
                                },

                            ]
                        }).container().appendTo($('#kt_datatable_example_buttons'));

                        const exportButtons = document.querySelectorAll('#kt_datatable_example_export_menu [data-kt-export]');
                        // exportButtons.forEach(exportButton => {
                        //     exportButton.addEventListener('click', e => {
                        //         console.log('work');
                        //         e.preventDefault();

                        //         const exportValue = e.target.getAttribute('data-kt-export');
                        //         const target = document.querySelector('.dt-buttons .buttons-' + exportValue);
                        //         target.click();
                        //     });
                        // });
             }
             exportButtons();
        });
        @if(session('message'))

        success("{{session('message')}}");

        @endif
    </script>
@endpush
