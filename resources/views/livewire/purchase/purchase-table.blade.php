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
                    <a href="{{route('purchase_add')}}">
                        <button class="btn btn-sm btn-primary fs-9 fs-sm-7">
                            Add Purchase
                        </button>
                    </a>
                    <!--end::Add customer-->
                    @endif
                </div>
                <!--end::Toolbar-->
                <!--begin::Group actions-->
                <div class="d-flex justify-content-end align-items-center d-none"
                    data-kt-saleItem-table-toolbar="selected">
                    <div class="fw-bold me-5">
                        <span class="me-2" data-kt-saleItem-table-select="selected_count"></span>Selected
                    </div>
                    <button type="button" class="btn btn-danger" data-kt-table-select="delete_selected">Delete
                        Selected</button>
                </div>
                <!--end::Group actions-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <div class="card-body pt-0 saleTableCard table-responsive position-relative" id="">
            <div class="position-absolute w-fit  top-10 bg-white p-3 rounded-1 border border-1 border-gray-500 "
                wire:loading style="top: 40px;left:50%;">
                <h2 class="text-primary">Loading....</h2>
            </div>
            <table class="table align-middle table-row-dashed fs-7 gy-3 table-max-high" id="kt_Item_table"
                data-sticky-header="true">
                <thead>
                    <tr class="text-end text-gray-600 fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">
                            <div class="form-check form-check-sm form-check-custom  me-3">
                                <input class="form-check-input checkForDelete" data-checked="selectAll" id="selectAll"
                                    type="checkbox" data-kt-check="true"
                                    data-kt-check-target="#kt_Item_table .form-check-input" value="" />
                            </div>
                        </th>
                        <th class="min-w-100px text-center">
                            {{__('table/label.actions')}}
                        </th>
                        <th class="min-w-100px cursor-pointer" wire:click="sortBy('sales_voucher_no')">
                            {{__('table/label.voucher_no')}}
                            <x-datatable.sort-icon field="sales_voucher_no" :sortField="$sortField"
                                :sortAsc="$sortAsc" />
                        </th>
                        <th class="min-w-100px cursor-pointer" wire:click="sortBy('contacts.first_name')">
                            <div class="">
                                <span>Supplier</span>
                                <x-datatable.sort-icon field="contacts.first_name" :sortField="$sortField"
                                    :sortAsc="$sortAsc" />
                            </div>
                        </th>
                        <th class="min-w-100px text-end">{{__('table/label.total_purchase_amount')}}</th>
                        <th class="min-w-100px text-end">Paid Amount</th>
                        <th class="min-w-100px text-end">Balance Amount</th>
                        <th class="min-w-100px">Payment Status</th>
                        <th class="min-w-100px">{{__('table/label.location')}}</th>
                        <th class="min-w-100px">{{__('table/label.status')}}</th>
                        <th class="min-w-100px">{{__('table/label.received_at')}}</th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="fw-semibold text-gray-600 fs-6 fw-semibold" id="allSaleTable">

                    @foreach ($purchases as $p)
                    <tr class="text-end">
                        <td>
                            <div class="form-check form-check-sm form-check-custom ">
                                <input class="form-check-input checkForDelete" type="checkbox" data-checked="delete"
                                    value='{{$p->id}}' />
                            </div>
                        </td>
                        <td>
                            <div class="dropdown text-center">
                                <button class="btn btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle "
                                    type="button" id="saleItemDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="z-3">
                                    <ul class="dropdown-menu z-10 p-5 " aria-labelledby="saleItemDropDown" role="menu">
                                        @if ($hasView)
                                        <a class="dropdown-item p-2   view_detail" type="button"
                                            data-href="{{route('purchaseDetail', $p->id)}}">
                                            View
                                        </a>
                                        @endif
                                        @if ($hasUpdate && $p->status != "confirmed")
                                        <a href="{{route('purchase_edit', $p->id)}}"
                                            class="dropdown-item p-2   edit-unit ">Edit</a>
                                        @endif
                                        @if ($hasPrint)
                                        <a class="dropdown-item p-2  cursor-pointer  print-invoice"
                                            data-href="{{route('print_purchase', $p->id)}}"
                                            data-layoutId="{{$p->invoice_layout}}">Print</a>
                                        @endif
                                        @if ($p->balance_amount > 0 || $p->paid_amount < $p->purchase_amount)
                                        <a class="dropdown-item p-2 cursor-pointer " id="paymentCreate"
                                            data-href="{{route('paymentTransaction.createForPurchase', ['id' => $p->id, 'currency_id' => $p->currency_id])}}">Add
                                            Payment</a>
                                        @endif

                                        <a class="dropdown-item p-2 cursor-pointer " id="viewPayment"
                                            data-href="{{route('paymentTransaction.viewForPurchase', $p->id)}}">View
                                            Payment</a>
                                        @if ($hasDelete)
                                        <a class="dropdown-item p-2  cursor-pointer bg-active-danger text-danger"
                                            data-id="{{$p->id}}" data-kt-table="delete_row">Delete</a>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td>{{$p['purchase_voucher_no']}}</td>
                        <td>{{$p['company_name'] ?? getFullNameAttribute([
                            'prefix'=> $p['prefix'],
                            'first_name'=>$p['first_name'],
                            'last_name'=>$p['last_name'],
                            'middle_name'=>$p['middle_name'],
                            ])}}</td>
                        <td>{{formatPrice($p['total_purchase_amount'] ?? 0,$p->currency)}}</td>
                        <td>{{formatPrice($p['paid_amount'] ?? 0,$p->currency)}}</td>
                        <td>{{formatPrice($p['balance_amount'] ?? 0,$p->currency)}}</td>
                        <td><x-payment-status :status="$p['payment_status']" /></td>
                        <td>{{$p['location_name']}}</td>
                        <td>
                            @php
                            $status=$p->status;
                            @endphp
                            @if( $status == 'received')
                                <span class='badge badge-success'> Received </span>
                            @elseif ( $status == 'request')
                                <span class='badge badge-secondary'> {{$status}}</span>
                            @elseif ( $status == 'pending')
                                <span class='badge badge-warning'> {{$status}}</span>
                            @elseif ( $status == 'order')
                                <span class='badge badge-primary'> {{$status}}</span>
                            @elseif ( $status == 'partial')
                                <span class='badge badge-info'>{{$status}}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            {{fdate($p->received_at)}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold fs-6 border-top-1">
                        <th colspan="3" class="text-nowrap text-start fs-2">Total:</th>
                        <th colspan="2" class="min-w-125px text-dark text-end  pe-3">
                            {{formatPrice($purchases->sum('total_purchase_amount'),$saleData[0]->currency ?? [])}}</th>
                        <th colspan="1" class="min-w-125px text-dark text-end  pe-3">
                            {{formatPrice($purchases->sum('paid_amount'),$saleData[0]->currency ?? [])}}
                        </th>
                        <th colspan="1" class="min-w-125px text-dark text-end  pe-3">
                            {{formatPrice($purchases->sum('balance_amount'),$saleData[0]->currency ?? [])}}
                        </th>
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
                    {{$purchases->links()}}
                </div>
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
