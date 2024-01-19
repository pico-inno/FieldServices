"use strict";

// Class definition
    var datatable;
    var KTCustomersList = function () {
        // Define shared variables
        var filterMonth;
        var filterPayment;
        var table

        // Private functions
        var initCustomerList = function () {
            // Set date data order
            const tableRows = table.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                const dateRow = row.querySelectorAll('td');
                const realDate = moment(dateRow[5].innerHTML, "DD MMM YYYY, LT").format(); // select date from 5th column in table
                dateRow[5].setAttribute('data-order', realDate);
            });

            // Init datatable --- more info on datatables: https://datatables.net/manual/
            let columns = [
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    orderable: false,
                },
                {
                    name: 'saleItems',
                    data: 'saleItems',
                },
                {
                    data: 'sales_voucher_no',
                    name: 'sales_voucher_no'
                },
                {
                    data: 'customer',
                    name: 'customer'
                }, {
                    data: 'sale_amount',
                    name: 'sale_amount',
                    render: (data, display, value) => {
                        return nfpDecimal(data,value.currency,'a');
                    }
                },
                {
                    data: 'paid_amount',
                    name: 'paid_amount',
                    render: (data, display, value) => {
                        return nfpDecimal(data,value.currency,'a');
                    }
                },
                 {
                    data: 'balance_amount',
                    name: 'balance_amount',
                    render: (data, display, value) => {
                        return nfpDecimal(data,value.currency,'a');
                    }
                },
                {
                    data: "businessLocation",
                    name: "businessLocation"
                },

                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'sold_at',
                    name: 'sold_at'
                },

            ];
            if (saleType == 'posSales') {
                columns = [
                    ...columns.slice(0, 4),
                    {
                        data: 'table_id',
                        name: 'table_id',
                        render: function (data) {
                            return data ?? '-';
                        }
                    },
                    ...columns.slice(4)
                ];
            }

            datatable = $(table).DataTable({
                 "ordering": false,
                pageLength: 25,
                lengthMenu: [15, 25, 35, 45,50,80],
                'columnDefs': [
                    { orderable: false, targets: 0 },
                    { orderable: false, targets: 1 },
                    {    targets: [2],
                        visible: false
                    }
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/sell/get/saleItem',
                    data: function (data) {
                        data.saleType=$('#saleType').val() ?? 'allSale',
                        data.from_date=$('#kt_daterangepicker_4').data('daterangepicker').startDate.format('YYYY-MM-DD');
                        data.to_date=$('#kt_daterangepicker_4').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    },
                },

                columns,
                footerCallback: function (row, data, start, end, display) {
                    let api = this.api();

                    // Remove the formatting to get integer data for summation
                    let intVal = function (i) {
                        return typeof i === 'string'
                            ? i.replace(/[\$,]/g, '') * 1
                            : typeof i === 'number'
                            ? i
                            : 0;
                    };

                    // Total over this page
                    var pageTotal = api
                        .column(5, { page: 'current' })
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                    api.column(5).footer().innerHTML = dfpDecimal(pageTotal);


                    var pageBalanceTotal = api
                        .column(6, { page: 'current' })
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                    api.column(6).footer().innerHTML = dfpDecimal(pageBalanceTotal) ;

                    var pageBalanceTotal = api
                        .column(7, { page: 'current' })
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                    api.column(7).footer().innerHTML = dfpDecimal(pageBalanceTotal) ;
                }
            });

            // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
            datatable.on('draw', function () {
                initToggleToolbar();
                handleDeleteRows();
                handleBusinessLocationFilter();
                // DateRangeFilter();
                handleCustomerFilter();
                handleStatusFilter();
                // toggleToolbars();
                // handleStatusFilter();
                // handleDateFilterDatatable();
            });
        }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-saleItem-table-filter="search"]');
        filterSearch.addEventListener('keyup', debounce(function (e) {
            datatable.search(e.target.value).draw();
        }));
    }
    var handleDateFilterDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-date-filter="date"]');
        $(filterSearch).on('change', e => {
            let value = e.target.value;
            console.log(value);
            if (value === 'all') {
                value = '';
            }
            datatable.column(2).search(value).draw();
        });
    }
        // Handle status filter dropdown
    var handleBusinessLocationFilter = () => {
        const filterStatus = document.querySelector('[data-kt-saleItem-table-filter="businesslocation"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            console.log(value);
            if (value === 'all') {
                value = '';
            }
            datatable.column(8).search(value).draw();
        });
    }
    // var DateRangeFilter = () => {
    //         const filterStatus = document.querySelector('[data-kt-saleItem-table-filter="dateRange"]');

    //         $(filterStatus).on('change', function(e) {
    //             // Get selected dates
    //             var dates = $(this).val().split(" - ");
    //             var startDate = dates[0];
    //             var endDate = dates[1];

    //             // datatable.ext.search.push(function (settings, data, dataIndex) {
    //             //     var startDate = dates[0];
    //             //     var endDate = dates[1];
    //             //     var age = parseFloat(data[3]) || 0; // use data for the age column

    //             //     if (
    //             //         (isNaN(min) && isNaN(max)) ||
    //             //         (isNaN(min) && age <= max) ||
    //             //         (min <= age && isNaN(max)) ||
    //             //         (min <= age && age <= max)
    //             //     ) {
    //             //         return true;
    //             //     }

    //             //     return false;
    //             // });
    //             // Apply date range filter
    //             datatable.column(4).search('^(' + startDate + '|' + endDate + ')$').draw();
    //         });
    // }
    var handleCustomerFilter = () => {
        const filterStatus = document.querySelector('[data-filter="customer"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            console.log(value);
            if (value === 'all') {
                value = '';
            }
            datatable.column(4).search(value).draw();
        });
    }
    var handleStatusFilter = () => {
        const filterStatus = document.querySelector('[data-filter="status"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            console.log(value);
            if (value === 'all') {
                value = '';
            }
            datatable.column(9).search(value).draw();
        });
    }


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
                                    datatable.ajax.reload();
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


    // Toggle toolbars

        // Init toggle toolbar
        var initToggleToolbar = () => {
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
                        toggleToolbars();
                    }, 50);
                });
            });
            selectAll.addEventListener('click',function () {
                 setTimeout(function () {
                        toggleToolbars();
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
                        swal.fire({
                        text: "Restore sale stock or not  ?",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "restore",
                        cancelButtonText: "not restore",
                        customClass: {
                            confirmButton: "btn fw-bold btn-success",
                            cancelButton: "btn fw-bold btn-danger"
                        }
                        }).then(function (result) {
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
                                        datatable.ajax.reload();
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
                                            toggleToolbars();

                                        });

                                    }
                                })
                                const headerCheckbox = table.querySelectorAll('[type="checkbox"]')[0];
                                headerCheckbox.checked = false;
                                // Swal.fire({
                                //     text: "You have deleted all selected locations!.",
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
                        })
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


        // Toggle toolbars
        const toggleToolbars = () => {
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

                    // cb(start, end);
            var start = moment().subtract(1, "M");
            var end = moment();

            function cb(start, end) {
                $("#kt_daterangepicker_4").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
                datatable.draw();
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

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#kt_saleItem_table');

            if (!table) {
                return;
            }

            initCustomerList();
            initToggleToolbar();
            handleSearchDatatable();
            handleDeleteRows();
            // handleStatusFilter();
            handleBusinessLocationFilter();
            // DateRangeFilter();
            handleCustomerFilter();
            handleStatusFilter();
            // handleDateFilterDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});


