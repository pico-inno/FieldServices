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
            const realDate = moment(dateRow[2].innerHTML, "DD MMM YYYY, LT").format(); // select date from 5th column in table
            // dateRow[2].setAttribute('data-order', realDate);
        });

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            pageLength: 30,
            lengthMenu: [10, 20, 30, 50,40,80],
            'columnDefs': [
                { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
                { orderable: false, targets: 1 }, // Disable ordering on column 6 (actions)
                // {    targets: [2],
                //     visible: true,
                //     searchable: false
                // }
            ],
            processing: true,
            serverSide: true,
            ajax: {
                url: '/transfer/tableData',
            },

            columns: [
                // {
                //     data: 'checkbox',
                //     name: 'checkbox',
                //
                // },
                {
                    data: 'action',
                    name: 'action',
                },
                {
                    name:'transfered_at',
                    data:'transfered_at',
                },
                {
                    data: 'transfer_voucher_no',
                    name: 'transfer_voucher_no'
                },
                {
                    name: "business_location_from",
                    data: "business_location_from",
                },
                {
                    data: 'business_location_to',
                    name: 'business_location_to'
                },
               {
                    name:'status',
                    data:'status'
                },


            ]

        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            // initToggleToolbar();
            handleDeleteRows();
            handleFromLocationFilter();
            handleToLocationFilter();
            // toggleToolbars();
            handleStatusFilter();
            handleDateFilterDatatable();
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

    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-transfer-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }
    var handleDateFilterDatatable = () => {
        const filterSearch = document.querySelector('[data-transfer-date-filter="date"]');
        $(filterSearch).on('change', e => {
            // console.log('good');
            let value = e.target.value;
            const dateRange = value.split(" - ");

            if (dateRange.length === 2) {

                $.fn.dataTableExt.afnFiltering.push(
                    function( settings, data, dataIndex ) {
                        // var min  = $('#min-date').val()
                        // var max  = $('#max-date').val()
                        // var createdAt = data[0] || 0; // Our date column in the table
                        //createdAt=createdAt.split(" ");
                        // var startDate   = moment(min, "DD/MM/YYYY");
                        // var endDate     = moment(max, "DD/MM/YYYY");
                        // var diffDate = moment(createdAt, "DD/MM/YYYY");
                        //console.log(startDate);
                        if (
                            (min == "" || max == "") ||
                            (diffDate.isBetween(startDate, endDate))


                        ) {  return true;  }
                        return false;

                    }
                );
            }

        });
    }

    var handleFromLocationFilter = () => {
        const filterStatus = document.querySelector('[data-transfer-table-filter="from_location"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            if (value === 'all') {
                value = '';
            }

            datatable.column(3).search(value).draw();
        });
    }

    var handleToLocationFilter = () => {
        const filterStatus = document.querySelector('[data-transfer-table-filter="to_location"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            if (value === 'all') {
                value = '';
            }

            datatable.column(4).search(value).draw();
        });
    }


    var handleStatusFilter = () => {
        const filterStatus = document.querySelector('[data-transfer-table-filter="status"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            if (value === 'all') {
                value = '';
            }
            datatable.column(5).search(value).draw();
        });
    }

    // Delete location
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('[data-kt-transferItem-table="delete_row"]');
        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                var id = $(this).data('id');
                var voucherNo = $(this).data('transfer-voucher-no');
                var status = $(this).data('transfer-status');

                console.log('ID:', id);
                console.log('Voucher No:', voucherNo);
                e.preventDefault();

                if(status == 'in_transit' || status == 'completed'){
                    Swal.fire({
                        text: "Are you sure you want to delete " + voucherNo + "?",
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
                        if (result.isConfirmed) {
                            Swal.fire({
                                text: "Restore delivered stock or not?",
                                icon: "question",
                                showCancelButton: false,
                                buttonsStyling: false,
                                // cancelButtonText: "not restore",
                                confirmButtonText: "restore",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-danger",
                                    // cancelButton: "btn fw-bold btn-active-light-primary"
                                }
                            }).then(function (result) {
                                let url;
                                if (result.isConfirmed) {
                                    url = `/stock-transfer/${id}/delete?restore=true`;
                                } else if (result.dismiss === 'cancel') {
                                    url = `/stock-transfer/${id}/delete?restore=true`;
                                } else {
                                    url = `/stock-transfer/${id}/delete?restore=true`;
                                }
                                $.ajax({
                                    url,
                                    type: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (s) {
                                        datatable.ajax.reload();
                                        Swal.fire({
                                            text: voucherNo + ' was successfully deleted. '+s.success,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn fw-bold btn-primary",
                                            }
                                        }).then(function () {
                                          });
                                    }
                                });
                            });
                        } else if (result.dismiss === 'cancel') {
                            Swal.fire({
                                text: voucherNo + " was not deleted.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            });
                        }
                    });
                }else{
                    Swal.fire({
                        text: "Are you sure you want to delete " + voucherNo + "?",
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
                        if (result.isConfirmed) {

                            let url = `/stock-transfer/${id}/delete?restore=true`;

                            $.ajax({
                                url,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (s) {
                                    datatable.ajax.reload();
                                    Swal.fire({
                                        text: s.success + voucherNo + "!",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    }).then(function () {
                                 });
                                }
                            });
                        } else if (result.dismiss === 'cancel') {
                            Swal.fire({
                                text: voucherNo + " was not deleted.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            });
                        }
                    });
                }

            })
        });
    }



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
                        url: `#`,
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
                        }
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
            table = document.querySelector('#stocktransfer_table');

            if (!table) {
                return;
            }

            initCustomerList();
            // initToggleToolbar();
            handleSearchDatatable();
            handleDeleteRows();
            handleStatusFilter();
            handleFromLocationFilter();
            handleToLocationFilter();
            handleDateFilterDatatable();

        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});
