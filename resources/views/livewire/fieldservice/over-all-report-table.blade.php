<style>
    .pagination{
        justify-content: center !important;
        }
        @media(min-width:780px){
        .pagination{
        justify-content: end !important;
        }
        }
</style>
<div class="">
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
                        <div class="col-12 col-md-4 col-lg-3 mb-5">
                            <label class="form-label  fs-6 fw-semibold">date:</label>
                            <input class="form-control form-control-sm form-control-solid" placeholder="Pick date rage"
                                data-kt-saleItem-table-filter="dateRange" id="kt_daterangepicker_4"
                                data-dropdown-parent="#filter" />
                        </div>
                        <div class="col-12 col-md-4 col-lg-3 mb-5">
                            <label class="form-label  fs-6 fw-semibold">
                                <i class="fa-solid fa-circle fs-9 text-primary me-1"></i>
                                Filter By Outlet:</label>
                            <select class="form-select form-select-sm fw-bold locationFilter"
                                data-placeholder="Select option" id="select2" data-kt-select2="true"
                                data-kt-table-filter="outlet">
                                <option value="all">All</option>
                                @foreach ($locations as $l)
                                <option value="{{$l->id}}">{{ businessLocationName($l) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3 mb-5">
                            <label class="form-label  fs-6 fw-semibold">
                                <i class="fa-solid fa-circle fs-9 text-success me-1"></i>
                                Filter By PG:</label>
                            <select class="form-select form-select-sm fw-bold locationFilter"
                                data-placeholder="Select option" id="select2employee" data-kt-select2="true"
                                data-kt-table-filter="employee">
                                <option value="all">All</option>
                                @foreach ($employee as $e)
                                <option value="{{ $e->id }}">{{ $e->username }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3 mb-5">
                            <label class="form-label  fs-6 fw-semibold">
                                {{-- <i class="fa-solid fa-circle fs-9 text-success me-1"></i> --}}
                                Category:</label>
                            <select class="form-select form-select-sm fw-bold locationFilter"
                                data-placeholder="Select option" id="select2category" data-kt-select2="true"
                                data-kt-table-filter="category">
                                {{-- <option value="all">All</option> --}}
                                @foreach ($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
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
                        <!--end::Filter-->
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
            <div class="position-absolute w-fit  top-10 bg-white p-3 rounded-1 border border-1 border-gray-500 "
                wire:loading style="top: 40px;left:50%;">
                <h2 class="text-primary">Loading....</h2>
            </div>
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-7 gy-3 table-max-high" id="kt_saleItem_table"
                    data-sticky-header="true">
                    <thead>
                        <tr class="text-end text-gray-600 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-100px cursor-pointer text-start" wire:click="sortBy('sales_voucher_no')">
                                Product Name
                                <x-datatable.sort-icon field="sales_voucher_no" :sortField="$sortField" :sortAsc="$sortAsc" />
                            </th>
                            <th class="min-w-100px cursor-pointer text-start" wire:click="sortBy('contacts.first_name')">
                                <div class="">
                                    <span>Category</span>
                                    <x-datatable.sort-icon field="contacts.first_name" :sortField="$sortField" :sortAsc="$sortAsc" />
                                </div>
                            </th>
                            <th class="text-start pe-3 min-w-100px text-end">UOM</th>
                            <th class="text-start pe-3 min-w-100px">package Qty </th>
                            <th class="text-start pe-3 min-w-100px">Pkg</th>
                            <th class="text-start pe-3 min-w-100px">Outlet</th>
                            <th class="text-start pe-3 min-w-100px">Employee Location</th>
                            <th class="text-start pe-3 min-w-100px">Campaign</th>
                            <th class="text-start pe-3 min-w-100px">Employee</th>
                            <th class="text-start pe-3 min-w-100px "> Date</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-semibold text-gray-600 fs-6 fw-semibold" id="allSaleTable">

                        @foreach ($datas as $data)
                        <tr class="text-end">
                            <td class="text-start">{{$data['name']}}</td>
                            <td class="text-start">{{$data['category_name']}}</td>
                            <td>{{$data['uom']}}</td>
                            <td>{{formatPrice($data['paid_amount'] ?? 0,$data->currency)}}</td>
                            <td>{{formatPrice($data['balance_amount'] ?? 0,$data->currency)}}</td>
                            <td>{{$data['location_name']}}</td>
                            {{-- <td>
                                @php
                                $status=$s->status;
                                @endphp
                                @if($status == 'delivered')
                                <span class='badge badge-success'> {{$status}} </span>
                                @elseif ($status == 'draft')
                                <span class='badge badge-dark'>{{$status}}</span>
                                @elseif ($status == 'pending')
                                <span class='badge badge-warning'>{{$status}}</span>
                                @elseif ($status == 'order')
                                <span class='badge badge-primary'>{{$status}}</span>
                                @elseif ($status == 'partial')
                                <span class='badge badge-info'>{{$status}}</span>
                                @elseif ($status == 'quotation')
                                <span class='badge badge-secondary'>{{$status}}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                {{($s->sold_at)}}
                            </td> --}}
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
    <script wire:ignore>
        $(document).ready(function() {
        let table = document.querySelector('#kt_saleItem_table');
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


        // Delete location
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('[data-kt-saleItem-table="delete_row"]');
        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();
                console.log('hello');
                // Select parent row
                const parent = e.target.closest('tr');

                // Get saleItem name
                const saleItemName = parent.querySelectorAll('td')[2].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + saleItemName + "?",
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
                            let url = `/sell/${id}/delete?restore=true`;
                            $.ajax({
                                url,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(s) {
                                    $('.checkForDelete').prop('checked',false);
                                    @this.set('statusFilter', $('#statusFilter').select2("val"));
                                    Swal.fire({
                                        text: "You have deleted " + saleItemName + "!.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    }).then(function () {
                                        success(s.success);
                                    });
                                }
                            })
                    }else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: saleItemName + " was not deleted.",
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
        const deleteSelected = document.querySelector('[data-kt-saleItem-table-select="delete_selected"]');

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
                            let url;
                            if (result.value) {
                                url = `/sell/deletee/selected?restore=true`;
                            }else if (result.dismiss === 'cancel') {
                                url = `/sell/deletee/selected?restore=false`;
                            } else{
                                url = `/sell/deletee/selected?restore=true`;
                            }
                            let data=[];
                            checkboxes.forEach(c => {
                                if (c.checked) {
                                    data = [...data,c.value];
                                }
                            });
                            $.ajax({
                                url,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                data,
                                },
                                success: function(s) {
                                    $('.checkForDelete').prop('checked',false);
                                    @this.set('statusFilter', $('#statusFilter').select2("val"));
                                    Swal.fire({
                                        text: "You have deleted selected sale vouchers!.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    }).then(function () {
                                        //sth
                                        success(s.success);
                                        toggleToolbars(table);

                                    });

                                }
                            })
                            const headerCheckbox = table.querySelectorAll('[type="checkbox"]')[0];
                            headerCheckbox.checked = false;

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
