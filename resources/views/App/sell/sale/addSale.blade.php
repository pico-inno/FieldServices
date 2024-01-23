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
            padding: 1px;
        }
        .data-table-body tr td input{
            max-width: 100%;
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
                <div class="card " style="overflow-x: hidden;">
                    <div class="card-body  px-5">

                        <div class="row mb-5 mb-sm-10 flex-wrap">
                            @error('sale_details')
                                <div class="alert-danger alert">
                                    At least one sale item is required to complete sale!
                                </div>
                            @enderror
                            <div class="mb-3 col-12 col-sm-6 mt-3 col-md-3">
                                <label class="form-label fs-7 mb-3 fw-semibold" for="">
                                    Select Price List
                                </label>
                                <select name="price_list"  class="form-select form-select-sm price_group priceList price_list_input" data-kt-select2="true" data-hide-search="true" data-placeholder="Select Selling Price">
                                    {{-- <option value="default_selling_price">defalut selling price</option> --}}
                                    @foreach ($priceLists as $PriceList)
                                        <option value="{{$PriceList->id}}" @selected($defaultPriceListId == $PriceList->id)>{{$PriceList->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 mt-3 col-12 col-sm-6 col-md-3 fv-row">
                                <label class="form-label fs-7 mb-3 fw-semibold required">Customer:</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-user text-muted"></i>
                                    </div>
                                    <div class="overflow-hidden  flex-grow-1">
                                        <x-customersearch placeholder='Select customer name' name="contact_id" className=" form-select-sm contact_id fw-bold rounded-start-0" >
                                                <x-slot:defaultOption>
                                                <option value="{{$walkInCustomer->id}}" selected>
                                                    {{$walkInCustomer->getFullNameAttribute()}}-{{'('.arr($walkInCustomer,'mobile','-').')'}}</option>
                                                </x-slot>
                                        </x-customersearch>
                                    </div>
                                    <button class="input-group-text  add_supplier_modal"  data-bs-toggle="modal" type="button" data-bs-target="#contact_add_modal" data-href="{{ route('pos.contact.add') }}">
                                        <i class="fa-solid fa-circle-plus fs-3 text-primary"></i>
                                    </button>

                                </div>
                                <div class="text-gray-600 ms-2 fw-semibold mt-3">Credit Limit : <span class="credit_limit_txt">0</span></div>
                                <input type="hidden" name="" id="credit_limit" value="0">
                                @error('contact_id')
                                    <div class="p-2 text-danger">* {{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3  col-12 mt-3 col-sm-6 col-md-3">
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
                            <div class="mb-3  col-12 mt-3 col-sm-6 col-md-3">
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
                            <div class="mb-3  col-12 mt-3 col-sm-6 col-md-3">
                                <label class="form-label fs-6 fw-semibold required" for="purchaseDatee">
                                    sold At:
                                </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input class="form-control" name="sold_at" placeholder="Pick a date" data-td-toggle="sold_at"  id="sold_at" value="{{old('sold_at',now()->format('Y-m-d H:i'))}}" />


                                </div>
                            </div>

                        </div>
                        <div class="d-flex justify-content-center align-items-center mt-3 mb-10">
                            <div class="col-6 col-md-2 fs-7  fw-semibold  text-primary">
                                Select Products
                            </div>
                            <div class="separator   border-primary-subtle col-md-10 col-6"></div>
                        </div>
                        <div class="row align-items-center mb-2 justify-content-sm-center">
                            <div class="col-12 col-md-9">
                                <div class="input-group quick-search-form p-0">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </div>
                                    <input type="search" onkeypress="preventEnterSubmit(event)" class="form-control form-control-sm rounded-start-0" id="searchInput" placeholder="Search...">
                                    <div class="quick-search-results overflow-scroll rounded-1 p-3 position-absolute d-none card w-100 mt-18  card  autocomplete shadow" id="autocomplete" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                                </div>
                            </div>
                            <button class="col-md-3 col-12 p-3 mt-lg-0 mt-3 col-md-3 btn-sm btn-primary btn add_new_product_modal  productQuickAdd"   data-href="{{route('product.quickAdd')}}" type="button">
                                <i class="fa-solid fa-plus me-2 text-white"></i> Add new product
                            </button>
                        </div>
                        <div class="mb-5">
                            <!--begin::Heading-->
                            <div class="d-flex align-items-start collapsible py-1 toggle mb-0 collapsed user-select-none" data-bs-toggle="collapse"
                                data-bs-target="#keyword_setting" aria-expanded="false">
                                <!--begin::Icon-->
                                <div class="me-1">
                                    <i class="ki-outline ki-down toggle-on text-primary fs-3"></i>
                                    <i class="ki-outline ki-right toggle-off fs-3"></i>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex align-items-start flex-wrap">
                                    <!--begin::Title-->
                                    <h3 class="text-gray-800 fw-semibold cursor-pointer me-3 mb-0 fs-7 ">
                                       Click to set Search Keyword
                                    </h3>
                                    <!--end::Title-->

                                    <!--begin::Label-->
                                    <span class="badge badge-light my-1 d-block d-none">React</span>
                                    <!--end::Label-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Heading-->

                            <!--begin::Body-->
                            <div id="keyword_setting" class="fs-6 ms-10 collapse" style="">
                                <div class="row mt-5">
                                    <div class="col-2">
                                        <div class="form-check form-check-sm user-select-none">
                                            <input class="form-check-input " type="checkbox" value="on" id="p_kw" checked disabled />
                                            <label class="form-check-label cursor-pointer" for="p_kw">
                                                Product Name
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-check form-check-sm user-select-none">
                                            <input class="form-check-input " type="checkbox" value="on" id="psku_kw" checked />
                                            <label class="form-check-label cursor-pointer" for="psku_kw">
                                                Product Sku
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-check form-check-sm user-select-none">
                                            <input class="form-check-input " type="checkbox" value="on" id="vsku_kw" />
                                            <label class="form-check-label cursor-pointer" for="vsku_kw">
                                                Variation Sku
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-check form-check-sm user-select-none">
                                            <input class="form-check-input " type="checkbox" value="on" id="pgbc_kw" />
                                            <label class="form-check-label cursor-pointer" for="pgbc_kw">
                                                Packaging Barcode
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Content-->
                        </div>
                        {{-- <div class="d-flex ps-2 gap-1 overflow-x-scroll hover-scroll pb-3" id="suggestionProducts">

                        </div> --}}
                        <div class="table-responsive">
                            <table class="table table-row-dashed fs-6 gy-4 mt-3" id="sale_table">
                                <!--begin::Table head-->
                                <thead class="bg-light">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-8 text-uppercase gs-0 ">
                                        <th class="px-1 min-w-20px text-start" ><i class="fa-solid fa-trash text-primary" type="button"></i></th>
                                        <th class="min-w-100px ps-3 text-start product_name">Product </th>
                                        <th class="min-w-100px">Quantity </th>
                                        <th class="min-w-100px">UOM </th>
                                        <th class="min-w-100px">{{__('product/product.package_qty')}}</th>
                                        <th class="min-w-100px">{{__('product/product.package')}}</th>
                                        {{-- <th class="min-w-100px" style="max-width: 100px;">Price List</th> --}}
                                        <th class="min-w-150px">Uom Price</th>
                                        <th class="min-w-150px">Subtotal</th>
                                        <th class="min-w-100px {{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">Disc </th>
                                        <th class="min-w-100px {{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">Disc Amount </th>
                                        {{-- <th class="min-w-125px">Discount Amount</th> --}}
                                        {{-- <th class="min-w-125px">Subtotal</th> --}}
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600 fs-6 data-table-body saleDetailItems p-2">
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
                                    <input type="text" name="sale_amount" class="sale_amount_input input_number form-control form-control-sm" value="0" readonly>
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
                <button type="submit" class="btn btn-warning save_btn" data-kt-sale-action="submit" name="save" value='save_&_download_image'>Save & Download Image</button>

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
@include('App.purchase.newProductAdd')
@endsection

@push('scripts')
<script>
    function preventEnterSubmit(event) {
      if (event.key === "Enter") {
        event.preventDefault();
      }
    }
    $('[data-td-toggle="sold_at"]').flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    });

    const myModalEl = document.getElementById('suggestionModal')
    myModalEl.addEventListener('hidden.bs.modal', event => {
        $('.modal-backdrop').remove();
    })
    //get/customer/{customer}/{key}
    let contacts=@json($walkInCustomer->toArray());
    getCreditLimit(contacts['id']);
    $(document).on('change','[name="contact_id"]',function(){
        getCreditLimit($(this).val());
    })
    function getCreditLimit(id){
        $('.credit_limit_txt').text('loading....');
        $('#credit_limit').val(0);
        $.ajax({
            url: `/contact/get/${id}`,
            type: 'GET',
            data:{
                'key':['credit_limit','receivable_amount']
            },
            error:function(e){
                status=e.status;
                if(status==405){
                    warning('Method Not Allow!');
                }else if(status==419){
                    error('Session Expired')
                }else{
                    console.log(e);
                    console.log(' Something Went Wrong! Error Status: '+status )
                };
            },
            success: function(results){
                credit_limit=parseFloat(results.credit_limit ? results.credit_limit: 0) ;
                receivable_amount=parseFloat(results.receivable_amount ? results.receivable_amount: 0) ;
                let result= credit_limit-receivable_amount;
                console.log(result);
                $('.credit_limit_txt').text(result ? result.toFixed(2) : 0);
                $('#credit_limit').val(result ?? 0);
            },
        })
    }
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
    $('.disappearing-div').click(function(){
        var clone=$(this).clone();
        $('.main_div').append(clone);
         clone.css({
            "animation-name": "example",
            "animation-duration": "0.5s",
        });
        setTimeout(()=>{
            clone.remove();
        },400)
    })
</script>
@include('App.sell.js.saleJs')
@include('App.pos.contactAdd')
<script src={{asset('customJs/Ajax/getAccountByCurrency.js')}}></script>
<script src={{asset('customJs/Purchases/contactAdd.js')}}></script>
<script src={{asset('customJs/sell/saleValidator.js')}}></script>
@endpush


