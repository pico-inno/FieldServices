@extends('App.main.navBar')

@section('sell_icon', 'active')
@section('sell_show', 'active show')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Edit Sale</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Sale</li>
        {{-- <li class="breadcrumb-item text-muted">add</li> --}}
        <li class="breadcrumb-item text-dark">Edit </li>
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
    <div class="container-xxl" id="sale_container">
        <form action={{route('update_sale',$sale->id)}} method="POST" id="sale_form">
            @csrf
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" >
                <div class="fv-row">
                    <input type="hidden" name="business_location_id" id="business_location_id" value="{{$sale->business_location_id}}">
                </div>
                {{-- <div class="col-12 my-5 input-group flex-nowrap">
                    <div class="input-group-text"><i class="fa-solid fa-location-dot"></i></div>
                    <select name="business_location_id" id="business_location_id" class="form-select rounded-0" data-kt-select2="true" data-placeholder="Select locations">
                        @foreach ($locations as $l)
                            <option value="{{$l->id}}" @selected($sale->business_location_id==$l->id)>{{$l->name}}</option>
                        @endforeach
                    </select>
                    <button type="button" class="input-group-text "  data-bs-toggle="tooltip" data-bs-custom-class="tooltip" data-bs-placement="top" data-bs-html="true" title="<span class='text-primary-emphasis'>Business location from where you went to sell </span>">
                        <i class="fa-solid fa-circle-info text-primary"></i>
                    </button>
                </div> --}}
                <div class="card">
                    <div class="card-body  px-5">
                        <div class="row mb-3 flex-wrap">
                            @error('sale_details')
                                <div class="alert-danger alert">
                                    At least one sale item is required to complete sale!
                                </div>
                            @enderror
                            <div class="mb-10 col-12 col-sm-6 mt-3 col-md-3">
                                <label class="form-label fs-7 mb-3 fw-semibold required" for="">
                                    Default Selling Price
                                </label>
                                <select name="price_list"  class="form-select form-select-sm price_group price_list_input" data-kt-select2="true" data-hide-search="true" data-placeholder="Select Selling Price">
                                    <option value="default_selling_price">defalut selling price</option>
                                    @foreach ($priceLists as $PriceList)
                                        <option value="{{$PriceList->id}}">{{$PriceList->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-10 mt-3 col-12 col-sm-6 col-md-3 fv-row">
                                <label class="form-label fs-6 fw-semibold required">Customer:</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-user text-muted"></i>
                                    </div>
                                    <div class="overflow-hidden  flex-grow-1">
                                        <select class="form-select  form-select-sm   fw-bold rounded-0"  name="contact_id" data-kt-select2="true" data-hide-search="false" data-placeholder="Select customer name" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true" >
                                            <option value=""></option>
                                            {{-- <option value="2">Aung Aung</option> --}}
                                            @foreach ($customers as $customer)
                                                <option value="{{$customer->id}}" @selected($customer->id==$sale->contact_id) priceList={{$customer->pricelist_id}}>{{ $customer->first_name ?? $customer->company_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="input-group-text add_supplier_modal"  data-bs-toggle="modal" type="button" data-bs-target="#add_supplier_modal" data-href="{{ url('purchase/add/supplier')}}">
                                        <i class="fa-solid fa-circle-plus fs-3 text-primary"></i>
                                    </button>

                                </div>
                            </div>
                            <div class="mb-10 col-12 mt-3 col-sm-6 col-md-3">
                                <label class="form-label fs-7 mb-3 fw-semibold required" for="">
                                    Currency
                                </label>
                                {{-- @php
                                    dd($sale->toArray())
                                @endphp --}}
                                <select name="currency_id" id="currency_id"  class="form-select form-select-sm" data-kt-select2="true" data-placeholder="Select Currency" data-status="filter" data-hide-search="true" required>
                                    @foreach ($currencies as $c)
                                        <option value="{{$c->id}}" @selected($c->id==$sale->currency_id)>{{$c->name}} - {{$c->symbol}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-10 col-12 mt-3 col-sm-6 col-md-3">
                                <label class="form-label fs-7 mb-3 fw-semibold required" for="">
                                    Status
                                </label>
                                @php
                                    $current_location=App\Models\settings\businessLocation::where('id',$sale->business_location_id)->first();
                                @endphp

                                <div class="overflow-hidden  flex-grow-1">
                                    <select name="status" id="" class="form-select form-select-sm" data-kt-select2="true" data-hide-search="true">
                                        <option value="quotation" @selected($sale->status=='quotation')>Quotation</option>
                                        <option value="draft" @selected($sale->status=='draft')>Draft</option>
                                        <option value="pending" @selected($sale->status=='pending')>Pending</option>
                                        <option value="order" @selected($sale->status=='order')>Ordering</option>
                                        <option value="partial" @selected($sale->status=='partial')>Partial</option>
                                        @if ($current_location->allow_sale_order == 0)
                                            <option value="delivered"   @selected($sale->status=="delivered")>Delivered</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mt-3 mb-10">
                            <div class="col-6 col-md-2 fs-7  fw-semibold  text-primary">
                                Select Products
                            </div>
                            <div class="separator   border-primary-subtle col-md-10 col-6"></div>
                        </div>
                        <div class="row align-items-center mb-5">
                            <div class="col-12 col-md-9">
                                <div class="input-group quick-search-form p-0">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </div>
                                    <input type="text" class="form-control form-control-sm rounded-start-0" id="searchInput" placeholder="Search...">
                                    <div class="quick-search-results overflow-scroll  p-3 position-absolute d-none card w-100 mt-18  card autocomplete shadow" id="autocomplete" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                                </div>
                            </div>
                            <button class="col-4 mt-lg-0 mt-3 col-md-3 btn-sm btn-primary btn add_new_product_modal  productQuickAdd"   data-href="{{route('product.quickAdd')}}" type="button">
                                <i class="fa-solid fa-plus me-2 text-white"></i> Add new product
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-row-dashed fs-6 gy-4 mt-10" id="sale_table">
                                <!--begin::Table head-->
                                <thead class="bg-light">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-8 text-uppercase gs-0 ">
                                        <th class="min-w-150px ps-3">Product </th>
                                        <th class="min-w-125px">Quantity </th>
                                        <th class="min-w-125px">UOM </th>
                                        <th class="min-w-125px">{{__('product/product.package_qty')}}</th>
                                        <th class="min-w-125px">{{__('product/product.package')}}</th>
                                        <th class="min-w-80px" style="max-width: 100px;">Price List</th>
                                        <th class="min-w-125px">Uom Price</th>
                                        <th class="min-w-125px">Subtotal</th>
                                        <th class="min-w-125px {{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">Disc </th>
                                        <th class="min-w-125px {{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">Disc Amount </th>
                                        <th class="pe-1" ><i class="fa-solid fa-trash text-primary" type="button"></i></th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600 data-table-body">
                                    <tr class="dataTables_empty text-center d-none">
                                        <td colspan="8 " >There is no data to show</td>
                                    </tr>
                                    @foreach ($sale_details as $key=>$sale_detail)
                                        @php
                                            $key++;
                                            $product=$sale_detail->product;
                                            $product_variation =$sale_detail->toArray()['product_variation'];
                                            $additionalProduct=$sale_detail->productVariation->additionalProduct;
                                            $parentkey=$sale_detail->parent_id ?array_search($sale_detail->parent_id,array_column($sale_details->toArray(),'id')) +1:$key;
                                        @endphp
                                        <tr class="sale_row sale_row_{{$key}}" data-unid="{{$parentkey}}" data-product="{{$sale_detail->variation_id}}">
                                            <td class="d-flex ps-2">

                                                <div class="ms-2">
                                                    <span>{{$product->name}}</span>
                                                    <span class="text-gray-500 fw-semibold fs-5">{{ $product_variation['variation_template_value']['name']??'' }}</span>
                                                    <br>
                                                    @if ($product->product_type =='storable')
                                                        <span class="current_stock_qty_txt">{{$sale_detail->stock_sum_current_quantity}}</span> <span
                                                            class='smallest_unit_txt'>{{$sale_detail->product->uom['name']}}</span>(s/es)
                                                    @endif
                                                    @if (count($additionalProduct) >0)
                                                        <div class="cursor-pointer me-1 suggestProductBtn text-decoration-underline text-primary user-select-non" data-varid="{{$sale_detail->variation_id}}"
                                                            data-uniqueNameId="{{$key}}" data-parentsaledetailid="{{$sale_detail->id}}" >
                                                            Additional Product
                                                            {{-- <i class="fa-regular fa-lightbulb text-primary me-1 "></i> --}}
                                                        </div>
                                                        <input type="hidden" value="{{$key}}" name="sale_details[{{$key}}][isParent]" />
                                                    @else
                                                        <input type="hidden" value="{{$key}}" name="sale_details[{{$key}}][parentUniqueNameId]" />
                                                    @endif
                                                </div>
                                                <div>
                                                    <input type='hidden' value="{{$sale_detail->product_id}}" class="product_id"
                                                        name="sale_details[{{$key}}][product_id]" />
                                                    <input type='hidden' value="{{$sale_detail->id}}" name="sale_details[{{$key}}][sale_detail_id]" />
                                                    <input type='hidden' value="{{$sale_detail->variation_id}}" class="variation_id"
                                                        name="sale_details[{{$key}}][variation_id]" />
                                                    {{-- <input type='hidden' name="sale_details[{{$key}}][uom_id]" value="{{$sale_detail->uom_id}}" class="uom_id" />
                                                    --}}
                                                </div>
                                            </td>
                                            {{-- <td class="">
                                                <input class="form-control form-control-sm uom_set" placeholder="UOM SET" value="{{$sale_detail->toArray()['uom_set']['uomset_name']}}" disabled/>
                                            </td> --}}
                                            <td>
                                                <span class="text-danger-emphasis  stock_alert_{{$sale_detail->variation_id}} d-none fs-7 p-2">* Out of Stock</span>
                                                <div class="dialer_obj input-group-sm sale_dialer_{{$key}} input-group mb-2 flex-nowrap">
                                                    <button class="btn btn-sm btn-icon btn-outline btn-active-color-danger" type="button" data-kt-dialer-control="decrease">
                                                        <i class="fa-solid fa-minus fs-2"></i>
                                                    </button>
                                                    <input type="text" class="form-control form-control-sm quantity  quantity-{{$sale_detail->variation_id}}"  placeholder="quantity" name="sale_details[{{$key}}][quantity]" value="{{round($sale_detail->quantity,2)}}" data-kt-dialer-control="input"/>

                                                    <button class="btn btn-sm btn-icon btn-outline btn-active-color-primary" type="button" data-kt-dialer-control="increase">
                                                        <i class="fa-solid fa-plus fs-2"></i>
                                                    </button>
                                                </div>

                                            </td>
                                            <td>
                                                <select name="sale_details[{{$key}}][uom_id]" id="" class="form-select form-select-sm  unit_input uom_select" data-kt-repeater="uom_select"  data-hide-search="true"  data-placeholder="Select Unit" required>
                                                    @foreach ($product->toArray()['uom']['unit_category']['uom_by_category'] as $uom)
                                                        <option value="{{$uom['id']}}" @selected($uom['id']==$sale_detail->uom_id)>{{$uom['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="fv-row">
                                                <input type="text" class="form-control form-control-sm mb-1 package_qty input_number" placeholder="Quantity"
                                                    name="sale_details[{{$key}}][packaging_quantity]" value="{{arr($sale_detail['packagingTx'],'quantity')}}">
                                            </td>
                                            <td class="fv-row">
                                                <select name="sale_details[{{$key}}][packaging_id]" class="form-select form-select-sm package_id"
                                                    data-kt-repeater="package_select_{{$key}}" data-kt-repeater="select2" data-hide-search="true"
                                                    data-placeholder="Select Package" placeholder="select Package" >
                                                    <option value="">Select Package</option>
                                                    @foreach ($product_variation['packaging'] as $package)
                                                    <option @selected($package['id']==arr($sale_detail['packagingTx'],'product_packaging_id'))
                                                        data-qty="{{$package['quantity']}}" data-uomid="{{$package['uom_id']}}" value="{{$package['id']}}">
                                                        {{$package['packaging_name']}} ({{fquantity($package['quantity'])}}-{{$package['uom']['short_name']}})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="fv-row">
                                                <select name=""  class="form-select form-select-sm price_group price_list w-180px priceL" data-kt-select2="true" data-hide-search="true" data-placeholder="Select Selling Price" disabled >
                                                    <option value="default_selling_price">defalut selling price</option>b
                                                    @foreach ($priceLists as $priceList)
                                                        <option value="{{$priceList->id}}" @selected($priceList->id==$sale_detail->price_list_id)>{{$PriceList->name}}</option>
                                                    @endforeach
                                                </select>

                                                <input type="hidden" class="price_list_id" name="sale_details[{{$key}}][price_list_id]" value='{{$sale_detail->price_list_id ?? 'default_selling_price'}}'/>
                                            </td>
                                            <input type="hidden" name="sale_details[{{$key}}][lot_serial_no]" class="lot_serial_no" value="{{$sale_detail->lot_serial_no}}">
                                            <td class=" fv-row">
                                                <input type="text" class="form-control form-control-sm uom_price" value="{{$sale_detail->uom_price}}" name="sale_details[{{$key}}][uom_price]">
                                            </td>
                                            <td>
                                                <input type="text" class="subtotal form-control form-control-sm" name="sale_details[{{$key}}][subtotal]" value="{{$sale_detail->subtotal}}" readonly >
                                            </td>
                                            <td class="{{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">
                                                <select name="sale_details[{{$key}}][discount_type]" id="" class="form-select form-select-sm discount_type" data-kt-repeater="select2"  data-hide-search="true">
                                                    <option value="fixed" @selected($sale_detail->discount_type=='fixed')>fixed</option>
                                                    <option value="percentage" @selected($sale_detail->discount_type=='percentage')>Percentage</option>
                                                </select>
                                            </td>
                                            <td class="{{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">
                                                <input type="text" class="form-control form-control-sm per_item_discount" value="{{round($sale_detail->per_item_discount,2)??0}}" name="sale_details[{{$key}}][per_item_discount]" placeholder="Discount amount">
                                                <div class='mt-3 d-none'>Discount : <span class="line_discount_txt">0</span>{{$currency->symbol}}</div>
                                                <input type="hidden" class="line_subtotal_discount" name="sale_details[{{$key}}][line_subtotal_discount]" value="0">
                                                {{-- <input type="hidden" class="currency_id" name="sale_details[{{$key}}][currency_id]" value="{{$sale_detail->currency_id}}"> --}}
                                            </td>
                                            <th><i class="fa-solid fa-trash text-danger deleteRow" type="button" ></i></th>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                        <div class="col-6 float-end mt-3">
                            <table class="col-12 ">
                                <tbody>
                                    <tr>
                                        <th class="text-end">Items: <span class="fw-medium fs-5 total_item">0</span></th>
                                        {{-- <th class="d-flex justify-content-center align-items-center">
                                            <span class="min-w-100px fw-semibold">Sale Amount:</span>
                                            <div class="">
                                                <input type="text" name="sale_amount" class="sale_amount_input input_number form-control form-control-sm" value="{{$sale->sale_amount }}" >
                                            </div>
                                        </th> --}}
                                        {{-- <input type="hidden" name="total_line_discount" class="total_line_discount" value="0"> --}}
                                        {{-- <input type="hidden" name="currency_id" value="{{$currency['id']}}"> --}}
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
                                <span class="min-w-200px pe-2 text-gray-800" for="">
                                    Sale Amount:(=)Ks
                                </span>
                               <input type="text" name="sale_amount" class="sale_amount_input input_number form-control form-control-sm fs-7" value="{{$sale->sale_amount }}" >
                            </div>
                        </div>
                        <div class="row justify-content-end {{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">
                            <div class="fs-6 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2 text-gray-800" for="">
                                    Total Item Discount:(-)Ks
                                </span>
                                <input type="number" name="total_item_discount"  id="total_item_discount" class="form-control form-control-sm fs-7 total_item_discount" value="{{$sale->total_item_discount}}" placeholder="0" readonly />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-7  col-12 col-md-3 col-12 offset-md-1">
                                <label class="form-label fs-7 fw-semibold text-gray-800" for="">
                                    Extra Discount Type
                                </label>
                                <select name="extra_discount_type" id="" class="form-select form-select-sm extra_discount_type" data-control="select2" data-hide-search="true">
                                    <option value="fixed" @selected($sale->extra_discount_type=='fixed')>Fixed</option>
                                    <option value="percentage" @selected($sale->extra_discount_type=='percentage')>Percentage</option>
                                </select>
                            </div>
                            <div class="mb-7  col-12 col-md-3 col-12 ">
                                <label class="form-label fs-7 fw-semibold text-gray-800" for="">
                                  Extra Discount Amount
                                </label>
                                 <input type="text" name="extra_discount_amount" class="form-control form-control-sm extra_discount_amount" value="{{$sale->extra_discount_amount ?? 0}}">
                            </div>
                            <div class="fs-7  fw-semibold col-12 col-md-5 d-flex justify-content-end align-items-center">
                                <span class="min-w-200px pe-2 text-gray-800" for="">
                                    Discount :(-)Ks
                                </span>
                                <input type="text" class="form-control form-control-sm input_number max-w-100px extra_discount" id="extra_discount" value="{{$sale->extra_discount}}" disabled>
                            </div>
                        </div>
                        <div class="row justify-content-end mt-2">
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2 text-gray-800" for="">
                                    Total Sale Amount:(=)Ks
                                </span>
                                <input type="text" name="total_sale_amount" class="form-control form-control-sm input_number max-w-100px total_sale_amount" value="{{$sale->total_sale_amount}}">
                            </div>
                        </div>
                        {{-- <div class="row justify-content-end mt-2">
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2 text-gray-800" for="">
                                    Paid Amount:(=)Ks
                                </span>
                                <input type="text" name="paid_amount" class="form-control form-control-sm input_number max-w-100px paid_amount_input" id="total_paid_amount" value="{{$sale->paid_amount}}">
                            </div>
                        </div>
                        <div class="row justify-content-end mt-2">
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2 text-gray-800" for="">
                                    Balance Amount:(=)Ks
                                </span>
                                <input type="text" name="balance_amount" class="form-control form-control-sm input_number max-w-100px balance_amount_input" id="total_balance_amount" value="{{$sale->balance_amount}}">
                            </div>
                        </div> --}}
                    </div>
                </div>

                {{-- <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-end mb-3">
                            <div class="fs-7 fw-semibold col-12 col-md-3 d-flex justify-content-between align-items-center mb-5 mb-md-0">
                                <select name="payment_account" id="payment_accounts" class="form-select form-select-sm" data-kt-select2="true" data-placeholder="select payment account">
                                    <option disabled selected>Select Account</option>
                                    @php
                                        $paymentAccounts=App\Models\paymentAccounts::where('currency_id',$sale->currency->id)->get();
                                    @endphp
                                    @foreach ($paymentAccounts as $p)
                                        <option value="{{$p->id}}" selected>{{$p->name}} ({{$p->account_number}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Paid Amount:(=)Ks
                                </span>
                                <input type="text" name="paid_amount" class="form-control form-control-sm input_number max-w-100px paid_amount_input" id="total_paid_amount" value="{{$sale->paid_amount}}">
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Balance Amount:(=)Ks
                                </span>
                                <input type="text" name="balance_amount" class="form-control form-control-sm input_number max-w-100px balance_amount_input" id="total_balance_amount" value="{{$sale->balance_amount}}">
                            </div>
                        </div>

                    </div>
                </div> --}}

            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary save_btn" data-kt-sale-action="submit">Save</button>
                 <button type="submit" class="btn btn-success save_btn" data-kt-sale-action="submit">Save & Print</button>
            </div>
        </form>
    </div>
    <!--end::Container-->
</div>

<div class="modal modal-lg fade " tabindex="-1"  data-bs-focus="false"  id="quick_add_product_modal" ></div>
<div class="modal fade" id="suggestionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog w-lg-600px modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-gray-800">Product Suggestion</h5>
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
@include('App.sell.sale.subscribeModel')

@endsection

@push('scripts')
{{-- <script src={{asset('customJs/Purchases/contactAdd.js')}}></script> --}}
{{-- @include('App.purchase.contactAdd') --}}
    <script>
        $('[data-kt-select2="select2"]').select2();
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

        $(document).on('click', '.productQuickAdd', function(){
            $url=$(this).data('href');

            loadingOn();
            $('#quick_add_product_modal').load($url, function() {
                $(this).modal('show');
                loadingOff();
            });
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
@include('App.sell.js.saleJs')

<script src={{asset('customJs/Ajax/getAccountByCurrency.js')}}></script>
<script src="{{asset('customJs/sell/saleValidator.js')}}"></script>
@endpush


