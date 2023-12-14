@extends('App.main.navBar')

@section('styles')
    {{-- css file for this page --}}
@endsection

@section('inventory_icon', 'active')
@section('inventory_show', 'active show')
@section('stock_adjustment_here_show', 'here show')
@section('stock_adjustment_active_show', 'active show')

@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
@endsection

@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('adjustment.edit_adjustment')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('adjustment.adjustment')}}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('stock-adjustment.index') }}" class="text-muted text-hover-primary">{{__('adjustment.list')}}</a>
        </li>
        <li class="breadcrumb-item text-dark">{{$stockAdjustment->adjustment_voucher_no}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <form action="{{route('stock-adjustment.update', $stockAdjustment->id)}}" method="POST">
                @csrf
                @method('put')
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
{{--                    <div class="col-12 my-5 mb-2 input-group flex-nowrap">--}}
{{--                        <div class="input-group-text"><i class="fa-solid fa-location-dot"></i></div>--}}
{{--                        <select name="business_location" id="business_location_id"--}}
{{--                                class="form-select fw-bold rounded-0"--}}
{{--                                data-kt-select2="true" data-hide-search="false"--}}
{{--                                data-placeholder="Select Location" data-allow-clear="true"--}}
{{--                                data-kt-user-table-filter="role" data-hide-search="true">--}}
{{--                            <option></option>--}}
{{--                            @foreach ($locations as $location)--}}
{{--                                <option  @selected($stockAdjustment->business_location == $location->id) value="{{$location->id}}">{{$location->name}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                        <button type="button" class="input-group-text "  data-bs-toggle="tooltip" data-bs-custom-class="tooltip" data-bs-placement="top" data-bs-html="true" title="<span class='text-primary-emphasis'>business location where you went to admustment </span>">--}}
{{--                            <i class="fa-solid fa-circle-info text-primary"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
                    <div class="card card-flush py-4">

                        <div class="card-body pt-0">
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label required" for="">
                                        {{__('adjustment.location')}}
                                    </label>
                                    <div class="input-group flex-nowrap">
                                        <div class="input-group-text"><i class="fa-solid fa-location-dot"></i></div>
                                        <select name="business_location" id="business_location_id"
                                                {{ $stockAdjustment->status == 'completed' ? 'disabled' : '' }}
                                                class="form-select fw-bold rounded-0 form-select-sm"
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="{{__('adjustment.placeholder_location')}}"
                                                 data-hide-search="true">
                                            <option></option>
                                            @foreach ($locations as $location)
                                                <option value="{{$location->id}}"  @selected(old('business_location', $stockAdjustment->business_location) == $location->id)>{{businessLocationName($location)}}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="input-group-text "  data-bs-toggle="tooltip" data-bs-custom-class="tooltip" data-bs-placement="top" data-bs-html="true" title="<span class='text-primary-emphasis'>{{__('adjustment.location_tip')}}</span>">
                                            <i class="fa-solid fa-circle-info text-primary"></i>
                                        </button>
                                        @if($stockAdjustment->status == 'completed')
                                            <input type="hidden" value="{{$stockAdjustment->business_location}}" name="business_location">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label class="form-label required" for="status">
                                        Condition
                                    </label>
                                    <div class="fv-row">
                                        <select name="condition" class="form-select form-select-sm fw-bold "
                                                data-kt-select2="true"
                                                data-hide-search="true" data-placeholder="Condition">
                                            <option></option>
                                            <option value="normal" @selected(old('condition') == 'normal') selected>Normal</option>
                                            <option value="abnormal" @selected(old('condition') == 'abnormal')>Abnormal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label class="form-label required" for="">
                                        {{__('adjustment.status')}}
                                    </label>
                                    <div class="input-group flex-nowrap">
                                        <select name="status" class="form-select form-select-sm fw-bold"
                                                data-kt-select2="true"
                                                data-hide-search="true" data-placeholder="Status"
                                                data-allow-clear="true" data-kt-user-table-filter="role"
                                                data-hide-search="true">
                                            <option></option>
                                            <option value="prepared" @selected($stockAdjustment->status == 'prepared') {{ $stockAdjustment->status == 'completed' ? 'disabled' : '' }} >Prepared</option>
                                            <option value="completed" @selected($stockAdjustment->status == 'completed')>Completed</option>
                                        </select>
                                        <input type="hidden" value="{{$stockAdjustment->status}}" name="old_status">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">Remark</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea name="remark" class="form-control " cols="10" rows="3">{{$stockAdjustment->remark??''}}</textarea>
                                    <!--end::Input-->
                                </div>
                            </div>
                        </div>
                        <!--end::Card header-->
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center mb-8">

                                <div class="col-12">
                                    <div class="input-group quick-search-form p-0">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </div>
                                        <input type="text" class="form-control rounded-end-1" id="searchInput"
                                               placeholder="{{__('adjustment.search_products')}}">
                                        <div
                                            class="quick-search-results overflow-scroll  p-3 position-absolute d-none card w-100 mt-18  card z-3 autocomplete shadow"
                                            id="autocomplete" data-allow-clear="true"
                                            style="max-height: 300px;z-index: 100;"></div>
                                    </div>
                                </div>
                                {{--                                <div class="col-6 col-md-3 btn-light-primary btn add_new_product_modal my-5 my-lg-0"--}}
                                {{--                                     data-bs-toggle="modal" type="button"--}}
                                {{--                                     --}}{{--  data-bs-target="#add_new_product_modal" --}}{{-- data-href="{{ url('purchase/add/supplier')}}">--}}
                                {{--                                    <i class="fa-solid fa-plus me-2 "></i> Add new product--}}
                                {{--                                </div>--}}
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mt-10" id="adjustment_table">
                                    <thead>
                                    <tr class="fw-bold fs-6 text-gray-800">
                                        <th class="min-w-200px">{{__('adjustment.product')}}</th>
                                        <td class="w-175px">{{__('adjustment.total_current_qty')}}</td>
                                        <th class="w-175px">{{__('adjustment.on_ground_qty')}}</th>
                                        <th class="w-175px">{{__('adjustment.difference_qty')}}</th>
                                        <th class="min-w-100px">{{__('adjustment.unit')}}</th>
                                        <th class="w-125px">{{__('adjustment.package_qty')}}</th>
                                        <th class="min-w-100px">{{__('adjustment.package')}}</th>
                                        <th class="w-200px">Remark</th>
                                        <th>
                                            <i class="fas fa-trash fw-bold"></i>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                    <tr class="dataTables_empty text-center d-none">
                                        <td colspan="8 " >{{__('adjustment.no_data_table')}}</td>
                                    </tr>
                                    @foreach($adjustment_details as $key=>$detail)
                                        @php
                                            $product=$detail->product;
                                            $product_variation =$detail->toArray()['product_variation'];
                                        @endphp
                                        <tr class="adjustment_row">
                                            <td>
                                                <div class="my-5">
                                                    <span>{{$product->name}}</span>
                                                    <span class="text-gray-500 fw-semibold fs-5">{{ $product_variation['variation_template_value']['name']??'' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="current_stock_qty_txt">{{$detail->stock_sum_current_quantity}}</span> <span class='smallest_unit_txt'>{{$detail->product->uom['name']}}</span>
                                                <input type="hidden" class="balance_qty" value="{{$detail->stock_sum_current_quantity}}"  name="adjustment_details[{{$key}}][balance_quantity]"  />
                                            </td>
                                            <td class="d-none">
                                                <div>
                                                    <input type="text" value="{{$detail->id}}" name="adjustment_details[{{$key}}][adjustment_detail_id]"/>
                                                    <input type='hidden' value="{{$detail->product_id}}" class="product_id"  name="adjustment_details[{{$key}}][product_id]"  />
                                                    <input type='hidden' value="{{$detail->variation_id}}" class="variation_id" name="adjustment_details[{{$key}}][variation_id]"  />
                                                    <input type="text" value="{{$detail->ref_uom_id}}" name="adjustment_details[{{$key}}][ref_uom_id]"/>
                                                    <input type="text" value="{{$detail->adjustment_type}}" name="adjustment_details[{{$key}}][old_adjustment_type]"/>
{{--                                                    <input type="text" value="{{$detail->currency_id}}" name="adjustment_details[{{$key}}][currency_id]"/>--}}
                                                </div>
                                            </td>

                                            <td>
                                                <input type="text" class="form-control form-control-sm gnd_quantity form-control-sm gnd_quantity-{{$detail->variation_id}}"   placeholder="quantity" name="adjustment_details[{{$key}}][gnd_quantity]" value="{{round($detail->gnd_quantity,2)}}" data-kt-dialer-control="input"/>
                                                <input type="hidden" class="before_edit_gnd_quantity" name="adjustment_details[{{$key}}][before_edit_gnd_quantity]" value="{{round($detail->gnd_quantity,2)}}">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm adj_quantity" type="text" value="{{$detail->adj_quantity}}" name="adjustment_details[{{$key}}][adj_quantity]">
{{--                                                <span class="adj_quantity_text">{{$detail->adj_quantity}}</span> <span class='smallest_unit_txt'>{{$detail->product->uom['name']}}</span>--}}
{{--                                                <input class="adj_quantity" type="hidden" value="{{$detail->adj_quantity}}" name="adjustment_details[{{$key}}][adj_quantity]">--}}
                                                <input type="hidden" value="{{$detail->adj_quantity}}" name="adjustment_details[{{$key}}][before_edit_adj_quantity]">
                                            </td>
                                            <td>
                                                <select name="adjustment_details[{{$key}}][uom_id]" id="" class="form-select form-select-sm  unit_input uom_select" data-kt-repeater="uom_select_{{$key}}"  data-hide-search="true"  data-placeholder="Select Unit"  required>
                                                    @foreach ($product->toArray()['uom']['unit_category']['uom_by_category'] as $uom)
                                                        @php
                                                            $isCompleted = $stockAdjustment->status === 'completed';
                                                            $isSelected = $uom['id'] == $detail->uom_id;
                                                            $isDisabled = $isCompleted && !$isSelected;
                                                        @endphp
                                                        <option value="{{$uom['id']}}" @if ($isSelected) selected @endif @if ($isDisabled) disabled @endif>{{$uom['name']}}</option>
{{--                                                        <option value="{{$uom['id']}}" @selected($uom['id']==$detail->uom_id)  >{{$uom['name']}}</option>--}}
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="fv-row">
                                                <input type="text"  class="form-control form-control-sm mb-1 package_qty input_number" placeholder="Quantity"
                                                       name="adjustment_details[{{$key}}][packaging_quantity]" value="{{$detail['packagingTx']['quantity'] ?? 1}}">
                                            </td>
                                            <td class="fv-row">
                                                <select  name="adjustment_details[{{$key}}][packaging_id]" class="form-select form-select-sm package_id"
                                                         data-kt-repeater="package_select_{{$key}}" data-kt-repeater="select2" data-hide-search="true"
                                                         data-placeholder="Select Package" placeholder="select Package">
                                                    <option value="">Select Package</option>
                                                    @foreach ($product_variation['packaging'] as $package)
                                                        <option @selected($package['id']==$detail['packagingTx']['product_packaging_id'])
                                                                data-qty="{{$package['quantity']}}" data-uomid="{{$package['uom_id']}}" value="{{$package['id']}}">
                                                            {{$package['packaging_name']}} ({{ number_format($package['quantity'], 2, '.', '') }} {{$package['uom']['short_name']}})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="fv-row">
                                                <input type="text" class="form-control form-control-sm mb-1"
                                                       name="adjustment_details[{{$key}}][remark]" value="{{$detail->remark}}">
                                            </td>
                                            <th><i class="fa-solid fa-trash text-danger deleteRow" type="button"></i>
                                            </th>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{--                            <div class="separator my-5"></div>--}}
                            {{--                            <div class="col-4 float-end mt-3">--}}
                            {{--                                <table class="col-12 ">--}}
                            {{--                                    <tbody>--}}
                            {{--                                    <tr>--}}
                            {{--                                        <th>Total Item:</th>--}}
                            {{--                                        <td class="rowcount text-left fs-4" id="total_item">0</td>--}}
                            {{--                                    </tr>--}}
                            {{--                                    <tr>--}}
                            {{--                                        <th>Net Total Amount</th>--}}
                            {{--                                        <td class="rowSum text-left fs-4 net_purchase_total_amount_text" id=''>0</td>--}}
                            {{--                                    </tr>--}}
                            {{--                                    </tbody>--}}
                            {{--                                </table>--}}
                            {{--                            </div>--}}

                        </div>
                    </div>

                    <div class="col-12 text-center mt-2 mb-5">
                        <button type="submit" class="btn btn-primary btn-lg save_btn">{{__('adjustment.update')}}</button>
                    </div>
                </div>
                <!--end::Main column-->
            </form>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
    <script>
        // $(document).ready(function() {
        //
        //     var $businessLocation = $('select[name="business_location"]');
        //
        //
        //     $businessLocation.on('change', function() {
        //         var selectedLocation = $(this).val();
        //
        //         $toLocationSelect.find('option').prop('disabled', false);
        //         $toLocationSelect.find('option[value="' + selectedLocation + '"]').prop('disabled', true);
        //         $toLocationSelect.trigger('change');
        //     });
        // });


    </script>
    @include('App.stock.adjustment.include.quickSearchProducts')
@endpush
