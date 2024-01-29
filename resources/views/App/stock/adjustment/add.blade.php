@extends('App.main.navBar')

@section('styles')
    {{-- css file for this page --}}
@endsection

@section('inventory_icon', 'active')
@section('inventory_show', 'active show')
@section('stock_adjustment_here_show', 'here show')
@section('stock_adjustment_add_active_show', 'active show')

@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
@endsection

@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('adjustment.create')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('adjustment.adjustment')}}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('stock-adjustment.index') }}" class="text-muted text-hover-primary">{{__('adjustment.list')}}</a>
        </li>
        <li class="breadcrumb-item text-dark">{{__('adjustment.add')}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="adjustment_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <form action="{{route('stock-adjustment.store')}}" method="POST" id="adjustment_add_form">
                @csrf
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin::Main card-->
                    <div class="card card-flush py-4">
                        <div class="card-body pt-0">
                            <div class="row mt-3">
                                <div class="col-md-6 fv-row">
                                    <label class="form-label required" for="business_location">
                                        {{__('adjustment.location')}}
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fa-solid fa-location-dot"></i></div>
                                        <div class="overflow-hidden flex-grow-1">
                                        <select name="business_location" id="business_location_id"
                                                class="form-select fw-bold rounded-0 form-select-sm"
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="{{__('adjustment.placeholder_location')}}" data-hide-search="true">
                                            <option></option>
                                            @foreach ($locations as $location)
                                                <option value="{{$location->id}}" >{{businessLocationName($location)}}</option>
                                            @endforeach
                                        </select>
{{--                                            Auth::user()->default_location_id--}}
                                        </div>
                                        <button type="button" class="input-group-text "  data-bs-toggle="tooltip" data-bs-custom-class="tooltip" data-bs-placement="top" data-bs-html="true" title="<span class='text-primary-emphasis'>{{__('adjustment.location_tip')}}</span>">
                                            <i class="fa-solid fa-circle-info text-primary"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label class="form-label required" for="status">
                                        {{__('common.condition')}}
                                    </label>
                                    <div class="fv-row">
                                        <select name="condition" class="form-select form-select-sm fw-bold "
                                                data-kt-select2="true"
                                                data-hide-search="true" data-placeholder="Condition">
                                            <option></option>
                                            <option value="normal" @selected(old('condition') == 'normal') selected>Normal</option>
                                            <option value="abnormal" @selected(old('condition') == 'abnormal')>Abnormal</option>
                                            <option value="expire" @selected(old('expire') == 'expire')>Expire</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label class="form-label required" for="status">
                                       {{__('adjustment.status')}}
                                    </label>
                                    <div class="fv-row">
                                        <select name="status" class="form-select form-select-sm fw-bold "
                                                data-kt-select2="true"
                                                data-hide-search="true" data-placeholder="Status">
                                            <option></option>
                                            <option value="prepared" @selected(old('status') == 'prepared') selected>Prepared</option>
                                            <option value="completed" @selected(old('status') == 'completed')>Completed</option>
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
                    <!--end::Main card-->
                    <div class="card">
                        <div class="card-body">
                            @error('adjustment_details')
                            <div class="alert-danger alert">
                                {{$message}}
                            </div>
                            @enderror
                            <div class="row align-items-center mb-8">
                                <div class="col-12">
                                    <div class="input-group quick-search-form p-0">

                                        <span class="input-group-text" id="search-input-type">Keyword</span>
                                        <input type="text" class="form-control" id="searchInput"
                                               placeholder="{{__('adjustment.search_products')}}">
                                        <div class="input-group-text rounded-end-1">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </div>

                                        <div
                                            class="quick-search-results overflow-scroll rounded-1 p-3 position-absolute d-none card w-100 mt-18  card z-3 autocomplete shadow"
                                            id="autocomplete" data-allow-clear="true"
                                            style="max-height: 300px;z-index: 100;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-5 search-keyword-block">
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
                                            {{__('common.click_to_set_search_keyword')}}
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
                                                   {{__('product/product.name')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-check form-check-sm user-select-none">
                                                <input class="form-check-input " type="checkbox" value="on" id="psku_kw" checked />
                                                <label class="form-check-label cursor-pointer" for="psku_kw">
                                                    {{__('product/product.sku')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-check form-check-sm user-select-none">
                                                <input class="form-check-input " type="checkbox" value="on" id="vsku_kw" />
                                                <label class="form-check-label cursor-pointer" for="vsku_kw">
                                                    {{__('product/product.variation_sku')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-check form-check-sm user-select-none">
                                                <input class="form-check-input " type="checkbox" value="on" id="pgbc_kw" />
                                                <label class="form-check-label cursor-pointer" for="pgbc_kw">
                                                    {{__('product/product.packaging_barcode')}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Content-->
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
                                        <th class="w-200px">{{__('common.remark')}}</th>
                                        <th>
                                            <i class="fas fa-trash fw-bold"></i>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                    <tr class="dataTables_empty text-center">
                                        <td colspan="8 ">{{__('adjustment.no_data_table')}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 text-center mt-2 mb-5">
                        <button type="submit" data-adjustment-create-action="submit" value="save" name="save" class="btn btn-primary btn-lg save_btn">{{__('adjustment.save')}}</button>
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

    @include('App.stock.adjustment.include.quickSearchProducts')
    <script src={{asset('customJs/stock/validation/adjustmentAdd.js')}}></script>


@endpush
