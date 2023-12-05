@extends('App.main.navBar')


@section('inventory_icon', 'active')
@section('inventory_show', 'active show')
@section('opening_stock_here_show','here show')
{{-- @section('add_opening_stock_active_show', 'active ') --}}
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Edit Opening Stock</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Opening Stock</li>
        <li class="breadcrumb-item text-muted"><a href="{{route('opening_stock_list')}}">List</a></li>
        <li class="breadcrumb-item text-dark">Edit</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/businessSetting.css")}}>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
    <style>
            #opening_stock_tbody tr td {
                padding: 3px;
            }
        </style>
@endsection



@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl" id="openingStock_container">
            <form action="{{route('openingStockUpdate',$openingStock->id)}}" method="POST" id="openingStock_form">
                @csrf
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h2 class="fs-5">Voucher No : ({{$openingStock->opening_stock_voucher_no}})</h2>
                        </div>
                        <div class="card-body">
                            <div class="row mb-5 flex-wrap">
                                <div class="mb-7 mt-3 col-12 col-md-4 fv-row">
                                    <label class="form-label fs-6 fw-semibold required" for="">
                                        Bussiness Location
                                    </label>
                                    <div class="">
                                        <select name="business_location_id" class="form-select form-select-sm fw-bold "
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="Select Location" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach ($locations as $location)
                                                <option value="{{$location->id}}" @selected($openingStock->business_location_id==$location->id)>{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-7 mt-3 col-12 col-md-4 fv-row">
                                    <label class="form-label fs-6 fw-semibold required" for="purchaseDatee">
                                        Opening Stock Date
                                    </label>
                                    <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                        <input class="form-control form-control-sm" name="opening_date" placeholder="Pick a date"
                                               data-td-toggle="datetimepicker" id="kt_datepicker_1"
                                               value="{{$openingStock->opening_date}}"/>
                                    </div>
                                </div>
                                {{-- <div class="mb-7 mt-3 col-12 col-md-3 fv-row">
                                    <label class="form-label fs-6 fw-semibold" for="">
                                        Status
                                    </label>
                                    <div class="">
                                        <select name="status" class="form-select  fw-bold form-select-sm" data-kt-select2="true"
                                                data-hide-search="false" data-placeholder="Select Location"
                                                data-allow-clear="true" data-kt-user-table-filter="role"
                                                data-hide-search="true">
                                            <option></option>
                                            <option value="pending" @selected($openingStock->status=='pending')>pending</option>
                                            <option value="received" @selected($openingStock->status=='received')>received</option>
                                            <option value="issued" @selected($openingStock->status=='issued')>issued</option>
                                            <option value="confirmed" @selected($openingStock->status=='confirmed')>confirmed</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="mb-7 mt-3 col-4">
                                    <label class="form-label fs-6">
                                        Note
                                    </label>
                                    <textarea class="form-control" name="note" id="" cols="30" rows="3">{{$openingStock->note}}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center mb-8">

                                <div class="col-6 col-md-9">
                                    <div class="input-group quick-search-form p-0">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </div>
                                        <input type="text" class="form-control rounded-start-0" id="searchInput"
                                               placeholder="Search...">
                                        <div
                                            class="quick-search-results overflow-scroll  p-3 position-absolute d-none card w-100 mt-18  card z-3 autocomplete shadow"
                                            id="autocomplete" data-allow-clear="true"
                                            style="max-height: 300px;z-index: 100;"></div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 btn-light-primary btn productQuickAdd my-5 my-lg-0"
                                      type="button"
                                     {{--  data-bs-target="#add_new_product_modal" --}}data-href="{{route('product.quickAdd')}}">
                                    <i class="fa-solid fa-plus me-2 "></i> Add new product
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mt-10" id="openingStockTable">
                                    <!--begin::Table head-->
                                    <thead class="">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0 ">
                                        <th class="min-w-125px">Product Name</th>
                                        <th class="min-w-150px">Qty</th>
                                        <th class="min-w-150px">Unit</th>
                                        <th class="min-w-125px">{{__('product/product.package_qty')}}</th>
                                        <th class="min-w-125px">{{__('product/product.package')}}</th>
                                        <th class="min-w-150px">Purchase Price</th>
                                        <th class="min-w-150px">Subtotal</th>
                                        <th class="min-w-200px">EXP Date</th>
                                        <th class="min-w-250px">Remark</th>
                                        <th class="text-center">
                                            <i class="fa-solid fa-trash text-primary" type="button"></i>
                                        </th>
                                    </tr>
                                    <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fw-semibold text-gray-600" id="opening_stock_tbody">
                                        <tr class="dataTables_empty text-center d-none">
                                            <td colspan="8 ">There is no data to show</td>
                                        </tr>
                                        @php
                                            $osdCount=0;
                                            $productsOnSelectData=[];
                                        @endphp
                                        @foreach ($openingStockDetailsChunks as $openingStockDetails)
                                            @foreach ($openingStockDetails as $key=>$osd)
                                            @if ($osd->product != null)
                                                    @php
                                                        $product=$osd->product;
                                                        $product_variation=$osd->toArray()['product_variation'];
                                                        $osdCount++;
                                                        $newProductData = [
                                                            'id' => $osd->product->id,
                                                            'variation_id' => $product_variation,
                                                            'product_variations' => $osd['product_variation'],
                                                            'uoms' => $osd['product']['uom']['unit_category']['uom_by_category']
                                                        ];
                                                        $indexToReplace = array_search($newProductData['id'], array_column($productsOnSelectData, 'id'));
                                                        if($indexToReplace !== false){
                                                            $productsOnSelectData[$indexToReplace] = $newProductData;
                                                        }else{
                                                            $productsOnSelectData[] = $newProductData;
                                                        }
                                                    @endphp
                                                    <tr class='cal-gp'>
                                                        <td class="d-none">
                                                            <input type="hidden" class="input_number product_id" value="{{$osd->product_id}}" name="opening_stock_details[{{$key}}][product_id]">
                                                            <input type="hidden" class="input_number variation_id" value="{{$osd->variation_id}}" name="opening_stock_details[{{$key}}][variation_id]">
                                                            <input type="hidden" class="input_number" value="{{$osd->id}}" name="opening_stock_details[{{$key}}][opening_stock_detail_id]">
                                                        </td>
                                                        <td>
                                                            <a href="#" class="text-gray-600 text-hover-primary mb-1 ">{{$product->name}}</a><br>
                                                            @if(isset($product_variation['variation_template_value']))
                                                                <span class="my-2 d-block">
                                                                    ({{$product_variation['variation_template_value']['name']}})
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="fv-row">
                                                            <input type="text" class="form-control form-control-sm quantity input_number" placeholder="Quantity" name="opening_stock_details[{{$key}}][quantity]" value="{{round($osd->quantity,2)}}">

                                                        </td>
                                                        <td>
                                                            <select name="opening_stock_details[{{$key}}][uom_id]" class="form-select form-select-sm unit_id "
                                                                data-kt-repeater="unit_select" data-hide-search="true" data-control="select2" data-hide-search="false"
                                                                data-placeholder="Select unit" placeholder="select unit">
                                                                @foreach ($product->uom->unit_category->uomByCategory as $unit)
                                                                <option value="{{$unit['id']}}" @selected($unit['id']==$osd['uom_id'])>{{$unit['name']}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="fv-row">
                                                            <input type="text" class="form-control form-control-sm mb-1 package_qty input_number" placeholder="Quantity"
                                                                name="opening_stock_details[{{$key}}][packaging_quantity]" value="{{arr($osd['packagingTx'],'quantity')}}">
                                                        </td>
                                                        <td class="fv-row">
                                                            <select name="opening_stock_details[{{$key}}][packaging_id]" class="form-select form-select-sm package_id"
                                                                data-kt-repeater="package_select_{{$key}}" data-kt-repeater="select2" data-hide-search="true"
                                                                data-placeholder="Select Package" placeholder="select Package" >
                                                                <option value="">Select Package</option>
                                                                @foreach ($product_variation['packaging'] as $package)
                                                                    <option @selected($package['id']==arr($osd['packagingTx'],'product_packaging_id'))
                                                                        data-qty="{{$package['quantity']}}" data-uomid="{{$package['uom_id']}}" value="{{$package['id']}}">
                                                                        {{$package['packaging_name']}} ({{fquantity($package['quantity'])}}-{{$package['uom']['short_name']}})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm sum uom_price  input_number" name="opening_stock_details[{{$key}}][uom_price]" id="numberonly"  value="{{round($osd->uom_price,2)}}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control sum subtotal  form-control-sm input_number text-dark" name="opening_stock_details[{{$key}}][subtotal]" id="numberonly"  value="{{round($osd->subtotal,2)}}" >
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <span class="input-group-text" data-td-target="#kt_datepicker_1"  data-kt-repeater="datepicker">
                                                                    <i class="fas fa-calendar"></i>
                                                                </span>
                                                                <input class="form-control form-control-sm" name="opening_stock_details[{{$key}}][expired_date]" class="exp_date" placeholder="Pick a date"  data-allow-clear="true" data-kt-repeater="datepicker" value="" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <textarea name="opening_stock_details[{{$key}}][remark]" class="form-control form-control-sm" data-kt-autosize="true"  id="" >{{$osd->remark}}</textarea>
                                                        </td>
                                                        <th><i class="fa-solid fa-trash text-danger deleteRow btn" ></i></th>
                                                    </tr>
                                            @endif

                                            @endforeach
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                                <div class="col-sm-5 col-12 float-end mt-3">
                                    <table>
                                        <tbody>
                                                <tr class="">
                                                    <td class="text-end fw-semibold pe-4">Item :</td>
                                                    <td class="text-start fw-bold item_count">{{$osdCount}}</td>
                                                </tr>
                                                <tr class="">
                                                <td class="text-end fw-bold pe-4 min-w-150px">Total Opening Amount :</td>
                                                    <td class="text-end fw-bold ">
                                                        <input type="text" class="form-control form-control-sm total_opening_amount input_number" name="total_opening_amount" value="{{$openingStock->total_opening_amount}}">
                                                    </td>
                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-2 mb-5">
                        <button type="submit" class="btn btn-primary btn-lg" data-kt-opening-stock-action="submit">Save</button>
                    </div>
            </form>
        </div>
        <!--end::Container-->
    </div>
    <div class="modal modal-lg fade " tabindex="-1" data-bs-focus="false" id="quick_add_product_modal"></div>
    {{-- @include('App.purchase.contactAdd')
    @include('App.purchase.newProductAdd') --}}
@endsection

@push('scripts')
    <script src={{asset('customJs/Purchases/contactAdd.js')}}></script>
    <script src="{{asset('customJs/openingStock/validation.js')}}"></script>

    <script>
        $("#kt_datepicker_1").flatpickr({
            dateFormat: "Y-m-d",
        });
        $('[data-kt-repeater="datepicker"]').flatpickr({
            dateFormat: "Y-m-d",
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
    @include('App.openingStock.JS.openingStockJs')
@endpush

