@extends('App.main.navBar')

@section('invoice', 'active')
@section('invoice_show', 'active show')
@section('invoice_active', 'active')

@section('styles')
<style>
    .image-input-placeholder {
        background-image: url('assets/media/svg/files/blank-image.svg');
    }

    [data-bs-theme="dark"] .image-input-placeholder {
        background-image: url('assets/media/svg/files/blank-image-dark.svg');
    }
</style>
@endsection
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Create Invoice Template</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        {{-- <li class="breadcrumb-item text-muted">
            <a href="../../demo7/dist/index.html" class="text-muted">Home</a>
        </li> --}}
        <li class="breadcrumb-item text-muted">Invoice Templates</li>
        <li class="breadcrumb-item text-dark">Create</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="invoice-container">
        <!--begin::Container-->
        <div class="container-xxl" id="invoice">
            <form action="{{ route('invoice.add') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card" data-kt-sticky="true" data-kt-sticky-name="invoice"
                    data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '250px', lg: '300px'}"
                    data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="false"
                    data-kt-sticky-zindex="95">

                    <!--begin::Card body-->
                    <div class="card-body p-10">
                        <!--begin::Input group-->
                        <div class="row mb-5">
                            <div class="col-6 fv-row">
                                <label class="form-label fw-bold fs-6 text-gray-700">Template Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Template-1">
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
                                    <option value="A4">Default (For -A4,A3,A5 and Legal)</option>
                                    <option value="80mm">Simple (For - 80mm , 4in,A4,A3)</option>
                                </select>
                                @error('layout') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
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
                                        <input type="file" name="logo" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="logo" />
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
                                <textarea style="color: red !important;" name="header" id="kt_docs_ckeditor_classic"><h2>{{$businessInfo->name}}</h2><p>{{implode(', ',[$businessInfo->address,$businessInfo->city,$businessInfo->state ,$businessInfo->country])}}.</p></textarea>
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

                                        <input class="form-check-input cursor-pointer" type="checkbox" checked name="customerName" />
                                    </label>
                                    <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Supplier Name
                                        </span>

                                        <input class="form-check-input cursor-pointer" type="checkbox" name="supplierName" />
                                    </label>
                                    <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Phone Number
                                        </span>
                                        <input class="form-check-input cursor-pointer" type="checkbox" name="phone" />
                                    </label>

                                    <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Address
                                        </span>
                                        <input class="form-check-input cursor-pointer" type="checkbox" name="address" />
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <!--begin::Option-->
                                    <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Status
                                        </span>

                                        <input class="form-check-input cursor-pointer" type="checkbox"  name="purchaseStatus" />
                                    </label>

                                    <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Date
                                        </span>
                                        <input class="form-check-input cursor-pointer" type="checkbox" name="date" checked />
                                    </label>

                                    <label
                                        class="form-check col-lg-6 form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            Invoice Number
                                        </span>

                                        <input class="form-check-input" type="checkbox" checked name="invoiceNumber" />
                                    </label>
                                </div>

                            </div>

                        </div>

                        <!--end::Option-->

                        <div class="separator separator-dashed mb-8"></div>


                        <div class="row mb-10">
                            <label class="form-label fw-bold fs-6 text-gray-700">Column Fields</label>
                            <div class="col-md-3 mb-3 fv-row">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" checked type="checkbox" value="true" name="number[is_show]" id="number" class="column" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="No" name="number[label]" value="No">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 fv-row">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" checked type="checkbox" value="true" name="description[is_show]" class="description" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="description[label]" placeholder="Description" value="Description">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 fv-row">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" checked type="checkbox" value="true" name="quantity[is_show]" class="column" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="quantity[label]" placeholder="Quantity" value="Quantity">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 fv-row">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" checked type="checkbox" value="true" name="uom_price[is_show]" class="column" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Unit Price" name="uom_price[label]" value="Unit Price">
                                </div>
                            </div>

                            <div class="col-md-3 mb-3 fv-row">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" checked type="checkbox" value="true" name="discount[is_show]" class="column" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Discount" name="discount[label]" value="Discount">
                                </div>
                            </div>

                            <div class="col-md-3 mb-3 fv-row">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" checked type="checkbox" value="true" name="subtotal[is_show]" class="subtotal" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Subtotal" name="subtotal[label]" value="Subtotal">
                                </div>
                            </div>

                        </div>

                        <div class="separator separator-dashed mb-8"></div>
                        <div class="row">
                            <label class="form-label fw-bold fs-6 text-gray-700">Summary Field</label>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3 offset-md-9">
                                <div class="input-group input-group-sm fv-row">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" checked type="checkbox" value="true"
                                            name="net_sale_amount[is_show]" class="expense" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Net Sale Amount" name="net_sale_amount[label]"
                                        value="Net Sale Amount">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3 offset-md-9 fv-row">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" checked type="checkbox" value="true"
                                            name="extra_discount_amount[is_show]" class="expense" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Extra Discount Amount" name="extra_discount_amount[label]"
                                        value="Extra Discount Amount">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3 offset-md-9 fv-row">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text cursor-pointer">
                                        <input class="w-15px  form-check cursor-pointer" checked type="checkbox" value="true"
                                            name="total_sale_amount[is_show]" class="total_sale_amount" />
                                    </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Total Amount"
                                        name="total_sale_amount[label]" value="Total Amount">
                                </div>
                            </div>
                        </div>

                        <div class="separator separator-dashed mb-8"></div>
                        <div class="row justify-content-between overflow-hidden">
                            <div class="col-md-6">
                                <div class="py-5" data-bs-theme="light">
                                    <label class="form-label fw-bold fs-6 text-gray-700">Footer</label>
                                    <textarea name="footer" id="kt_docs_ckeditor_classic2"></textarea>
                                    @error('footer') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="py-5" data-bs-theme="light">
                                    <label class="form-label fw-bold fs-6 text-gray-700">Note</label>
                                    <textarea name="note" id="kt_docs_ckeditor_classic3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="w-auto">
                                <button type="submit" id="submit" class="mt-5 btn btn-success ">Save</button>
                            </div>
                        </div>

                    </div>
                    <!--end::Container-->
                </div>
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
