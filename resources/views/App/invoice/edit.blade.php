@extends('App.main.navBar')

@section('invoice', 'active')
@section('invoice_show', 'active show')

@section('styles')

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

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="location">
            <form action="{{ route('invoice.update') }}" method="post">
                @csrf
                <div class="card" data-kt-sticky="true" data-kt-sticky-name="invoice"
                    data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '250px', lg: '300px'}"
                    data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="false"
                    data-kt-sticky-zindex="95">
                    <input type="hidden" value="{{ $layout->id }}" name="layoutId">

                    <!--begin::Card body-->
                    <div class="card-body p-10">
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label fw-bold fs-6 text-gray-700">Layout Name</label>
                                    <input type="text" class="form-control" name="layoutName" value="{{ $layout->name }}" placeholder="Layout-1">
                                    @error('layoutName') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <!--begin::Label-->
                                <div class="col-6">
                                    <label class="form-label fw-bold fs-6 text-gray-700">Paper Size</label>
                                    <!--end::Label-->

                                    <!--begin::Select-->
                                    <select name="paperSize" id="paperSize" aria-label="Select a Papersize"
                                        data-status="filter" data-kt-select2="true" data-hide-search="false"
                                        data-allow-clear="true" data-hide-search="true" data-placeholder="Select Papersize"
                                        class="form-select form-select-solid">
                                        <option value="" selected disabled>Choose Paper Size</option>

                                        <option value="A4" @if($layout->paper_size ==="A4") selected @endif><b>A4</option>
                                        <option value="A5" @if($layout->paper_size === "A5") selected @endif><b>A5</option>
                                        <option value="Legal" @if($layout->paper_size === "Legal") selected @endif><b>Legal</option>
                                        <option value="80mm" @if($layout->paper_size === "80mm") selected @endif><b>80mm</option>
                                    </select>
                                    @error('paperSize') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <!--end::Select-->
                        </div>
                        <!--end::Input group-->

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

                        <!--begin::Option-->
                        <div class="mb-10">
                            <div class="row">


                                <div class="col-md-6">
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
                                    {{-- <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Purchase Status
                                        </span>

                                        <input class="form-check-input" type="checkbox" @if($layout->data_text['status']) checked @endif checked name="purchaseStatus" />
                                    </label> --}}

                                    <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Date
                                        </span>
                                        <input class="form-check-input" type="checkbox" @checked($data_text->date)  name="date" checked />
                                    </label>

                                    <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Invoice Number
                                        </span>

                                        <input class="form-check-input" type="checkbox" @checked($data_text->invoice_number)   name="invoiceNumber" />
                                    </label>
                                </div>

                            </div>

                        </div>

                        <!--end::Option-->

                        <div class="separator separator-dashed mb-8"></div>

                        <div class="row mb-10">
                            <label class="form-label fw-bold fs-6 text-gray-700">Column Fields</label>
                            <div class="col-md-3 mb-3">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($table_text->number->is_show) type="checkbox" value="true"
                                            name="number[is_show]" id="number" class="column" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="No" name="number[label]" value="No">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($table_text->description->is_show ?? false) type="checkbox" value="true"
                                            name="description[is_show]" class="description" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="description[label]" placeholder="Description"
                                        value="Description">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($table_text->quantity->is_show ?? false) type="checkbox" value="true"
                                            name="quantity[is_show]" class="column" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="quantity[label]" placeholder="Quantity"
                                        value="Quantity">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($table_text->uom_price->is_show ?? false) type="checkbox" value="true"
                                            name="uom_price[is_show]" class="column" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Unit Price" name="uom_price[label]"
                                        value="Unit Price">
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($table_text->discount->is_show ?? false) type="checkbox" value="true"
                                            name="discount[is_show]" class="column" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Discount" name="discount[label]"
                                        value="Discount">
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($table_text->subtotal->is_show ?? false) type="checkbox" value="true"
                                            name="subtotal[is_show]" class="subtotal" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Subtotal" name="subtotal[label]"
                                        value="Subtotal">
                                </div>
                            </div>

                        </div>
                        <div class="separator separator-dashed mb-8"></div>
                        <div class="row">
                            <label class="form-label fw-bold fs-6 text-gray-700">Summary Field</label>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3 offset-md-9">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($data_text->net_sale_amount->is_show ?? false) type="checkbox" value="true"
                                            name="net_sale_amount[is_show]" class="expense" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Net Sale Amount"
                                        name="net_sale_amount[label]" value="Net Sale Amount">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3 offset-md-9">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($data_text->extra_discount_amount->is_show ?? false) type="checkbox" value="true"
                                            name="extra_discount_amount[is_show]" class="expense" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Extra Discount Amount"
                                        name="extra_discount_amount[label]" value="Extra Discount Amount">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3 offset-md-9">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" @checked($data_text->total_sale_amount->is_show ?? false) type="checkbox" value="true"
                                            name="total_sale_amount[is_show]" class="total_sale_amount" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Total Amount"
                                        name="total_sale_amount[label]" value="Total Amount">
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
                <button type="submit" class="mt-5 btn btn-success">Save</button>
            </form>

        </div>

    </div>
@endsection

@push('scripts')
    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            $('#paperSize').change(function() {
                let value = $('#paperSize').val();
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
