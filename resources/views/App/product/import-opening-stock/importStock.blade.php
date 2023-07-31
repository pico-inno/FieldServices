@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('import_opening_stock_menu_link', 'active')


@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">Import Stock</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Product</li>
        <li class="breadcrumb-item text-dark">Import Opening Stock</li>
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
                                <h2>Import Opening Stock</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Input group-->
                            <div class="row mb-5">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="">
                                        <!--begin::Label-->
                                        <label class="form-label" for="formFileSm">
                                            File to import
                                            <i class="fas fa-info-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                            title="This feature is used to import opening stock of already added products. If the products are not added in the system then it is advisable to use import products for adding product details with opening stock."></i>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input class="form-control form-control-sm" id="formFileSm" type="file" />
                                        <!--end::Input-->
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-success btn-sm">Submit</button>
                            </div>
                            <!--end::Input group-->
                            <div class="mt-5">
                                <a href="https://demo.picosbs.com/files/import_opening_stock_csv_template.xls" download class="btn btn-light-primary btn-sm">
                                    <i class="fas fa-download"></i>Download template file
                                </a>
                            </div>
                        </div>
                        <!--end::Card header-->
                </div>
            </form>
                    <!--end::General options-->
                    <!--begin::Automation-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Instructions</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <strong>Follow the instructions carefully before importing the file.</strong>
                            <p class="mt-4">The columns of the file should be in the following order.</p>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800">
                                            <th>Column Number</th>
                                            <th>Column Name</th>
                                            <th>Instruction</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>SKU
                                                <span class="text-muted">(Required)</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                Location <span class="text-muted">(Optional)</span> <br/>
                                                <span class="text-muted">If blank first business location will be used</span>
                                            </td>
                                            <td>Name of the business location</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                Quantity <span class="text-muted">(Required)</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                Unit Cost (Before Tax) <span class="text-muted">(Required)</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>
                                                Lot Number  <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>
                                                Expiry Date <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Stock expiry date in <strong>Business date format</strong> <br/>
                                                <strong>dd-mm-yyyy</strong>, Type: <strong>text</strong>, Example: <strong>13-03-2023</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::Automation-->
                </div>
                <!--end::Main column-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')

@endpush
