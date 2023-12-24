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



        let userPageLength = $('#pageLengthInput').val();
        let defaultPageLength = 25;
        let pageLength = userPageLength ? parseInt(userPageLength, 25) : defaultPageLength;

        datatable = $(table).DataTable({
            pageLength: pageLength,
            lengthMenu: [25, 30, 50,80, 150, 300],
            'columnDefs': [
                { orderable: false, targets: 0 },
                {    targets: [1],
                    visible: true,
                    searchable: false
                }
            ],
            processing: true,
            serverSide: true,
            ajax: {
                url: '/adjustment/tableData',
                type: 'GET',
                dataType: 'json',
                data: function (d) {
                    d.pageLength = d.length;
                    d.page = (d.start / d.length) + 1;
                }
            },
            columns: [
                {
                    data: 'action',
                    name: 'action',
                    render: function (data, type, row) {
                        var printUrl = 'adjustment/print/'+row.id+'/invoice';
                        var viewUrl = '/stock-adjustment/' + row.id;
                        var editUrl =  'stock-adjustment/'+row.id+'/edit';

                        var html = '<div class="dropdown ">' +
                            '<button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="purchaseDropDown" data-bs-toggle="dropdown" aria-expanded="false">' +
                            'Actions' +
                            '</button>' +
                            '<div class="z-3">' +
                            '<ul class="dropdown-menu z-10 p-5 " aria-labelledby="purchaseDropDown" role="menu">';


                            html += '<a class="dropdown-item p-2  px-3 view_detail  text-gray-600 rounded-2" type="button" data-href="' + viewUrl + '">' +
                                'View' +
                                '</a>';


                            html += ' <a class="dropdown-item p-2  px-3  text-gray-600 print-invoice rounded-2"  data-href="' + printUrl + '">Print</a>';
                            if (row.status === 'prepared') {
                                html += '<a href="' + editUrl + '" class="dropdown-item p-2  px-3 view_detail  text-gray-600 rounded-2">Edit</a>';
                            }

                            html += '<a class="dropdown-item p-2  px-3 view_detail  text-gray-600 round rounded-2" data-id=' + row.id + ' data-adjustment-voucher-no=' + row.adjustment_voucher_no + ' data-adjustment-status=' + row.status + ' data-kt-adjustmentItem-table="delete_row">Delete</a>';

                        html += '</ul></div></div>';

                        return html;
                    }
                },
                {
                    data: 'adjustmented_at',
                    name: 'adjustmented_at',
                    render: function (data, type, row) {
                        var date = new Date(row.adjustmented_at);

                        var options = { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric' };
                        var formattedDate = date.toLocaleDateString('en-US', options);

                        return formattedDate;
                    }
                },
                { data: 'adjustment_voucher_no', name: 'adjustment_voucher_no' },
                { data: 'business_location.name', name: 'businessLocation.name' },
                { data: 'increase_subtotal', name: 'increase_subtotal' },
                { data: 'decrease_subtotal', name: 'decrease_subtotal' },
                {
                    name: 'status',
                    data: 'status',
                    render: function (data, type, row) {
                        if (row.status === 'prepared') {
                            return '<span class="badge badge-light-warning">' + row.status + '</span>';
                        } else if (row.status === 'completed') {
                            return '<span class="badge badge-light-success">' + row.status + '</span>';
                        } else {
                            return row.status;
                        }
                    }
                },
                {
                    name: 'condition',
                    data: 'condition',
                    render: function (data, type, row) {
                        if (row.condition === 'abnormal') {
                            return '<span class="badge badge-light-warning">' + row.condition + '</span>';
                        } else if (row.condition === 'normal') {
                            return '<span class="badge badge-light-success">' + row.condition + '</span>';
                        } else {
                            return row.condition;
                        }
                    }
                },
                { data: 'created_person.username', name: 'createdPerson.username' },
            ]
        });




        datatable.on('draw', function () {

            handleDeleteRows();
            handleBusinessLocationFilter();
            // toggleToolbars();
            handleStatusFilter();
            // handleDateFilterDatatable();
            // handleStatusFilter();
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
        const filterSearch = document.querySelector('[data-kt-purchase-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }
    var handleDateFilterDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-date-filter="date"]');
        $(filterSearch).on('change', e => {
            let value = e.target.value;
            datatable.column(2).search(value).draw();
        });
    }

    var handleBusinessLocationFilter = () => {
        const filterStatus = document.querySelector('[data-kt-business-location-filter="locations"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            if (value === 'all') {
                value = '';
            }

            datatable.column(3).search(value).draw();
        });
    }

    var handleStatusFilter = () => {
        const filterStatus = document.querySelector('[data-table-filter="status"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            if (value === 'all') {
                value = '';
            }
            datatable.column(6).search(value).draw();
        });
    }
    var handleStatusFilter = () => {
        const filterStatus = document.querySelector('[data-kt-status-table-filter="status"]');
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
        const deleteButtons = document.querySelectorAll('[data-kt-adjustmentItem-table="delete_row"]');
        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();
                // Select parent row
                var id = $(this).data('id');
                var voucherNo = $(this).data('adjustment-voucher-no');
                var status = $(this).data('adjustment-status');
                const parent = e.target.closest('tr');

                if(status == 'completed'){
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
                                    url = `/stock-adjustment/${id}/delete?restore=true`;
                                } else if (result.dismiss === 'cancel') {
                                    url = `/stock-adjustment/${id}/delete?restore=false`;
                                } else {
                                    url = `/stock-adjustment/${id}/delete?restore=true`;
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

                                        })
                                        loadingOff();
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

                            let url = `/stock-adjustment/${id}/delete?restore=false`;

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
        const deleteSelected = document.querySelector('[data-adjustment-table-select="delete_selected"]');

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
        const toolbarBase = document.querySelector('[data-adjustment-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-adjustment-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-adjustment-table-select="selected_count"]');

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
            table = document.querySelector('#stockadjustment_table');

            if (!table) {
                return;
            }

            initCustomerList();
            // initToggleToolbar();
            handleSearchDatatable();
            handleDeleteRows();
            handleStatusFilter();
            handleBusinessLocationFilter();
            handleDateFilterDatatable();
            handleStatusFilter();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});
