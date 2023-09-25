@extends('App.main.navBar')

@section('setting_active','active')
@section('setting_active_show','active show')
@section('location_here_show','here show')
@section('location_add_nav','active')

@section('styles')
		<link href="{{asset('customCss/businessSetting.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Add Bussiness Location</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Setting</li>
        <li class="breadcrumb-item text-muted">
            <a href="" class="text-muted text-hover-primary">Bussiness Location</a>
        </li>
        <li class="breadcrumb-item text-dark">Add Bussiness Location </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Container-->
    <div class="container-xxl" id="location">

        <!--begin::Modals-->
        <div class="card" >
            <div class="card-body user-select-none">
            <!--begin::Form-->
            <form class="form" action="{{route('location_add')}}" method="POST" id="location_form">
                @csrf
                 <!--begin::Scroll-->
                    <div class="me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="false" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                       <div class="row">
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-4 fv-row">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Location Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <x-forms.input  placeholder="Eg : Warehouse" name="name" ></x-forms.input>
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-4 fv-row">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Sub Location Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <x-forms.input placeholder="sub location name" name="sub_location_name"></x-forms.input>
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-4 fv-row">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Locatino Type</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <x-forms.nob-select placeholder="Select Location Type">
                                    <option value=""></option>
                                    @foreach ($locationType as $lt)
                                        <option value="{{$lt->id}}">{{$lt->name}}</option>
                                    @endforeach
                                </x-forms.nob-select>
                                <!--end::Input-->
                            </div>
                       </div>
                       <div class="row">
                            <!--end::Input group-->
                            <div class="col-md-4 col-sm-6 col-12 d-flex align-items-end justify-content-md-start mb-12">
                                <div class="form-check">
                                    <label class="form-check-label text-gray-700 fs-7" for='is_active'>Is Active Location?</label>
                                    <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" checked>
                                </div>

                            </div>
                             <!--end::Input group-->
                            <div class="col-md-4 col-sm-6 col-12 d-flex align-items-end justify-content-md-start mb-12">
                                <div class="form-check">
                                    <label class="form-check-label text-gray-700 fs-7" for='allow_purchase_order'>Allow Purchase Order</label>
                                    <input type="checkbox" name="allow_purchase_order" id="allow_purchase_order" value="1" class="form-check-input" >
                                </div>
                            </div>
                             <!--end::Input group-->
                            <div class="col-md-4 col-sm-6 col-12 d-flex align-items-end justify-content-md-start mb-12">
                                <div class="form-check">
                                    <label class="form-check-label text-gray-700 fs-7" for='allow_sale_order'>Allow Sale Order</label>
                                    <input type="checkbox" name="allow_sale_order" id="allow_sale_order" value="1" class="form-check-input" >
                                </div>
                            </div>
                       </div>
                        <!--begin::Input group-->
                        <div class="row row-cols">
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Location ID</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="location_id" class="form-control form-control-sm " placeholder="Location Id" name="location_id" value="{{old('location_id')}}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Landmark</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="landmark" name="landmark" value="{{old('landmark')}}" />
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">City</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="location_id" class="form-control form-control-sm " placeholder="city" name="city" value="{{old('city')}}" />
                                <!--end::Input-->
                            </div>
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Zip Code:</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="Zip Code" name="zip_code" value="{{old('zip_code')}}" />
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">State</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="location_id" class="form-control form-control-sm " placeholder="State" name="state" value="{{old('state')}}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Country</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="country" name="country" value="{{old('country')}}" />
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Mobile</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="location_id" class="form-control form-control-sm " placeholder="Mobile" name="mobile" value="{{old('mobile')}}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Alternate contact number:</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="Alternate contact number" name="alternate_number" value="{{old('alternate_number')}}" />
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Email</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="mail" class="form-control form-control-sm " placeholder="email" name="email" value="{{old('email')}}" />
                                <!--end::Input-->
                            </div>
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Website</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm" placeholder="website" name="website" value="{{old('website')}}" />
                                <!--end::Input-->
                            </div>

                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Price list:</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                               <select name="price_lists_id" id="" class="form-select form-select-sm" data-control="select2" data-allow-clear="true" data-placeholder="Price List" placeholder="price list">

                                    <option value=""></option>
                                    @foreach ($priceLists as $p)
                                        <option value="{{$p->id}}">{{$p->name}}</option>
                                    @endforeach
                               </select>
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Invoice scheme:</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                               <select name="" id="" class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                    <option value="">Please Select</option>
                                    <option value="">Default</option>
                               </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                             <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Invoice Layouts For POS:</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                               <select name="" id="" class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                    <option value="">Please Select</option>
                                    <option value="">Default</option>
                               </select>
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Invoice layout for sale:</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                               <select name="" id="" class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                    <option value="">Please Select</option>
                                    <option value="">Default</option>
                               </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                             <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Default Selling Price Group:</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                               <select name="" id="" class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                    <option value="">Please Select</option>
                                    <option value="">Default</option>
                                    <option value="">10 PCS Price</option>
                                    <option value="">50 PCS Price</option>
                                    <option value="">100 PCS Price</option>
                                    <option value=""></option>
                               </select>
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2" id="custom_field_1">
                                    <span class="required">custom field 1</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="" class="form-control form-control-sm " placeholder="custom field 1" name="custom_field1" value="{{old('custom_field1')}}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2" id="custom_field_2">custom field 2</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="Custom Field 2" name="custom_field2" value="{{old('custom_field2')}}" />
                                <!--end::Input-->
                            </div>
                             <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2" id="custom_field_3">
                                    <span class="required">custom field 3</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="location_id" class="form-control form-control-sm " placeholder="Custom Field 3" name="custom_field3"  value="{{old('custom_field3')}}"/>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-12 col-md-4 col-sm-6 col-12">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2" id="custom_field_4">custom field 4</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-sm " placeholder="Custom Field 4" name="custom_field4"  value="{{old('custom_field4')}}" />
                                <!--end::Input-->
                            </div>
                        </div>

                        <div class="separator border-gray-600 my-8"></div>

                        <div class="fv-row mb-12 d-none">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-semibold mb-2">POS screen Featured Products:</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-sm " placeholder="" name="pos_screen" value="" />
                            <!--end::Input-->
                        </div>

                        <div class="separator border-gray-600 my-8 d-none"></div>

                        <div class="fv-row mb-12 d-none">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-semibold mb-2">Payment Options</label>
                            <!--end::Label-->
                        </div>
                        <div class="row row-cols mb-7 d-none">
                            <div class=" col-4">
                                <label class="fs-6 fw-semibold form-label mt-3 col-12 mb-5 text-success text-center" for="purchase_shipping_customer_field_1">
                                    <span > Payment Methods</span>
                                </label>
                            </div>
                            <div class="col-8">
                                <label class="fs-6 fw-semibold form-label mt-3 text-center col-12 mb-5 text-success" for="purchase_shipping_customer_field_1">
                                    <span > Default Account</span>
                                </label>
                            </div>
                        </div>
                        {{-- <div class="row row-cols d-none">
                            <div class=" col-4 mb-7 text-center fs-6  d-flex align-items-center justify-content-center">
                                Cash
                            </div>
                            <div class="col-8">
                                <div class="col-12 mb-7 ">
                                    <div class="input-group">
                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center w-100 w-sm-auto">
                                            <input  type="checkbox" checked class="form-check-input border-gray-400 " name="cash_check" id="cash_check">
                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="cash_check">
                                                <span >Enable</span>
                                            </label>
                                        </div>
                                         <div class="overflow-hidden flex-grow-1">
                                            <select name="cash_option" id="cash_option" data-hide-search="true" class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Kpay</option>
                                                <option value="2" selected>None</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="separator border-gray-200 mb-8 d-none"></div>

                        <div class="row row-cols d-none">
                            <div class=" col-4 mb-7 text-center fs-6  d-flex align-items-center justify-content-center">
                                Card
                            </div>
                            <div class="col-8">
                                <div class="col-12 mb-7 ">
                                    <div class="input-group">
                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center w-100 w-sm-auto w">
                                            <input  type="checkbox" checked class="form-check-input border-gray-400 " name="card_checked" id="card_checked">
                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="card_checked">
                                                <span >Enable</span>
                                            </label>
                                        </div>
                                         <div class="overflow-hidden flex-grow-1">
                                            <select name="card_option" id="card_option" data-hide-search="true" class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Kpay</option>
                                                <option value="2" selected>None</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator border-gray-200 mb-8 d-none"></div>

                        <div class="row row-cols d-none">
                            <div class=" col-4 mb-7 text-center fs-6  d-flex align-items-center justify-content-center">
                                Cheque
                            </div>
                            <div class="col-8">
                                <div class="col-12 mb-7 ">
                                    <div class="input-group">
                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center w-100 w-sm-auto w">
                                            <input  type="checkbox" checked class="form-check-input border-gray-400 " name="cheque_checked" id="cheque_checked">
                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="cheque_checked">
                                                <span >Enable</span>
                                            </label>
                                        </div>
                                         <div class="overflow-hidden flex-grow-1">
                                            <select name="cheque_option" id="cheque_option" data-hide-search="true" class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="">
                                                 <option value="1">Kpay</option>
                                                <option value="2" selected>None</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator border-gray-200 mb-8 d-none"></div>

                        <div class="row row-cols d-none">
                            <div class=" col-4 mb-7 text-center fs-6  d-flex align-items-center justify-content-center">
                                Bank Transfer
                            </div>
                            <div class="col-8">
                                <div class="col-12 mb-7 ">
                                    <div class="input-group">
                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center w-100 w-sm-auto w">
                                            <input  type="checkbox" checked class="form-check-input border-gray-400 " name="bank_transfer_checked" id="bank_transfer_checked">
                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="bank_transfer_checked">
                                                <span >Enable</span>
                                            </label>
                                        </div>
                                         <div class="overflow-hidden flex-grow-1">
                                            <select name="bank_transfer_option" id="bank_transfer_option" data-hide-search="true" class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Kpay</option>
                                                <option value="2" selected>None</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator border-gray-200 mb-8 d-none"></div>

                        <div class="row row-cols d-none">
                            <div class=" col-4 mb-7 text-center fs-6  d-flex align-items-center justify-content-center">
                                Other
                            </div>
                            <div class="col-8">
                                <div class="col-12 mb-7 ">
                                    <div class="input-group">
                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center w-100 w-sm-auto w">
                                            <input  type="checkbox" checked class="form-check-input border-gray-400 " name="other_check" id="other_check">
                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="other_check">
                                                <span >Enable</span>
                                            </label>
                                        </div>
                                         <div class="overflow-hidden flex-grow-1">
                                            <select name="other_option" id="other_option" data-hide-search="true" class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Kpay</option>
                                                <option value="2" selected>None</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator border-gray-200 mb-8 d-none"></div>

                        <div class="row row-cols d-none">
                            <div class=" col-4 mb-7 text-center fs-6  d-flex align-items-center justify-content-center">
                                Custom Payment 1
                            </div>
                            <div class="col-8">
                                <div class="col-12 mb-7 ">
                                    <div class="input-group">
                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center w-100 w-sm-auto w">
                                            <input  type="checkbox" checked class="form-check-input border-gray-400 " name="custom_payment_1_checked" id="custom_payment_1_checked">
                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="custom_payment_1_checked">
                                                <span >Enable</span>
                                            </label>
                                        </div>
                                         <div class="overflow-hidden flex-grow-1">
                                            <select name="custom_payment_1_option" id="custom_payment_1_option" data-hide-search="true" class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Kpay</option>
                                                <option value="2" selected>None</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator border-gray-200 mb-8 d-none"></div>

                        <div class="row row-cols d-none">
                            <div class=" col-4 mb-7 text-center fs-6  d-flex align-items-center justify-content-center">
                                Custom Payment 2
                            </div>
                            <div class="col-8">
                                <div class="col-12 mb-7 ">
                                    <div class="input-group">
                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center w-100 w-sm-auto w">
                                            <input  type="checkbox" checked class="form-check-input border-gray-400 " name="custom_payment_2_checked" id="custom_payment_2_checked">
                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="custom_payment_2_checked">
                                                <span >Enable</span>
                                            </label>
                                        </div>
                                         <div class="overflow-hidden flex-grow-1">
                                            <select name="custom_payment_2_option" id="custom_payment_2_option" data-hide-search="true" class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Kpay</option>
                                                <option value="2" selected>None</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator border-gray-200 mb-8 d-none"></div>

                        <div class="row row-cols d-none">
                            <div class=" col-4 mb-7 text-center fs-6  d-flex align-items-center justify-content-center">
                                Custom Payment 3
                            </div>
                            <div class="col-8">
                                <div class="col-12 mb-7 ">
                                    <div class="input-group">
                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center w-100 w-sm-auto w">
                                            <input  type="checkbox" checked class="form-check-input border-gray-400 " name="custom_payment_3_checked" id="custom_payment_3_checked">
                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="custom_payment_3_checked">
                                                <span >Enable</span>
                                            </label>
                                        </div>
                                         <div class="overflow-hidden flex-grow-1">
                                            <select name="custom_payment_3_option" id="custom_payment_3_option" data-hide-search="true" class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Kpay</option>
                                                <option value="2" selected>None</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator border-gray-200 mb-8 d-none"></div>

                        <div class="row row-cols d-none">
                            <div class=" col-4 mb-7 text-center fs-6  d-flex align-items-center justify-content-center">
                                Custom Payment 4
                            </div>
                            <div class="col-8">
                                <div class="col-12 mb-7 ">
                                    <div class="input-group">
                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center w-100 w-sm-auto w">
                                            <input  type="checkbox" checked class="form-check-input border-gray-400 " name="custom_payment_4_checked" id="custom_payment_4_checked">
                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="custom_payment_4_checked">
                                                <span >Enable</span>
                                            </label>
                                        </div>
                                         <div class="overflow-hidden flex-grow-1">
                                            <select name="custom_payment_4_option" id="custom_payment_4_option" data-hide-search="true" class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Kpay</option>
                                                <option value="2" selected>None</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator border-gray-200 mb-8 d-none"></div>


                        <div class="row row-cols d-none">
                            <div class=" col-4 mb-7 text-center fs-6  d-flex align-items-center justify-content-center">
                                Custom Payment 5
                            </div>
                            <div class="col-8">
                                <div class="col-12 mb-7 ">
                                    <div class="input-group">
                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center w-100 w-sm-auto w">
                                            <input  type="checkbox" checked class="form-check-input border-gray-400 " name="custom_payment_5_checked" id="custom_payment_5_checked">
                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="custom_payment_5_checked">
                                                <span >Enable</span>
                                            </label>
                                        </div>
                                         <div class="overflow-hidden flex-grow-1">
                                            <select name="custom_payment_5_option" id="custom_payment_5_option" data-hide-search="true" class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Kpay</option>
                                                <option value="2" selected>None</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator border-gray-200 mb-8 d-none"></div>

                        <div class="row row-cols d-none">
                            <div class=" col-4 mb-7 text-center fs-6  d-flex align-items-center justify-content-center">
                                Custom Payment 6
                            </div>
                            <div class="col-8">
                                <div class="col-12 mb-7 ">
                                    <div class="input-group">
                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center w-100 w-sm-auto w">
                                            <input  type="checkbox" checked class="form-check-input border-gray-400 " name="custom_payment_6_checked" id="custom_payment_6_checked">
                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="custom_payment_6_checked">
                                                <span >Enable</span>
                                            </label>
                                        </div>
                                         <div class="overflow-hidden flex-grow-1">
                                            <select name="custom_payment_6_option" id="custom_payment_6_option" data-hide-search="true" class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="">
                                                <option value="1">Kpay</option>
                                                <option value="2" selected>None</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator border-gray-200 mb-8 d-none"></div>

                        <div class="row row-cols d-none">
                            <div class=" col-4 mb-7 text-center fs-6  d-flex align-items-center justify-content-center">
                                Custom Payment 7
                            </div>
                            <div class="col-8">
                                <div class="col-12 mb-7 ">
                                    <div class="input-group">
                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center w-100 w-sm-auto w">
                                            <input  type="checkbox" checked class="form-check-input border-gray-400 " name="custom_payment_7_checked" id="custom_payment_7_checked">
                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="custom_payment_7_checked">
                                                <span >Enable</span>
                                            </label>
                                        </div>
                                         <div class="overflow-hidden flex-grow-1">
                                            <select name="custom_payment_7_option" id="custom_payment_7_option" data-hide-search="true" class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="">
                                                 <option value="1">Kpay</option>
                                                <option value="2" selected>None</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                    <!--end::Scroll-->
                    <div class="d-flex flex-center mt-10" >
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_add_customer_cancel" class="btn btn-light me-3">Discard</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" class="btn btn-primary" data-kt-location-action="submit">
                            <span class="indicator-label">Submit</span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
            </form>
            <!--end::Form-->
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>

@endsection

@push('scripts')
		<script src={{asset('customJs/businessJs/locationValidation.js')}}></script>
@endpush
