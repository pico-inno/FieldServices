"use strict";

// Class definition
var KTCustomersList = function () {
    // Define shared variables
    var datatable;
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
            'columnDefs': [
               // Disable ordering on column 0 (checkbox)
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 },
            ],
            order: [[1, 'desc']],
            processing: true,
            serverSide: true,
               ajax: {
                url: '/openingStock/list/data',
            },

            columns: [
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false ,
                    orderable: false,
                },
                {
                    data: 'opening_stock_voucher_no',
                    name: 'opening_stock_voucher_no'
                },
                {
                    data: "opening_person.username",
                    name: "opening_person.username"
                },
                {
                    data: "business_location_id.name",
                    name: "business_location_id.name"
                },
                {
                    data: "total_opening_amount",
                    name: "total_opening_amount"
                },
                {
                    data: 'opening_date',
                    name: 'opening_date'
                }

            ]

        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            initToggleToolbar();
            handleDeleteRows();
            // handleBusinessLocationFilter();
            // toggleToolbars();
            // handleStatusFilter();
            // handleDateFilterDatatable();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-saleItem-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
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
        const filterStatus = document.querySelector('[data-kt-business-location-filter="locations"]');
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
        const filterStatus = document.querySelector('[data-kt-status-table-filter="status"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            if (value === 'all') {
                value = '';
            }
            datatable.column(6).search(value).draw();
        });
    }

        // Delete location
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('[data-kt-openingStock-table="delete_row"]');
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
                            $.ajax({
                                url: `/delete/${id}/opening/stock`,
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
                    } else if (result.dismiss === 'cancel') {
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
            const deleteSelected = document.querySelector('[data-kt-openingStock-table-select="delete_selected"]');

            // Toggle delete selected toolbar
            checkboxes.forEach(c => {
                // Checkbox on click event
                c.addEventListener('click', function () {
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
                            url: `/all/delete/opening/stock`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                               data,
                            },
                            success: function(s) {
                                datatable.ajax.reload();
                                setTimeout(function () {
                                    toggleToolbars();
                                    $('#selectAll').prop('checked', false);
                                }, 50);
                                Swal.fire({
                                    text: "You have deleted selected locations!.",
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
                        Swal.fire({
                            text: "You have deleted all selected locations!.",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            // Remove all selected locations


                            // Remove header checked box
                            const headerCheckbox = table.querySelectorAll('[type="checkbox"]')[0];
                            headerCheckbox.checked = false;
                        });
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

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#openingStockTable');

            if (!table) {
                return;
            }

            initCustomerList();
            initToggleToolbar();
            // handleSearchDatatable();
            handleDeleteRows();
            // handleStatusFilter();
            // handleBusinessLocationFilter();
            // handleDateFilterDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});
