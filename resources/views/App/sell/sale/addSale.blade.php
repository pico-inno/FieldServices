@extends('App.main.navBar')

@section('sell_icon', 'active')
@section('sell_show', 'active show')
@section('add_sales_active_show', 'active ')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Add Sale</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Sale</li>
        {{-- <li class="breadcrumb-item text-muted">add</li> --}}
        <li class="breadcrumb-item text-dark">add </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/businessSetting.css")}}>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
    <style>
        .data-table-body tr td{
            padding: 3px;
        }
        /* label{
            font-size: 50px !important ;
        } */
    </style>
@endsection



@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xl" id="sale_container">
        <form action={{route('crate_sale')}} method="POST" id="sale_form">
            @csrf
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-5 mb-5 " >
                <div class="col-12 my-2 fv-row" >
                    <div class="input-group col-12">
                        <div class="input-group-text"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="overflow-hidden  flex-grow-1">
                            <select name="business_location_id" id="business_location_id" class="form-select rounded-0" data-kt-select2="true"  data-placeholder="Select locations">
                                @foreach ($locations as $l)
                                    <option value="{{$l->id}}">{{businessLocationName($l)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="input-group-text " data-bs-toggle="tooltip" data-bs-custom-class="tooltip"
                            data-bs-placement="top" data-bs-html="true"
                            title="<span class='text-primary-emphasis'>Business location from where you went to sell </span>">
                            <i class="fa-solid fa-circle-info text-primary"></i>
                        </button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body  px-5">

                        <div class="row mb-5 flex-wrap">
                            @error('sale_details')
                                <div class="alert-danger alert">
                                    At least one sale item is required to complete sale!
                                </div>
                            @enderror
                            <div class="mb-sm-10 mb-3 col-12 col-sm-6 mt-3 col-md-3">
                                <label class="form-label fs-7 mb-3 fw-semibold" for="">
                                    Default Selling Price
                                </label>
                                <select name="price_list"  class="form-select form-select-sm price_group priceList price_list_input" data-kt-select2="true" data-hide-search="true" data-placeholder="Select Selling Price">
                                    <option value="default_selling_price">defalut selling price</option>
                                    @foreach ($priceLists as $PriceList)
                                        <option value="{{$PriceList->id}}">{{$PriceList->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-sm-10 mb-3 mt-3 col-12 col-sm-6 col-md-3 fv-row">
                                <label class="form-label fs-7 mb-3 fw-semibold required">Customer:</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-user text-muted"></i>
                                    </div>
                                    <div class="overflow-hidden  flex-grow-1">
                                        <select class="form-select form-select-sm  fw-bold rounded-start-0"  name="contact_id" data-kt-select2="true" data-hide-search="false" data-placeholder="Select customer name" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true" >
                                            <option value=""></option>
                                            {{-- <option value="2">Aung Aung</option> --}}
                                            @foreach ($customers as $customer)
                                                <option value="{{$customer->id}}" @selected(old('contact_id')==$customer->id)  priceList={{$customer->pricelist_id}}>{{ $customer->company_name ?? $customer->getFullNameAttribute() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <button class="input-group-text  add_supplier_modal"  data-bs-toggle="modal" type="button" data-bs-target="#add_supplier_modal" data-href="{{ url('purchase/add/supplier')}}">
                                        <i class="fa-solid fa-circle-plus fs-3 text-primary"></i>
                                    </button> --}}

                                </div>
                                <div class="text-gray-600 ms-2 fw-semibold mt-3">Credit Limit : <span class="credit_limit_txt">0</span></div>
                                <input type="hidden" name="" id="credit_limit" value="0">
                                @error('contact_id')
                                    <div class="p-2 text-danger">* {{$message}}</div>
                                @enderror
                            </div>

                            <div class="mb-sm-10 mb-3  col-12 mt-3 col-sm-6 col-md-3">
                                <label class="form-label fs-7 mb-3 fw-semibold required" for="">
                                    Currency
                                </label>
                                <select name="currency_id" id="currency_id"  class="form-select form-select-sm" data-kt-select2="true" data-placeholder="Select Currency" data-status="filter" data-hide-search="true" required>
                                    @foreach ($currencies as $c)
                                        <option value="{{$c->id}}" @selected($c->id==$defaultCurrency['id'])>{{$c->name}} - {{$c->symbol}}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- <input type="hidden" name="currency_id" value="{{$defaultCurrency['id']}}"> --}}
                            <div class="mb-sm-10 mb-3  col-12 mt-3 col-sm-6 col-md-3">
                                <label class="form-label fs-7 mb-3 fw-semibold required" for="">
                                    Status
                                </label>
                                <select name="status" id="saleStatus"  class="form-select form-select-sm" data-kt-select2="true" data-status="filter" data-hide-search="true" required>
                                    <option value="quotation"  >Quotation</option>
                                    <option value="draft"  >Draft</option>
                                    <option value="pending"  >Pending</option>
                                    <option value="order" >Ordering</option>
                                    <option value="partial"  >Partial</option>
                                    <option value="delivered"  >Delivered</option>
                                </select>
                            </div>


                        </div>
                        <div class="d-flex justify-content-center align-items-center mt-3 mb-10">
                            <div class="col-6 col-md-2 fs-7  fw-semibold  text-primary">
                                Select Products
                            </div>
                            <div class="separator   border-primary-subtle col-md-10 col-6"></div>
                        </div>
                        <div class="row align-items-center mb-5 justify-content-sm-center">
                            <div class="col-12 col-md-9">
                                <div class="input-group quick-search-form p-0">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </div>
                                    <input type="text" class="form-control form-control-sm rounded-start-0" id="searchInput" placeholder="Search...">
                                    <div class="quick-search-results overflow-scroll  p-3 position-absolute d-none card w-100 mt-18  card  autocomplete shadow" id="autocomplete" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                                </div>
                            </div>
                            <button class="col-md-3 col-12 p-3 mt-lg-0 mt-3 col-md-3 btn-sm btn-primary btn add_new_product_modal  productQuickAdd"   data-href="{{route('product.quickAdd')}}" type="button">
                                <i class="fa-solid fa-plus me-2 text-white"></i> Add new product
                            </button>
                        </div>
                        {{-- <div class="d-flex ps-2 gap-1 overflow-x-scroll hover-scroll pb-3" id="suggestionProducts">

                        </div> --}}
                        <div class="table-responsive">
                            <table class="table table-row-dashed fs-6 gy-4 mt-3" id="sale_table">
                                <!--begin::Table head-->
                                <thead class="bg-light">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-8 text-uppercase gs-0 ">
                                        <th class="min-w-250px ps-3">Product </th>
                                        <th class="min-w-200px ">Quantity </th>
                                        <th class="min-w-100px">UOM </th>
                                        <th class="min-w-100px" style="max-width: 100px;">Price List</th>
                                        <th class="min-w-200px">Uom Price</th>
                                        <th class="min-w-200px">Subtotal</th>
                                        <th class="min-w-105px {{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">Disc </th>
                                        <th class="min-w-100px {{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">Disc Amount </th>
                                        {{-- <th class="min-w-125px">Discount Amount</th> --}}
                                        {{-- <th class="min-w-125px">Subtotal</th> --}}
                                        <th class="pe-1 min-w-50px text-end" ><i class="fa-solid fa-trash text-primary" type="button"></i></th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600 fs-6 data-table-body saleDetailItems">
                                    <tr class="dataTables_empty text-center">
                                        <td colspan="8 " >There is no data to show</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="separator my-5"></div>
                        <div class="col-6 float-end mt-3">
                            <table class="col-12 ">
                                <tbody>
                                    <tr>
                                        <th class="text-end">Items: <span class="fw-medium fs-5 total_item">0</span></th>
                                        {{-- <th class="d-flex justify-content-center align-items-center">
                                            <span class="min-w-100px fw-semibold">Sale Amount:</span>
                                            <div class="">
                                                <input type="text" name="sale_amount" class="sale_amount_input input_number form-control form-control-sm" value="0" >
                                            </div>
                                        </th> --}}
                                        {{-- <input type="hidden" name="total_line_discount" class="total_line_discount" value="0"> --}}
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-end mb-2">
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Sale Amount:(=)
                                </span>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="sale_amount" class="sale_amount_input input_number form-control form-control-sm" value="0" >
                                    <span class="input-group-text currencySymbol">{{$defaultCurrency['symbol']}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end  {{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Total Item Discount:(-)
                                </span>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="total_item_discount"  id="total_item_discount" class="form-control form-control-sm fs-7 total_item_discount" value="" placeholder="0" readonly />
                                    <span class="input-group-text currencySymbol">{{$defaultCurrency['symbol']}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" mt-2 col-12 col-md-3 offset-md-1">
                                <label class="form-label fs-7 fw-semibold" for="">
                                    Extra Discount Type
                                </label>
                                <select name="extra_discount_type" id="" class="form-select form-select-sm extra_discount_type" data-control="select2" data-hide-search="true">
                                    <option value="fixed">Fixed</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>
                            <div class="mt-2 col-12 col-md-3 mb-5 mb-md-0">
                                <label class="form-label fs-7 fw-semibold" for="">
                                  Extra Discount Amount
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="extra_discount_amount" class="form-control form-control-sm extra_discount_amount input_number">
                                    <span class="input-group-text currencySymbol">{{$defaultCurrency['symbol']}}</span>
                                </div>
                            </div>
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-end align-items-center mb-5 mb-md-0">
                                <span class="min-w-200px pe-2" for="">
                                    Discount :(-)
                                </span>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm input_number max-w-100px extra_discount" id="extra_discount" disabled>
                                    <span class="input-group-text currencySymbol">{{$defaultCurrency['symbol']}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Total Sale Amount:(=)
                                </span>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="total_sale_amount" class="form-control form-control-sm input_number max-w-100px total_sale_amount" value="">
                                    <span class="input-group-text currencySymbol">{{$defaultCurrency['symbol']}}</span>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row justify-content-end">
                            <div class="my-9 fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Paid Amount:(=)Ks
                                </span>
                                <input type="text" name="paid_amount" class="form-control form-control-sm input_number max-w-100px paid_amount_input" id="total_paid_amount" value="0">
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Balance Amount:(=)Ks
                                </span>
                                <input type="text" name="balance_amount" class="form-control form-control-sm input_number max-w-100px balance_amount_input" id="total_balance_amount" value="">
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-end mb-3">
                            <div class="fs-6 fw-semibold col-12 col-md-3 d-flex justify-content-between align-items-center mb-5 ">
                                <select name="payment_account" id="payment_accounts" class="form-select form-select-sm" data-kt-select2="true" data-placeholder="select payment account">
                                    <option disabled selected>Select Account</option>
                                    @php
                                        $paymentAccounts=App\Models\paymentAccounts::where('currency_id',$defaultCurrency['id'])->get();
                                    @endphp
                                    @foreach ($paymentAccounts as $p)
                                        <option value="{{$p->id}}">{{$p->name}} ({{$p->account_number}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Paid Amount:(=)
                                </span>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="paid_amount" class="form-control form-control-sm input_number max-w-100px paid_amount_input" id="total_paid_amount" value="0">
                                    <span class="input-group-text currencySymbol">{{$defaultCurrency['symbol']}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-cente fv-row">
                                <span class="min-w-200px pe-2" for="">
                                    Balance Amount:(=)
                                </span>
                                <div class="w-100">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="balance_amount"
                                            class="form-control form-control-sm input_number max-w-100px balance_amount_input" id="total_balance_amount"
                                            value="">
                                        <span class="input-group-text currencySymbol">{{$defaultCurrency['symbol']}}</span>
                                    </div>
                                    <div class="text-danger ps-2 d-none" id="credit_limit_message" for="">
                                        Reached credit limit.
                                    </div>
                                </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary save_btn" data-kt-sale-action="submit" name="save" value='save'>Save</button>
                <button type="submit" class="btn btn-success save_btn" data-kt-sale-action="submit" name="save" value='save_&_print'>Save & Print</button>
            </div>
        </form>
    </div>
    <!--end::Container-->
</div>

<div class="modal modal-lg fade " tabindex="-1"  data-bs-focus="false"  id="quick_add_product_modal" ></div>

<div class="modal fade" id="suggestionModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog w-lg-600px modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-gray-800">Product Suggestion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="suggestionProducts">
                   <div class="border border-1 rounded px-2 py-3 d-flex mb-2">
                     <div class="img bg-light w-50px h-50px rounded">

                     </div>
                     <div class="product-info ms-4 pt-1">
                        <span class="fw-bold text-gray-800">Rk 61 wireless Keyboard <span class="text-gray-700 fw-semibold">(Cherry Mx blue switch)</span></span>
                        <span class="fw-bold text-gray-700 pt-2 d-block">Qty : <span class="text-gray-900"> 30 pcs</span></span>
                     </div>
                   </div>
                   <div class="border border-1 rounded px-2 py-3 d-flex">
                    <div class="img bg-light w-50px h-50px rounded">

                    </div>
                    <div class="product-info ms-4 pt-1">
                        <span class="fw-bold text-gray-800">Rk 61 wireless Keyboard <span class="text-gray-700 fw-semibold">(Cherry Mx
                                blue switch)</span></span>
                        <span class="fw-bold text-gray-700 pt-2 d-block">Qty : <span class="text-gray-900"> 30 pcs</span></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- @include('App.purchase.contactAdd')o --}}
@include('App.purchase.newProductAdd')
@endsection

@push('scripts')

<script>
    let contacts=@json($customers ?? []);
    var credit_limit=0;
    $(document).on('change','[name="contact_id"]',function(){
        let contact_id=contacts.find(c=>c.id==$(this).val());
        credit_limit=parseFloat(contact_id.credit_limit ?? 0) ;
        $('.credit_limit_txt').text(credit_limit.toFixed(2));
        $('credit_limit').val(credit_limit);
    })
    $("#kt_datepicker_1").flatpickr({
        dateFormat: "d-m-Y",
    });
    $("#kt_datepicker_2").flatpickr({
        dateFormat: "d-m-Y",
    });
    $("#kt_datepicker_3").flatpickr({
        dateFormat: "d-m-Y",
    });



    $(document).on('click', '.productQuickAdd', function(){
        $url=$(this).data('href');

        loadingOn();
        $('#quick_add_product_modal').load($url, function() {
            $(this).modal('show');
            loadingOff();
        });
    });
</script>
@include('App.sell.js.saleJs')
<script src={{asset('customJs/Ajax/getAccountByCurrency.js')}}></script>
<script src={{asset('customJs/Purchases/contactAdd.js')}}></script>
<script src="{{asset('customJs/sell/saleValidator.js')}}"></script>
<script>

</script>
@endpush


