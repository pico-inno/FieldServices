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
                {{-- <div class="card-body">
                    <div class="row mb-5 flex-wrap">
                        <!--begin::Input group-->
                        <input type="hidden" id="saleType" value="">
                        <div class="mb-5 col-4 col-sm12 col-md-3 ">
                            <label class="form-label fs-6 fw-semibold">Bussiness Location:</label>
                            <select wire:model.change="businesslocationFilterId"
                                class="form-select form-select-sm fw-bold locationFilter"
                                data-placeholder="Select option" id="select2"
                                data-kt-saleItem-table-filter="businesslocation" data-hide-search="true">
                                <option value="all">All</option>
                                @foreach ($locations as $l)
                                <option value="{{ $l->id }}">{{ businessLocationName($l) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-5 col-4 col-sm12 col-md-3 ">
                            <label class="form-label fs-6 fw-semibold">Customer:</label>
                            <select wire:model.change='customerFilterId' id="customerFilter"
                                class="form-select  form-select-sm  fw-bold" data-placeholder="Select option"
                                data-allow-clear="true" data-filter="customer">
                                <option value="all">All</option>
                                @foreach ($customers as $c)
                                <option value="{{$c->id}}">
                                    {{ $c->first_name }} {{ $c->middle_name }} {{ $c->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-5 col-4 col-sm12 col-md-3 ">
                            <label class="form-label fs-7 fw-semibold">Status:</label>
                            <select wire:model.change='statusFilter' id="statusFilter"
                                class="form-select form-select-sm   fw-bold" data-placeholder="Select option"
                                data-filter="status" data-hide-search="true">
                                <option value="all" selected>All</option>
                                <option value="request">Request</option>
                                <option value="order">order</option>
                                <option value="pending">Pending</option>
                                <option value="deliver">Deliver</option>
                                <option value="received">Received</option>
                                <option value="confirmed">Confirmed</option>
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
                </div> --}}
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
                    <!--end::Filter-->

                    <!--begin::Add customer-->
                    <button class="btn btn-sm btn-primary fs-9 fs-sm-7"  wire:click="export(false)">
                        Export
                    </button>
                <!--end::Add customer-->
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
            <table class="table align-start table-hover   table-row-dashed fs-7 gy-3 table-max-high mt-5" id="kt_Item_table"
                data-sticky-header="true">
                <thead>
                    <tr class="text-start text-gray-600 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-100px min-w-175px">Opening Date</th>
                        <th class="min-w-100px cursor-pointer" wire:click="sortBy('opening_stock_voucher_no')">
                            {{__('table/label.voucher_no')}}
                            <x-datatable.sort-icon field="voucher_no" :sortField="$sortField"
                                :sortAsc="$sortAsc" />
                        </th>
                        <th class="min-w-100px ">Product Name</th>
                        <th class="min-w-100px text-end">UOM Price</th>
                        <th class="min-w-100px text-end">Qty</th>
                        <th class="min-w-100px text-end">Subtotal</th>
                        <th class="min-w-100px ">
                            Expired Date
                        </th>
                        <th>Opening Person</th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class=" text-gray-600 fs-6 fw-semibold" id="allSaleTable">

                    @foreach ($openingStockDetails as $osd)
                    <tr class="text-start" wire:key="{{ $osd['id'] }}">
                        <td class="fw-semibold ">{{fdate($osd['opening_date'])}}</td>
                        <td class="fw-semibold ">{{$osd->opening_stock_voucher_no}}</td>
                        <td class="fw-semibold ">{{$osd['productName']}} {{$osd['variation_name'] ? '('.$osd['variation_name'].')' : ''}} <br>
                            <span class="fs-7 text-gray-600 fw-bold">SKU : {{$osd['variation_sku']}}</span>

                        </td>
                        <td class="fw-semibold text-end">{{price($osd->uom_price ?? 0)}}</td>
                        <td class="fw-semibold text-end">{{formatNumberV2($osd['quantity'])}} {{$osd['uomShortName'] ?? ''}}</td>
                        <td class="fw-semibold  text-end">{{price($osd->subtotal)}}</td>
                        <td class="fw-semibold  text-end">{{fdate($osd->expired_date,false,false)}}</td>
                        <td class="fw-semiobold text-end">
                            {{$osd->username}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold fs-6 border-top-1">
                        <th colspan="3" class="text-nowrap text-start fs-2">Total Opening Amount:</th>
                        <th colspan="3" class="min-w-125px text-dark text-end  pe-3">
                            {{price($openingStockDetails->sum('subtotal'))}}</th>
                        {{-- <th colspan="1" class="min-w-125px text-dark text-end  pe-3">
                            {{formatPrice($purchases->sum('paid_amount'),$saleData[0]->currency ?? [])}}
                        </th>
                        <th colspan="1" class="min-w-125px text-dark text-end  pe-3">
                            {{formatPrice($purchases->sum('balance_amount'),$saleData[0]->currency ?? [])}}
                        </th> --}}
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="row justify-content-center mt-5  justify-content-md-between">
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
                {{$openingStockDetails->onEachSide(3)->links()}}
            </div>
        </div>
    </div>
    <script wire:ignore>
        $(document).ready(function() {
        let table = document.querySelector('#kt_Item_table');
        initToggleToolbar(table);
        handleDeleteRows();
        $('#select2').select2().on('select2:select', function (e) {
            @this.set('businesslocationFilterId', $('#select2').select2("val"));
        });
         $('#customerFilter').select2().on('select2:select', function (e) {
            @this.set('customerFilterId', $('#customerFilter').select2("val"));
        });

         $('#statusFilter').select2().on('select2:select', function (e) {
            @this.set('statusFilter', $('#statusFilter').select2("val"));
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
        }, cb);
    });


             // Delete
    var handleDeleteRows = (table) => {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('[data-kt-table="delete_row"]');
        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();
                console.log('hello');
                // Select parent row
                const parent = e.target.closest('tr');

                // Get purchase name
                const purchaseName = parent.querySelectorAll('td')[3].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + purchaseName + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        let id=d.getAttribute('data-id')
                            $.ajax({
                                url: `/purchase/${id}/softDelete`,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(s) {
                                    @this.set('statusFilter', $('#statusFilter').select2("val"));
                                    Swal.fire({
                                        text: "You have deleted " + purchaseName + "!.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    }).then(function () {
                                    });
                                },
                                error: function (response, error) {
                                    let message = response.responseJSON.message
                                    Swal.fire({
                                        text: message,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    })

                                }
                            })
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: purchaseName + " was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            })
        });
    }


    // Init toggle toolbar
    var initToggleToolbar = (table) => {
        // Toggle selected action toolbar
        // Select all checkboxes
        const checkboxes = table.querySelectorAll('[data-checked="delete"]');
        const selectAll = table.querySelector('#selectAll');
        // Select elements
        const deleteSelected = document.querySelector('[data-kt-table-select="delete_selected"]');

        // Toggle delete selected toolbar
        checkboxes.forEach(c => {
            // Checkbox on click event
            c.addEventListener('click', function () {
                console.log('click');
                setTimeout(function () {
                    toggleToolbars(table);
                }, 50);
            });
        });
        selectAll.addEventListener('click',function () {
                setTimeout(function () {
                    toggleToolbars(table);
                }, 50);
        })

        // Deleted selected rows
        deleteSelected.addEventListener('click', function () {
            // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
            Swal.fire({
                text: "Are you sure you want to delete selected locations?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function (result) {
                if (result.value) {
                    let data=[];
                    checkboxes.forEach(c => {
                        if (c.checked) {
                            console.log(c.value);
                            data = [...data,c.value];
                        }
                    });
                    $.ajax({
                        url: `/purchase/softDelete`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            data,
                        },
                        success: function(s) {
                            @this.set('statusFilter', $('#statusFilter').select2("val"));
                            Swal.fire({
                                text: "You have successfully deleted selected Purchase!.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            }).then(function () {
                                //sth
                                success(s.success);
                            });
                        },

                        error: function (response, error) {
                            let message = response.responseJSON.message
                            Swal.fire({
                                text: message,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            })

                        }
                    })
                    // Swal.fire({
                    //     text: "You have deleted all selected Purchases!.",
                    //     icon: "success",
                    //     buttonsStyling: false,
                    //     confirmButtonText: "Ok, got it!",
                    //     customClass: {
                    //         confirmButton: "btn fw-bold btn-primary",
                    //     }
                    // }).then(function () {
                    //     // Remove all selected locations


                    //     // Remove header checked box
                    //     const headerCheckbox = table.querySelectorAll('[type="checkbox"]')[0];
                    //     headerCheckbox.checked = false;
                    // });
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Selected locations was not deleted.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });
        });
    }
    const toggleToolbars = (table) => {
            // Define variables
            const toolbarBase = document.querySelector('[data-kt-saleItem-table-toolbar="base"]');
            const toolbarSelected = document.querySelector('[data-kt-saleItem-table-toolbar="selected"]');
            const selectedCount = document.querySelector('[data-kt-saleItem-table-select="selected_count"]');

            // Select refreshed checkbox DOM elements
            const allCheckboxes = table.querySelectorAll('tbody [type="checkbox"]');

            // Detect checkboxes state & count
            let checkedState = false;
            let count = 0;

            // Count checked boxes
            allCheckboxes.forEach(c => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
            });

            // Toggle toolbars
            if (checkedState) {
                selectedCount.innerHTML = count;
                toolbarBase.classList.add('d-none');
                toolbarSelected.classList.remove('d-none');
            } else {
                toolbarBase.classList.remove('d-none');
                toolbarSelected.classList.add('d-none');
            }
            }
    </script>
</div>
