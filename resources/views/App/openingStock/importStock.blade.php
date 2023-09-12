@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('inventory_icon', 'active')
@section('inventory_show', 'active show')
@section('opening_stock_here_show','here show')
@section('import_opening_stock_menu_link', 'active ')

{{-- @section('products_icon', 'active')
@section('products_show', 'active show')
@section('import_opening_stock_menu_link', 'active') --}}


@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Import Stock</h1>
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
            <form id="kt_ecommerce_add_category_form" method="POST" action="{{route('importOpeningStock')}}" enctype="multipart/form-data">
                  @csrf
                <div  method="POST" class="form d-flex flex-column flex-lg-row" >

                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-6">
                        <!--begin::General options-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Import Opening Stock</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <div class="row ps-3">
                                <div class="text-danger">
                                    @if ($errors->any())
                                        <div class="text-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!--begin::Card body-->
                            <div class="card-body">
                                <div class="row mb-5 flex-wrap">
                                    <div class="mb-7 mt-3 col-12 col-md-4">
                                        <label class="form-label fs-6 fw-semibold required" for="">
                                            Bussiness Location
                                        </label>
                                        <div class="input-group input-group-sm flex-nowrap">
                                            <select name="business_location_id" class="form-select form-select-sm  fw-bold "
                                                    data-kt-select2="true" data-hide-search="false"
                                                    data-placeholder="Select Location" data-allow-clear="true"
                                                    data-kt-user-table-filter="role" data-hide-search="true" required>
                                                <option></option>
                                            @foreach ($locations as $location)
                                                <option value="{{$location->id}}">{{$location->name}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        @error('business_location_id')
                                            <div class="p-2 text-danger">* {{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-7 mt-3 col-12 col-md-4">
                                        <label class="form-label fs-6 fw-semibold required" for="purchaseDatee">
                                            Opening Stock Date
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                            <input class="form-control form-control-sm" name="opening_date" placeholder="Pick a date"
                                                    data-td-toggle="datetimepicker" id="kt_datepicker_1"
                                                    value="{{date('Y-m-d')}}"/>
                                        </div>
                                        @error('opening_date')
                                            <div class="p-2 text-danger">* {{$message}}</div>
                                        @enderror
                                    </div>
                                    {{-- <div class="mb-7 mt-3 col-12 col-md-4">
                                        <label class="form-label fs-6 fw-semibold" for="">
                                            Status
                                        </label>
                                        <div class="input-group flex-nowrap">
                                            <select name="status" class="form-select  fw-bold  form-control-sm" data-kt-select2="true"
                                                    data-hide-search="false" data-placeholder="Select Location"
                                                    data-allow-clear="true" data-kt-user-table-filter="role"
                                                    data-hide-search="true">
                                                <option></option>
                                                <option value="pending" selected>pending</option>
                                                <option value="received">received</option>
                                                <option value="issued">issued</option>
                                                <option value="confirmed">confirmed</option>
                                            </select>
                                        </div>
                                        @error('status')
                                            <div class="p-2 text-danger">* {{$message}}</div>
                                        @enderror
                                    </div> --}}
                                    <div class="mb-7 mt-3 col-12 col-md-4">
                                        <label class="form-label fs-6">
                                            Note
                                        </label>
                                        <textarea class="form-control " name="note" id="" cols="30" rows="4"></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <div class="row gap-10">
                                    <div class="col-sm-3 row align-items-center mb-13">
                                        <a href="{{route('download-excel')}}" class="btn btn-primary btn-sm ">
                                            <i class="fas fa-download"></i>Download template file
                                        </a>
                                    </div>
                                    <div class="col-sm-3 row align-items-center mb-13">
                                        <a href="{{route('exprotOpeningStockWithProduct')}}" class="btn btn-info btn-sm ">
                                            <i class="fas fa-download"></i>Download template file with preset data
                                        </a>
                                    </div>
                                </div>
                                <div class="row mb-5 align-items-center ">
                                    <!--begin::Input-->
                                    <div class="col-sm-6 ">
                                        <!--begin::Label-->
                                        <label class="form-label  col-12 mb-5 fs-3 d-block" for="formFileSm">
                                            Opening Stock Details to import
                                            <i class="fas fa-info-circle ms-1 fs-7 text-success cursor-help" data-bs-toggle="tooltip" data-bs-html="true" style="cursor:help"
                                            title="This feature is used to import opening stock of already added products. If the products are not added in the system then it is advisable to use import products for adding product details with opening stock."></i>
                                        </label>

                                        <input class="form-control col-3 " id="formFileSm" name="ImportedFile" type="file"  />
                                        <!--end::Label-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <div class="card-footer text-center">
                                 <button type="submit" class="btn btn-success ">Submit</button>
                            </div>
                        </div>
                    </div>
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
                                    <td>Product Name
                                        <span class="text-muted">(Required)</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Variation Values
                                        <span class="text-muted">(Required if product type is variable)</span>
                                    </td>
                                    <td>Values for the variation (Eg: Red|Blue|Green)</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        Expiry Date <span class="text-muted">(Optional)</span>
                                    </td>
                                    <td>
                                        Stock expiry date in <strong>Business date format</strong> <br/>
                                        <strong>dd-mm-yyyy</strong>, Type: <strong>text</strong>, Example: <strong>13-03-2023</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>UOM Name
                                        <span class="text-muted">(Require)</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Quantity
                                        <span class="text-muted">(Require)</span>
                                    </td>
                                    <td></td>
                                </tr>
                                 <tr>
                                    <td>6</td>
                                    <td>Per Item Price
                                        <span class="text-muted">(Require)</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>remark
                                        <span class="text-muted">(optional)</span>
                                    </td>
                                    <td></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
                <!--end::Card header-->
            </div>
            <!--end::Automation-->

                <!--end::Main column-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
<script>
        $("#kt_datepicker_1").flatpickr({
        dateFormat: "d-m-Y",
    });
</script>
@error('ImportedFile')
<script>

    warning('Please Recheck File Tpye!')
</script>
@enderror
@endpush
