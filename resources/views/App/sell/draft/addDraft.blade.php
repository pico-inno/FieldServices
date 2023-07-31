@extends('App.main.navBar')

@section('sell_icon', 'active')
@section('sell_show', 'active show')
@section('add_drafts_active_show', 'active ')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Add Drafts</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Sale</li>
        <li class="breadcrumb-item text-muted">add</li>
        <li class="breadcrumb-item text-dark">Draft </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/bussingessSetting.css")}}>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
    <style>
    </style>
@endsection



@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="kt_content_container">
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" >
            <div class="col-12 my-5 input-group flex-nowrap">
                <div class="input-group-text"><i class="fa-solid fa-location-dot"></i></div>
                <select name="" id="" class="form-select rounded-0" data-kt-select2="true">
                    <option value="">Demo Bussiness</option>
                    <option value="">YanKin</option>
                    <option value="">Mandalay</option>
                </select>
                <button type="button" class="input-group-text "  data-bs-toggle="tooltip" data-bs-custom-class="tooltip" data-bs-placement="top" data-bs-html="true" title="<span class='text-primary-emphasis'>Bussiness location from where you went to sell </span>">
                    <i class="fa-solid fa-circle-info text-primary"></i>
                </button>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row mb-5 flex-wrap">
                        <!--begin::Input group-->
                        <div class="mb-10 mt-3 col-12 col-md-5">
                            <label class="form-label fs-6 fw-semibold required">Default Selling Price:</label>
                            <div class="input-group flex-nowrap">
                                <div class="input-group-text">
                                   <i class="fa-solid fa-money-bill-wave"></i>
                                </div>
                                <select class="form-select  fw-bold rounded-0" data-kt-select2="true" data-hide-search="false" data-placeholder="Select supplier" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                    <option></option>
                                    <option value="Administrator">Mg Mg</option>
                                    <option value="Analyst">Kyaw Kyaw</option>
                                    <option value="Developer">Aung Aung</option>
                                </select>
                                <button type="button" class="input-group-text" data-bs-toggle="tooltip" data-bs-custom-class="tooltip" data-bs-placement="top" data-bs-html="true" title="<span class='text-primary-emphasis'>Selling Price Group in which you want to sell </span>">
                                    <i class="fa-solid fa-circle-info text-primary"></i>
                                </button>
                            </div>
                        </div>
                        <!--end::Input group-->
                        <div class="mb-10 mt-3 col-12 col-md-5">
                            <label class="form-label fs-6 fw-semibold required">select types of service:</label>
                            <div class="input-group flex-nowrap">
                                <div class="input-group-text">
                                   <i class="fa-solid fa-up-right-from-square text-primary"></i>
                                </div>
                                <select class="form-select  fw-bold rounded-0" data-hide-search="true" data-kt-select2="true" data-hide-search="false" data-placeholder="Select supplier" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                    <option></option>
                                    <option value="Administrator">Select types of service</option>
                                </select>
                                <button type="button" class="input-group-text" data-bs-toggle="tooltip" data-bs-custom-class="tooltip" data-bs-placement="top" data-bs-html="true" title="<span class='text-primary-emphasis'>Type of service means services like dine-in, parcel, home delivery, third party delivery etc.</span></span>">
                                    <i class="fa-solid fa-circle-info text-primary"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-10 mt-3 col-12 col-md-2 d-flex align-items-center">
                           <div class="form-check form-check-sm pt-5">
                                <input class="form-check-input" type="checkbox" value="" id="subscribe"  />
                                <label class="form-check-label" for="subscribe">
                                    Subscribe
                                </label>
                            </div>
                        </div>
                            <!--begin::Input group-->
                        <div class="mb-10 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required">Customer:</label>
                            <div class="input-group flex-nowrap">
                                <div class="input-group-text">
                                    <i class="fa-solid fa-user text-muted"></i>
                                </div>
                                <select class="form-select  fw-bold rounded-0" data-kt-select2="true" data-hide-search="false" data-placeholder="Select customer name" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                    <option></option>
                                    <option value="Administrator">Walk-In Customer</option>
                                    <option value="Analyst">Kyaw Kyaw</option>
                                    <option value="Developer">Aung Aung</option>
                                </select>
                                <button class="input-group-text add_supplier_modal"  data-bs-toggle="modal" type="button" data-bs-target="#add_supplier_modal" data-href="{{ url('purchase/add/supplier')}}">
                                    <i class="fa-solid fa-circle-plus fs-3 text-primary"></i>
                                </button>

                            </div>
                        </div>
                        <!--end::Input group-->
                          <div class="mb-10 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Pay term:
                            </label>
                            <div class="input-group flex-nowrap">
                                <div class="input-text">
                                    <input type="text" class="form-control rounded-end-0" placeholder="Pay term:">
                                </div>
                                <select class="form-select  fw-bold rounded-start-0 border-gray-500" data-kt-select2="true" data-hide-search="false" data-placeholder="Select Location" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                    <option>Please Select</option>
                                    <option value="Administrator">Months</option>
                                    <option value="Analyst">Days</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-10 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="saleDate">
                                Sale Date:
                            </label>
                            <div class="input-group">
                                <span class="input-group-text " data-td-target="date_picker" data-td-toggle="datetimepicker">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input class="form-control" name="start_date" placeholder="Pick a date"  id="kt_datepicker_1" value="{{date('d-m-Y')}}" />
                            </div>
                        </div>
                        <div class="mb-10 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold text-gray-600" for="orderDate">
                                Billing Address:
                            </label>
                            <div class="">
                                ၅၅လမ်း၊ ၁၃၂ လမ်း နှင့် ၁၃၃လမ်းကြား၊ ပြည်ကြီးတံခွန်မြို့နယ်၊ မန္တလေးမြို့။
                            </div>

                            <label class="form-label fs-6 fw-semibold mt-8 text-gray-600" for="orderDate">
                                Shipping Address:
                            </label>
                            <div class="">
                                ၅၅လမ်း၊ ၁၃၂ လမ်း နှင့် ၁၃၃လမ်းကြား၊ ပြည်ကြီးတံခွန်မြို့နယ်၊ မန္တလေးမြို့။
                            </div>
                        </div>
                        <div class="mb-10 mt-3 col-12 col-md-8 row">
                            <div class="mb-10 col-12 mt-3 col-md-6">
                                 <label class="form-label fs-6 fw-semibold required" for="">
                                    Status
                                </label>
                                <div class="input-group flex-nowrap">
                                    <select class="form-select  fw-bold " data-kt-select2="true" data-hide-search="false" data-placeholder="Select Location" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                        <option></option>
                                        <option value="Administrator">Mandalay</option>
                                        <option value="Analyst">Nay Pyi Taw</option>
                                        <option value="Developer">Yangon</option>
                                    </select>
                                </div>
                            </div>
                             <div class="mb-10 col-12 mt-3 col-md-6">
                                 <label class="form-label fs-6 fw-semibold required" for="">
                                    Invoice Scheme
                                </label>
                                <div class="input-group flex-nowrap">
                                    <select class="form-select  fw-bold " data-kt-select2="true" data-hide-search="false" data-placeholder="Select Location" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                        <option></option>
                                        <option value="Administrator">Mandalay</option>
                                        <option value="Analyst">Nay Pyi Taw</option>
                                        <option value="Developer">Yangon</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-10 mt-3 col-12 col-md-6">
                                <label class="form-label fs-6 fw-semibold required">Invoice No.:</label>
                                <input type="text" class="form-control">
                                <div class="text-muted mt-3">Keep blank to auto generate</div>
                            </div>
                            <div class="mb-10 mt-3 col-12 col-md-6 browseLogo">
                                <label class="fs-6 fw-semibold form-label " for="update_logo">
                                    <span class="required">Attach Document:</span>
                                </label>
                                <div class="input-group browseLogo">
                                    <input type="file" class="form-control" id="update_logo" name="update_logo">
                                    <button type="button" class="btn btn-sm btn-danger d-none" id="removeFileBtn"><i class="fa-solid fa-trash"></i></button>
                                    <label class="input-group-text btn btn-primary rounded-end" for="update_logo">
                                        Browse
                                        <i class="fa-regular fa-folder-open"></i>
                                    </label>
                                </div>
                                <p class="text-gray-600 mt-3 d-block">
                                    Max File size: 5MB <br>
                                    Allowed File: .pdf, .csv, .zip, .doc, .docx, .jpeg, .jpg, .png
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mt-5 col-12 mb-10 ">
                                <div class="input-group flex-nowrap">
                                    <div class="input-group-text"><i class="fa-solid fa-table"></i></div>
                                    <select name="" id="" class="form-select rounded-start-0" data-kt-select2="true" data-placeholder="Select Table" data-hide-search="true">
                                        <option value="101">101</option>
                                        <option value="102">102</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5 mt-5 col-12 mb-10 ">
                                <div class="input-group flex-nowrap">
                                    <div class="input-group-text"><i class="fa-solid fa-user-tie"></i></div>
                                    <select name="" id="" class="form-select rounded-start-0" data-kt-select2="true" data-placeholder="Select Service Staff" data-hide-search="true">
                                        <option value="101">Select Service Staff</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center mb-8">
                        <div class="col-12 col-md-9">
                            <div class="input-group quick-search-form p-0">
                                <div class="input-group-text">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <input type="text" class="form-control rounded-start-0" id="searchInput" placeholder="Search...">
                                <div class="quick-search-results overflow-scroll  p-3 position-absolute d-none card w-100 mt-18  card z-3 autocomplete shadow" id="autocomplete" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 btn-primary btn add_new_product_modal"   data-bs-toggle="modal" type="button" data-bs-target="#add_new_product_modal" data-href="{{ url('purchase/add/supplier')}}">
                            <i class="fa-solid fa-plus me-2 text-white"></i> Add new product
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-row-dashed fs-6 gy-5 mt-10" id="purchase_table">
                            <!--begin::Table head-->
                            <thead class="">
                                <!--begin::Table row-->
                                <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0 ">
                                    <th class="min-w-200px">Product</th>
                                    <th class="min-w-200px">Quantity</th>
                                    <th class="min-w-150px">Unit Price</th>
                                    <th class="min-w-150px">Discount</th>
                                    <th class="min-w-150px">Subtotal</th>
                                    <th class="" ><i class="fa-solid fa-trash text-primary" type="button"></i></th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-600">
                                <tr class="dataTables_empty text-center">
                                    <td colspan="8 " >There is no data to show</td>
                                </tr>
                            </tbody>
                                {{-- <tr data-id='1'>

                                    <!--end::Action=-->
                                    <!--begin::Name=-->
                                    <td>
                                        <div class="">
                                            <span>Demo Product</span>
                                            <span>Lux(200)</span>
                                            <div class="input">
                                                <select name="" id="" class="form-control" data-kt-select2="true">
                                                    <option value="d">Lot</option>
                                                </select>
                                                <textarea name="" id="" cols="10" rows="5" class="form-control mt-5"></textarea>
                                                <span class="text-muted">add product IMEI, Serial number or other informations here.</span>
                                            </div>
                                        </div>
                                    </td>
                                    <!--end::Name=-->
                                    <!--begin::Email=-->
                                    <td>
                                        <!--begin::Dialer-->
                                        <div class="input-group "
                                        data-kt-dialer="true"
                                        data-kt-dialer-min="1000"
                                        data-kt-dialer-max="50000"
                                        data-kt-dialer-step="1000"
                                        data-kt-dialer-prefix="$">

                                        <!--begin::Decrease control-->
                                        <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" data-kt-dialer-control="decrease">
                                            <i class="fa-solid fa-minus fs-2"></i>
                                        </button>
                                        <!--end::Decrease control-->

                                        <!--begin::Input control-->
                                        <input type="text" class="form-control" readonly placeholder="Amount" value="$10000" data-kt-dialer-control="input"/>
                                        <!--end::Input control-->

                                        <!--begin::Increase control-->
                                        <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" data-kt-dialer-control="increase">
                                            <i class="fa-solid fa-plus fs-2"></i>
                                        </button>
                                        <!--end::Increase control-->
                                        </div>
                                        <!--end::Dialer-->
                                        <select name="" id="" class="form-select" data-kt-select2="true" data-hide-search="true">
                                            <option value="">box</option>
                                            <option value="">Pieces</option>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text" class="form-control sum" value="100">
                                        <span class="text-muted mt-2">Previous unit price: Ks 5,200	</span>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control mb-3" value="9">
                                        <select name="" id="" class="form-select" data-kt-select2="true" data-hide-search="true">
                                            <option value="">fixed</option>
                                            <option value="">Percentage</option>
                                        </select>
                                        <span class="text-muted mt-3">
                                            Previous discount: Ks 0
                                        </span>
                                    </td>
                                    <td>
                                        Ks 10,40
                                    </td>
                                    <th><i class="fa-solid fa-trash text-danger deleteRow " ></i></th>
                                </tr> --}}
                            <!--end::Table body-->
                        </table>
                    </div>
                    <div class="separator my-5"></div>
                    <div class="col-4 float-end mt-3">
                        <table class="col-12 ">
                        <tbody>
                            <tr>
                                <th >Items: <span class="fw-medium fs-5">0</span></th>
                                <th class=""> Total: <span class="fw-medium fs-5">0</span></th>
                            </tr>
                        </tbody>
                    </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Discount Type
                            </label>
                            <select name="" id="" class="form-select" data-control="select2">
                                <option value="">None</option>
                                <option value="" selected>Percentage</option>
                                <option value="">Fixed</option>
                            </select>
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Discount Amount
                            </label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mb-7  col-12 col-md-4 d-flex justify-content-center align-items-center">
                            <span class=" fs-6 fw-semibold " for="">
                                	Discount:(-) Ks 1
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Purchase Tax:
                            </label>
                            <select name="" id="" class="form-select" data-control="select2">
                                <option value="">None</option>
                            </select>
                        </div>
                        <div class="mb-7  col-12 col-md-4 d-flex justify-content-center align-items-center offset-lg-4 float-end">
                            <span class=" fs-6 fw-semibold " for="">
                                	Purchase Tax:(+) Ks 0
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                        <label class="form-label fs-6 fw-semibold required" for="">
                            Sell note
                        </label>
                        <textarea name="" id="" cols="10" rows="5" class="form-control" placeholder="Write Something"></textarea>
                    </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-10 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Shipping Details
                            </label>
                            <textarea name="" id="" cols="3" rows="0" class="form-control" placeholder="shipping details"></textarea>
                        </div>
                        <div class="mb-10 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Shipping Address
                            </label>
                            <textarea name="" id="" cols="3" rows="0" class="form-control" placeholder="shipping address"></textarea>
                        </div>
                        <div class="mb-10 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Shipping Charges
                            </label>
                            <div class="input-group flex-nowrap">
                                <div class="input-group-text">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div class="input-group">
                                    <input type="text" class="form-control rounded-start-0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Shipping Status
                            </label>
                            <select name="" id="" class="form-select" data-kt-select2="true" data-hide-search="true">
                                <option value="">Please Select</option>
                                <option value="">Ordered</option>
                                <option value="">Packed</option>
                                <option value="">Shipped</option>
                                <option value="">Delivered</option>
                                <option value="">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Delivered To:
                            </label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4 browseLogo">
                            <label class="fs-6 fw-semibold form-label " for="update_logo">
                                <span class="required">Shipping Documents:</span>
                            </label>
                            <div class="input-group browseLogo">
                                <input type="file" class="form-control" id="update_logo" name="update_logo">
                                <button type="button" class="btn btn-sm btn-danger d-none" id="removeFileBtn"><i class="fa-solid fa-trash"></i></button>
                                <label class="input-group-text btn btn-primary rounded-end" for="update_logo">
                                    Browse
                                    <i class="fa-regular fa-folder-open"></i>
                                </label>
                            </div>
                            <p class="text-gray-600 mt-3 d-block">
                                Max File size: 5MB <br>
                                Allowed File: .pdf, .csv, .zip, .doc, .docx, .jpeg, .jpg, .png
                            </p>
                        </div>
                    </div>
                    <div class="row mb-5" >
                        <div class="col-12 justify-content-center d-flex mt-3 mb-5">
                            <div class="col-6 text-center float-center ">
                                <button class="btn btn-primary  collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2" aria-expanded="false" aria-controls="kt_accordion_1_body_2">
                                    <i class="fa-solid fa-plus me-2"></i> Add additional expense
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row accordion-collapse collapse" id="kt_accordion_1_body_2" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_1">
                        <div class="col-4 offset-3">
                            <label class="fs-6 fw-semibold form-label " for="update_logo">
                                <span class="required">Additional expense name</span>
                            </label>
                            <input type="text" class="form-control mb-3">
                            <input type="text" class="form-control mb-3">
                            <input type="text" class="form-control  mb-3">
                            <input type="text" class="form-control mb-3">
                        </div>
                        <div class="col-4">
                            <label class="fs-6 fw-semibold form-label " for="update_logo">
                                <span class="required">Amount</span>
                            </label>
                            <input type="text" class="form-control mb-3 ">
                            <input type="text" class="form-control mb-3">
                            <input type="text" class="form-control  mb-3">
                            <input type="text" class="form-control mb-3">
                        </div>
                    </div>
                     <p class="mt-10 d-block  fs-5 text-end">
                        Total Payable: 5,100
                    </p>
                </div>
            </div>
        </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Save</button>
                 <button type="submit" class="btn btn-success">Save & Print</button>
            </div>
    </div>
    <!--end::Container-->
</div>

@include('App.purchase.contactAdd')
@include('App.purchase.newProductAdd')
@include('App.sell.sale.subscribeModel')
@endsection

@push('scripts')
<script src={{asset('customJs/Purchases/contactAdd.js')}}></script>

    <script>

        $('#subscribe').change(function() {
            // If the checkbox is checked, show the modal box
            if ($(this).is(':checked')) {
                $('#subscribe_models').modal('show');
            } else {
                // If the checkbox is unchecked, hide the modal box
                $('#subscribe_models').modal('hide');
            }
            });

        var quill = new Quill('#kt_docs_quill_basic', {
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            },
            placeholder: 'Type your text here...',
            theme: 'snow' // or 'bubble'
        });
    </script>
<script>
    $("#kt_datepicker_1").flatpickr({
        dateFormat: "d-m-Y",
    });
    $("#kt_datepicker_2").flatpickr({
        dateFormat: "d-m-Y",
    });
    $("#kt_datepicker_3").flatpickr({
        dateFormat: "d-m-Y",
    });
    // $("#kt_datepicker_2").flatpickr({
    //     dateFormat: "d-m-Y",
    // });
    // $("#kt_datepicker_3").flatpickr({
    //     dateFormat: "d-m-Y",
    // });
    // Init select2
    // $('[data-kt-repeater="select2"]').select2();

    // // Init flatpickr
    // $('[data-kt-repeater="datepicker"]').flatpickr();


var inputElm = document.querySelector('#kt_tagify_users');

const usersList = [
    { value: 1, name: 'Emma Smith', avatar: 'avatars/300-6.jpg', email: 'e.smith@kpmg.com.au' },
    { value: 2, name: 'Max Smith', avatar: 'avatars/300-1.jpg', email: 'max@kt.com' },
    { value: 3, name: 'Sean Bean', avatar: 'avatars/300-5.jpg', email: 'sean@dellito.com' },
    { value: 4, name: 'Brian Cox', avatar: 'avatars/300-25.jpg', email: 'brian@exchange.com' },
    { value: 5, name: 'Francis Mitcham', avatar: 'avatars/300-9.jpg', email: 'f.mitcham@kpmg.com.au' },
    { value: 6, name: 'Dan Wilson', avatar: 'avatars/300-23.jpg', email: 'dam@consilting.com' },
    { value: 7, name: 'Ana Crown', avatar: 'avatars/300-12.jpg', email: 'ana.cf@limtel.com' },
    { value: 8, name: 'John Miller', avatar: 'avatars/300-13.jpg', email: 'miller@mapple.com' }
];

            function tagTemplate(tagData) {
                return `
                    <tag title="${(tagData.title || tagData.email)}"
                            contenteditable='false'
                            spellcheck='false'
                            tabIndex="-1"
                            class="${this.settings.classNames.tag} ${tagData.class ? tagData.class : ""}"
                            ${this.getAttributes(tagData)}>
                        <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                        <div class="d-flex align-items-center">
                            <span class='tagify__tag-text'>${tagData.name}</span>
                        </div>
                    </tag>
                `
            }

        function suggestionItemTemplate(tagData) {
            return `
                <div ${this.getAttributes(tagData)}
                    class='tagify__dropdown__item d-flex align-items-center ${tagData.class ? tagData.class : ""}'
                    tabindex="0"
                    role="option">
                    <div class="d-flex flex-column">
                        <strong>${tagData.name}</strong>
                    </div>
                </div>
            `
        }

    // initialize Tagify on the above input node reference
    var tagify = new Tagify(inputElm, {
        tagTextProp: 'name', // very important since a custom template is used with this property as text. allows typing a "value" or a "name" to match input with whitelist
        enforceWhitelist: true,
        skipInvalid: true, // do not remporarily add invalid tags
        dropdown: {
            closeOnSelect: false,
            enabled: 0,
            classname: 'users-list',
            searchKeys: ['name', 'email']  // very important to set by which keys to search for suggesttions when typing
        },
        templates: {
            tag: tagTemplate,
            dropdownItem: suggestionItemTemplate
        },
        whitelist: usersList
    })

    tagify.on('dropdown:show dropdown:updated', onDropdownShow)
    tagify.on('dropdown:select', onSelectSuggestion)

    var addAllSuggestionsElm;

    function onDropdownShow(e) {
        var dropdownContentElm = e.detail.tagify.DOM.dropdown.content;

        if (tagify.suggestedListItems.length > 1) {
            addAllSuggestionsElm = getAddAllSuggestionsElm();

            // insert "addAllSuggestionsElm" as the first element in the suggestions list
            dropdownContentElm.insertBefore(addAllSuggestionsElm, dropdownContentElm.firstChild)
        }
    }

    function onSelectSuggestion(e) {
        if (e.detail.elm == addAllSuggestionsElm)
            tagify.dropdown.selectAll.call(tagify);
    }

    // create a "add all" custom suggestion element every time the dropdown changes
    function getAddAllSuggestionsElm() {
        // suggestions items should be based on "dropdownItem" template
        return tagify.parseTemplate('dropdownItem', [{
            class: "addAll",
            name: "Add all",
            email: tagify.settings.whitelist.reduce(function (remainingSuggestions, item) {
                return tagify.isTagDuplicate(item.value) ? remainingSuggestions : remainingSuggestions + 1
            }, 0) + " Members"
        }]
        )
    }

</script>
<script src={{asset('customJs/customFileInput.js')}}></script>
<script src="{{asset('customJs/sell/addSaleOrder.js')}}"></script>
{{-- <script src="{{asset('customJs/Purchases/purchaseOrderTable.js')}}"></script> --}}
<script>

</script>
@endpush


