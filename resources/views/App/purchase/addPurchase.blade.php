@extends('App.main.navBar')

@section('purchases_icon', 'active')
@section('pruchases_show', 'active show')
@section('purchases_add_active_show', 'active ')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('purchase.add_purchase')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('purchase.purchase')}}</li>
        <li class="breadcrumb-item text-muted">Order</li>
        <li class="breadcrumb-item text-dark">{{__('adjustment.add')}}</li>
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
    </style>
@endsection



@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="purchase_container">
    <div class="container-xxl">
        <form action="{{route('purchase_store')}}" method="POST" id="purchase_form">
            @csrf
            {{-- <input type="hidden" name="currency_id" value="{{$currency->id}}"> --}}
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" id="payment">
                <div class="card px-3 px-sm-5">
                    <div class="card-body px-3 px-sm-5">
                        @error('details')
                            <div class="alert-danger alert">
                                At least one purchase item is required to complete purchase!
                            </div>
                        @enderror

                        <div class="row mb-5 flex-wrap">
                            <!--begin::Input group-->
                            <div class="mb-7 mt-3 col-12 col-md-3 fv-row">
                                <label class="form-label fs-6 fw-semibold required">{{__('contact.supplier')}}:</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-user text-muted"></i>
                                    </div>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="contact_id" class="form-select form-select-sm fw-bold rounded-start-0 rounded-end-0" id="contact_id" data-kt-select2="true" data-hide-search="false" data-placeholder="Select supplier" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true" >
                                            <option></option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->id}}" @selected(old('contact_id')==$supplier->id)>{{$supplier->company_name ?? $supplier->getFullNameAttribute() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="input-group-text add_supplier_modal"  data-bs-toggle="modal" type="button" data-bs-target="#contact_add_modal" data-href="{{ route('pos.contact.add') }}">
                                       <i class="fa-solid fa-circle-plus fs-3 text-primary"></i>
                                    </button>
                                    {{-- <a class="input-group-text "  href="{{route('suppliers.create')}}" target="_blanck">
                                        <i class="fa-solid fa-circle-plus fs-3 text-primary"></i>
                                    </a> --}}
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold required" for="">
                                    {{__('business_settings.business_location')}}
                                </label>
                                <div class="fv-row">
                                    <select name="business_location_id" class="form-select form-select-sm fw-bold " data-kt-select2="true" data-hide-search="false" data-placeholder="Select Location" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true" >
                                        <option></option>
                                        @foreach ($locations as $l)
                                            <option value="{{$l->id}}"  @selected(Auth::user()->default_location_id==$l->id)>{{businessLocationName($l)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold required" for="">
                                    {{__('purchase.purchase_status')}}:
                                </label>
                                <div class="fv-row">
                                    <select name="status" class="form-select form-select-sm fw-bold " id="OrderStatus" data-status="filter"  data-kt-select2="true" data-hide-search="false" data-placeholder="Select Status" data-allow-clear="true"  data-hide-search="true" >
                                        <option></option>
                                        <option value="request" @selected(old('status')=='request')>Request</option>
                                        <option value="pending" @selected(old('status')=='pending')>Pending</option>
                                        <option value="order" @selected(old('status')=='order')>Ordering</option>
                                        <option value="partial" @selected(old('status')=='partial')>Partial</option>
                                        <option value="received" @selected(old('status')=='received')>Received</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-7 mb-3 fw-semibold required" for="">
                                    {{__('product/pricelist.currency')}}
                                </label>
                                <select name="currency_id" id="currency_id"  class="form-select form-select-sm" data-kt-select2="true" data-placeholder="Select Currency" placeholder="Select Currency" data-status="filter" data-hide-search="true" required>
                                    <option  disabled selected>Select Currency</option>
                                    @foreach ($currencies as $c)
                                        <option value="{{$c->id}}" @selected($c->id==$currency['id'])>{{$c->name}} - {{$c->symbol}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold" for="orderDate">
                                    {{__('purchase.supplier_address')}}:
                                </label>
                                <div class="fs-6 text-gray-70 supplier_address">
                                    {{-- ၅၅လမ်း၊ ၁၃၂ လမ်း နှင့် ၁၃၃လမ်းကြား၊ ပြည်ကြီးတံခွန်မြို့နယ်၊ မန္တလေးမြို့။ --}}
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold required" for="purchaseDatee">
                                    {{__('purchase.purchase_date')}}:
                                </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"  >
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input class="form-control" name="purchased_at" placeholder="Pick a date" data-td-toggle="datetimepicker"  id="kt_datepicker_1" value="{{old('purchased_at',now())}}" />
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold" for="received_at">
                                    {{__('purchase.received_date')}}:
                                </label>
                                <div class="input-group input-group-sm fv-row">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input class="form-control" name="received_at" placeholder="Pick a date" data-td-toggle="datetimepickerallowClear"
                                        id="received_at" value="{{old('received_at')}}" data-allow-clear="true" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border border-primary-subtle border-top-2 border-left-0 border-right-0 border-bottom-0" style="overflow-x: hidden;">
                    <div class="card-body px-5">
                        <div class="row align-items-center mb-sm-8">
                            <div class="col-12 col-md-9">
                                <div class="input-group quick-search-form p-0">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </div>
                                    <input type="text" onkeypress="preventEnterSubmit(event)" class="form-control form-control-sm rounded-start-0" id="searchInput" placeholder="{{__('transfer.search_products')}}...">
                                    <div class="quick-search-results overflow-scroll  position-absolute d-none card w-100 mt-14  card z-index-1 autocomplete shadow" id="autocomplete" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                                </div>
                            </div>
                            <a class="col-12 col-md-3 btn-light-primary btn btn-sm add_new_product_modal my-5 my-lg-0 productQuickAdd"   data-href="{{route('product.quickAdd')}}"  >
                                <i class="fa-solid fa-plus me-2 "></i> {{__('business_settings.add_new_product')}}
                            </a>
                        </div>
                        <div class="mb-5">
                            <!--begin::Heading-->
                            <div class="d-flex align-items-start collapsible py-1 toggle mb-0 collapsed user-select-none"
                                data-bs-toggle="collapse" data-bs-target="#keyword_setting" aria-expanded="false">
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
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 mt-10" id="purchase_table">
                                <!--begin::Table head-->
                                <thead class="bg-light">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-8 text-uppercase gs-0 ">
                                        {{-- <th class="min-w-50px">#</th> --}}
                                        <th class="min-w-125px ps-1" style="max-width: 125px">{{__('product/product.product_name')}}</th>
                                        <th class="min-w-80px">{{__('report.quantity')}} </th>
                                        <th class="min-w-125px">{{__('product/unit-and-uom.unit')}}</th>
                                        <th class="min-w-125px">{{__('product/product.package_qty')}}</th>
                                        <th class="min-w-125px">{{__('product/product.package')}}</th>
                                        <th class="min-w-125px">{{__('report.uom_price')}}</th>
                                        <th class="min-w-80px {{$setting->enable_line_discount_for_purchase == 1 ? '' :'d-none'}}"> {{__('service.discount_amount')}} </th>
                                        <th class="min-w-125px {{$setting->enable_line_discount_for_purchase == 1 ? '' :'d-none'}}">{{__('service.discount_amount')}} </th>
                                        <th class="min-w-125px">{{__('expense.per_item_expense')}}</th>
                                        <th class="min-w-125px d-none">{{__('table/label.subtotal')}} <br>
                                            {{-- with Expense & Disc --}}
                                        </th>
                                        <th class="min-w-125px">{{__('table/label.subtotal')}}</th>
                                        {{-- @if ( $setting->lot_control=='on')
                                            <th class="min-w-150px">Lot/Serial No</th>
                                        @endif --}}
                                        <th class="text-center" ><i class="fa-solid fa-trash text-primary" type="button"></i></th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600 data-table-body">
                                    <tr class="dataTables_empty text-center">
                                        <td colspan="8 " >{{__('table/label.no_data_to_show')}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="separator my-5"></div>
                        <div class="col-sm-4 col-12 float-end mt-3">
                            <table class="col-12 ">
                                <tbody class="">
                                    <tr class="mb-5 ">
                                        <th class="fw-semibold">{{__('table/label.total_items')}} :</th>
                                        <td class="rowcount text-left  fs-6 fw-semibold text-end" >
                                            <span id="total_item"> 0 </span>
                                        </td>
                                    </tr>
                                    <tr class="mb-2 d-none">
                                        <th class="fw-semibold"> Total Line Discount :</th>
                                        <td class="rowSum text-left  fs-6 fw-semibold text-end" >
                                            <span class="total_line_discount fw-bold">0 </span> <span class="currencySymbol">{{$currency['symbol']}}</span>
                                        </td>
                                    </tr>
                                    <input type="hidden" name="total_line_discount" class="total_line_discount_input input_number" id="" class="" value="{{old('purchase_amount',0)}}">
                                    <tr class="mb-2">
                                        <th class=" fw-semibold">{{__('purchase.total_purchase_amount')}} :</th>
                                        <td class="rowSum text-left fs-6 fw-semibold text-end" id=''>
                                            <span class="net_purchase_total_amount_text"> 0 </span> <span class="currencySymbol">{{$currency['symbol']}}</span>
                                        </td>
                                        <input type="hidden" name="purchase_amount" class="net_purchase_total_amount input_number" id="" class="" value="{{old('purchase_amount',0)}}">
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body" >
                        <div class="row justify-content-end">
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold" for="">
                                    Discount Type
                                </label>
                                <select name="extra_discount_type" id="extra_discount_type" class="form-select form-select-sm" data-control="select2">
                                    <option value="fixed" @selected(old('total_discount_type')=="fixed")>Fixed</option>
                                    <option value="percentage" @selected(old('total_discount_type')=="percentage")>Percentage</option>
                                </select>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold" for="">
                                    Discount Amount
                                </label>
                                <input type="text" class="form-control input_number form-select-sm extra_discount_amount"   id="extra_discount_amount" name="extra_discount_amount" value="{{old('total_discount',0)}}">
                                <input type="hidden" class="extra_discount" id="extra_discount">
                                <div class='mt-2'>Discount : <span class="extra_discount_txt">0</span> <span class="currencySymbol"> {{$currency['symbol']}}</span></div>
                            </div>
                            <div class="mb-7 col-12 col-md-4 fw-semibold fs-6  d-flex justify-content-between align-items-center">
                                <span >
                                       Extra Discount:(-)
                                </span>
                                <div class="">
                                    <span class="extra_discount_txt">0</span><span class="currencySymbol"> {{$currency['symbol']}}</span>
                                </div>
                                <input type="hidden" class="form-control input_number form-select-sm" id="total_discount_amount"  name="total_discount_amount" value="{{old('total_discount_amount',0)}}">
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold" for="">
                                    Purchase Expense
                                </label>
                                <input type="text" class="form-control input_number form-select-sm" id="purchase_expense" name="purchase_expense" value="{{old('purchase_expense',0)}}">
                            </div>
                            <div class="mb-7 col-12 fw-semibold fs-6 col-md-4  d-flex justify-content-between align-items-center">
                                <span class="" for="">
                                        Purchase Expense:(+)
                                </span>
                                <div class="">
                                    <span id="purchase_expense_txt">{{old('purchase_expense',0)}}</span><span class="currencySymbol"> {{$currency['symbol']}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="my-15 col-12 col-md-4 fw-semibold fs-6 offset-md-8 d-flex justify-content-between align-items-end">
                                <div class=" " for="">
                                        total Purchase Amount:(=)
                                </div>
                                <div class="">
                                    <span id="total_purchase_amount_txt">{{old('total_purchase_amount',0)}}</span><span class="currencySymbol"> {{$currency['symbol']}}</span>
                                </div>
                            </div>
                            <input type="hidden" name="total_purchase_amount" id="total_purchase_amount" value="{{old('total_purchase_amount',0)}}">
                        </div>
                        {{-- <div class="row">
                            <div class="mb-7 col-12 col-md-4">
                                <label class="form-label fs-6 fw-semibold" for="">
                                    Paid Amount
                                </label>
                                <input type="text" class="form-control input_number form-select-sm" name="paid_amount" id="paid_amount" value="{{old('paid_amount',0)}}">
                            </div>
                            <div class="mb-7  fs-6 fw-semibold col-12 col-md-4 offset-md-4 d-flex justify-content-between align-items-end">
                                <span class="" for="">
                                         Paid Amount:(-)
                                </span>
                                <div class="">
                                    <span id="paid_amount_txt">{{old('paid_amount',0)}}</span><span class="currencySymbol">{{$currency['symbol']}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="my-15 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-end">
                                <span class="" for="">
                                         Balance Amount:(=)
                                </span>
                                <div class="">
                                    <span id="balance_amount_txt">{{old('balance_amount',0)}}</span> <span class="currencySymbol">{{$currency['symbol']}}</span>
                                </div>
                                <input type="hidden" class="form-control input_number" name="balance_amount form-select-sm" id="balance_amount" value="{{old('balance_amount',0)}}">
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row ">
                            <div class="fv-row fs-6 fw-semibold col-12 col-md-4 mb-4 mb-md-0">
                                <label class="form-label fs-6 fw-semibold " for="">
                                    Payment Account
                                </label>
                                <select name="payment_account" id="payment_accounts" class="form-select form-select-sm" data-kt-select2="true" data-hide-search="true" placeholder="select payment account"  data-placeholder="select payment account">
                                    @php
                                        $paymentAccounts=App\Models\paymentAccounts::where('currency_id',$currency['id'])->get();
                                    @endphp
                                    @foreach ($paymentAccounts as $p)
                                        <option value="{{$p->id}}">{{$p->name}} ({{$p->account_number}})</option>
                                    @endforeach
                                </select>
                                <div class="fs-7 text-gray-400 mt-3">
                                    Account current balance :<span id="currentAccBalanceTxt">0</span> <span id="currencySymbol"></span>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start mb-7  fs-6 fw-semibold col-12 col-md-4 offset-md-4">
                                <label class="form-label" for="">
                                         Paid Amount:(-)
                                </label>
                                <div class="fv-row w-100">
                                    <input type="text" class="form-control input_number form-control-sm" name="paid_amount" id="paid_amount" value="{{old('paid_amount',0)}}">
                                    {{-- <span id="paid_amount_txt">{{old('paid_amount',0)}}</span><span class="currencySymbol">{{$currency['symbol']}}</span> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="my-sm-15 my-5 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-start">
                                <span class="" for="">
                                         Balance Amount:(=)
                                </span>
                                <div class="">
                                    <span id="balance_amount_txt">{{old('balance_amount',0)}}</span> <span class="currencySymbol">{{$currency['symbol']}}</span>
                                </div>
                                <input type="hidden" class="form-control input_number form-select-sm" name="balance_amount" id="balance_amount" value="{{old('balance_amount',0)}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center mt-5 mb-5">
                <button type="submit" class="btn btn-primary btn" data-kt-purchase-action="submit" name="save" value='save'>Save</button>
                <button type="submit" class="btn btn-success" data-kt-purchase-action="submit" name="save" value='save_&_print'>Save & Print</button>
            </div>
        </form>
    </div>
    <!--end::Container-->
</div>

{{-- @include('App.purchase.contactAdd') --}}
{{-- @include('App.purchase.newProductAdd') --}}
<div class="modal modal-lg fade " tabindex="-1"  data-bs-focus="false"  id="quick_add_product_modal" ></div>
@endsection

@push('scripts')
<script src={{asset('customJs/Purchases/contactAdd.js')}}></script>
@include('App.purchase.contactAdd')
<script>


function preventEnterSubmit(event) {
      if (event.key === "Enter") {
        event.preventDefault();
      }
    }
$(document).ready(function(){

    $('[data-td-toggle="datetimepicker"]').flatpickr({
             enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    let fp=$('[data-td-toggle="datetimepickerallowClear"]').flatpickr({
        enableTime: true,
        showClear: true,
        dateFormat: "Y-m-d H:i",
    });

    $('#OrderStatus').change(function(){
        let received_at=$('#received_at').val();
        if(received_at=='' && $(this).val()=='received'){
            var currentDate = new Date();
            var formattedCurrentDate = fp.formatDate(currentDate, "Y-m-d H:i");
            fp.setDate(formattedCurrentDate);
        }
    })
        let locations=@json($locations);


        $('[name="business_location_id"]').on('change',function(){
            limitStatusBylocation($(this).val());
        });

        function limitStatusBylocation($id) {
            let location=locations.filter(l => {
                return l.id==$id;
            })[0];
            if(location.allow_purchase_order=='1'){
                $('[data-status="filter"] option[value="received"]').prop('disabled', true);
                $('[data-status="filter"] option[value="confirmed"]').prop('disabled', true);
                $('#OrderStatus').val('order').trigger('change');
            }else{
                $('[data-status="filter"] option:disabled').prop('disabled', false);
            }

        }
    let locationVal=$('[name="business_location_id"]').val();
    if(locationVal){
        limitStatusBylocation(locationVal);
    }
})
</script>
<script src={{asset('customJs/Ajax/getAccountByCurrency.js')}}></script>
<script src={{asset('customJs/Purchases/purchaseValidator.js')}}></script>
{{-- <script src={{asset('customJs/customFileInput.js')}}></script> --}}
{{-- <script src="{{asset('customJs/Purchases/purchaseAdd.js')}}"></script> --}}
@include('App.purchase.Js.calPurchase')


<script>

    $(document).on('click', '.productQuickAdd', function(){
        $url=$(this).data('href');

        loadingOn();
        $('#quick_add_product_modal').load($url, function() {
            $(this).modal('show');
            loadingOff();
        });
    });
    $('form#add_contact_form').submit(function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            success: function(response){
                console.log(response);
                if (response.success == true) {
                    $('#contact_add_modal').modal('hide');
                    success(response.msg);
                    // Clear the input fields in the modal form
                    $('#add_contact_form')[0].reset();
                    // $('.contact_id').select2();
                    // Create a new option element

                    var newOption = new Option(response.new_contact_name, response.new_contact_id);

                    // Append the new option to the select2 dropdown
                    $('#contact_id').append(newOption).trigger('change');
                    $('#contact_id').val(response.new_contact_id).trigger('change');
                }
            },
            error: function(result) {
                error(result.responseJSON.errors, 'Something went wrong');
            }
        })
    })
</script>

@endpush


