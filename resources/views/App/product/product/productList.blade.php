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
                                    <select class="form-select form-select-sm" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                        <option>All</option>
                                        <option value="1">Single</option>
                                        <option value="0">Variable</option>
                                        <option value="">Combo</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-5">
                                    <label class="h5" for="">Category:</label>
                                    <select class="form-select form-select-sm" data-control="select2" data-hide-search="false" data-placeholder="Select an option">
                                        <option>All</option>
                                        <option value="1">Color</option>
                                        <option value="0">Underware</option>
                                        <option value="">Shirt</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-5">
                                    <label class="h5" for="">Unit:</label>
                                    <select class="form-select form-select-sm" data-control="select2" data-hide-search="false" data-placeholder="Select an option">
                                        <option>All</option>
                                        <option value="1">Pieces</option>
                                        <option value="0">ဘူး</option>
                                        <option value="">ထည်</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-5">
                                    <label class="h5" for="">Tax:</label>
                                    <select class="form-select form-select-sm" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                        <option>All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-3 mb-5">
                                    <label class="h5" for="">Brand:</label>
                                    <select class="form-select" data-control="select2" data-hide-search="false" data-placeholder="Select an option">
                                        <option>All</option>
                                        <option value="1">Alphine</option>
                                        <option value="0">iPhone</option>
                                        <option value="">Lux</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-5">
                                    <label class="h5" for="">Business Location:</label>
                                    <select class="form-select form-select-sm" data-control="select2" data-hide-search="false" data-placeholder="Select an option">
                                        <option>All</option>
                                        <option value="1">None</option>
                                        <option value="0">Demo Business (BL0001)</option>
                                        <option value="">Lux (BL0003)</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-5">
                                    <label for=""></label>
                                    <select class="form-select form-select-sm" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                        <option>All</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-5">
                                    <label class="h5" for="">Device Model:</label>
                                    <select class="form-select form-select-sm" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                        <option>All</option>
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
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general">All Produts</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_advanced">Stock Report</a>
                        </li>
                        <!--end:::Tab item-->
                    </ul>
                    <!--end:::Tabs-->
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
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
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-kt-export="copy">
                                                            Copy to clipboard
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-kt-export="excel">
                                                            Export as Excel
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-kt-export="csv">
                                                            Export as CSV
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-kt-export="pdf">
                                                            Export as PDF
                                                            </a>
                                                        </div>
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
                                                    <table class="table border-1 Datatable-tb align-middle  rounded table-row-dashed fs-6 g-5" id="kt_datatable_example">
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
                                                                <th class="text-start min-w-100px">{{ __('product/product.action') }}</th>
                                                                <th class="min-w-150px">{{ __('product/product.product') }}</th>
                                                                {{-- <th class="text-start min-w-150px">
                                                                    Business Location <i class="fas fa-exclamation-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                                    title="Product will be available only in this business locations"></i>
                                                                </th> --}}
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
                                                        <input type="text" data-kt-filter="search-stock" class="form-control form-control-solid w-250px ps-14" placeholder="Search Report" />
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
                                                    <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        Export Report
                                                    </button>
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
                                                            Export as CSV
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
                                                    @endif
                                                    <!--begin::Hide default export buttons-->
                                                    <div id="kt_datatable_example_buttons_stock" class="d-none"></div>
                                                    <!--end::Hide default export buttons-->
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table align-middle  rounded table-row-dashed fs-6 g-5" id="kt_datatable_example_stock">
                                                        <!--begin::Table head-->
                                                        <thead>
                                                            <!--begin::Table row-->
                                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                                <th class="text-start min-w-150px">SKU</th>
                                                                <th class="text-start min-w-100px">Product</th>
                                                                <th class="text-start min-w-150px">Variation</th>
                                                                <th class="text-start min-w-100px">Category</th>
                                                                <th class="text-start min-w-100px">Location</th>
                                                                <th class="text-start min-w-200px">Unit Selling Price</th>
                                                                <th class="text-start min-w-100px">Current Stock</th>
                                                                <th class="text-start min-w-150px">Current Stock Value (By purchase price)</th>
                                                                <th class="text-start min-w-150px">Current Stock Value (By sale price)</th>
                                                                <th class="text-start min-w-100px">Potential profit</th>
                                                                <th class="text-start min-w-100px">Total unit sold</th>
                                                                <th class="text-start min-w-150px">Total Unit Transfered</th>
                                                                <th class="text-start min-w-150px">Total Unit Adjusted</th>
                                                                <th class="text-start min-w-100px">IMEI</th>
                                                                <th class="text-start min-w-100px">Custom Field2</th>
                                                                <th class="text-start min-w-100px">Custom Field3</th>
                                                                <th class="text-start min-w-100px">Custom Field4</th>
                                                                <th class="text-start min-w-100px">
                                                                    Current Stock (Manufacturing) <i class="fas fa-exclamation-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                                                    title="Stock left from total manufactured"></i>
                                                                </th>
                                                            </tr>
                                                            <!--end::Table row-->
                                                        </thead>
                                                        <!--end::Table head-->
                                                        <!--begin::Table body-->
                                                        <tbody class="fw-semibold text-gray-600">
                                                            @foreach ([1,2,3,4,5,6,6,7,8,8,9,9,12,23,43,42,17] as $item)
                                                                <!--begin::Table row-->
                                                                <tr>
                                                                    <td>IP13PM256</td>
                                                                    <td>iPhone 13 Pro Max 256GB</td>
                                                                    <td></td>
                                                                    <td>Color</td>
                                                                    <td>Main Store</td>
                                                                    <td>
                                                                        Ks 3,000,000 <br/>
                                                                        <button type="button" class="btn btn-primary btn-sm p-1">View group prices</button>
                                                                    </td>
                                                                    <td>4.00 Pc(s)</td>
                                                                    <td>Ks 10,400,000</td>
                                                                    <td>Ks 12,200,400</td>
                                                                    <td>Ks 1,600,000</td>
                                                                    <td>1.00 Pc(s)</td>
                                                                    <td>0.00 Pc(s)</td>
                                                                    <td>0.00 Pc(s)</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>0.00 Pc(s)</td>
                                                                </tr>
                                                                <!--end::Table row-->
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="6">Total: </td>
                                                                <td>7885</td>
                                                                <td>55665</td>
                                                                <td>565</td>
                                                                <td>454541</td>
                                                                <td>5465</td>
                                                                <td>5465</td>
                                                                <td>0</td>
                                                                <td colspan="4"></td>
                                                                <td>0</td>
                                                            </tr>
                                                        </tfoot>
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
@endsection

@push('scripts')
    <script src="{{ asset('customJs/toastrAlert/alert.js') }}"></script>
    <script>
        // success message
        // toastr.options = {
        //     "closeButton": false,
        //     "debug": false,
        //     "newestOnTop": false,
        //     "progressBar": false,
        //     "positionClass": "toastr-top-center",
        //     "preventDuplicates": false,
        //     "onclick": null,
        //     "showDuration": "300",
        //     "hideDuration": "1000",
        //     "timeOut": "5000",
        //     "extendedTimeOut": "1000",
        //     "showEasing": "swing",
        //     "hideEasing": "linear",
        //     "showMethod": "fadeIn",
        //     "hideMethod": "fadeOut"
        // };

        $(document).ready(function () {
            let table = $('.Datatable-tb').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/product-datas',
                },

                columns: [
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
                                <div class="ms-3">${data.name}</div>
                            </div>
                            `;
                        }
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
                        render: function(data){
                            let category;
                            if(data.parentCategory && data.subCategory){
                                category = data.parentCategory + " ," + data.subCategory;
                            }
                            if(data.parentCategory && !data.subCategory){
                                category = data.parentCategory;
                            }
                            if(!data.parentCategory && data.subCategory){
                                category = data.subCategory;
                            }
                            if(category === undefined){
                                category = '';
                            }
                            return category;
                        }
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
            // Search
            $('#search').on('keyup', function() {
                table.search(this.value).draw();
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
                                success(response.message);
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
                                success(response.message);
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
                let variation_name = data.purchase_price.variation_name[index];
                let selling_price = data.selling_price.selling_prices[index];
                let variation_id = data.product_variations[index].id;
                return `
                    <table class="table">
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
                            <td class="w-150px text-gray-500">${data.name} | ${variation_name}</td>
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
        });

    </script>
    @if(session('message'))
        <script>
            success("{{session('message')}}");
        </script>
    @endif

    <script>
        var KTDatatablesExampleStock = function () {
            // Shared variables
            var table;
            var datatable;

            // Private functions
            var initDatatable = function () {
                // Set date data order
                const tableRows = table.querySelectorAll('tbody tr');

                // Init datatable --- more info on datatables: https://datatables.net/manual/
                datatable = $(table).DataTable({
                    "info": false,
                    'order': [],
                    'pageLength': 10,
                });
            }

            // Hook export buttons
            var exportButtons = () => {
                const documentTitle = 'Customer Orders Report';
                var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: [
                        {
                            extend: 'copyHtml5',
                            title: documentTitle
                        },
                        {
                            extend: 'excelHtml5',
                            title: documentTitle
                        },
                        {
                            extend: 'csvHtml5',
                            title: documentTitle
                        },
                        {
                            extend: 'pdfHtml5',
                            title: documentTitle
                        }
                    ]
                }).container().appendTo($('#kt_datatable_example_buttons_stock'));

                // Hook dropdown menu click event to datatable export buttons
                const exportButtons = document.querySelectorAll('#kt_datatable_example_export_menu_stock [data-kt-export-stock]');
                exportButtons.forEach(exportButton => {
                    exportButton.addEventListener('click', e => {
                        e.preventDefault();

                        // Get clicked export value
                        const exportValue = e.target.getAttribute('data-kt-export-stock');
                        const target = document.querySelector('.dt-buttons .buttons-' + exportValue);

                        // Trigger click event on hidden datatable export buttons
                        target.click();
                    });
                });
            }

            // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
            var handleSearchDatatable = () => {
                const filterSearch = document.querySelector('[data-kt-filter="search-stock"]');
                filterSearch.addEventListener('keyup', function (e) {
                    datatable.search(e.target.value).draw();
                });
            }

            // Public methods
            return {
                init: function () {
                    table = document.querySelector('#kt_datatable_example_stock');

                    if ( !table ) {
                        return;
                    }

                    initDatatable();
                    exportButtons();
                    handleSearchDatatable();
                }
            };
        }();

        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTDatatablesExampleStock.init();
        });
    </script>
@endpush
