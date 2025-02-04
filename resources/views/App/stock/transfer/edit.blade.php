@extends('App.main.navBar')

{{--@section('stock_transfer_icon', 'active')--}}
@section('inventory_icon', 'active')
@section('inventory_show', 'active show')
@section('stock_transfer_show', 'active show')
@section('stock_transfer_here_show', 'here show')
@section('stock_transfer_active_show', 'active ')


@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
@endsection

@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('transfer.edit_transfer')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('transfer.transfer')}}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{route('stock-transfer.index')}}" class="text-muted text-hover-primary">{{__('transfer.list')}}</a>
        </li>
        <li class="breadcrumb-item text-dark">{{$stockTransfer->transfer_voucher_no}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <form action="{{route('stock-transfer.update', $stockTransfer->id)}}" method="POST" id="stock_transfer_form">
                @csrf
                @method('put')
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin::General options-->
                    <!--begin::Alert-->
                    @if($stockTransfer->status == 'completed')
                    <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row p-5">
                        <div class="d-flex flex-column pe-0 pe-sm-10">
                            <h4 class="fw-semibold">{{__('transfer.cannot_editable')}}</h4>
                            <span>{{__('transfer.cannot_editable_message')}}</span>
                        </div>
                    </div>
                    @endif

                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
{{--                        <div class="card-header">--}}
{{--                            <div class="card-title">--}}
{{--                                <h2>Add Stock Transfer</h2>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{-- end::Card header --}}
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="row mt-3">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label required" for="">
                                        {{__('transfer.from_location')}}
                                    </label>
                                    <div class="fv-row">
                                        <select {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }} name="from_location" id="business_location_id"
                                                class="form-select form-select-sm fw-bold "
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="{{__('transfer.placeholder_from_location')}}" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach ($locations as $location)
                                                <option @selected($stockTransfer->from_location == $location->id)
                                                        value="{{$location->id}}">{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($stockTransfer->status == 'completed')
                                            <input type="hidden" name="from_location" value="{{ $stockTransfer->from_location }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label required" for="">
                                        {{__('transfer.to_location')}}
                                    </label>
                                    <div class="fv-row">
                                        <select {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }} name="to_location" class="form-select form-select-sm fw-bold "
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="{{__('transfer.placeholder_to_location')}}" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach ($locations as $location)
                                                <option @selected($stockTransfer->to_location == $location->id)
                                                        value="{{$location->id}}">{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($stockTransfer->status == 'completed')
                                            <input type="hidden" name="to_location"  value="{{ $stockTransfer->to_location }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <!--begin::Input group-->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label required">{{__('transfer.transfer_person')}}</label>
                                    <div class="fv-row">
                                        <select {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }} name="transfered_person" class="form-select form-select-sm fw-bold rounded-start-0"
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="{{__('transfer.placeholder_transfer_person')}}" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach($transfer_persons as $transfer_person)
                                                <option @selected($stockTransfer->transfered_person == $transfer_person->id)
                                                        value="{{$transfer_person->id}}">{{$transfer_person->username}}</option>
                                            @endforeach
                                        </select>
                                        @if ($stockTransfer->status == 'completed')
                                            <input type="hidden" name="transfered_person" value="{{ $stockTransfer->transfered_person }}">
                                        @endif
                                    </div>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label required">{{__('transfer.receive_person')}}</label>
                                    <div class="fv-row">
                                        <select {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }} name="received_person" class="form-select form-select-sm fw-bold rounded-start-0"
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="{{__('transfer.placeholder_receive_person')}}" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach($transfer_persons as $transfer_person)
                                                <option @selected($stockTransfer->received_person == $transfer_person->id)
                                                        value="{{$transfer_person->id}}">{{$transfer_person->username}}</option>
                                            @endforeach
                                        </select>
                                        @if ($stockTransfer->status == 'completed')
                                            <input type="hidden" name="received_person" value="{{ $stockTransfer->received_person }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="" class="form-label required">{{__('transfer.date')}}</label>
                                    <div class="fv-row">
                                        <input {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }} class="form-control form-control-sm" name="transfered_at" placeholder="Pick a date"
                                               data-td-toggle="datetimepicker" id="kt_datepicker_1"
                                               value="{{date('Y-m-d')}}"/>
                                    </div>
                                </div>
                                <!--end::Input group-->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label required" for="status">
                                        {{__('transfer.status')}}
                                    </label>
                                    <div class="fv-row">
                                        <select name="status" class="form-select form-select-sm fw-bold " data-kt-select2="true"
                                                data-hide-search="true" data-placeholder="Status"
                                                data-allow-clear="true" data-kt-user-table-filter="role"
                                                data-hide-search="true">
                                            <option></option>
                                            <option {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }}  @selected($stockTransfer->status == 'prepared') value="prepared" selected>Prepared</option>
                                            <option  {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }}  @selected($stockTransfer->status == 'pending') value="pending">Pending</option>
                                            <option @selected($stockTransfer->status == 'in_transit') value="in_transit">In Transit</option>
                                            <option @selected($stockTransfer->status == 'completed') value="completed">Completed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">Remark</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea name="remark" class="form-control " cols="10" rows="3">{{$stockTransfer->remark ?? ''}}</textarea>
                                    <!--end::Input-->
                                </div>
                            </div>

                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::General options-->
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center mb-8">

                                <div class="col-12">
                                    <div class="input-group quick-search-form p-0">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </div>
                                        <input {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }} type="text" class="form-control rounded-end-1" id="searchInput"
                                               placeholder="{{__('transfer.search_products')}}">
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
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mt-10" id="transfer_table">
                                    <thead>
                                    <tr class="fw-bold fs-6">
                                        <th class="min-w-200px">{{__('transfer.product')}}</th>
                                        <td class="min-w-100px">{{__('transfer.total_current_qty')}}</td>
                                        <th class="w-125px">{{__('transfer.transfer_qty')}}</th>
                                        <th class="min-w-100px">{{__('transfer.unit')}}</th>
                                        <th class="w-125px">{{__('transfer.package_qty')}}</th>
                                        <th class="min-w-100px">{{__('transfer.package')}}</th>
                                        <th class="min-w-200px">{{__('transfer.remark')}}</th>
                                        <th>
                                            <i class="fas fa-trash fw-bold"></i>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                    <tr class="dataTables_empty text-center d-none">
                                        <td colspan="8 " >{{__('transfer.no_data_table')}}</td>
                                    </tr>
                                    @foreach($stock_transfer_details as $key=>$detail)
                                        @php
                                            $product=$detail->product;
                                            $product_variation =$detail->toArray()['product_variation'];
                                        @endphp
                                        <tr class="transfer_row" data-row-id="{{ $key }}">
                                            <td>
                                                <div class="my-5">
                                                    <span>{{$product->name}}</span>
                                                    <span class="text-gray-500 fw-semibold fs-5">{{ $product_variation['variation_template_value']['name']??'' }}</span>



                                                </div>
                                            </td>
                                            <td>
                                                <span class="current_stock_qty_txt">{{$detail->stock_sum_current_quantity}}</span> <span class='smallest_unit_txt'>{{$detail->product->uom['name']}}</span>
                                                <p class="text-danger-emphasis  stock_alert_${selected_product.product_variations.id} d-none fs-7 p-2">* Out of Stock</p>
                                            </td>
                                            <td class="d-none">
                                                <div>
                                                    <input type="text" value="{{$detail->id}}" name="transfer_details[{{$key}}][transfer_detail_id]"/>
                                                    <input type='hidden' value="{{$detail->product_id}}" class="product_id"  name="transfer_details[{{$key}}][product_id]"  />
                                                    <input type='hidden' value="{{$detail->variation_id}}" class="variation_id" name="transfer_details[{{$key}}][variation_id]"  />
                                                    <input type="text" value="{{$detail->ref_uom_id}}" name="transfer_details[{{$key}}][ref_uom_id]"/>
                                                    <input type="text" value="{{$detail->currency_id}}" name="transfer_details[{{$key}}][currency_id]"/>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="input-group transfer_dialer_${unique_name_id}" >
                                                    @if(getSettingsValue('lot_control') == 'on')
                                                    <input {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }} type="text" class="form-control form-control-sm transfer_qty quantity form-control-sm" name="transfer_details[{{$key}}][quantity]"   aria-hidden="true" data-bs-toggle="modal" data-bs-target="#batch_lot_selection_modal_{{$key}}" value="{{round($detail->quantity,2)}}" readonly/>
                                                    @else
                                                    <input  {{ $stockTransfer->status == 'completed' ? 'readonly' : '' }} type="text" class="form-control form-control-sm quantity form-control-sm  quantity-{{$detail->variation_id}}"   placeholder="quantity" name="transfer_details[{{$key}}][quantity]" value="{{round($detail->quantity,2)}}" data-kt-dialer-control="input"/>
                                                    @endif
                                                </div>
                                                @if($stockTransfer->status == 'completed')
                                                    <input type="hidden" class="form-control form-control-sm quantity form-control-sm  quantity-{{$detail->variation_id}}"   name="transfer_details[{{$key}}][quantity]" value="{{round($detail->quantity,2)}}"/>
                                                @endif
                                                <input type="hidden" class="before_edit_quantity" name="transfer_details[{{$key}}][before_edit_quantity]" value="{{round($detail->quantity,2)}}">
                                            </td>

                                            <td>
                                                <select {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }} name="transfer_details[{{$key}}][uom_id]" id="" class="form-select form-select-sm  unit_input uom_select" data-kt-repeater="uom_select_{{$key}}"  data-hide-search="true"  data-placeholder="Select Unit" required>
                                                    @foreach ($product->toArray()['uom']['unit_category']['uom_by_category'] as $uom)
                                                        <option value="{{$uom['id']}}" @selected($uom['id']==$detail->uom_id)>{{$uom['name']}}</option>
                                                    @endforeach
                                                </select>
                                                @if($stockTransfer->status == 'completed')
                                                    <input type="hidden" name="transfer_details[{{$key}}][uom_id]" value="{{ $detail->uom_id }}">
                                                @endif
                                            </td>
                                            <td class="fv-row">
                                                <input type="text"  {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }} class="form-control form-control-sm mb-1 package_qty input_number" placeholder="Quantity"
                                                       name="transfer_details[{{$key}}][packaging_quantity]" value="{{$detail['packagingTx']['quantity'] ?? 1}}">
                                            </td>
                                            <td class="fv-row">
                                                <select  {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }} name="transfer_details[{{$key}}][packaging_id]" class="form-select form-select-sm package_id"
                                                        data-kt-repeater="package_select_{{$key}}" data-kt-repeater="select2" data-hide-search="true"
                                                        data-placeholder="Select Package" placeholder="select Package">
                                                    <option value="">Select Package</option>
                                                    @foreach ($product_variation['packaging'] as $package)
                                                        <option @selected($detail['packagingTx'] && $package['id']==$detail['packagingTx']['product_packaging_id'] )
                                                                data-qty="{{$package['quantity']}}" data-uomid="{{$package['uom_id']}}" value="{{$package['id']}}">
                                                            {{$package['packaging_name']}} ({{ number_format($package['quantity'], 2, '.', '') }} {{$package['uom']['short_name']}})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input {{ $stockTransfer->status == 'completed' ? 'disabled' : '' }} type="text" class="form-control form-control-sm" name="transfer_details[{{$key}}][remark]" value="{{$detail->remark}}">
                                                <input type="hidden" class="modal-data-input" name="transfer_details[{{ $key }}][lot_sertial_details]" value="">
                                            </td>
                                            <th><i class="fa-solid fa-trash text-danger deleteRow" type="button" ></i>
                                            </th>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 text-center mt-2 mb-5">
                        <button id="transfer_form_save_btn" type="submit" class="btn btn-primary">
                            <span class="indicator-label">
                              {{__('transfer.update')}}
                            </span>
                            <span class="indicator-progress">
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
                @foreach($stock_transfer_details as $key=>$detail)

                    <div class="modal fade batchLotModal" data-item="{{ $detail }}" id="batch_lot_selection_modal_{{$key}}"  tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog mw-800px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    <input type="hidden" class="csb_qty" value="{{$detail->stock_sum_current_quantity}}" />
                                </div>
                                <div class="modal-body">
                                    <table class="table table-rounded table-striped border gy-7 gs-7" id="lotSerialTable_{{ $key }}">
                                        <thead>
                                        <tr>
                                            <th>Lot Serial</th>
                                            <th class="min-w-40px">Quantity</th>
                                            <th>UOM</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody class="lot_serial_body">
                                        @foreach($detail->lot_serial_details as $key=>$lot_serial_detail)
                                            <tr class="lot_serial_row">
                                                <td>
                                                    <input type="hidden" name="lot_serial_detail_id[]" value="{{ $lot_serial_detail->id }}">
                                                    <select class="form-select form-select-sm lot_selection"
                                                            name="lot_serial_no[]"
                                                            data-kt-repeater="select2" data-hide-search="true"
                                                            data-placeholder="Select Batch" >
                                                        <option value="">Select Lot</option>
                                                        @foreach($detail->current_stock as $csb)
                                                            <option @selected($lot_serial_detail->lot_serial_numbers == $csb->lot_serial_no) value="{{ $csb->lot_serial_no }}" data-lotqty="{{ $csb->current_quantity + $lot_serial_detail->ref_uom_quantity }}">
                                                               <span> {{ $csb->lot_serial_no }} ({{ number_format($csb->current_quantity,2) }}) - </span><br>
                                                                <span>{{ $csb->expired_date }}</span>
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                    <input type="hidden" id="itemData" value='${JSON.stringify(item)}'>
                                                </td>
                                                <td>
                                                    <input type="number" name="lot_serial_qty[]" value="{{ $lot_serial_detail->ref_uom_quantity }}" class="form-control form-control-sm">
                                                    <input type="hidden" name="before_edit_lot_serial_qty[]" class="hidden_lot_qty" value="{{ $lot_serial_detail->ref_uom_quantity }}">
                                                    <input type="hidden" class="item_data" id="itemData" value="{{ $detail }}">

                                                </td>
                                                <td>
                                                    {{ $detail->uom->name }}
                                                </td>
                                                <td class="text-center">


                                                    @if($key !== 0)
                                                        <button class="btn btn-sm btn-light-danger btn-remove-row">
                                                            <i class="ki-duotone ki-trash fs-5">
                                                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                                            </i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-light-primary btn-add-row" type="button">
                                                            <i class="ki-duotone ki-plus fs-5"></i>
                                                        </button>
                                                    @endif



                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary modal-btn-save-changes">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!--end::Main column-->
            </form>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
    <script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
    <script src="{{asset('customJs/stock/validation/transfer_add.js')}}"></script>
    <script>
        const date = new Date();

        let day = date.getDate();
        let month = date.getMonth() + 1;
        let year = date.getFullYear();
        $('[data-td-toggle="datetimepicker"]').flatpickr({
            dateFormat: "Y-m-d",
        });


        $('.lot_selection').select2();

    </script>
    @include('App.stock.transfer.include.quickSearchProducts')
@endpush
