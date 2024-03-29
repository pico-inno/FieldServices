<div class="">
    <div class="accordion-collapse collapse" id="kt_accordion_1_body_2" aria-labelledby="kt_accordion_1_header_2"
        data-bs-parent="#kt_accordion_1" wire:ignore>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Filters</h2>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-5 flex-wrap">
                        <!--begin::Input group-->
                        <div class="mb-5 col-4 col-sm12 col-md-3 ">
                            <label class="form-label fs-6 fw-semibold">Bussiness Location:</label>
                            <select wire:model.change="businesslocationFilterId"
                                class="form-select form-select-sm fw-bold locationFilter"
                                data-placeholder="Select option" id="select2"
                                data-kt-saleItem-table-filter="businesslocation"
                                data-allow-clear="true">
                                <option value="all">All</option>
                                @foreach ($locations as $l)
                                <option value="{{ $l->id }}">{{ businessLocationName($l) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10 col-4 col-sm12 col-md-3 ">
                            <label class="form-label fs-6 fw-semibold">date:</label>
                            <input wire:model.live='filterDate' class="form-control form-control-sm form-control-solid"
                                placeholder="Pick date rage" data-kt-saleItem-table-filter="dateRange"
                                id="kt_daterangepicker_4" data-dropdown-parent="#filter" />
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--begin::Card-->
    <div class="card">
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
                        <!--begin::Menu 1-->
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-lg-600px w-md-450px" tabindex="-1"
                            id="filter" data-kt-menu="true">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bold">Filter Options</div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Separator-->
                            <!--begin::Content-->
                            <div class="px-7 py-5" data-kt-saleItem-table-filter="form">
                                <div class="d-flex flex-wrap justify-content-around">


                                </div>
                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end">
                                    <button type="reset"
                                        class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                        data-kt-menu-dismiss="true" data-kt-saleItem-table-filter="reset">Reset</button>
                                    <button type="submit" class="btn btn-primary fw-semibold px-6"
                                        data-kt-menu-dismiss="true"
                                        data-kt-saleItem-table-filter="filter">Apply</button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Menu 1-->
                        <!--end::Filter-->
                    </div>
                    <!--end::Toolbar-->
                    <!--end::Filter-->
                    @if(hasCreate('purchase'))
                    <!--begin::Add customer-->
                        <button class="btn btn-sm btn-primary fs-9 fs-sm-7"  wire:click="export()">
                            Export
                        </button>
                    <!--end::Add customer-->
                    @endif
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <div class="card-body pt-0 saleTableCard table-responsive position-relative" id="">
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
            <div class="position-absolute w-fit  top-10 bg-white p-3 rounded-1 border border-1 border-gray-500 "
                wire:loading style="top: 40px;left:50%;">
                <h2 class="text-primary">Loading....</h2>
            </div>
            <table class="table align-middle table-hover   table-row-dashed fs-7 gy-3 table-max-high mt-5" id="kt_Item_table"
                data-sticky-header="true">
                <thead>
                    <tr class="text-start text-gray-600 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-100px ">
                            {{__('os/os.opening_date')}}
                        </th>
                        <th class="min-w-100px cursor-pointer" wire:click="sortBy('opening_stock_voucher_no')">
                            {{__('table/label.voucher_no')}}
                            <x-datatable.sort-icon field="voucher_no" :sortField="$sortField"
                                :sortAsc="$sortAsc" />
                        </th>
                        <th class="min-w-100px ">Opening Person</th>
                        <th class="min-w-100px text-end">Opening Amount</th>
                        <th class="min-w-100px text-end">Opening Location</th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class=" text-gray-600 fs-6 fw-semibold" id="allSaleTable">

                    @foreach ($openingStocks as $openingStock)
                    <tr class="text-start" wire:key="{{ $openingStock['id'] }}">
                        <td class="fw-semibold ">{{fdate($openingStock->opening_date)}}</td>
                        <td class="fw-semibold ">{{$openingStock->opening_stock_voucher_no}}</td>
                        <td class="fw-semibold">{{$openingStock->username}}</td>
                        <td class="fw-semibold  text-end">{{price($openingStock->total_opening_amount)}}</td>
                        <td class="fw-semibold  text-end">{{$openingStock->locationName}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold fs-6 border-top-1">
                        <th colspan="2" class="text-nowrap text-start fs-2">Total Opening Amount:</th>
                        <th colspan="2" class="min-w-125px text-dark text-end  pe-3">
                            {{price($openingStocks->sum('total_opening_amount'))}}</th>
                        {{-- <th colspan="1" class="min-w-125px text-dark text-end  pe-3">
                            {{formatPrice($purchases->sum('paid_amount'),$saleData[0]->currency ?? [])}}
                        </th>
                        <th colspan="1" class="min-w-125px text-dark text-end  pe-3">
                            {{formatPrice($purchases->sum('balance_amount'),$saleData[0]->currency ?? [])}}
                        </th> --}}
                    </tr>
                </tfoot>
            </table>
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
                    {{$openingStocks->onEachSide(5)->links()}}
                </div>
            </div>
        </div>
    </div>
    <script wire:ignore>
    $(document).ready(function() {
        $('#select2').select2().on('select2:select', function (e) {
            @this.set('businesslocationFilterId', $('#select2').select2("val"));
        }).on('select2:unselect', function (e) {
                @this.set('businesslocationFilterId','all');
        });


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
