@extends('App.main.navBar')

@section('purchases_icon', 'active')
@section('pruchases_show', 'active show')

@section('edit_purchase')

<div class="menu-item">
    <a class="menu-link active px-0" href="#">
        <span class="menu-icon">
                <i class="fa-solid fa-cart-plus"></i>
        </span>
        <span class="menu-title px-0">Edit Purchase</span>
    </a>
</div>
@endsection
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Edit Purchase</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Purchases</li>
        <li class="breadcrumb-item text-muted">Order</li>
        <li class="breadcrumb-item text-dark">Edit</li>
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
@php
    $extra_discount_amount=$purchase->extra_discount_amount ?? 0;
    $extra_discount_amount_text=number_format($extra_discount_amount,2);

    $total_discount_amount=$purchase->total_discount_amount ?? 0;
    $total_discount_amount_text=number_format($total_discount_amount,2);

    $purchase_expense=$purchase->purchase_expense ?? 0;
    $purchase_expense_text=number_format($purchase_expense,2);

    $total_purchase_amount=$purchase->total_purchase_amount;
    $total_purchase_amount_text=number_format($purchase->total_purchase_amount,2);

    $paid_amount=$purchase->paid_amount;
    $paid_amount_text=number_format($paid_amount,2);

    $balance_amount=number_format($purchase->balance_amount ,2);
    $balance_amount_text=number_format($purchase->balance_amount,2);

    $total_line_discount=$purchase->total_line_discount ?? 0;
    $total_line_discount_text=number_format($total_line_discount,2);

    $purchase_amount=$purchase->purchase_amount;
    $purchase_amount_for_txt=number_format($purchase->purchase_amount,2);
@endphp
<div class="content d-flex flex-column flex-column-fluid" id="purchase_container">
    <div class="container-xxl">
        <form action="{{route('purchase_update',$purchase->id)}}" method="POST"  id="purchase_form">
            @csrf
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" id="payment">
                <div class="card">
                    <div class="card-body">
                         <h4 class="mb-5">Voucher No : <span class="text-primary"> {{$purchase->purchase_voucher_no}}</span> </h4>
                        @error('details')
                            <div class="alert-danger alert">
                                At least one purchase item is required to complete purchase!
                            </div>
                        @enderror
                        <div class="row mb-5 flex-wrap">
                            <!--begin::Input group-->
                            <div class="mb-7 mt-3 col-12 col-md-3 fv-row">
                                <label class="form-label fs-6 fw-semibold required">Contact:</label>
                                <div class="input-group input-group-sm flex-nowrap input-group-sm">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-user text-muted"></i>
                                    </div>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="contact_id" class="form-select form-select-sm  fw-bold rounded-start-0" data-kt-select2="true" data-hide-search="false" data-placeholder="Select supplier" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->id}}" @selected($supplier->id==old('contact_id',$purchase->contact_id))>{{$supplier->company_name ?? $supplier->getFullNameAttribute()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <button class="input-group-text add_supplier_modal"  data-bs-toggle="modal" type="button" data-bs-target="#add_supplier_modal" data-href="{{ url('purchase/add/supplier')}}">
                                        <i class="fa-solid fa-circle-plus fs-3 text-primary"></i>
                                    </button> --}}
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold required" for="">
                                    Business Location
                                </label>
                                <div class=" fv-row">
                                    <select class="form-select form-select-sm  fw-bold " name="business_location_id" id="businessLocationId" data-kt-select2="true"
                                         data-placeholder="Select Location" data-kt-user-table-filter="role" data-allow-clear="true" >
                                        <option></option>
                                        @foreach ($locations as $l)
                                        <option value="{{$l->id}}" @selected($l->id==$purchase->business_location_id)>{{businessLocationName($l)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-7 mb-3 fw-semibold required" for="">
                                    Currency
                                </label>
                                <select name="currency_id" id="currency_id"  class="form-select form-select-sm" data-kt-select2="true" data-placeholder="Select Currency" placeholder="Select Currency" data-status="filter" data-hide-search="true" required>
                                    <option  disabled selected>Select Currency</option>
                                    @foreach ($currencies as $c)
                                        <option value="{{$c->id}}" @selected($c->id==$purchase->currency_id)>{{$c->name}} - {{$c->symbol}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3" >
                                <label class="form-label fs-6 fw-semibold required" for="">
                                    Purchase Status:
                                </label>
                                <div class="  fv-row">
                                    <select name="status" id="OrderStatus" class="form-select form-select-sm  fw-bold " data-status="filter" data-kt-select2="true" data-hide-search="false" data-placeholder="Select Status" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                        <option></option>
                                        <option value="request" @selected(old('status',$purchase->status)=='request')>Request</option>
                                        <option value="pending"  @selected(old('status',$purchase->status)=='pending')>Pending</option>
                                        <option value="order"  @selected(old('status',$purchase->status)=='order')>Order</option>
                                        <option value="partial" @selected(old('status',$purchase->status)=='partial')>Partial</option>
                                        <option value="received"  @selected(old('status',$purchase->status)=='received')>Received</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold " for="orderDate">
                                    Address:
                                </label>
                                @php
                                    $selected_supplier= Arr::first($suppliers->toArray(),function($supplier) use ($purchase){
                                        return $supplier['id']==$purchase->contact_id;
                                    });
                                    // $selected_supplier=array_key_first(array $selected_supplier);
                                @endphp
                                <div class="fs-6 text-gray-700 contact_id">
                                    {{$selected_supplier['address_line_1']}}
                                    {{$selected_supplier['address_line_2']}}<br>
                                    {{$selected_supplier['city']}} {{$selected_supplier['state']}} {{$selected_supplier['country']}}<br>
                                    {{$selected_supplier['zip_code']}}
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold required" for="purchaseDatee">
                                    Purchase Date:
                                </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"  >
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input class="form-control form-control-sm" name="purchased_at" placeholder="Pick a date" data-td-toggle="datetimepicker"  id="kt_datepicker_1" value="{{old('purchased_at',$purchase->purchased_at)}}" />
                                </div>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-3">
                                <label class="form-label fs-6 fw-semibold required" for="received_at">
                                    {{__('purchase.received_date')}}:
                                </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input class="form-control" name="received_at" placeholder="Pick a date" data-td-toggle="datetimepickerallowClear"
                                        id="received_at" value="{{old('received_at',arr($purchase,'received_at'))}}" />
                                </div>
                            </div>
                            {{-- <div class="mb-7 mt-3 col-12 col-md-4">
                                <label class="form-label fs-6 fw-semibold required" for="">
                                    Pay term:
                                </label>
                                <div class="input-group flex-nowrap">
                                    <div class="input-text">
                                        <input type="text" class="form-control form-control-sm rounded-end-0" placeholder="Pay term:">
                                    </div>
                                    <select class="form-select form-select-sm  fw-bold rounded-start-0 border-gray-500" data-kt-select2="true" data-hide-search="false" data-placeholder="Select Location" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                        <option>Please Select</option>
                                        <option value="Administrator">Months</option>
                                        <option value="Analyst">Days</option>
                                    </select>
                                </div>
                            </div> --}}
                            {{-- <div class="mb-7 mt-3 col-12 col-md-4 browseLogo">
                                <label class="fs-6 fw-semibold form-label " for="update_logo">
                                    <span class="required">Attach Document:</span>
                                </label>
                                <div class="input-group browseLogo">
                                    <input type="file" class="form-control form-control-sm" id="update_logo" name="update_logo">
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
                            </div> --}}
                            {{-- <div class="row">
                                <div class="col-12 col-md-4 mb-3">
                                    <div class="col-12 mb-5">
                                        <label for="" class="form-label">Test:</label>
                                        <input type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-12">
                                        <label for="" class="form-label">Purchase Order:</label>
                                        <select class="form-select form-select-sm form-select form-select-sm " data-control="select2" data-hide-search="false" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true" multiple="multiple">
                                            <option></option>
                                            <option value="1">Option 1</option>
                                            <option value="2">Option 2</option>
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                    </div>
                </div>
                <div class="card border border-primary-subtle border-top-2 border-left-0 border-right-0 border-bottom-0">
                    <div class="card-body px-5">
                        <div class="row align-items-center mb-8">
                            <div class="col-5 col-12 col-md-2 btn-primary btn add_new_product_modal  my-5 my-lg-0 d-none"   data-bs-toggle="modal" type="button" data-bs-target="#add_new_product_modal" data-href="{{ url('purchase/add/supplier')}}">
                                <i class="fa-solid fa-plus me-2 text-white"></i> Import Products
                            </div>
                            <div class="col-6 col-md-7">
                                <div class="input-group quick-search-form p-0">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </div>
                                    <input  onkeypress="preventEnterSubmit(event)"  type="text" class="form-control form-control-sm rounded-start-0" id="searchInput" placeholder="Search...">
                                    <div class="quick-search-results overflow-scroll  p-3 position-absolute d-none card w-100 mt-18  card z-3 autocomplete shadow" id="autocomplete" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                                </div>
                            </div>
                            <button type="button" class="col-12 col-md-3 btn-light-primary btn btn-sm add_new_product_modal my-5 my-lg-0 productQuickAdd"   data-href="{{route('product.quickAdd')}}"  >
                                <i class="fa-solid fa-plus me-2 "></i> Add new product
                            </button>
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
                                <thead class=" bg-light p-2">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-8 text-uppercase gs-0">
                                        {{-- <th class="min-w-50px">#</th> --}}
                                        <th class="min-w-125px ps-1" style="max-width: 125px">Product Name</th>
                                        <th class="min-w-125px">Qty </th>
                                        <th class="min-w-100px">Unit</th>
                                        <th class="min-w-125px">{{__('product/product.package_qty')}}</th>
                                        <th class="min-w-125px">{{__('product/product.package')}}</th>
                                        <th class="min-w-125px">UOM Price</th>
                                        <th class="min-w-80px {{$setting->enable_line_discount_for_purchase == 1 ? '' :'d-none'}}">Disc Type</th>
                                        <th class="min-w-125px {{$setting->enable_line_discount_for_purchase == 1 ? '' :'d-none'}}">Disc Amount</th>
                                        <th class="min-w-125px"> Expense</th>
                                        <th class="min-w-125px">Subtotal <br>
                                             {{-- with Expense & Disc --}}
                                        </th>
                                        <th class="min-w-125px d-none">Subtotal</th>
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
                                        <tr class="dataTables_empty text-center {{count($purchase_detail)!=0? 'd-none' : ''}}">
                                            <td colspan="8 " >There is no data to show</td>
                                        </tr>
                                    @foreach ($purchase_detail as $key=>$pd)
                                    @php
                                        $pDecimal=$setting->currency_decimal_places;
                                        $product=$pd->product;
                                        $product_variation=$pd->toArray()['product_variation'];

                                        $varationName=$product_variation['variation_template_value']['name'] ?? '';
                                        $variationValueCount=count($product_variation['variation_values']);
                                        if($variationValueCount > 0){
                                            $varationName="";
                                            foreach ($product_variation['variation_values'] as $index=>$vv) {
                                                $separator= $index == 0 || $index ==$variationValueCount ? '': ',';
                                                $varationName.= $separator.' '.$vv['variation_template_value']['name'];
                                            }
                                        };


                                        $per_item_discount=$pd->per_item_discount;
                                        $per_item_discount_text=number_format($pd->per_item_discount,$pDecimal);


                                        $subtotal_with_discount=$pd->subtotal_with_discount;
                                        $subtotal_with_discount_text=number_format($subtotal_with_discount,$pDecimal);

                                        $uom_price=$pd->uom_price;

                                        $per_item_expense=$pd->per_item_expense;
                                        $per_item_expense_text=number_format($per_item_expense,$pDecimal);

                                        $subtotal_with_expense=$pd->subtotal_with_expense;
                                        $subtotal_with_expense_text=number_format($pd->subtotal_with_expense,$pDecimal);

                                        $subtotal_with_tax=$pd->subtotal_with_tax;
                                        $subtotal_with_tax_text=number_format($pd->subtotal_with_tax,$pDecimal);
                                    @endphp
                                        <tr class='cal-gp'>
                                            <td class="d-none">
                                                {{-- <a href='' class='text-gray-800 text-hover-primary mb-1'>{{$key+1}}</a> --}}
                                                <input type="hidden" class="input_number product_id" value="{{$pd->product_id}}" name="purchase_details[{{$key}}][product_id]">
                                            </td>
                                            <td class="d-none">
                                                <input type="hidden" class="input_number variation_id" value="{{$pd->variation_id }}" name="purchase_details[{{$key}}][variation_id]">
                                                <input type="hidden" class="input_number" value="{{$pd->id}}" name="purchase_details[{{$key}}][purchase_detail_id]">
                                            </td>
                                            <td>
                                                <a href="#" class="text-gray-600 text-hover-primary ">
                                                    {{$product['name']}}
                                                </a>
                                                 @if($varationName)
                                                        <span class="my-2 d-block">
                                                            ({{$varationName}})
                                                        </span>
                                                 @endif
                                            </td>
                                            <td class="fv-row">
                                                <input type="text" class="form-control form-control-sm  purchase_quantity input_number" name="purchase_details[{{$key}}][quantity]" value="{{round($pd->quantity,2)}}">

                                            </td>
                                            <td>
                                                <select  name="purchase_details[{{$key}}][purchase_uom_id]" class="form-select form-select-sm unit_id " data-kt-repeater="unit_select" data-hide-search="false" data-placeholder="Select unit"   placeholder="select unit">
                                                    @foreach ($product->toArray()['uom']['unit_category']['uom_by_category'] as $unit)
                                                        <option value="{{$unit['id']}}" @selected($unit['id']==$pd['purchase_uom_id'])>{{$unit['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </td>


                                            <td class="fv-row">
                                                <input type="text" class="form-control form-control-sm mb-1 package_qty input_number" placeholder="Quantity"
                                                    name="purchase_details[{{$key}}][packaging_quantity]" value="{{arr($pd['packagingTx'],'quantity')}}">
                                            </td>
                                            <td class="fv-row">
                                                <select name="purchase_details[{{$key}}][packaging_id]" class="form-select form-select-sm package_id"
                                                    data-kt-repeater="package_select_{{$key}}" data-kt-repeater="select2" data-hide-search="true"
                                                    data-placeholder="Select Package" placeholder="select Package" >
                                                    <option value="">Select Package</option>

                                                        @if (isset($product_variation['packaging']) && count($product_variation['packaging']) > 0)
                                                        @foreach ($product_variation['packaging'] as $package)
                                                            <option @selected($package['id'] == arr($pd['packagingTx'],"product_packaging_id")) data-qty="{{arr($package,'quantity')}}" data-uomid="{{$package['uom_id'] ?? ''}}" value="{{$package['id']}}">{{$package['packaging_name']}} ({{fquantity($package['quantity'])}}-{{$package['uom']['short_name'] ?? ''}})</option>
                                                        @endforeach

                                                        @endif
                                                </select>
                                            </td>

                                            <td>

                                                <div class="input-group input-group-sm">

                                                    <input type="text" class="form-control form-control-sm sum uom_price  input_number" name="purchase_details[{{$key}}][uom_price]" id="numberonly"  value="{{fprice($uom_price)}}">
                                                    <span class="input-group-text currencySymbol"> {{$currency['symbol']}}</span>
                                                </div>
                                            </td>
                                            <td class="{{$setting->enable_line_discount_for_purchase == 1 ? '' :'d-none'}}"">
                                                <select  name="purchase_details[{{$key}}][discount_type]" class="form-select form-select-sm mb-1 discount_type" data-kt-repeater="select2" data-hide-search="true" data-placeholder="Discount Type">
                                                    <option value="fixed" @selected($pd->discount_type==='fixed')>Fixed</option>
                                                    <option value="percentage" @selected($pd->discount_type==='percentage')>perc (%)</option>
                                                </select>
                                            </td>
                                            <td class="{{$setting->enable_line_discount_for_purchase == 1 ? '' :'d-none'}}"">

                                                <input type="text" class="form-control form-control-sm sum discount_amount per_item_discount input_number" name="purchase_details[{{$key}}][per_item_discount]" value="{{fprice($per_item_discount)}}">
                                                <div class='mt-3 d-none'>Discount : <span class="line_discount_txt">0</span><span class="currencySymbol"> {{$currency['symbol']}}</span></div>
                                                <input type="hidden" class="form-control form-control-sm sum line_discount"  value="0">
                                                <input type="hidden" class="subtotal_with_discount input_number" name="purchase_details[{{$key}}][subtotal_with_discount]"  value="{{$subtotal_with_discount}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm sum per_item_expense input_number" name="purchase_details[{{$key}}][per_item_expense]" value="{{fprice($per_item_expense)}}">
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                <input type="text" class="form-control form-control-sm sum subtotal_with_expense  input_number" name="purchase_details[{{$key}}][subtotal_with_expense]" value="{{fprice($subtotal_with_expense)}}">
                                                    <span class="input-group-text currencySymbol"> {{$currency['symbol']}}</span>
                                                </div>
                                            </td>

                                            <td class="d-none">
                                            <span class="subtotal_with_tax ms-2">{{$subtotal_with_tax_text}}</span> <span class="currencySymbol"> {{$currency['symbol']}}</span>
                                            </td>

                                            <input type="hidden" class="form-control form-control-sm sum  input_number subtotal_with_tax_input" name="purchase_details[{{$key}}][subtotal_with_tax]" value="{{fprice($subtotal_with_tax)}}">
                                            <th><i class="fa-solid fa-trash text-danger deleteRow btn" ></i></th>
                                        </tr>
                                    @endforeach

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
                                                <span class="total_line_discount">{{$total_line_discount}} </span> <span class="currencySymbol"> {{$currency['symbol']}}</span>
                                            </td>
                                            <input type="hidden" name="total_line_discount" class="total_line_discount_input input_number" id="" class="" value="{{$total_line_discount}}">
                                        </tr>
                                    <tr class="mb-2">
                                        <th class=" fw-semibold">Net Total Purchase Amount :</th>
                                        <td class="rowSum text-left fs-6 fw-semibold text-end" id=''>
                                            <span class="net_purchase_total_amount_text"> {{$purchase_amount_for_txt}} </span> <span class="currencySymbol"> {{$currency['symbol']}}</span>
                                        </td>
                                        <input type="hidden" name="purchase_amount" class="net_purchase_total_amount input_number" id="" class="" value="{{$purchase_amount}}">
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body" >
                        <div class="row">
                            <div class="mb-7 mt-3 col-12 col-md-4">
                                <label class="form-label fs-6 fw-semibold" for="">
                                    Discount Type
                                </label>
                                <select name="extra_discount_type" id="extra_discount_type" class="form-select form-select-sm" data-control="select2">
                                    <option value="fixed" @selected($purchase->extra_discount_type=="fixed")>Fixed</option>
                                    <option value="percentage" @selected($purchase->extra_discount_type=="percentage")>Percentage</option>
                                </select>
                            </div>
                            <div class="mb-7 mt-3 col-12 col-md-4">
                                <label class="form-label fs-6 fw-semibold" for="">
                                    Discount Amount
                                </label>
                                <input type="text" class="form-control input_number form-select-sm extra_discount_amount"   id="extra_discount_amount" name="extra_discount_amount" value="{{fprice($extra_discount_amount)}}">
                                <input type="hidden" class="extra_discount" id="extra_discount">
                                <div class='mt-2'>Discount : <span class="extra_discount_txt">0</span> <span class="currencySymbol"> {{$currency['symbol']}}</span></div>
                            </div>
                            <div class="mb-7 col-12 col-md-4 fw-semibold fs-6  d-flex justify-content-between align-items-end">
                                <span >
                                    Discount:(-)
                                </span>
                                <div class="">
                                    <span class="extra_discount_txt"></span><span class="currencySymbol"> {{$currency['symbol']}}</span>
                                </div>
                                <input type="hidden" class="form-control input_number form-select-sm" id="total_discount_amount"  name="total_discount_amount" value="{{$total_discount_amount}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-7 mt-3 col-12 col-md-4">
                                <label class="form-label fs-6 fw-semibold" for="">
                                    Purchase Expense
                                </label>
                                <input type="text" class="form-control input_number form-select-sm" id="purchase_expense" name="purchase_expense" value="{{fprice($purchase_expense)}}">
                            </div>
                            <div class="mb-7 col-12 fw-semibold fs-6 col-md-4 offset-md-4 d-flex justify-content-between align-items-end">
                                <span class="" for="">
                                        Purchase Expense:(+)
                                </span>
                                <div class="">
                                    <span id="purchase_expense_txt">{{$purchase_expense_text}}</span><span class="currencySymbol"> {{$currency['symbol']}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="my-15 col-12 col-md-4 fw-semibold fs-6 offset-md-8 d-flex justify-content-between align-items-end">
                                <span class=" " for="">
                                        total Purchase Amount:(=)
                                </span>
                                <div class="">
                                    <span id="total_purchase_amount_txt">{{$total_purchase_amount_text}}</span><span class="currencySymbol"> {{$currency['symbol']}}</span>
                                </div>
                            </div>
                            <input type="hidden" name="total_purchase_amount" id="total_purchase_amount" value="{{$total_purchase_amount}}">
                        </div>
                        {{-- <div class="row">
                            <div class="mb-7 col-12 col-md-4">
                                <label class="form-label fs-6 fw-semibold" for="">
                                    Paid Amount
                                </label>
                                <input type="text" class="form-control input_number form-select-sm" name="paid_amount" id="paid_amount" value="{{$paid_amount}}">
                            </div>
                            <div class="mb-7  fs-6 fw-semibold col-12 col-md-4 offset-md-4 d-flex justify-content-between align-items-end">
                                <span class="" for="">
                                        Paid Amount:(-)
                                </span>
                                <div class="">
                                    <span id="paid_amount_txt">{{$paid_amount_text}}</span><span class="currencySymbol">{{$currency['symbol']}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="my-15 fs-6 fw-semibold col-12 col-md-4 offset-md-8 d-flex justify-content-between align-items-end">
                                <span class="" for="">
                                        Balance Amount:(=)
                                </span>
                                <div class="">
                                    <span id="balance_amount_txt">{{$balance_amount_text}}</span> <span class="currencySymbol">{{$currency['symbol']}}</span>
                                </div>
                                <input type="hidden" class="form-control input_number" name="balance_amount form-select-sm" id="balance_amount" value="{{$balance_amount}}">
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-12 text-center mt-5 mb-5">
                <button type="submit" class="btn btn-primary btn-lg"  data-kt-purchase-action="submit">Save</button>
            </div>
        </form>
    </div>
    <!--end::Container-->
</div>

<div class="modal modal-lg fade " tabindex="-1"  data-bs-focus="false"  id="quick_add_product_modal" ></div>
{{-- @include('App.purchase.contactAdd') --}}
{{-- @include('App.purchase.newProductAdd') --}}
@endsection

@push('scripts')
<script src={{asset('customJs/Ajax/getAccountByCurrency.js')}}></script>
<script src={{asset('customJs/Purchases/contactAdd.js')}}></script>
<script src={{asset('customJs/Purchases/purchaseValidator.js')}}></script>
    <script>
        // var quill = new Quill('#kt_docs_quill_basic', {
        //     modules: {
        //         toolbar: [
        //             [{
        //                 header: [1, 2, false]
        //             }],
        //             ['bold', 'italic', 'underline'],
        //             ['image', 'code-block']
        //         ]
        //     },
        //     placeholder: 'Type your text here...',
        //     theme: 'snow' // or 'bubble'
        // });
    // $("#kt_datepicker_1").flatpickr({
    //     dateFormat: "d-m-Y",
    // });
    // $("#kt_datepicker_2").flatpickr({
    //     dateFormat: "d-m-Y",
    // });
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
        console.log($(this).val(),received_at);
        if(received_at=='' && $(this).val()=='received'){
            var currentDate = new Date();
            var formattedCurrentDate = fp.formatDate(currentDate, "Y-m-d H:i");
            fp.setDate(formattedCurrentDate);
        }
    })

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



$(document).ready(function() {
        $('[data-kt-repeater="select2"]').select2();
        $('[data-kt-repeater="unit_select"]').select2();
        $('[data-kt-repeater="uom_select2"]').select2();
        // Re-init flatpickr
        $('#purchase_table tbody').find('[data-kt-repeater="datepicker"]').flatpickr();
        // $('[data-kt-select="select2"]').select2();
        $('[data-kt-repeater="datepicker"]').flatpickr();


        let locations=@json($locations);
        limitStatusBylocation("{{$purchase->business_location_id ?? 0}}" ,false);

                $('#businessLocationId').on('change',function(){
                    console.log('here');
                    limitStatusBylocation($(this).val());
                });

                function limitStatusBylocation($id,triggerOrder=true) {
                    let location=locations.filter(l => {
                    return l.id==$id;
                    })[0];
                    if(location.allow_purchase_order=='1'){
                        $('[data-status="filter"] option[value="received"]').prop('disabled', true);
                        $('[data-status="filter"] option[value="confirmed"]').prop('disabled', true);
                        if(triggerOrder){
                            $('#OrderStatus').val('order').trigger('change');
                        }
                    }else{
                        $('[data-status="filter"] option:disabled').prop('disabled', false);
                    }

                }

    $(document).on('click', '.productQuickAdd', function(){
        $url=$(this).data('href');

        loadingOn();
        $('#quick_add_product_modal').load($url, function() {
            $(this).modal('show');
            loadingOff();
        });
    });

});

</script>
@include('App.purchase.Js.calPurchase')
@endpush


