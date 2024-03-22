@extends('App.main.navBar')

@section('inventory_icon', 'active')
@section('inventory_show', 'active show')
@section('opening_stock_here_show','here show')
@section('add_opening_stock_active_show', 'active ')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Add Opening Stock</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Opening stock</li>
        <li class="breadcrumb-item text-muted"><a href="{{route('opening_stock_list')}}">List</a></li>
        <li class="breadcrumb-item text-dark">Add</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('styles')
    <style>
        .opening_stock_tbody tr td{
            padding: 3px;
        }
    </style>
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/businessSetting.css")}}>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
@endsection



@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl" id="openingStock_container">
            <form action="{{route('store_opening_stock')}}" method="POST" id="openingStock_form">
                @csrf
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
                    <div class="card">
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
                                                data-kt-user-table-filter="role" >
                                            <option></option>
                                            @foreach ($locations as $location)
                                                <option value="{{$location->id}}" @selected($location->id==old('business_location_id'))>{{businessLocationName($location)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('business_location_id')
                                        <div class="p-2 text-danger">* {{$message}}</div>
                                    @enderror

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
                                                value="{{old('opening_date',date('Y-m-d H:i'))}}"/>
                                    </div>

                                    @error('opening_date')
                                        <div class="p-2 text-danger">* {{$message}}</div>
                                    @enderror
                                </div>
                                {{-- <div class="mb-7 mt-3 col-12 col-md-3 fv-row">
                                    <label class="form-label fs-6 fw-semibold required" for="">
                                        Status
                                    </label>
                                    <div class="">
                                        <select name="status" class="form-select form-select-sm fw-bold " data-kt-select2="true"
                                                data-hide-search="false" data-placeholder="Select Location"
                                                data-kt-user-table-filter="role"
                                                data-hide-search="true">
                                            <option></option>
                                            <option value="pending" selected @selected(old('status')=='pending') >pending</option>
                                            <option value="received"  @selected(old('status')=='received') >received</option>
                                            <option value="issued"  @selected(old('status')=='issued') >issued</option>
                                            <option value="confirmed"  @selected(old('status')=='confirmed') >confirmed</option>
                                        </select>
                                    </div>
                                    @error('status')
                                        <div class="p-2 text-danger">* {{$message}}</div>
                                    @enderror
                                </div> --}}
                                <div class="mb-7 mt-3 col-md-4 col-12">
                                    <label class="form-label fs-6">
                                    Note
                                    </label>
                                    <textarea class="form-control form-control-sm" name="note" id="" cols="30" rows="3">{{old('note')}}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center mb-8">

                                <div class="col-12  col-md-9">
                                    <div class="input-group quick-search-form p-0">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </div>
                                        <input type="text" class="form-control form-control-sm rounded-start-0" id="searchInput"
                                               placeholder="Search...">
                                        <div
                                            class="quick-search-results overflow-scroll  p-3 position-absolute d-none card w-100 mt-18  card z-3 autocomplete shadow"
                                            id="autocomplete" data-allow-clear="true"
                                            style="max-height: 300px;z-index: 100;"></div>
                                    </div>
                                </div>
                                <a class="col-12 col-md-3 btn-light-primary btn btn-sm add_new_product_modal my-5 my-lg-0 productQuickAdd"   data-href="{{route('product.quickAdd')}}"  >
                                    <i class="fa-solid fa-plus me-2 "></i> Add new product
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mt-10" id="openingStockTable">
                                    <!--begin::Table head-->
                                    <thead class="">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0 ">
                                        <th class="min-w-125px">Product Name</th>
                                        <th class="min-w-150px">Qty </th>
                                        <th class="min-w-150px">Unit </th>
                                        <th class="min-w-125px">{{__('product/product.package_qty')}}</th>
                                        <th class="min-w-125px">{{__('product/product.package')}}</th>
                                        <th class="min-w-150px">Purchase Price</th>
                                        <th class="min-w-150px">Subtotal</th>
                                        @if ($lotControl=='on')
                                        <th class="min-w-150px">Lot Number</th>
                                        @endif
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
                                    <tbody class="fw-semibold text-gray-600 opening_stock_tbody">
                                        <tr class="dataTables_empty text-center">
                                            <td colspan="8 ">There is no data to show</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-5 col-12 float-end mt-3">
                                <table>
                                    <tbody>
                                            <tr class="">
                                                <td class="text-end fw-semibold pe-4">Item :</td>
                                                <td class="text-start fw-bold item_count">0</td>
                                            </tr>
                                            <tr class="">
                                                <td class=" fw-semibold pe-2 min-w-150px">Total Opening Amount :</td>
                                                <td class="text-end fw-bold ">
                                                    <input type="text" class="form-control form-control-sm total_opening_amount input_number" name="total_opening_amount" value="0">
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

@endsection

@push('scripts')
    <script src="{{asset('customJs/openingStock/validation.js')}}"></script>
    <script>
        @error('opening_stock_details')
            warning('Openg Stocks are requried!')
        @enderror
    </script>
    <script>

        $('[data-td-toggle="datetimepicker"]').flatpickr({
            dateFormat: "Y-m-d H:i",
            // defaultDate: "today",
            enableTime: true,
        });

    $(document).on('click', '.productQuickAdd', function(){
        $url=$(this).data('href');

        loadingOn();
        $('#quick_add_product_modal').load($url, function() {
            $(this).modal('show');
            loadingOff();
        });
    });

    var lotControl="{{$lotControl ?? 'off'}}";

    </script>
    @include('App.openingStock.JS.openingStockJs')
@endpush

