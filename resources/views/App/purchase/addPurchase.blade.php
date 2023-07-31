@extends('App.main.navBar')

@section('purchases_icon', 'active')
@section('pruchases_show', 'active show')
@section('purchases_add_active_show', 'active ')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Add Purchase</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Purchases</li>
        <li class="breadcrumb-item text-muted">Order</li>
        <li class="breadcrumb-item text-dark">Add</li>
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
    </style>
@endsection



@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="purchase_container">
    <div class="container-xxl">
        <form action="{{route('purchase_store')}}" method="POST" id="purchase_form">
            @csrf
            {{-- <input type="hidden" name="currency_id" value="{{$currency->id}}"> --}}
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" id="payment">
                <div class="card">
                    <div class="card-body">
                        @error('details')
                            <div class="alert-danger alert">
                                At least one purchase item is required to complete purchase!
                            </div>
                        @enderror

                        <div class="row mb-5 flex-wrap">
                            <!--begin::Input group-->
                            <div class="mb-7 mt-3 col-12 col-md-4 fv-row">
                                <label class="form-label fs-6 fw-semibold required">Supplier:</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-user text-muted"></i>
                                    </div>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="contact_id" class="form-select form-select-sm fw-bold rounded-0" id="contact_id" data-kt-select2="true" data-hide-search="false" data-placeholder="Select supplier" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true" >
                                            <option></option>
                                            @foreach($suppliers as $supplier)

                                                <option value="{{$supplier->id}}" @selected(old('contact_id')==$supplier->id)>{{$supplier->company_name ?? $supplier->firstname}}</option>

                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <button class="input-group-text add_supplier_modal"  data-bs-toggle="modal" type="button" data-bs-target="#add_supplier_modal" data-href="{{ url('purchase/add/supplier')}}">
                                       <i class="fa-solid fa-circle-plus fs-3 text-primary"></i>
                                    </button> --}}
                                    <a class="input-group-text "  href="{{route('suppliers.create')}}" target="_blanck">
                                        <i class="fa-solid fa-circle-plus fs-3 text-primary"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-4">
                                <label class="form-label fs-6 fw-semibold required" for="">
                                    Business Location
                                </label>
                                <div class="fv-row">
                                    <select name="business_location_id" class="form-select form-select-sm fw-bold " data-kt-select2="true" data-hide-search="false" data-placeholder="Select Location" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true" >
                                        <option></option>
                                        @foreach ($locations as $l)
                                            <option value="{{$l->id}}"  @selected(Auth::user()->default_location_id==$l->id)>{{$l->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-4">
                                <label class="form-label fs-6 fw-semibold required" for="">
                                    Purchase Status:
                                </label>
                                <div class="fv-row">
                                    <select name="status" class="form-select form-select-sm fw-bold " id="OrderStatus" data-status="filter"  data-kt-select2="true" data-hide-search="false" data-placeholder="Select Status" data-allow-clear="true"  data-hide-search="true" >
                                        <option></option>
                                        <option value="request" @selected(old('status')=='request')>Request</option>
                                        <option value="pending" @selected(old('status')=='pending')>Pending</option>
                                        <option value="order" @selected(old('status')=='order')>order</option>
                                        <option value="partial" @selected(old('status')=='partial')>Partial</option>
                                        <option value="received" @selected(old('status')=='received')>Received</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-4">
                                <label class="form-label fs-7 mb-3 fw-semibold required" for="">
                                    Currency
                                </label>
                                <select name="currency_id" id="currency_id"  class="form-select form-select-sm" data-kt-select2="true" data-placeholder="Select Currency" placeholder="Select Currency" data-status="filter" data-hide-search="true" required>
                                    <option  disabled selected>Select Currency</option>
                                    @foreach ($currencies as $c)
                                        <option value="{{$c->id}}" @selected($c->id==$currency['id'])>{{$c->name}} - {{$c->symbol}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-4">
                                <label class="form-label fs-6 fw-semibold" for="orderDate">
                                    Address:
                                </label>
                                <div class="fs-6 text-gray-70 supplier_address">
                                    {{-- ၅၅လမ်း၊ ၁၃၂ လမ်း နှင့် ၁၃၃လမ်းကြား၊ ပြည်ကြီးတံခွန်မြို့နယ်၊ မန္တလေးမြို့။ --}}
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-4">
                                <label class="form-label fs-6 fw-semibold required" for="purchaseDatee">
                                    Purchase Date:
                                </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"  >
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input class="form-control" name="purchased_at" placeholder="Pick a date" data-td-toggle="datetimepicker"  id="kt_datepicker_1" value="{{old('purchased_at',now())}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border border-primary-subtle border-top-2 border-left-0 border-right-0 border-bottom-0">
                    <div class="card-body px-5">
                        <div class="row align-items-center mb-8">
                            <div class="col-5 col-12 col-md-2 btn-primary btn add_new_product_modal  my-5 my-lg-0 d-none"   data-bs-toggle="modal" type="button" data-bs-target="#add_new_product_modal" data-href="{{ url('purchase/add/supplier')}}">
                                <i class="fa-solid fa-plus me-2 text-white"></i> Import Products
                            </div>
                            <div class="col-6 col-md-9">
                                <div class="input-group quick-search-form p-0">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </div>
                                    <input type="text" class="form-control form-control-sm rounded-start-0" id="searchInput" placeholder="Search...">
                                    <div class="quick-search-results overflow-scroll  position-absolute d-none card w-100 mt-14  card z-index-1 autocomplete shadow" id="autocomplete" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                                </div>
                            </div>
                            <a class="col-6 col-md-3 btn-light-primary btn btn-sm add_new_product_modal my-5 my-lg-0"  target="__blank" href="{{route('product.add')}}">
                                <i class="fa-solid fa-plus me-2 "></i> Add new product
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 mt-10" id="purchase_table">
                                <!--begin::Table head-->
                                <thead class="bg-light">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-8 text-uppercase gs-0 ">
                                        {{-- <th class="min-w-50px">#</th> --}}
                                        <th class="min-w-125px ps-1" style="max-width: 125px">Product Name</th>
                                        <th class="min-w-80px">Qty </th>
                                        <th class="min-w-100px">Unit</th>
                                        <th class="min-w-125px">UOM Price</th>
                                        <th class="min-w-80px {{$setting->enable_line_discount_for_purchase == 1 ? '' :'d-none'}}"> Dis Type </th>
                                        <th class="min-w-125px {{$setting->enable_line_discount_for_purchase == 1 ? '' :'d-none'}}">Discount Amount </th>
                                        <th class="min-w-125px">Per Item  Expense</th>
                                        <th class="min-w-125px d-none">Subtotal <br>
                                            {{-- with Expense & Disc --}}
                                        </th>
                                        <th class="min-w-125px">Subtotal</th>
                                        @if ( $setting->lot_control=='on')
                                            <th class="min-w-150px">Lot/Serial No</th>
                                        @endif
                                        <th class="text-center" ><i class="fa-solid fa-trash text-primary" type="button"></i></th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600 data-table-body">
                                    <tr class="dataTables_empty text-center">
                                        <td colspan="8 " >There is no data to show</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="separator my-5"></div>
                        <div class="col-sm-4 col-12 float-end mt-3">
                            <table class="col-12 ">
                                <tbody>
                                    <tr class="mb-2">
                                        <th class="fw-semibold">Total Item :</th>
                                        <td class="rowcount text-left  fs-6 fw-semibold text-end" >
                                            <span id="total_item"> 0 </span>
                                        </td>
                                    </tr>
                                    <tr class="mb-2 d-none">
                                        <th class="fw-semibold"> Total Line Discount :</th>
                                        <td class="rowSum text-left  fs-6 fw-semibold text-end" >
                                            <span class="total_line_discount">0 </span> {{$currency['symbol']}}
                                        </td>
                                    </tr>
                                    <input type="hidden" name="total_line_discount" class="total_line_discount_input input_number" id="" class="" value="{{old('purchase_amount',0)}}">
                                    <tr class="mb-2">
                                        <th class=" fw-semibold">Net Total Purchase Amount :</th>
                                        <td class="rowSum text-left fs-6 fw-semibold text-end" id=''>
                                            <span class="net_purchase_total_amount_text"> 0 </span> {{$currency['symbol']}}
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
                                <div class='mt-2'>Discount : <span class="extra_discount_txt">0</span> {{$currency['symbol']}}</div>
                            </div>
                            <div class="mb-7 col-12 col-md-4 fw-semibold fs-6  d-flex justify-content-between align-items-center">
                                <span >
                                       Extra Discount:(-)
                                </span>
                                <div class="">
                                    <span class="extra_discount_txt">0</span>{{$currency['symbol']}}
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
                                    <span id="purchase_expense_txt">{{old('purchase_expense',0)}}</span>{{$currency['symbol']}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="my-15 col-12 col-md-4 fw-semibold fs-6 offset-md-8 d-flex justify-content-between align-items-end">
                                <span class=" " for="">
                                        total Purchase Amount:(=)
                                </span>
                                <div class="">
                                    <span id="total_purchase_amount_txt">{{old('total_purchase_amount',0)}}</span>{{$currency['symbol']}}
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
                                    <span id="paid_amount_txt">{{old('paid_amount',0)}}</span>{{$currency['symbol']}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="my-15 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-end">
                                <span class="" for="">
                                         Balance Amount:(=)
                                </span>
                                <div class="">
                                    <span id="balance_amount_txt">{{old('balance_amount',0)}}</span> {{$currency['symbol']}}
                                </div>
                                <input type="hidden" class="form-control input_number" name="balance_amount form-select-sm" id="balance_amount" value="{{old('balance_amount',0)}}">
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row ">
                            <div class="fv-row fs-6 fw-semibold col-12 col-md-4 ">
                                <label class="form-label fs-6 fw-semibold required" for="">
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
                            <div class="mb-7  fs-6 fw-semibold col-12 col-md-4 offset-md-4 d-flex justify-content-between align-items-start">
                                <span class="" for="">
                                         Paid Amount:(-)
                                </span>
                                <div class="fv-row">
                                    <input type="text" class="form-control input_number form-select-sm" name="paid_amount" id="paid_amount" value="{{old('paid_amount',0)}}">
                                    {{-- <span id="paid_amount_txt">{{old('paid_amount',0)}}</span>{{$currency['symbol']}} --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="my-15 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-start">
                                <span class="" for="">
                                         Balance Amount:(=)
                                </span>
                                <div class="">
                                    <span id="balance_amount_txt">{{old('balance_amount',0)}}</span> {{$currency['symbol']}}
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
{{--
@include('App.purchase.contactAdd')
@include('App.purchase.newProductAdd') --}}
@endsection

@push('scripts')
{{-- <script src={{asset('customJs/Purchases/contactAdd.js')}}></script> --}}

<script>


$(document).ready(function(){

    $('[data-td-toggle="datetimepicker"]').flatpickr({
             enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
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
@endpush


