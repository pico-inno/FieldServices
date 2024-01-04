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

                                        <input class="form-check-input" type="checkbox" @if($layout->data_text['customer_name']) checked @endif name="customerName" />
                                    </label>
                                    <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Supplier Name
                                        </span>

                                        <input class="form-check-input" type="checkbox" @if($layout->data_text['supplier_name']) checked @endif name="supplierName" />
                                    </label>
                                    <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Phone Number
                                        </span>
                                        <input class="form-check-input" type="checkbox" @if($layout->data_text['phone']) checked @endif name="phone" />
                                    </label>

                                    <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Address
                                        </span>
                                        <input class="form-check-input" type="checkbox" @if($layout->data_text['address']) checked @endif name="address" />
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
                                        <input class="form-check-input" type="checkbox" @if($layout->data_text['date']) checked @endif name="date" checked />
                                    </label>

                                    <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Invoice Number
                                        </span>

                                        <input class="form-check-input" type="checkbox" @if($layout->data_text['invoice_number']) checked @endif checked name="invoiceNumber" />
                                    </label>
                                </div>

                            </div>

                        </div>

                        <!--end::Option-->

                        <div class="separator separator-dashed mb-8"></div>


                        <div class="row mb-10">
                            <label class="form-label fw-bold fs-6 text-gray-700">Choose Extra Columns</label>
                            <div class="col-md-3">
                                <label class="form-check-label ms-0 fs-6 text-gray-700" for="">Number</label>
                                <input type="checkbox" @if($layout->table_text['number']) checked @endif name="number" class="column">
                            </div>

                            <div class="col-md-3">
                                <label class="form-check-label ms-0 fs-6 text-gray-700" for="">Expense</label>
                                <input type="checkbox" @if($layout->table_text['expense']) checked @endif name="expense" class="column">
                            </div>

                            <div class="col-md-3">
                                <label class="form-check-label ms-0 fs-6 text-gray-700" for="">Discount</label>
                                <input type="checkbox" @if($layout->table_text['discount']) checked @endif name="discount" class="column">
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
