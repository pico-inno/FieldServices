
<div class="">
    <style>
        .pagination {
            justify-content: center !important;
        }

        @media(min-width:780px) {
            .pagination {
                justify-content: end !important;
            }
        }
    </style>
    <div class="accordion-collapse collapse show" id="kt_accordion_1_body_2" aria-labelledby="kt_accordion_1_header_2"
        data-bs-parent="#kt_accordion_1" wire:ignore>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
            <div class="card">
                <div class="card-header py-1" style="min-height:fit-content !important;">
                    <div class="card-title p-1">
                        <h2>Filters</h2>
                    </div>
                </div>
                <div class="card-body p-5 p-sm-7">
                    <div class="row mb-5 flex-wrap">

                            <div class="row">
                                <div class="col-12 col-md-4 col-lg-3 mb-5">
                                    <label class="form-label  fs-6 fw-semibold">date:</label>
                                    <input autocomplete="off" class="form-control form-control-sm form-control-solid" placeholder="Pick date rage"
                                        data-kt-table-filter="dateRange" id="kt_daterangepicker_4" data-dropdown-parent="#filter" />
                                </div>

                                @if ($defaultCampaignId ==null)
                                    <div class="col-12 col-md-4 col-lg-3 mb-5">
                                        <label class="form-label  fs-6 fw-semibold">
                                            Filter By Campaign:</label>
                                        <select class="form-select form-select-sm fw-bold campaignfilter" data-allow-clear="true"
                                            data-placeholder="Select option" id="campaignfilter" data-kt-select2="true" data-kt-table-filter="outlet">
                                            <option value="all">All</option>
                                            @foreach ($campaigns as $campaign)
                                            <option value="{{$campaign['id']}}">{{ $campaign['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 mb-5">
                                        <label class="form-label  fs-6 fw-semibold">
                                            Filter By Outlet Type:</label>
                                        <select class="form-select form-select-sm fw-bold campaignfilter" data-allow-clear="true"
                                            data-placeholder="Select option" id="outletTypeFilter" data-kt-select2="true" data-kt-table-filter="outletType">
                                            <option value="all">All</option>
                                            <option value="on">On</option>
                                            <option value="off">Off</option>
                                        </select>
                                    </div>
                                 @endif
                            </div>

                        <div class="row">

                            @if ($defaultCampaignId ==null)
                            <div class="col-12 col-md-4 col-lg-3 mb-5">
                                <label class="form-label  fs-6 fw-semibold">
                                    <i class="fa-solid fa-circle fs-9 text-primary me-1"></i>
                                    Filter By Outlet:</label>
                                <x-locationsselect  className="form-select-sm" id="outletfilter" placeholder="Please Select Location"></x-locationsselect>
                                {{-- <select class="form-select form-select-sm fw-bold locationFilter" data-allow-clear="true"
                                    data-placeholder="Select option" id="outletfilter" data-kt-select2="true" data-kt-table-filter="outlet">
                                    <option value="all">All</option>
                                    @foreach ($locations as $l)
                                    <option value="{{$l['id']}}">{{ businessLocationName($l) }}</option>
                                    @endforeach
                                </select> --}}
                            </div>
                            @endif
                            <div class="col-12 col-md-4 col-lg-3 mb-5">
                                <label class="form-label  fs-6 fw-semibold">
                                    <i class="fa-solid fa-circle fs-9 text-success me-1"></i>
                                    Filter By PG:</label>
                                <select class="form-select form-select-sm fw-bold pgFilter" data-allow-clear="true" data-placeholder="Select option"
                                    id="pgFilter" data-kt-select2="true" data-kt-table-filter="employee">
                                    <option value="all">All</option>
                                    @foreach ($employee as $e)
                                    <option value="{{ $e['id'] }}">{{ $e['personal_info']['first_name'] }}{{ $e['personal_info']['last_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3 mb-5">
                                <label class="form-label  fs-6 fw-semibold">
                                    {{-- <i class="fa-solid fa-circle fs-9 text-success me-1"></i> --}}
                                    Category:</label>
                                <select class="form-select form-select-sm fw-bold categoryFilter" data-allow-clear="true"
                                    data-placeholder="Select option" id="categoryFilter" data-kt-select2="true" data-kt-table-filter="category">
                                    <option value="all">All</option>
                                    @foreach ($categories as $c)
                                    <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--begin::Card-->
    <div class="card">
        <div class="modal fade show " tabindex="-1" id="kt_modal_1" wire:loading wire:target='export'>
            <div class="modal-dialog w-md-500px w-100">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class="modal-title text-center">
                            Exporting......
                        </h3>
                        <div class="progress mt-5">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1 me-2">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <input type="search" wire:model.live.debounce.50ms="search" data-kt-saleItem-table-filter="search"
                        class="form-control form-control-sm w-250px ps-15" placeholder="Search" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-saleItem-table-toolbar="base">
                    <!--begin::Filter-->
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-saleItem-table-toolbar="base">
                        <button type="button" class="btn btn-sm btn-light-primary me-3 collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2" aria-expanded="false"
                            aria-controls="kt_accordion_1_body_2">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->Filter
                        </button>
                        <button type="button" class="btn btn-sm btn-light-success me-3 collapsed" type="button" wire:click="export">
                            <i class="fa-solid fa-upload"></i>
                            Export With Filter
                        </button>
                        <button type="button" class="btn btn-sm btn-light-dark me-3 collapsed" type="button" wire:click="export(false)">
                            <i class="fa-solid fa-upload"></i>
                            Export All
                        </button>
                    </div>
                </div>
                <!--end::Toolbar-->
                <!--begin::Group actions-->
                <div class="d-flex justify-content-end align-items-center d-none"
                    data-kt-saleItem-table-toolbar="selected">
                    <div class="fw-bold me-5">
                        <span class="me-2" data-kt-saleItem-table-select="selected_count"></span>Selected
                    </div>
                    <button type="button" class="btn btn-danger" data-kt-saleItem-table-select="delete_selected">Delete
                        Selected</button>
                </div>
                <!--end::Group actions-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <div class="card-body pt-0 saleTableCard table-responsive position-relative" id="">

            {{-- @if($dataLoading) --}}
                <div class="position-absolute w-fit  top-10 bg-white p-3 rounded-1 border border-1 border-gray-500 " wire:loading
                        style="top: 40px;left:50%;">
                        <h2 class="text-primary">Loading....</h2>
                    </div>
            {{-- @endif --}}

            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-7 gy-3 table-max-high" id="kt_saleItem_table"
                    data-sticky-header="true">
                    <thead>
                        <tr class="text-end text-gray-600 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-100px cursor-pointer text-start cursor-pointer" wire:click="sortBy('products.name')">
                                Product Name
                                <x-datatable.sort-icon field="products.name" :sortField="$sortField" :sortAsc="$sortAsc" />
                            </th>
                            <th class="text-start pe-3 min-w-100px">Quantity</th>
                            <th class="text-start pe-3 min-w-100px">UOM</th>
                            <th class="text-start pe-3 min-w-100px">package Qty </th>
                            <th class="text-start pe-3 min-w-100px">Pkg</th>
                            <th class="min-w-100px cursor-pointer text-start cursor-pointer" wire:click="sortBy('categories.name')">
                                <div class="">
                                    <span>Category</span>
                                    <x-datatable.sort-icon field="categories.name" :sortField="$sortField" :sortAsc="$sortAsc" />
                                </div>
                            </th>
                            <th class="text-start pe-3 min-w-150px">Outlet</th>
                            <th class="text-start pe-3 min-w-150px">PG</th>
                            <th class="text-start pe-3 min-w-150px">Campaign</th>
                            <th class="text-start pe-3 min-w-100px cursor-pointer" wire:click="sortBy('sales.sold_at')"> Date <x-datatable.sort-icon field="sales.sold_at" :sortField="$sortField" :sortAsc="$sortAsc" /></th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-semibold text-gray-600 fs-6 fw-semibold" id="allSaleTable">

                        @foreach ($datas as $i=>$data)
                        <tr class="" :key="$i">
                            <td class="text-start">{{$data['name']}}</td>
                            <td>{{$data['quantity']}}</td>
                            <td>{{$data['uom']}}</td>
                            <td>{{$data['pkgQty']}}</td>
                            <td>{{$data['pkg']}}</td>
                            <td class="text-start">{{$data['category_name'] ?? ''}}</td>
                            <td>{{$data['outlet']}}</td>
                            <td>{{$data['pg_fs']}}{{$data['pg_ls']}}</td>
                            <td>{{$data['campaignName']}}</td>
                            <td>{{fdate($data['soldAt'])}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row justify-content-center  justify-content-md-between">
                <div class="col-md-6 col-12 mb-3 ">
                    <div class="w-auto">
                        <select name="" id="" wire:model.change="perPage"
                            class="form-select form-select-sm w-auto m-auto m-md-0">
                            @foreach ($aviablePerPages as $page)
                            <option value="{{$page}}">{{$page}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-12 mb-3">
                    {{$datas->links()}}
                </div>
            </div>
        </div>
    </div>
    <script >

        $(document).ready(function() {
            // handleDeleteRows();
            // window.addEventListener('contentChanged', event => {
                if(typeof locationSelect !== 'undefined'){
                    locationSelect.on('select2:select', function (e) {
                        @this.set('businesslocationFilterId', $('#outletfilter').select2("val"));
                    }).on('select2:unselect', function (e) {
                        @this.set('businesslocationFilterId', 'all');
                    });
                }
                $('#pgFilter').select2().on('select2:select', function (e) {
                    @this.set('pgFilterId', $('#pgFilter').select2("val"));
                }).on('select2:unselect', function (e) {
                    @this.set('pgFilterId', 'all');
                });

                $('#categoryFilter').select2().on('select2:select', function (e) {
                    @this.set('categotryFilterIdOA', $('#categoryFilter').select2("val"));
                }).on('select2:unselect', function (e) {
                    @this.set('categotryFilterIdOA','all');
                });
                $('#campaignfilter').select2().on('select2:select', function (e) {
                    @this.set('campaignFilterId', $('#campaignfilter').select2("val"));
                }).on('select2:unselect', function (e) {
                    @this.set('campaignFilterId','all');
                });


                $('#outletTypeFilter').select2().on('select2:select', function (e) {
                    @this.set('outletTypeFilter', $('#outletTypeFilter').select2("val"));
                }).on('select2:unselect', function (e) {
                    @this.set('outletTypeFilter','all');
                });
            // });



            // cb(start, end);
            var start = moment().subtract(1, "M");
            var end = moment();

            function cb(start, end) {
                $("#kt_daterangepicker_4").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
                let startDate=$('#kt_daterangepicker_4').data('daterangepicker').startDate.format('YYYY-MM-DD');
                let endDate=$('#kt_daterangepicker_4').data('daterangepicker').endDate.format('YYYY-MM-DD');
                @this.set('filterDate', [startDate,endDate]);
            }

            $("#kt_daterangepicker_4").daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                "Today": [moment(), moment()],
                "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [moment().startOf("month"), moment().endOf("month")],
                "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
                }
            }, cb).val('');
        });
    </script>
</div>
