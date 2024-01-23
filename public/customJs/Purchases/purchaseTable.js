"use strict";


var datatable;
// Class definition
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
        datatable = $(table).DataTable({
            pageLength: 30,
            lengthMenu: [10, 20, 30, 50,40,80],
            'columnDefs': [
                { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
                { orderable: false, targets: 1 }, // Disable ordering on column 6 (actions)
                {    targets: [2],
                    visible: false,
                    searchable: true
                }
            ],
            processing: true,
            serverSide: true,
               ajax: {
                url: 'purchase/list/data',
                data: function (data) {
                    data.from_date=$('#kt_daterangepicker_4').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    data.to_date=$('#kt_daterangepicker_4').data('daterangepicker').endDate.format('YYYY-MM-DD');
                }
            },

            columns: [
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false ,
                    orderable: false,
                },
                {
                    name:'purchaseItems',
                    data:'purchaseItems',
                },
                {
                    data: 'date',
                    name: 'date'
                },{
                    data: 'purchase_voucher_no',
                    name: 'purchase_voucher_no',

                },
                {
                    name: "location",
                    data: "location",
                },
                {
                    data: 'supplier',
                    name: 'supplier'
                },
                {
                    data: 'received_at',
                    name: 'received_at',
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    name:'total_purchase_amount',
                    data:'total_purchase_amount'
                },
                {
                    name:'payment_status',
                    data:'payment_status'
                }

            ]

        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            initToggleToolbar();
            handleDeleteRows();
            handleBusinessLocationFilter();
            toggleToolbars();
            handleStatusFilter();
            // handleDateFilterDatatable();
            handleSupplierFilter();
        });
    }
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
    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-purchase-table-filter="search"]');
        filterSearch.addEventListener('keyup',debounce( function (e) {
            datatable.search(e.target.value).draw();
        }));
    }
    // var handleDateFilterDatatable = () => {
    //     const filterSearch = document.querySelector('[data-kt-date-filter="date"]');
    //     $(filterSearch).on('change', e => {
    //         let value = e.target.value;
    //         if (value === 'all') {
    //             value = '';
    //         }
    //         datatable.column(3).search(value).draw();
    //     });
    // }
        // Handle status filter dropdown
    var handleBusinessLocationFilter = () => {
        const filterStatus = document.querySelector('[data-kt-business-location-filter="locations"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            if (value === 'all') {
                value = '';
            }
            datatable.column(5).search(value).draw();
        });
    }
    // supplierFilter
    var handleSupplierFilter = () => {
        const filterStatus = document.querySelector('[data-kt-supplier-table-filter="supplier"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            if (value === 'all') {
                value = '';
            }
            datatable.column(5).search(value).draw();
        });
    }
    var handleStatusFilter = () => {
        const filterStatus = document.querySelector('[data-kt-status-table-filter="status"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            if (value === 'all') {
                value = '';
            }
            datatable.column(6).search(value).draw();
        });
    }

        // Delete
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('[data-kt-purchase-table="delete_row"]');
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
                                url: `purchase/${id}/softDelete`,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(s) {
                                    datatable.ajax.reload();
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


    // Toggle toolbars

        // Init toggle toolbar
        var initToggleToolbar = () => {
            // Toggle selected action toolbar
            // Select all checkboxes
            const checkboxes = table.querySelectorAll('[data-checked="delete"]');
            const selectAll = table.querySelector('#selectAll');
            // Select elements
            const deleteSelected = document.querySelector('[data-kt-purchase-table-select="delete_selected"]');

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
                        let data=[];
                        checkboxes.forEach(c => {
                            if (c.checked) {
                                console.log(c.value);
                                data = [...data,c.value];
                            }
                        });
                        $.ajax({
                            url: `purchase/softDelete`,
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


        // Toggle toolbars
        const toggleToolbars = () => {
            // Define variables
            const toolbarBase = document.querySelector('[data-kt-purchase-table-toolbar="base"]');
            const toolbarSelected = document.querySelector('[data-kt-purchase-table-toolbar="selected"]');
            const selectedCount = document.querySelector('[data-kt-purchase-table-select="selected_count"]');

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

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#kt_purchase_table');

            if (!table) {
                return;
            }

            initCustomerList();
            initToggleToolbar();
            handleSearchDatatable();
            handleDeleteRows();
            handleStatusFilter();
            handleBusinessLocationFilter();
            // handleDateFilterDatatable();
            handleSupplierFilter();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});
