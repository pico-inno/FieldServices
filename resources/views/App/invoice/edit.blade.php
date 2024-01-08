@extends('App.main.navBar')

@section('invoice', 'active')
@section('invoice_show', 'active show')

@section('styles')
@php
    $logo=$data_text->logo ?? null;
    $url=asset('/storage/logo/invoice/'.$logo);
@endphp
@if($logo)
   <style>
    .image-input-placeholder {
        background-image: url({{$url}});
    }
      .h-fit{
        min-height: 0 !important;
        height: fit-content !important;
        }
</style>
@else
<style>
    .image-input-placeholder {
        background-image: url({{asset('assets/media/svg/files/blank-image.svg')}});
    }

    [data-bs-theme="dark"] .image-input-placeholder {
        background-image: url({{asset('assets/media/svg/files/blank-image.svg')}});
    }
    .h-fit{
        min-height: 0 !important;
        height: fit-content !important;
        }
</style>
@endif
@endsection
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Edit Invoice Template</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        {{-- <li class="breadcrumb-item text-muted">
            <a href="../../demo7/dist/index.html" class="text-muted">Home</a>
        </li> --}}
        <li class="breadcrumb-item text-muted">Invoice Templates</li>
        <li class="breadcrumb-item text-dark">Edit</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')

    <div class="content d-flex flex-column flex-column-fluid"  id="invoice-container">
        <!--begin::Container-->
        <div class="container-xxl" id="invoice">
            <form action="{{ route('invoice.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card" data-kt-sticky="true" data-kt-sticky-name="invoice"
                    data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '250px', lg: '300px'}"
                    data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="false"
                    data-kt-sticky-zindex="95">
                    <input type="hidden" value="{{ $layout->id }}" name="layoutId">

                    <!--begin::Card body-->
                    <div class="card-body p-10">
                        <!--begin::Input group-->
                        <div class="mb-5">
                            <div class="row">
                                <div class="col-6 fv-row">
                                    <label class="form-label fw-bold fs-6 text-gray-700">Template Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ $layout->name }}" placeholder="Template-1">
                                    @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <!--begin::Label-->
                                <div class="col-6 fv-row">
                                    <label class="form-label fw-bold fs-6 text-gray-700">Layout</label>
                                    <!--end::Label-->

                                    <!--begin::Select-->
                                    <select name="layout" id="layout" aria-label="Select a layout"
                                        data-status="filter" data-kt-select2="true" data-hide-search="false"
                                        data-allow-clear="true" data-hide-search="true" data-placeholder="Select layout"
                                        class="form-select form-select-solid">
                                        <option value="" selected disabled>Choose Paper Size</option>

                                        <option value="A4" @if($layout->layout ==="A4") selected @endif><b>Default (For -A4,A3,A5 and Legal)</option>
                                        <option value="80mm" @if($layout->layout === "80mm") selected @endif><b>80mm</option>
                                    </select>
                                    @error('layout') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <!--end::Select-->
                        </div>
                        <!--end::Input group-->

                        <div class="row">
                            <div class="col-md-4 mb-5">
                                <label class="form-label d-block">Business Logo</label>
                                <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3 "
                                    data-kt-image-input="true">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper w-100px h-100px"></div>
                                    <!--end::Preview existing avatar-->
                                    <!--begin::Label-->
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change Logo">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <!--begin::Inputs-->
                                        <input type="file" name="logo" accept=".png, .jpg, .jpeg" value="{{$logo}}" />
                                        <input type="hidden" name="logo" value="{{$logo}}" />
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Cancel-->
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <!--end::Cancel-->
                                    <!--begin::Remove-->
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <!--end::Remove-->
                                </div>
                                <div class="text-muted fs-7">Max File Size: 5MB</div>
                                <div class="text-muted fs-7">Aspect ratio should be 1:1</div>
                                @error('avatar')
                                    <div class="text-danger my-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!--begin::Separator-->
                        <div class="separator separator-dashed mb-8"></div>
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <div class="py-5" data-bs-theme="light">
                                <label class="form-label fw-bold fs-6 text-gray-700">Header</label>
                                <textarea style="color: red !important;" name="header" id="kt_docs_ckeditor_classic">{!! $layout->header_text !!}</textarea>
                                @error('header') <span class="text-danger">{{ $message }}</span>@enderror

                            </div>

                        </div>
                        <div class="separator separator-dashed mb-8"></div>

                        <div class="row mb-10">


                            <div class="col-md-6 ">
                                <label
                                    class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                    <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                        Customer Name
                                    </span>

                                    <input class="form-check-input" type="checkbox" @checked($data_text->customer_name) name="customerName" />
                                </label>
                                <label
                                    class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                    <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                        Supplier Name
                                    </span>

                                    <input class="form-check-input" type="checkbox" @checked($data_text->supplier_name) name="supplierName" />
                                </label>
                                <label
                                    class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                    <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                        Phone Number
                                    </span>
                                    <input class="form-check-input" type="checkbox" @checked($data_text->phone) name="phone" />
                                </label>

                                <label
                                    class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                    <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                        Address
                                    </span>
                                    <input class="form-check-input" type="checkbox" @checked($data_text->address)  name="address" />
                                </label>
                            </div>
                            <div class="col-md-6">
                                <!--begin::Option-->
                                <label
                                    class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                    <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                        Status
                                    </span>

                                    <input class="form-check-input" type="checkbox" @if($data_text->purchaseStatus ?? false) checked @endif checked name="purchaseStatus" />
                                </label>

                                <label
                                    class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5 cursor-pointer">
                                    <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                        Date
                                    </span>
                                    <input class="form-check-input" type="checkbox" @checked($data_text->date)  name="date" checked />
                                </label>

                                <label
                                    class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5 cursor-pointer">
                                    <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                        Invoice Number
                                    </span>

                                    <input class="form-check-input" type="checkbox" @checked($data_text->invoice_number)   name="invoiceNumber" />
                                </label>
                            </div>

                        </div>
                        <div class="separator separator-dashed mb-8"></div>

                        <div class="row mb-10">
                            <label class="form-label fw-bold fs-6 text-gray-700">Column Fields</label>
                            <div class="row">
                                <div class="mb-5 fv-row col-md-3">
                                    <label for="" class="form-label"> Font Size (px) </label>
                                    <input type="text" class="form-control form-control-sm input_number" placeholder="No" name="tableFontSize"
                                        value="{{$data_text->tableFontSize ?? 16}}">
                                </div>
                            </div>


                            <div class="col-md-3 mb-3">
                                <div class="card shadow-none border border-1  border-gray-300">
                                    <div class="card-header px-3 py-1 h-fit ">
                                        <label for="" class="form-label card-title fs-7"> Number Column </label>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="form-check mb-4 mt-3 cursor-pointer">
                                            <label class="form-check-label text-gray-800 cursor-pointer user-select-none"  for="number">
                                                Is Show
                                            </label>
                                            <input class="cursor-pointer form-check-input" @checked($table_text->number->is_show) type="checkbox" value="true"
                                                name="number[is_show]" id="number" class="column" />

                                        </div>
                                        <div class="mb-5 fv-row">
                                            <label for="" class="form-label"> Label </label>
                                            <input type="text" class="form-control form-control-sm" placeholder="No" name="number[label]"
                                                value="{{$table_text->number->label}}">
                                        </div>
                                        <div class="">
                                            <label for="" class="form-label"> Column Width (%) </label>
                                            <input type="text" class="form-control form-control-sm input_number" placeholder="auto"
                                                name="number[width]"  value="{{$table_text->number->width}}">
                                            <span class="text-gray-500 inline-block fs-8">Leave Blank To Set auto</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card shadow-none border border-1  border-gray-300">
                                    <div class="card-header px-3 py-1 h-fit ">
                                        <label for="" class="form-label card-title fs-7"> Description Column </label>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="form-check mb-4 mt-3 cursor-pointer">
                                            <label class="form-check-label text-gray-800 cursor-pointer user-select-none" for="description">
                                                Is Show
                                            </label>
                                            <input class="cursor-pointer form-check-input" @checked($table_text->description->is_show) type="checkbox" value="true"
                                                name="description[is_show]" class="description" id="description" />

                                        </div>
                                        <div class="mb-5 fv-row">
                                            <label for="" class="form-label"> Label </label>
                                            <input type="text" class="form-control form-control-sm" name="description[label]"
                                                placeholder="Description" value="{{$table_text->description->label}}">
                                        </div>
                                        <div class="">
                                            <label for="" class="form-label"> Column Width (%) </label>
                                            <input type="text" class="form-control form-control-sm input_number" placeholder="auto"
                                                name="description[width]" value="{{$table_text->description->width}}">
                                            <span class="text-gray-500 inline-block fs-8">Leave Blank To Set auto</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card shadow-none border border-1  border-gray-300">
                                    <div class="card-header px-3 py-1 h-fit ">
                                        <label for="" class="form-label card-title fs-7"> Quantity Column </label>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="form-check mb-4 mt-3 cursor-pointer">
                                            <label class="form-check-label text-gray-800 cursor-pointer user-select-none" for="quantity">
                                                Is Show
                                            </label>
                                            <input class="cursor-pointer form-check-input" @checked($table_text->quantity->is_show) type="checkbox" value="true"
                                                name="quantity[is_show]" class="quantity" id="quantity" />

                                        </div>
                                        <div class="mb-5 fv-row">
                                            <label for="" class="form-label"> Label </label>
                                            <input type="text" class="form-control form-control-sm" name="quantity[label]" placeholder="Quantity"
                                                value="{{$table_text->quantity->label}}">
                                        </div>
                                        <div class="">
                                            <label for="" class="form-label"> Column Width (%) </label>
                                            <input type="text" class="form-control form-control-sm input_number" placeholder="auto"
                                                name="quantity[width]" value="{{$table_text->quantity->width}}">
                                            <span class="text-gray-500 inline-block fs-8">Leave Blank To Set auto</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card shadow-none border border-1  border-gray-300 ">
                                    <div class="card-header px-3 py-1 h-fit ">
                                        <label for="" class="form-label card-title fs-7"> UoM Price Column </label>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="form-check mb-4 mt-3 cursor-pointer">
                                            <label class="form-check-label text-gray-800 cursor-pointer user-select-none" for="uom_price">
                                                Is Show
                                            </label>
                                            <input class="cursor-pointer form-check-input" @checked($table_text->uom_price->is_show) type="checkbox" value="true" id="uom_price"
                                                name="uom_price[is_show]" class="column" />

                                        </div>
                                        <div class="mb-5 fv-row">
                                            <label for="" class="form-label"> Label </label>
                                            <input type="text" class="form-control form-control-sm" name="uom_price[label]" placeholder="Uom Price"
                                                value="{{$table_text->uom_price->label}}">
                                        </div>
                                        <div class="">
                                            <label for="" class="form-label"> Column Width (%) </label>
                                            <input type="text" class="form-control form-control-sm input_number" placeholder="auto"
                                                name="uom_price[width]" value="{{$table_text->uom_price->width}}">
                                            <span class="text-gray-500 inline-block fs-8">Leave Blank To Set auto</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card shadow-none border border-1  border-gray-300 ">
                                    <div class="card-header px-3 py-1 h-fit ">
                                        <label for="" class="form-label card-title fs-7">Discount Column </label>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="form-check mb-4 mt-3 cursor-pointer">
                                            <label class="form-check-label text-gray-800 cursor-pointer user-select-none" for="discount">
                                                Is Show
                                            </label>
                                            <input class="cursor-pointer form-check-input" @checked($table_text->discount->is_show ?? false) type="checkbox" value="true" id="discount"
                                                name="discount[is_show]" class="column" />

                                        </div>
                                        <div class="mb-5 fv-row">
                                            <label for="" class="form-label"> Label </label>
                                            <input type="text" class="form-control form-control-sm" name="discount[label]" placeholder="Discount"
                                                value="{{$table_text->discount->label}}">
                                        </div>
                                        <div class="">
                                            <label for="" class="form-label"> Column Width (%) </label>
                                            <input type="text" class="form-control form-control-sm input_number" placeholder="auto"
                                                name="discount[width]" value="{{$table_text->discount->width}}">
                                            <span class="text-gray-500 inline-block fs-8">Leave Blank To Set auto</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card shadow-none border border-1  border-gray-300">
                                    <div class="card-header px-3 py-1 h-fit ">
                                        <label for="" class="form-label card-title fs-7">Subtotal Column </label>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="form-check mb-4 mt-3 cursor-pointer">
                                            <label class="form-check-label text-gray-800 cursor-pointer user-select-none" for="subtotal">
                                                Is Show
                                            </label>
                                            <input class="cursor-pointer form-check-input" @checked($table_text->subtotal->is_show ?? false) type="checkbox" value="true" id="subtotal"
                                                name="subtotal[is_show]" class="column" />

                                        </div>
                                        <div class="mb-5 fv-row">
                                            <label for="" class="form-label"> Label </label>
                                            <input type="text" class="form-control form-control-sm" name="subtotal[label]" placeholder="Subtotal"
                                                value="{{$table_text->subtotal->label}}">
                                        </div>
                                        <div class="">
                                            <label for="" class="form-label"> Column Width (%) </label>
                                            <input type="text" class="form-control form-control-sm input_number" placeholder="auto"
                                                name="subtotal[width]" value="{{$table_text->subtotal->width}}">
                                            <span class="text-gray-500 inline-block fs-8">Leave Blank To Set auto</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-3 mb-3">
                                <div class="input-group input-group-sm fv-row">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($table_text->number->is_show) type="checkbox" value="true"
                                            name="number[is_show]" id="number" class="column" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="No" name="number[label]" value="{{$table_text->number->label}}">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="input-group input-group-sm fv-row">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($table_text->description->is_show ?? false) type="checkbox" value="true"
                                            name="description[is_show]" class="description" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="description[label]" placeholder="Description"
                                        value="{{$table_text->description->label}}">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="input-group input-group-sm fv-row">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($table_text->quantity->is_show ?? false) type="checkbox" value="true"
                                            name="quantity[is_show]" class="column" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="quantity[label]" placeholder="Quantity"
                                        value="{{$table_text->quantity->label}}">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="input-group input-group-sm fv-row">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($table_text->uom_price->is_show ?? false) type="checkbox" value="true"
                                            name="uom_price[is_show]" class="column" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Unit Price" name="uom_price[label]"
                                        value="{{$table_text->uom_price->label}}">
                                </div>
                            </div> --}}

                            {{-- <div class="col-md-3 mb-3">
                                <div class="input-group input-group-sm fv-row">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($table_text->discount->is_show ?? false) type="checkbox" value="true"
                                            name="discount[is_show]" class="column" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Discount" name="discount[label]"
                                        value="{{$table_text->discount->label}}">
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="input-group input-group-sm fv-row">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($table_text->subtotal->is_show ?? false) type="checkbox" value="true"
                                            name="subtotal[is_show]" class="subtotal" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Subtotal" name="subtotal[label]"
                                        value="{{$table_text->subtotal->label}}">
                                </div>
                            </div> --}}

                        </div>
                        <div class="separator separator-dashed mb-8"></div>
                        <div class="row">
                            <label class="form-label fw-bold fs-6 text-gray-700">Summary Field</label>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3 offset-md-9">
                                <div class="input-group input-group-sm fv-row">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($data_text->net_sale_amount->is_show ?? false) type="checkbox" value="true"
                                            name="net_sale_amount[is_show]" class="expense" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Net Sale Amount"
                                        name="net_sale_amount[label]" value="{{$data_text->net_sale_amount->label}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3 offset-md-9">
                                <div class="input-group input-group-sm fv-row">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($data_text->extra_discount_amount->is_show ?? false) type="checkbox" value="true"
                                            name="extra_discount_amount[is_show]" class="expense" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Extra Discount Amount"
                                        name="extra_discount_amount[label]" value="{{$data_text->extra_discount_amount->label}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3 offset-md-9">
                                <div class="input-group input-group-sm fv-row">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($data_text->total_sale_amount->is_show ?? false) type="checkbox" value="true"
                                            name="total_sale_amount[is_show]" class="total_sale_amount" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Total Amount"
                                        name="total_sale_amount[label]" value="{{$data_text->total_sale_amount->label}}">
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed mb-8"></div>



                        <div class="row">
                            <div class="col-6">
                                <div class="py-5" data-bs-theme="light">
                                    <label class="form-label fw-bold fs-6 text-gray-700">Footer</label>
                                    <textarea name="footer" id="kt_docs_ckeditor_classic2">{!! $layout->footer_text !!}</textarea>
                                    @error('footer') <span class="text-danger">{{ $message }}</span>@enderror

                                </div>
                            </div>
                            <div class="col-5">
                                <div class="py-5" data-bs-theme="light">
                                    <label class="form-label fw-bold fs-6 text-gray-700">Note</label>
                                    <textarea name="note" id="kt_docs_ckeditor_classic3">{!! $layout->note !!}</textarea>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!--end::Container-->
                </div>
                <button type="submit" class="mt-5 btn btn-success" id="submit">Save</button>
            </form>

        </div>

    </div>
@endsection

@push('scripts')
    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <script src="{{asset('customJs/invoice/validator.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#layout').change(function() {
                let value = $('#layout').val();
                if (value === "80mm") {
                    $('.column').prop('disabled', true);
                }
            })

        })
        ClassicEditor
            .create(document.querySelector('#kt_docs_ckeditor_classic'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#kt_docs_ckeditor_classic2'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#kt_docs_ckeditor_classic3'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
