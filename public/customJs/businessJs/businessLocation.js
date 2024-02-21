"use strict";

// Class definition
var KTlocationsList = function () {
    // Define shared variables
    var datatable;
    var filterMonth;
    var filterPayment;
    var table

    // Private functions
    var initlocationList = function () {
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
                url: '/location/data',
            },

            columns: [

                {
                    data: 'checkbox',
                    name: 'checkbox',

                },
                {
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'location_code',
                    name: 'location_code'
                },
                {
                    data: 'location_type',
                    name: 'location_type'
                },
                {
                    data: 'address',
                    name: 'address',
                },
                {
                    data: 'city',
                    name: 'city'
                },
                {
                    data: 'zip_code',
                    name: 'zip_code'
                },
                {
                    data: 'state',
                    name: 'state'
                },
                {
                    data: 'country',
                    name: 'country'
                },
            ]
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            initToggleToolbar();
            handleDeleteRows();
            handleDeactiveRole();
            handleActiveRole();
            toggleToolbars();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-location-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Delete location
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-location-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get location name
                const locationName = parent.querySelectorAll('td')[1].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + locationName + "?",
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
                                url: `/location/${id}/delete`,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(s) {
                                    console.log(s)
                                 if (s.success){
                                     datatable.ajax.reload();
                                     Swal.fire({
                                         text: "You have deleted " + locationName + "!.",
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

                                 if (s.error){
                                     datatable.ajax.reload();
                                     Swal.fire({
                                         text: s.error,
                                         icon: "error",
                                         buttonsStyling: false,
                                         confirmButtonText: "Ok, got it!",
                                         customClass: {
                                             confirmButton: "btn fw-bold btn-primary",
                                         }
                                     })
                                 }

                                }
                            })
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: locationName + " was not deleted.",
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
 // Delete location
    var handleDeactiveRole = () => {
        // Select all delete buttons
        const deactiveButton = table.querySelectorAll('[data-kt-location-table-filter="deactive_row"]');
        console.log(deactiveButton);
        deactiveButton.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();
                const parent = e.target.closest('tr');
                let id = d.getAttribute('data-id');
                // Get location name
                const locationName = parent.querySelectorAll('td')[1].innerText;
                $.ajax({
                    url: `/location/${id}/deactive`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(s) {
                        datatable.ajax.reload();
                        success(s.success);
                    }
                })

            })
        });
    }

     // active location
    var handleActiveRole = () => {
        // Select all delete buttons
        const deactiveButton = table.querySelectorAll('[data-kt-location-table-filter="active_row"]');
        console.log(deactiveButton);
        deactiveButton.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();
                const parent = e.target.closest('tr');
                let id = d.getAttribute('data-id');
                // Get location name
                const locationName = parent.querySelectorAll('td')[1].innerText;
                $.ajax({
                    url: `/location/${id}/active`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(s) {
                        datatable.ajax.reload();
                        success(s.success);
                    }
                })

            })
        });
    }
    // Handle status filter dropdown
    var handleStatusFilter = () => {
        const filterStatus = document.querySelector('[data-kt-ecommerce-order-filter="status"]');
        $(filterStatus).on('change', e => {
            let value = e.target.value;
            if (value === 'all') {
                value = '';
            }
            datatable.column(9).search(value).draw();
        });
    }

    // Init toggle toolbar
    var initToggleToolbar = () => {
        // Toggle selected action toolbar
        // Select all checkboxes
        const checkboxes = table.querySelectorAll('[data-checked="delete"]');
        const selectAll = table.querySelector('#selectAll');
        // Select elements
        const deleteSelected = document.querySelector('[data-kt-location-table-select="delete_selected"]');

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
                        url: `/location/delete`,
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
                                text: "You have deleted selected locations!.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            }).then(function () {
                                //sth
                                success(s.success);
                                const headerCheckbox = table.querySelectorAll('[type="checkbox"]')[0];
                                headerCheckbox.checked = false
                            });
                        }
                    })
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
        const toolbarBase = document.querySelector('[data-kt-location-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-location-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-location-table-select="selected_count"]');

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
            table = document.querySelector('#business_location');

            if (!table) {
                return;
            }

            initlocationList();
            initToggleToolbar();
            handleSearchDatatable();
            handleDeleteRows();
            handleDeactiveRole();
            handleActiveRole();
            handleStatusFilter();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTlocationsList.init();
});


    // const dataTable=$('#business_location').DataTable({
    //     processing: true,
    //     serverSide: true,
    //     ajax: {
    //         url: 'location/data',
    //     },
    //     "columnDefs": [
    //         { "orderable": false, "targets": 0 }
    //     ],
    //     columns: [
    //         {
    //             data: 'checkbox',
    //             name: 'checkbox'
    //         },
    //         {
    //             data: 'name',
    //             name: 'name'
    //         },
    //         {
    //              data: 'location_id',
    //             name: 'location_id'
    //         },
    //         {
    //             data: 'landmark',
    //             name: 'landmark'
    //         },
    //         {
    //             data: 'city',
    //             name: 'city'
    //         },
    //         {
    //             data: 'zip_code',
    //             name: 'zip_code'
    //         },
    //         {
    //             data: 'state',
    //             name: 'state'
    //         },
    //         {
    //             data: 'country',
    //             name: 'country'
    //         },
    //         {
    //             data: 'action',
    //             name: 'action'
    //         }
    //     ]
    // })
    // table = document.querySelector('#business_location');
    // // // search data
    // var handleSearchDatatable = () => {
    //     const filterSearch = document.querySelector('[data-kt-location-table-filter="search"]');
    //     filterSearch.addEventListener('keyup', function (e) {
    //         dataTable.search(e.target.value).draw();
    //     });
    // }
    // $(document).on('click', '.location_delete_confirm', function(e) {
    //         e.preventDefault();
    //         let id = $(this).data('id');
    //         Swal.fire({
    //             title: 'Are you sure?',
    //             text: "You want to delete it!",
    //             type: 'warning',
    //             showCancelButton: true,
    //             confirmButtonColor: '#d33',
    //             cancelButtonColor: '#3085d6',
    //             confirmButtonText: 'Yes, delete it!'
    //         }).then((result) => {
    //             if (result.value) {
    //                 $.ajax({
    //                     url: '/uom/delete/'+id,
    //                     type: 'POST',
    //                     headers: {
    //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //                     },
    //                     data: {
    //                         _method: 'DELETE',
    //                     },
    //                     success: function() {
    //                         table.ajax.reload();
    //                     }
    //                 })
    //             }
    //         });

    // });
