<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->

    <head>
        <base href="../" />
        <title>ERPPOS</title>
        <meta charset="utf-8" />
        <meta name="description" content="သင့်လုပ်ငန်းနဲ့ အသင့်တော်ဆုံး PICO SBS ကိုသုံး" />
        <meta name="keywords" content="pico, picosbs, sbs, erp, pos, erppos, picoerp, picosbs" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="ERPPOS Business Software" />
        <meta property="og:site_name" content="ERPPOS" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{asset('default/pico.png')}}" />
        <!--begin::Fonts(mandatory for all pages)-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <!--end::Fonts-->
        <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        <link href={{ asset("assets/plugins/global/plugins.bundle.css") }} rel="stylesheet" type="text/css" />
        <link href={{ asset("assets/css/style.bundle.css") }} rel="stylesheet" type="text/css" />
        <!--end::Global Stylesheets Bundle-->
        <link href={{ asset("assets/plugins/custom/datatables/datatables.bundle.css") }} rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href={{asset("customCss/scrollbar.css")}}>
        <style>

            .clicked {
            background-color: red;
            border: 2px solid blue;
            }
            #invoice_with_sidebar tr td{
                padding: 3px;
                }

            .search-item-container {
                max-height: 250px;
                overflow-y: auto;
            }
            /* Rotate the landscape text */
            .landscape-text {
                writing-mode: vertical-lr;
                transform: rotate(180deg);
            }

            .app-engage .app-engage-btn{
                width: 30px;
                height: 100px;
            }

            .app-engage-secondary .app-engage-btn:first-child {
                width: 30px;
                height: 40px;
            }

            .custom-modal{
                max-width: 500px;
                margin: 0 auto !important;
                left: 0 !important;
                right: 0 !important;
            }

            .sec-custom-modal {
                max-width: 500px;
                margin: 0 auto !important;
                left: 5% !important;
            }

            .sec-custom-modal-body {
                height: calc(80vh - 138px);
                overflow-y: auto;
            }

            /* #search_container{
                max-height: 300px;
            } */

            @media (max-width: 576px) { /* x-sm screens */
                .custom-width {
                    width: 50px;
                }
                .custom-padding {
                    padding-left: 0;
                    padding-right: 0;
                }
            }

            .tbody-scrollable {
                display: block;
                max-height: 40vh;
                overflow-y: scroll;
            }

            .second-view-height {
                height: 60vh;
                overflow-y: auto;
            }

            .for_disable_btn {
                /* cursor: not-allowed; */
            }
            @keyframes example {
                0%   {
                    opacity: 1;
                    top: 0;
                }
                100% {
                    opacity: 0;
                    top: -40px;
                }
            }
          </style>
          <script src={{ asset("assets/plugins/global/plugins.bundle.js") }}></script>
        <script src={{ asset("assets/js/scripts.bundle.js") }}></script>
    </head>
    <!--end::Head-->
    <!--begin::Body-->
    <body >
        <div style="height: 100vh; overflow: hidden; ">
            <div class="">
                <div id="spinnerWrapper" class="spinner-wrapper">
                    <div class="spinner">
                    </div>
                </div>
            </div>
            <div class="row bg-primary px-2 mh-80px ">
                <div class=" d-flex  justify-content-between align-items-center ">
                    {{-- <button class="btn btn-sm p-2 btn-light">Home</button> --}}
                    <div class="d-flex">
                        <a href="{{ route('home') }}" class="btn btn-sm  rounded-0"> <i class="fa-solid fa-house text-light fs-3"></i></a>
                        @if ($posRegister->use_for_res=='1')
                            <select name="table_id" id="table_nav_id" autofocus="false" data-placeholder="Select Table" placeholder="Select Table" class="w-150px form-select form-select-sm form-select w-auto m-0 border border-1 border-top-0 border-right-0 border-left-0 rounded-0 border-gray-300 text-light table_id tableSelect" data-control="select2" data-allow-clear="true">
                                <option disabled selected>Select Table</option>
                                @if ($tables)
                                    @foreach ($tables as $table)
                                        <option value="{{$table->id}}">{{$table->table_no}}</option>
                                    @endforeach
                                @endif
                            </select>
                            {{-- <a href="{{url('/restaurant/table/dashboard?pos_register_id='.encrypt($posRegisterId))}}" class="ms-0 btn btn-sm btn-info rounded-0"><< {{request('table_no')}}</a> --}}
                        @endif
                    </div>
                    <a class="navbar-brand fw-bold fs-3 text-white" href="#"></a>
                    <div class="">
                        {{-- <button class="btn btn-sm  text-dark fw-bold  rounded-0" id="extendScreen" >
                            <i class="fa-solid fa-display text-white">

                            </i>
                        </button> --}}
                        <button class="btn btn-sm  text-dark fw-bold  rounded-0"  data-href="{{route('pos.recentSale',$posRegister->id)}}" id="pos_sale_recent_btn"><i class="fa-solid fa-clock-rotate-left fs-3 text-white"></i></button>
                        <button class="btn btn-sm  btn-danger fw-bold  rounded-0"  data-href="{{route(
                        'pos.closeSession',
                            [
                            'posRegisterId'=>$posRegister->id,
                            'sessionId'=>request('sessionId')
                            ]
                            )}}" id="close_session_btn"><i class="fa-solid fa-power-off  fw-bolder"></i></button>
                    </div>
                </div>
            </div>
            <!--begin::Content-->
            <div class="content d-flex flex-column flex-column-fluid ms-8 " id="pos_kt_content" style="height:100%;">
                <!--begin::container-->
                <div class="container-fluid  pe-1 h-100" id="kt_content_container">
                    <!--begin::Layout-->

                    <div class="d-flex flex-column flex-lg-row p-2">
                        <!--begin::Content-->
                        <div class="d-flex flex-column flex-row-fluid me-lg-9 mb-lg-0 me-xl-9 mb-10 mb-xl-0" style="height: 100vh;">
                            <div class="row mt-3" style="max-height: 5%">
                                <div class="col-6">
                                    <select name="business_location_id" id="business_location_id" class="form-select form-select-sm me-2" data-kt-select2="true" data-placeholder="Select locations">
                                        <option></option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}" @selected(Auth::user()->default_location_id==$location->id)>{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select name="selling_price_group" id="selling_price_group" class="form-select form-select-sm " data-kt-select2="true" data-placeholder="Select selling price group">

                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3" style="max-height: 5%">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" name="pos_product_search" autocomplete="false" placeholder="Search products..." >
                                    <span class="input-group-text custom-tooltip productQuickAdd"   data-href="{{route('product.quickAdd')}}" type="button"  >
                                        <i class="fas fa-plus text-primary fs-2"></i>
                                    </span>
                                </div>
                                <div class="search-item-container " style="z-index: 500;">
                                    <ul class="list-group rounded-0 d-none" id="search_container">

                                    </ul>
                                </div>
                            </div>
                            <!--end::Select-->
                            <div class="row mb-3 mt-3" style="max-height: 5%">
                                <span class="text-end">
                                    <i class="fa-solid fa-list fs-1 " type="button"></i>
                                </span>
                            </div>
                            <!--begin::Pos product-->
                            <div class=" bg-transparent border-0 my-3 mb-10" style="height: 85%; overflow: scroll;">
                                <!--begin::Nav-->
                                <div class="row mb-10 p-5  flex-wrap justify-content-center align-items-stretch" id="all_product_list">

                                </div>
                                <!--end::Nav-->
                            </div>
                            <!--end::Pos product-->
                        </div>
                        <!--end::Content-->
                        <!--begin::Sidebar-->
                        <div class="flex-row-auto w-lg-550px w-xl-650px mt-3 d-none d-md-none d-sm-none d-lg-block d-xl-block mb-5 pe-3" id="invoice_side_bar" style="height: 100vh;" >

                            <div class="row mb-1" style="max-height: 5%;z-index: 200;">
                                <div class="input-group input-group-solid flex-nowrap">
                                    <x-customersearch placeholder='Select customer name' id="sb_pos_customer" name="pos_customer"
                                        className="form-select rounded-end-0 border-start border-end" >
                                        <x-slot:defaultOption>
                                            <option value="{{$walkInCustomer->id}}" selected>
                                                {{$walkInCustomer->getFullNameAttribute()}}-{{'('.arr($walkInCustomer,'mobile','-').')'}}</option>
                                            </x-slot>
                                        </x-customersearch>
                                    <span class="input-group-text border-gray-300 cursor-pointer" data-bs-toggle="modal" data-bs-target="#contact_add_modal" data-href="{{ route('pos.contact.add') }}">
                                        <i class="fa-solid fa-circle-plus text-primary fs-3"></i>
                                    </span>

                                    <span class="input-group-text border-gray-300 cursor-pointer contact_edit_btn " id="contact_edit_btn">
                                        <i class="fas fa-edit text-success-emphasis"></i>
                                    </span>

                                    <span class="input-group-text border-gray-300 cursor-pointer" id="contact_edit_phone_btn">
                                        <i class="fa-sharp fa-solid fa-phone text-info"></i>
                                    </span>

                                    <span class="input-group-text border-gray-300 receivable-amount">
                                        00
                                    </span>
                                    {{-- <span class="input-group-text border-gray-300 cursor-pointer" data-bs-toggle="modal" data-bs-target="#contact_add_modal">
                                        <i class="fas fa-plus"></i>
                                    </span> --}}

                                </div>
                            </div>
                            <div class="row position-relative " style="height: 95%">
                                <div class="table-responsive position-absolute top-0" style="max-height: 60%; overflow: scroll;padding-bottom: 300px">
                                    <table id="kt_datatable_zero_configuration invoice_with_sidebar_table" class="table table-row-bordered w-100">
                                        <thead class="table-layout-fixed bg-light" style="position: sticky; top: 0; z-index: 300;">
                                            <tr class="text-gray-700 fs-8 fw-bold text-uppercase p-3">
                                                <th class="min-w-175px">Product</th>
                                                <th class="min-w-80px">Price</th>
                                                <th class="min-w-130px">Quantity</th>
                                                <th class="min-w-100px">Subtotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center" id="invoice_with_sidebar">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="position-absolute " style="z-index: 600;bottom:30px" id="info_price_with_sidebar">
                                    <div class="row bg-primary rounded-3 px-3 py-5 mb-3 justify-content-between align-items-center">
                                        <div class="col-6 d-flex justify-content-between   text-white">
                                            <div class="fs-7 fw-bold ">
                                                <span class="d-block lh-1  mb-6">Item Qty</span>
                                                <span class="d-block mb-3 mb-6">Tax</span>
                                            </div>
                                            <div class="fs-7 fw-bold  text-end">
                                                <span class="d-block lh-1  mb-6 sb-item-quantity" data-kt-pos-element="total"></span>
                                                <span class="d-block mb-3 mb-6 sb-tax" data-kt-pos-element="tax"></span>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex  justify-content-between  text-white ">
                                            <!--begin::Content-->
                                            <div class="fs-7 fw-bold">
                                                <span class="d-block lh-1  mb-6">Total</span>
                                                <span class="d-block mb-5 mb-6 ">Discounts</span>
                                            </div>
                                            <!--end::Content-->
                                            <!--begin::Content-->
                                            <div class="fs-7 fw-bold text-end">
                                                <div class="d-block lh-1  mb-6">
                                                    <span class=" sb-total" data-kt-pos-element="total">

                                                    </span>
                                                    <span>
                                                         {{$currencySymbol}}
                                                    </span>
                                                </div>
                                                <div class="d-block mb-5 mb-6">
                                                    <span class="sb-discount" data-kt-pos-element="discount">

                                                    </span>
                                                    <span>
                                                         {{$currencySymbol}}
                                                    </span>
                                                </div>
                                            </div>
                                            <!--end::Content-->
                                        </div>
                                        <div class="col-12 d-flex flex-stack text-white my-2 mt-3">
                                            <div class="fs-6 fw-bold ">
                                                <span class="d-block fs-md-2  fs-2x lh-1">Total Amount</span>
                                            </div>
                                            <div class="fs-6 fw-bold  text-end">
                                                <span class="d-inline-block fs-md-2 lh-1 sb-total-amount" data-kt-pos-element="grant-total">
                                                    0.00
                                                </span>
                                                <span>
                                                    {{$currencySymbol}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row  px-3 py-2 ">
                                        <div class="col-7">
                                            <div class="d-flex  flex-equal gap-5  justify-content-around px-0 mb-5" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                                                    <div class="row mb-3 g-1">
                                                        <label class="for_disable_btn mb-3 btn btn-light  btn-sm  border border-1 border-gray-600 hover-elevate-up w-100 px-4" data-kt-button="true">
                                                            <!--begin::Input-->
                                                            <input class="btn-check" type="radio" name="method" value="0" />
                                                            <!--end::Input-->
                                                            <!--begin::Title-->
                                                            <span class="fs-7 text-dark fw-bold d-block sale_credit">Credit</span>
                                                            <!--end::Title-->
                                                        </label>
                                                        <!--end::Radio-->
                                                        <!--begin::Radio-->
                                                        @if ($posRegister->use_for_res=='1')
                                                            <label  data-bs-toggle="modal" id="order_confirm_modal_btn" data-bs-target="#order_confirm_modal" class="for_disable_btn mb-3 btn btn-light  btn-sm  border border-1 border-gray-600 border-gray-100 hover-elevate-up w-100 px-4 order_confirm_modal_btn" data-kt-button="true">
                                                                <input class="btn-check" type="radio" name="method" value="1" />
                                                                <span class="  text-dark fw-bold  rounded-0">Order</span>
                                                            </label>
                                                        @else
                                                            <label class="for_disable_btn mb-3 btn btn-light  btn-sm  border border-1 border-gray-600 hover-elevate-up w-100 px-4 finalizeOrder" data-kt-button="true">
                                                                {{-- <input class="btn-check" type="radio" name="method" value="1" /> --}}
                                                                <span class=" text-dark fw-bold  rounded-0">Order</span>
                                                            </label>
                                                        @endif

                                                        <!--end::Radio-->
                                                    </div>
                                                    <div class="row mb-3 g-1">
                                                        <!--begin::Radio-->
                                                        <label class="for_disable_btn mb-3 btn btn-light  btn-sm  border border-1 border-gray-600 hover-elevate-up w-100 px-4 " data-kt-button="true">
                                                            <!--begin::Input-->
                                                            <input class="btn-check" type="radio" name="method" value="3" />
                                                            <!--end::Input-->
                                                            <!--begin::Title-->
                                                            <span class="fs-7 text-dark fw-bold d-block sale_draft">Draft</span>
                                                            <!--end::Title-->
                                                        </label>
                                                        <!--end::Radio-->
                                                        <label  class="for_disable_btn mb-3 btn btn-light  btn-sm  border border-1 border-gray-600  rounded rounded-1 hover-elevate-up w-100 px-4 split_order_modal_btn_from_create" data-kt-button="true">
                                                            <input class="btn-check" type="radio" name="method" value="1" />
                                                            <span class="fs-7 text-dark fw-bold d-block">Split Voucher</button>
                                                        </label>
                                                    </div>
                                            </div>

                                        </div>
                                        <div class="col-5 btn-primary text-center " data-bs-toggle="modal" data-bs-target="#payment_info">
                                            <input type="submit" class="btn btn-lg btn-success d-block for_disable_btn " value="Payment" style="width: 100%; height: 70%;">
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <!--end::Sidebar-->
                    </div>
                    <!--end::Layout-->
                </div>
                <!--end::container-->
            </div>
            <!--end::Content-->

            <!--begin::Content-->
            <div class="content pb-15 d-flex flex-column flex-column-fluid ms-8 d-none d-lg-none d-xl-none d-xxl-none " id="pos_second_content"  style="height: 100vh">
                <!--begin::container-->
                <div class="container-xxl" id="pos_second_content_container"  style="height: 90%;">
                    <div class="card  card-flush" style="height: 100%; overflow: hidden">
                        <div class="card-body p-5">
                            <div class="row mb-1">
                                <div class="col-12">
                                    <div class="input-group input-group-solid input-group-sm mb-2 flex-nowrap">

                                        <x-customersearch placeholder='Select customer name' id="pos_customer" name="pos_customer" className="form-select rounded-end-0 border-start border-end" >
                                            <x-slot:defaultOption>
                                                <option value="{{$walkInCustomer->id}}" selected>
                                                    {{$walkInCustomer->getFullNameAttribute()}}-{{'('.arr($walkInCustomer,'mobile','-').')'}}</option>
                                                </x-slot>
                                        </x-customersearch>
                                        <span class="input-group-text border-gray-300 cursor-pointer " data-bs-toggle="modal" data-bs-target="#contact_add_modal" data-href="{{ route('pos.contact.add') }}">
                                            <i class="fa-solid fa-circle-plus text-primary fs-3"></i>
                                        </span>

                                        <span class="input-group-text border-gray-300 cursor-pointer contact_edit_btn " id="contact_edit_btn_modal">
                                            <i class="fas fa-edit text-success-emphasis"></i>
                                        </span>

                                        <span class="input-group-text border-gray-300 cursor-pointer " id="contact_edit_phone_btn_modal"><i class="fa-sharp fa-solid fa-phone text-info"></i>
                                        </span>

                                        <span class="input-group-text border-gray-300 receivable-amount">
                                            00
                                        </span>

                                    </div>
                                </div>
                            </div>
                            <div class="row  " style="height: 95%">
                                <div class="table-responsive position-absolute top-30" style="max-height: 60%; overflow: scroll;padding-bottom: 300px">
                                    <table id="kt_datatable_zero_configuration " class="table table-row-bordered ">
                                        <thead class="table-layout-fixed">
                                            <tr class="text-gray-700 fs-9 fw-bold text-uppercase text-center">
                                                <th class="min-w-100px text-start">Product</th>
                                                <th class="min-w-70px">Price</th>
                                                <th class="min-w-125px">Quantity</th>
                                                <th class="min-w-70px">Subtotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class=" text-center" id="invoice_with_modal">

                                        </tbody>
                                    </table>
                                </div>
                                <div id="info_price_with_modal" class="position-absolute  bg-light px-5" style="z-index: 600;bottom:0px;left: 0;">
                                    <div class="row bg-primary rounded-3 px-3 py-5 mb-3 ">
                                        <div class="col-12 d-flex flex-stack text-white ">
                                            <!--begin::Content-->
                                            <div class="fs-7 fs-md-6  fw-bold">
                                                <span class="d-block lh-1  mb-4">Item Quantity</span>
                                                <span class="d-block lh-1  mb-4">Total</span>
                                                <span class="d-block mb-5 mb-4 ">Discounts</span>
                                            </div>
                                            <!--end::Content-->
                                            <!--begin::Content-->
                                            <div class="fs-7 fs-md-6 fw-bold text-end">
                                                <span class="d-block lh-1  mb-4 sb-item-quantity" data-kt-pos-element="total"></span>
                                                <span class="d-block lh-1  mb-4 sb-total" data-kt-pos-element="total"></span>
                                                <span class="d-block mb-5 mb-4 sb-discount" data-kt-pos-element="discount"></span>
                                            </div>
                                            <!--end::Content-->
                                        </div>
                                        <div class="col-12 d-flex flex-stack text-white my-2 mt-3">
                                            <div class="fs-9 fw-bold ">
                                                <span class="d-block fs-md-1  fs-2 fs-md-2x lh-1">Total Amount</span>
                                            </div>
                                            <div class="fs-9 fw-bold  text-end">
                                                <span class="d-block fs-md-1 fs-2 fs-md-2x lh-1 sb-total-amount" data-kt-pos-element="grant-total"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row  px-3 py-2">
                                        <div class="col-md-7 col-sm-8 col-12">
                                            <div class="col-12 d-flex  flex-equal gap-5  justify-content-around px-0 mb-sm-5 align-items-center" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                                                <div class="row mb-sm-3 col-6">
                                                    <label class=" mb-3 btn  btn-sm bg-light btn-color-gray-900  border border-3 border-gray-100 hover-elevate-up w-100 px-4" data-kt-button="true">
                                                        <!--begin::Input-->
                                                        <input class="btn-check" type="radio" name="method" value="0" />
                                                        <!--end::Input-->
                                                        <!--begin::Icon-->
                                                        {{-- <i class="fonticon-cash-payment fs-2x mb-2 pe-0"></i> --}}
                                                        <!--end::Icon-->
                                                        <!--begin::Title-->
                                                        <span class="fs-8 fw-bold d-block sale_credit">Credit</span>
                                                        <!--end::Title-->
                                                    </label>
                                                    <!--end::Radio-->
                                                    <!--begin::Radio-->

                                                    @if ($posRegister->use_for_res=='1')
                                                        <label  data-bs-toggle="modal"  data-bs-target="#order_confirm_modal" class="for_disable_btn mb-3 btn  btn-sm  bg-light btn-color-gray-900  border border-3 border-gray-100 hover-elevate-up w-100 px-4 order_confirm_modal_btn" data-kt-button="true">
                                                            <input class="btn-check" type="radio" name="method" value="1" />
                                                            <button class="btn btn-sm  text-dark fw-bold  rounded-0">Order</button>
                                                        </label>
                                                    @else
                                                        <label class="for_disable_btn mb-3 btn  btn-sm  bg-light btn-color-gray-900  border border-3 border-gray-100 hover-elevate-up w-100 px-4 finalizeOrder" data-kt-button="true">
                                                            <input class="btn-check" type="radio" name="method" value="1" />
                                                            <button class="btn btn-sm  text-dark fw-bold  rounded-0">Order</button>
                                                        </label>
                                                    @endif

                                                    {{-- <label class=" mb-3 btn  btn-sm  bg-light btn-color-gray-900  border border-3 border-gray-100 hover-elevate-up w-100 px-4" data-kt-button="true">

                                                        <input class="btn-check" type="radio" name="method" value="1" />
                                                        <span class="fs-8 fw-bold d-block sale_order">Order</span>
                                                    </label> --}}
                                                    <!--end::Radio-->
                                                </div>
                                                <div class="row mb-sm-3 col-6">
                                                    <!--begin::Radio-->
                                                    <label class=" mb-3 btn  btn-sm  bg-light btn-color-gray-900  border border-3 border-gray-100 hover-elevate-up w-100 px-4 " data-kt-button="true">
                                                        <!--begin::Input-->
                                                        <input class="btn-check" type="radio" name="method" value="3" />
                                                        <!--end::Input-->
                                                        <!--begin::Icon-->
                                                        {{-- <i class="fonticon-card fs-2hx mb-2 pe-0"></i> --}}
                                                        <!--end::Icon-->
                                                        <!--begin::Title-->
                                                        <span class="fs-8 fw-bold d-block sale_draft">Draft</span>
                                                        <!--end::Title-->
                                                    </label>
                                                    <!--end::Radio-->
                                                    <label  class="for_disable_btn mb-3 btn  btn-sm   rounded rounded-1 btn-color-gray-900  border border-3 border-gray-100 hover-elevate-up w-100 px-4 split_order_modal_btn_from_create" data-kt-button="true">
                                                        <input class="btn-check" type="radio" name="method" value="1" />
                                                        <button class="btn btn-sm   fw-bold  rounded-0">
                                                             Split Voucher
                                                        </button>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-sm-4 col-12  btn-primary text-center" data-bs-toggle="modal" data-bs-target="#payment_info">
                                            <input type="submit" class="btn btn-lg btn-success d-block text-center h-sm-100px" value="Payment" style="width: 100%; ">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-engage" id="kt_app_engage">
                <button class="app-engage-btn hover-gray bg-success text-white" data-bs-toggle="modal" data-bs-target="#filter_category">
                    <span class="landscape-text">Category</span>
                </button>
                <button class="app-engage-btn hover-gray bg-primary text-white" data-bs-toggle="modal" data-bs-target="#filter_sub_category">
                    <span class="landscape-text">Sub Category</span>
                </button>
                <button class="app-engage-btn hover-gray bg-warning text-white" data-bs-toggle="modal" data-bs-target="#filter_brand">
                    <span class="landscape-text">Brand</span>
                </button>
                <button class="app-engage-btn hover-gray  bg-info text-white" data-bs-toggle="modal" data-bs-target="#filter_manufacturer">
                    <span class="landscape-text">Manufacturer</span>
                </button>
                <button class="app-engage-btn hover-gray generic bg-success text-white" data-bs-toggle="modal" data-bs-target="#filter_generic">
                    <span class="landscape-text">Generic</span>
                </button>
            </div>

            <div class="app-engage-secondary" style="z-index: 700;">
                <button class="app-engage-btn hover-gray d-lg-none d-xl-none d-xxl-none btn bg-primary btn-lg me-2 position-relative" id="pos_shopping_cart">
                    <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger badgeQtyCount">
                        0
                    </span>
                    <i class="fa-sharp fa-solid fa-cart-shopping text-white fs-3"></i>
                </button>
            </div>
        </div>

        {{-- Payment --}}
        <div class="modal fade dropDownParent" tabindex="-1" id="payment_info">
            <div class="modal-dialog modal-dialog-scrollable w-lg-600px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card rounded-bottom-0  shadow-none pb-10" style="z-index: 800">
                            <div class="card-body d-flex flex-column ">
                                <div class=" rounded-top d-flex justify-content-between align-items-center ">
                                    <h4 class="p-1 text-primary">Payable Amount</h4>
                                    <div class="">
                                        <span class="fs-3 text-primary me-3 print_payable_amount fw-bold">0.00</span>
                                        <span class="fw-bold fs-4 text-primary">{{$currencySymbol}}</span>
                                    </div>
                                </div>
                                <div class="separator my-2 separator-dashed border-gray-400"></div>
                                <div class=" d-flex justify-content-between align-items-center">
                                    <h4 class="p-1">Paid</h4>
                                    <div class="">
                                        <span class="fs-5 me-5 print_paid fw-bold"></span>
                                        <span class="fw-bold fs-5">{{$currencySymbol}}</span>
                                    </div>
                                </div>
                                <div class="separator my-2 separator-dashed border-gray-400"></div>
                                <div class=" d-flex justify-content-between align-items-center">
                                    <h4 class="p-1">Balance</h4>
                                    <div class="">
                                        <span class="fs-5 me-5 print_balance fw-bold"></span>
                                        <span class="fw-bold fs-5">{{$currencySymbol}}</span>
                                    </div>
                                </div>

                                <div class="separator my-2 separator-dashed border-gray-400"></div>
                                <div class=" rounded-bottom d-flex justify-content-between align-items-center">
                                    <h4 class="p-1">Change</h4>
                                    <div class="">
                                        <span class="fs-5 me-5 print_change fw-bold">0.00</span>
                                        <span class="fw-bold fs-5">{{$currencySymbol}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card  border border-left-0 border-right-0 border-bottom-0 border-top-1 border-primary  ">
                            <div class="card-body">
                                <!--begin::Repeater-->
                                <div id="payment_amount_repeater">
                                    <!--begin::Form group-->
                                    <div class="form-group">
                                        <form action="" id="paymentForm">
                                            @if (count($reservations) > 0)
                                            <div class="folio-Form mb-5">
                                                <div class="form-group">
                                                    <label for="" class="form-label fw-bold">Post to Folio</label>
                                                    <select name="reservation_id_from_delivered"  class="form-select form-select-sm reservation_id"
                                                        data-placeholder="Select Reservation" placeholder='Select Reservation' data-kt-select2="true"
                                                        data-allow-clear="true" data-dropdown-parent="#payment_info" autofocus="false">

                                                    </select>
                                                </div>
                                            </div>
                                            @endif
                                            <div id="payment_row_body">

                                            </div>
                                        </form>
                                    </div>
                                    <!--end::Form group-->

                                    <!--begin::Form group-->
                                    @if (isUsePaymnetAcc())
                                    <div class="form-group mt-5 ">
                                        <a href="javascript:;" class="btn btn-light-primary btn-sm add-payment-row">
                                            <i class="fas fa-plus fs-3"></i>
                                            Add
                                        </a>
                                    </div>

                                    @endif
                                    <!--end::Form group-->
                                </div>
                                <!--end::Repeater-->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info btn-sm payment_save_btn" data-bs-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-primary btn-sm" id="payment_print" data-bs-dismiss="modal">Save /
                            Print</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Phone --}}
        <div class="modal fade custom-modal" tabindex="-1" id="pos_contact_phone_edit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        Contact Phone Edit
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <label for="">Name </label>
                            <input type="text" class="form-control" readonly value="Customer 1">
                        </div>
                    </div>
                    <div class="modal-body">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Category --}}
        <div class="modal fade sec-custom-modal" tabindex="-1" id="filter_category">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h3 class="modal-title">Select Category</h3>
                        <div>
                          <div class="btn btn-light-primary btn-sm clear_all_filter">Clear all filter</div>
                          <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-sharp fa-solid fa-xmark"></i>
                          </div>
                        </div>
                    </div>

                    <div class="modal-body sec-custom-modal-body">
                        @foreach ($categories as $category)
                            <div class="mb-5">
                                <input class="form-check-input me-3 cursor-pointer" name="filter_parent_category_id" type="radio" value="{{ $category->id }}" id="filter_parent_category_{{ $category->id }}"/>

                                <label class="form-check-label" for="filter_parent_category_{{ $category->id }}">
                                    <div class="fw-semibold text-gray-800 cursor-pointer">{{ $category->name }}</div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary save-parent-category">Save</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Sub Category --}}
        <div class="modal fade sec-custom-modal" tabindex="-1" id="filter_sub_category">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h3 class="modal-title">Select Category</h3>
                        <div>
                          <div class="btn btn-light-primary btn-sm clear_all_filter">Clear all filter</div>
                          <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-sharp fa-solid fa-xmark"></i>
                          </div>
                        </div>
                    </div>

                    <div class="modal-body sec-custom-modal-body" >
                        @foreach ($categories as $category)
                            @if ($category->parent_id !== null)
                                <div class="mb-5">
                                    <input class="form-check-input me-3 cursor-pointer" name="filter_child_category_id" type="radio" value="{{ $category->id }}" id="filter_child_category_id_{{ $category->id }}"/>

                                    <label class="form-check-label" for="filter_child_category_id_{{ $category->id }}">
                                        <div class="fw-semibold text-gray-800 cursor-pointer">{{ $category->name }}</div>
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary save-sub-category">Save</button>
                    </div>
                </div>
            </div>
        </div>



        {{-- Filter Brand --}}
        <div class="modal fade sec-custom-modal" tabindex="-1" id="filter_brand">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h3 class="modal-title">Select Category</h3>
                        <div>
                          <div class="btn btn-light-primary btn-sm clear_all_filter">Clear all filter</div>
                          <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-sharp fa-solid fa-xmark"></i>
                          </div>
                        </div>
                    </div>

                    <div class="modal-body sec-custom-modal-body">

                        @foreach ($brands as $brand)
                            <div class="mb-5">
                                <input class="form-check-input me-3 cursor-pointer" name="filter_brand_id" type="radio" value="{{ $brand->id }}" id="filter_brand_id_{{ $brand->id }}"/>

                                <label class="form-check-label" for="filter_brand_id_{{ $brand->id }}">
                                    <div class="fw-semibold text-gray-800 cursor-pointer">{{ $brand->name }}</div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary save-brand">Save</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Manufacturer --}}
        <div class="modal fade sec-custom-modal" tabindex="-1" id="filter_manufacturer">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h3 class="modal-title">Select Category</h3>
                        <div>
                          <div class="btn btn-light-primary btn-sm clear_all_filter">Clear all filter</div>
                          <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-sharp fa-solid fa-xmark"></i>
                          </div>
                        </div>
                    </div>

                    <div class="modal-body sec-custom-modal-body" >
                        @foreach ($manufacturers as $manu)
                            <div class="mb-5">
                                <input class="form-check-input me-3 cursor-pointer" name="filter_manufacturer_id" type="radio" value="{{ $manu->id }}" id="filter_manufacturer_id_{{ $manu->id }}"/>

                                <label class="form-check-label" for="filter_manufacturer_id_{{ $manu->id }}">
                                    <div class="fw-semibold text-gray-800 cursor-pointer">{{ $manu->name }}</div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary save-manufacturer">Save</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Generic --}}
        <div class="modal fade sec-custom-modal" tabindex="-1" id="filter_generic">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h3 class="modal-title">Select Category</h3>
                        <div>
                          <div class="btn btn-light-primary btn-sm clear_all_filter">Clear all filter</div>
                          <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-sharp fa-solid fa-xmark"></i>
                          </div>
                        </div>
                    </div>

                    <div class="modal-body sec-custom-modal-body" >
                        @foreach ($generics as $generic)
                            <div class="mb-5">
                                <input class="form-check-input me-3 cursor-pointer" name="filter_generic_id" type="radio" value="{{ $generic->id }}" id="filter_generic_id_{{ $generic->id }}"/>

                                <label class="form-check-label" for="filter_generic_id_{{ $generic->id }}">
                                    <div class="fw-semibold text-gray-800 cursor-pointer">{{ $generic->name }}</div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary save-generic">Save</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Each Invoice Row Discount --}}
        <div class="modal fade custom-modal" tabindex="-1" id="invoice_row_discount">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-sharp fa-solid fa-xmark"></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        @if($setting['lot_control']=='on')
                        <div class="row mb-5">
                            <div class="col-12">
                                <label for="" class="fs-5 mb-1">Batch/Lot/Expire Date</label>
                                <select class="form-select mb-2 form-select-sm rounded-0" id="lot_serial_modal_input" name="lot_serial_modal_input"
                                    data-control="select2" data-placeholder="Select Batch/Lot/Expire Date" placeholder="Select Batch/Lot/Expire Date" >


                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="row mb-5">
                            <div class="col-12">
                                <label for="" class="fs-5 mb-1">Packaging</label>
                                <select class="form-select mb-2 form-select-sm rounded-0" id="packaging_modal" name="packaging_id"
                                    data-control="select2" data-placeholder="Select Packaging" placeholder="Select Packaging" data-hide-search="true" data-allow-clear="true">


                                </select>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-12">
                                <label for="" class="fs-5 mb-1">Packaging Qty</label>
                                <input type="text" name="packaging_quantity" class="form-control packaging_quantity">

                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-12">
                                <label for="" class="fs-5">Selling Price</label>
                                <select class="form-select mb-2 form-select-sm rounded-0" disabled name="each_selling_price" data-control="select2" data-placeholder="-" placeholder="-" data-hide-search="true">
                                    <option value="default_selling_price">Default Selling Price</option>
                                    @foreach ($price_lists as $pricelist)
                                        <option value="{{ $pricelist->id }}">{{ $pricelist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-12">
                                <label for="" class="fs-5">Price</label>
                                <input type="text" class="form-control form-control-sm rounded-0" name="modal_price_without_dis" value="" readonly>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-6">
                                <label for="" class="fs-5">Discount Type</label>
                                <select class="form-select mb-2 form-select-sm rounded-0" name="invoice_row_discount_type" data-control="select2" data-hide-search="true">
                                    <option value="fixed">Fixed</option>
                                    <option value="percentage" selected>Percentage</option>
                                    <option value="foc" >FOC</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="" class="fs-5">Discount Amount</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm rounded-0" name="discount_amount" value="0">
                                    <div class="input-group-text percSymbol  rounded-0">%</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-12">
                                <label for="" class="fs-5">Subtotal With Discount</label>
                                <input type="text" class="form-control form-control-sm rounded-0" name="subtotal_with_discount" value="">
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-12">
                                <label for="" class="fs-5">Note</label>
                                <textarea name="item_detail_note_input"  id="item_detail_note_input" cols="30" rows="4" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal"  id="saveExtraSetting">Save</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- POS order confirm  --}}
        <div class="modal fade dropDownParent"  tabindex="-1" id="order_confirm_modal">
            <div class="modal-dialog modal-dialog-scrollable  w-md-500px">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title"> (<span id="table-text"></span>) Order Preview </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                    @if (count($reservations) > 0)
                        <div class="folio-Form p-5">
                            <div class="form-group">
                                <label for="" class="form-label fw-bold">Post to Folio</label>
                                <select name="reservation_id_from_order"  class="form-select form-select-sm reservation_id"
                                    data-placeholder="Select Reservation" placeholder='Select Reservation' data-kt-select2="true"
                                    data-allow-clear="true" data-dropdown-parent="#order_confirm_modal" autofocus="false">

                                </select>
                            </div>
                        </div>

                    @endif
                  <div class="p-5 pt-0">
                    <label for="" class="form-label">Sevices:</label>
                    <select name="services" class="form-select form-select-sm" id="services" placeholder="Services" data-placeholder="Services" data-kt-select2="true" data-hide-search="true">
                        <option value="dine_in">dine in</option>
                        <option value="take_away">Take Away</option>
                        <option value="delivery">Delivery</option>
                    </select>
                  </div>
                   <div class="px-5 mt-3 mb-5">
                        <select id="tableForFinalize"  autofocus="false" data-placeholder="Select Table" placeholder="Select Table" class="form-select form-select-sm tableSelect" data-kt-select2="true" data-allow-clear="true">
                            <option disabled selected>Select Table</option>
                            @if ($tables)
                                @foreach ($tables as $table)
                                    <option value="{{$table->id}}">{{$table->table_no}}</option>
                                @endforeach
                            @endif
                        </select>
                   </div>
                  <div class="modal-body" id="orderDetailConfirm">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm finalizeOrder" id="" data-bs-dismiss="modal">Finalize Order</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
            </div>
        </div>
        {{-- Edit Contact Modal  --}}
        <div class="modal fade" tabindex="-1"  id="edit_contact_modal">
        </div>

        {{-- Edit Contact Phone --}}
        <div class="modal fade custom-modal" tabindex="-1" id="contact_edit_phone">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST" id="edit_customer_phone_form">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h3 class="modal-title">Customer Phone</h3>
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-sharp fa-solid fa-xmark"></i>
                            </div>
                        </div>

                        <div class="modal-body " >
                            <div class="row mb-5">
                                <div class="col-12">
                                    <label for="" class="fs-5">Name</label>
                                    <input type="text" class="form-control form-control-sm rounded-0" name="customer_name" readonly value="">
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-12">
                                    <label for="" class="fs-5">Phone</label>
                                    <input type="text" class="form-control form-control-sm rounded-0" name="customer_phone" value="">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary save-generic">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" id="pos_sale_recent"></div>
        <div class="modal fade viewDetailModal" style="z-index: 99999" tabindex="-1"></div>
        <div class="modal modal-lg fade " tabindex="-1"  data-bs-focus="false"  id="modal"></div>
        {{-- POS Sale Recent --}}
        <div class="modal fade" tabindex="-1" id="closeSessionModal"></div>
        <div class="modal modal-lg fade " tabindex="-1"  data-bs-focus="false"  id="quick_add_product_modal" ></div>
        <div class="modal modal-lg fade" tabindex="-1"  data-bs-focus="false"  id="reservationFolioPosting"></div>
        <div class="modal fade" id="suggestionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog w-lg-600px modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-gray-800 position-relative d-flex">
                            Product Suggestion
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="suggestionProducts">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="{{ asset('customJs/toastrAlert/alert.js') }}"></script>
        <script src={{asset('customJs/loading/miniLoading.js')}}></script>
		<script src={{asset('customJs/print/print.js')}}></script>
        <!--end::Global Javascript Bundle-->
        @include('App.resources.common')
        @include('App.pos.contactAdd')
        @include('App.alert.alert')
        @include('App.pos.js.pos_js')
    </body>

    <!--end::Body-->
</html>
<script>
    $('#extendScreen').click(()=>{
        let route=`{{route('pos.extendScreen')}}`;
        let wd=window.open(route, '_blank', 'width=600, height=400');
    })

    let options=`
        <option value="">
            @foreach ($reservations as $reservation)
                @php
                $guest = $reservation->contact;
                $guestName = ($guest['prefix'] ?? '') . ' ' . ($guest['first_name'] ?? '') . ' ' . ($guest['middle_name'] ??
                '')
                . ' ' .
                ($guest['last_name'] ?? '');
                @endphp
                <option value="{{$reservation->id}}">{{$guestName}} ({{$reservation->reservation_code}})</option>
        @endforeach
        </option>
    `


    $(document).ready(function(){
        $('.reservation_id').append(options);
        $('[data-kt-select2="true"]').select2();
        $(document).on('click', '#pos_sale_recent_btn', function(e){
            e.preventDefault();
            loadingOn();
            $('#pos_sale_recent').load($(this).data('href'), function() {
            //     // $(this).remove();
                $(this).modal('show');
                loadingOff();

            });
        });
        $(document).on('click', '#close_session_btn', function(e){
            e.preventDefault();
            loadingOn();
            $('#closeSessionModal').load($(this).data('href'), function() {
            //     // $(this).remove();
                $(this).modal('show');
                loadingOff();

            });
        });
        $(document).on('click', '#pos_sale_recent_btn', function(e){
            e.preventDefault();
            loadingOn();
            $('#pos_sale_recent').load($(this).data('href'), function() {
            //     // $(this).remove();
                $(this).modal('show');
                loadingOff();

            });
        });
        $(document).on('click','.editRecent',function(){
            $('#pos_sale_recent').modal('hide');
        })
        $(document).on('click', '.view_detail', function(){
            $url=$(this).data('href');

            loadingOn();
            $('.viewDetailModal').load($url, function() {
                $(this).modal('show');

                loadingOff();
            });
         });


    $(document).on('click', '.print-invoice', function(e) {
            e.preventDefault();
            loadingOn();
            var url = $(this).data('href');
            ajaxPrint(url); //function from print.js
        });


    $(document).on('click', '.productQuickAdd', function(){
        $url=$(this).data('href');
        loadingOn();
        $('#quick_add_product_modal').load($url, function() {
            $(this).modal('show');
            loadingOff();
// Begin:: quick add product
        $('form#quick_add_product_form').submit(function(e) {
            setTimeout(() => {
                (()=>{getProductVariations()})();
            }, 700);
        })
        });
    });
        $(document).on('click', '.post-to-reservation', function(e){
        loadingOn();
        e.preventDefault();
        $('#reservationFolioPosting').load($(this).data('href'), function() {
            loadingOff();
            $('.joinSelect').select2();
            $(this).modal('show');
            $('#pos_sale_recent').modal('hide');
            $('form#postToReservationFolio').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();
                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            $('#reservationFolioPosting').modal('hide');
                            toastr.success(result.msg);
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                    error: function(result) {
                        toastr.error(result.responseJSON.errors, 'Something went wrong');
                    }
                });
            });
        });
    });
})
</script>
@stack('scripts')
