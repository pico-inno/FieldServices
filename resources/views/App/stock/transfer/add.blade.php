@extends('App.main.navBar')

@section('styles')
    {{-- css file for this page --}}
@endsection

@section('stock_transfer_icon', 'active')
@section('inventory_icon', 'active')
@section('inventory_show', 'active show')
@section('stock_transfer_show', 'active show')
@section('stock_transfer_here_show', 'here show')
@section('stock_transfer_add_active_show', 'active ')

@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
@endsection

@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('transfer.transfer')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('transfer.transfer')}}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{route('stock-transfer.index')}}" class="text-muted text-hover-primary">{{__('transfer.list')}}</a>
        </li>
        <li class="breadcrumb-item text-dark">{{__('transfer.add')}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <form action="{{route('stock-transfer.store')}}" method="POST">
                @csrf
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin::General options-->
                    <div class="card card-flush py-4">
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="row mb-3 pt-3">
                                <div class="col-md-6 mb-4 ">
                                    <label class="form-label required" for="from_location">
                                      {{__('transfer.from_location')}}
                                    </label>
                                    <div class="input-group flex-nowrap">
                                        <select name="from_location" id="business_location_id"
                                                class="form-select form-select-sm fw-bold "
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="{{__('transfer.placeholder_from_location')}}" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach ($locations as $location)
                                                <option {{$location->id == \Illuminate\Support\Facades\Auth::user()->default_location_id ? 'selected' : ''}} value="{{$location->id}}">{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label required" for="to_location">
                                        {{__('transfer.to_location')}}
                                    </label>
                                    <div class="input-group flex-nowrap">
                                        <select name="to_location" class="form-select form-select-sm fw-bold "
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="{{__('transfer.placeholder_to_location')}}" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach ($locations as $location)
                                                <option value="{{$location->id}}">{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!--begin::Input group-->
                                <div class="col-md-3 mb-4">
                                    <label class="form-label required">{{__('transfer.transfer_person')}}</label>
                                    <div class="input-group flex-nowrap">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-user text-muted"></i>
                                        </div>
                                        <select name="transfered_person"
                                                class="form-select form-select-sm  fw-bold rounded-start-0"
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="{{__('transfer.placeholder_transfer_person')}}" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach($transfer_persons as $transfer_person)
                                                <option
                                                    value="{{$transfer_person->id}}">{{$transfer_person->username}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="col-md-3 mb-4">
                                    <label class="form-label required">{{__('transfer.receive_person')}}</label>
                                    <div class="input-group flex-nowrap">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-user text-muted"></i>
                                        </div>
                                        <select name="received_person"
                                                class="form-select form-select-sm fw-bold rounded-start-0"
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="{{__('transfer.placeholder_receive_person')}}" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach($transfer_persons as $transfer_person)
                                                <option
                                                    value="{{$transfer_person->id}}">{{$transfer_person->username}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--end::Input group-->
                                <div class="col-md-3 mb-4">
                                    <label for="transfered_at" class="form-label required">{{__('transfer.date')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-text" data-td-target="#kt_td_picker_basic"
                                              data-td-toggle="datetimepicker">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        <input name="transfered_at" class="form-control form-control-sm"
                                               id="kt_datepicker_2"/>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label class="form-label required" for="status">
                                        {{__('transfer.status')}}
                                    </label>
                                    <div class="input-group flex-nowrap">
                                        <select name="status" class="form-select form-select-sm fw-bold "
                                                data-kt-select2="true"
                                                data-hide-search="true" data-placeholder="{{__('transfer.status')}}"
                                                data-allow-clear="true" data-kt-user-table-filter="role"
                                                data-hide-search="true">
                                            <option></option>
                                            <option value="prepared" selected>Prepared</option>
                                            <option value="pending">Pending</option>
                                            <option value="in_transit">In Transit</option>
                                            <option value="completed">Completed</option>
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
                                    <textarea name="remark" class="form-control " cols="10" rows="3"></textarea>
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
                                        <input type="text" class="form-control rounded-end-1" id="searchInput"
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
                                    <tr class="fw-bold fs-6 text-gray-800">
                                        <th class="min-w-200px">{{__('transfer.product')}}</th>
                                        <td class="min-w-100px">{{__('transfer.total_current_qty')}}</td>
                                        <th class="w-125px">{{__('transfer.transfer_qty')}}</th>
                                        <th class="min-w-100px">{{__('transfer.unit')}}</th>
                                        <th class="w-125px">{{__('transfer.package_qty')}}</th>
                                        <th class="min-w-100px">{{__('transfer.package')}}</th>
                                        <th class="min-w-200px">{{__('transfer.remark')}}</th>
                                        <th class="text-center">
                                            <i class="fas fa-trash fw-bold"></i>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                    <tr class="dataTables_empty text-center">
                                        <td colspan="8 ">There is no data to show</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center mt-2 mb-5">
                        <button type="submit" class="btn btn-primary btn-lg save_btn">{{__('transfer.save')}}</button>
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
        //Begin:  initialize transfer date
        $("#kt_datepicker_2").flatpickr({
            dateFormat: "d-m-Y",
            defaultDate: new Date(),
            conjunction: ""
        });
        //End:  initialize transfer date

        //Begin: Disable to Location(To) option
        $(document).ready(function() {
            var fromLocationSelect = $('select[name="from_location"]');
            var toLocationSelect = $('select[name="to_location"]');

            disableToLocation(fromLocationSelect.val());

            fromLocationSelect.on('change', function() {
                var selectedLocation = $(this).val();
                disableToLocation(selectedLocation);
            });

            function disableToLocation(selectedLocation){
                toLocationSelect.find('option').prop('disabled', false);
                toLocationSelect.find('option[value="' + selectedLocation + '"]').prop('disabled', true);
                toLocationSelect.trigger('change');
            }
        });
        //End:  Disable to Location(To) option
    </script>
    @include('App.stock.transfer.include.quickSearchProducts')
@endpush
