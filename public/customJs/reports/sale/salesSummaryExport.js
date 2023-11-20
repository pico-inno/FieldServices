"use strict";

// Class definition
var KTCustomersExport = function () {
    var element;
    var submitButton;
    var cancelButton;
	var closeButton;
    var validator;
    var form;
    var modal;


    // Init form inputs
    var handleForm = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		validator = FormValidation.formValidation(
			form,
			{
				fields: {
                    'date': {
						validators: {
							notEmpty: {
								message: 'Date range is required'
							}
						}
					},
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap5({
						rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
					})
				}
			}
		);

		// Action buttons
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status === 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable submit button whilst loading
                        submitButton.disabled = true;

                        setTimeout(function () {
                            submitButton.removeAttribute('data-kt-indicator');

                            // Get the DataTable instance
                            var table = $('#sale_reports_table').DataTable();

                            // Get the selected export format
                            var format = $('#exportFormat').val();

                            // Create DataTable buttons
                            var dataTableButtons = new $.fn.dataTable.Buttons(table, {
                                buttons: [
                                    {
                                        extend: 'excel',
                                        title: 'Sale Summary Report',
                                        text:'<i class="fa fa-table fainfo" aria-hidden="true" ></i>',
                                        titleAttr: 'Export Excel',
                                        "oSelectorOpts": {filter: 'applied', order: 'current'},
                                        exportOptions: {
                                            columns: ':visible'
                                        },
                                            modifier: {
                                                page: 'all'
                                            },
                                            format: {
                                                header: function ( data, columnIdx ) {
                                                    if(columnIdx===1){
                                                        return 'column_1_header';
                                                    }
                                                    else{
                                                        return data;
                                                    }
                                                }
                                            }
                                        },

                                    {
                                        extend: 'csv',
                                        title: 'Stock Report',
                                        text: 'CSV',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    },
                                    {
                                        extend: 'pdf',
                                        title: 'Stock Report',
                                        text: 'PDF',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    },
                                    {
                                        extend: 'print',
                                        text: 'Stock Report',
                                        messageTop: 'Data Date Range: '+$('#kt_daterangepicker_5').val(),
                                        exportOptions: {
                                            columns: ':visible'
                                        },
                                    }
                                ]
                            });

                            // Append DataTable buttons to the exportOptions element
                            dataTableButtons.container().appendTo('#exportOptions');

                            // Trigger the export button based on the selected format
                            if (format === 'print') {
                                table.button('.buttons-' + format).trigger();
                            } else {
                                table.button('.buttons-' + format + ':visible').trigger();
                            }

                            // Show a success message using SweetAlert (Swal)
                            Swal.fire({
                                text: "Customer list has been successfully exported!",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function (result) {
                                if (result.isConfirmed) {
                                    modal.hide();
                                    // Enable submit button after loading
                                    submitButton.disabled = false;
                                    handleSwalConfirmation();
                                }
                            });

                            //form.submit(); // Submit form
                        }, 2000);
                    } else {
                        // Show an error message using SweetAlert (Swal)
                        Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            }
        });


        cancelButton.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    modal.hide(); // Hide modal
                    handleSwalConfirmation();
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        }
                    });
                }
            });
        });

		closeButton.addEventListener('click', function(e){
			e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    modal.hide(); // Hide modal
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        }
                    });
                }
            });
		});
    }



    var initForm = function () {
        const datepicker = form.querySelector("[name=date]");

        // Handle datepicker range -- For more info on flatpickr plugin, please visit: https://flatpickr.js.org/
        $(datepicker).flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            mode: "range"
        });
    }

    return {
        // Public functions
        init: function () {
            // Elements
            element = document.querySelector('#sale_summary_export_modal');
            modal = new bootstrap.Modal(element);

            form = document.querySelector('#kt_customers_export_form');
            submitButton = form.querySelector('#kt_customers_export_submit');
            cancelButton = form.querySelector('#kt_customers_export_cancel');
			closeButton = element.querySelector('#kt_customers_export_close');

            handleForm();
            initForm();

        }
    };
}();


//Date Range Picker
    var start1 = moment().startOf("year");
    var end1 = moment().endOf("year");
    var start2 = moment().startOf("year");
    var end2 = moment().endOf("year");

    function cb1(start, end) {
    $("#kt_daterangepicker_5").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
    start2 = start;
    end2 = end;
    $("#kt_daterangepicker_4").data("daterangepicker").setStartDate(start2);
    $("#kt_daterangepicker_4").data("daterangepicker").setEndDate(end2);
}

    function cb2(start, end) {
    $("#kt_daterangepicker_4").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
    start1 = start;
    end1 = end;
    $("#kt_daterangepicker_5").data("daterangepicker").setStartDate(start1);
    $("#kt_daterangepicker_5").data("daterangepicker").setEndDate(end1);
}

    $("#kt_daterangepicker_5").daterangepicker({
    startDate: start1,
    endDate: end1,
    ranges: {
        "Today": [moment(), moment()],
        "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
        "Last 7 Days": [moment().subtract(6, "days"), moment()],
        "Last 30 Days": [moment().subtract(29, "days"), moment()],
        "This Month": [moment().startOf("month"), moment().endOf("month")],
        "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
        "This Year": [moment().startOf("year"), moment().endOf("year")],
        "Last Year": [moment().subtract(1, "year").startOf("year"), moment().subtract(1, "year").endOf("year")]
    }

}, cb1);

    $("#kt_daterangepicker_4").daterangepicker({
    startDate: start2,
    endDate: end2,
    ranges: {
        "Today": [moment(), moment()],
        "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
        "Last 7 Days": [moment().subtract(6, "days"), moment()],
        "Last 30 Days": [moment().subtract(29, "days"), moment()],
        "This Month": [moment().startOf("month"), moment().endOf("month")],
        "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
        "This Year": [moment().startOf("year"), moment().endOf("year")],
        "Last Year": [moment().subtract(1, "year").startOf("year"), moment().subtract(1, "year").endOf("year")]
    }

    }, cb2);

    cb1(start1, end1);
    cb2(start2, end2);

//Date Range Picker

//Column Visiable Checkbox
$(document).ready(function() {
    var dataTable = $('#sale_reports_table').DataTable();
    var allColumnsCheckbox = $('#chkAllColumns');
    var columnCheckboxes = $('.column_checkboxes input[type="checkbox"]').not(allColumnsCheckbox);

    // Event handler for the "All" checkbox
    allColumnsCheckbox.on('change', function() {
        var checked = $(this).is(':checked');
        columnCheckboxes.prop('checked', checked);

        // Toggle visibility for all columns
        dataTable.columns().visible(checked);
    });

    // Event handlers for individual column checkboxes
    columnCheckboxes.on('change', function() {
        var columnIndex = $(this).parent().index();

        if (columnIndex === 0) {
            dataTable.column(0).visible($(this).is(':checked'));
        } else {
            var column = dataTable.column(columnIndex - 1);
            column.visible($(this).is(':checked'));
        }

        // Check/uncheck "All" checkbox based on the state of individual column checkboxes
        var allChecked = columnCheckboxes.filter(':checked').length === columnCheckboxes.length;
        allColumnsCheckbox.prop('checked', allChecked);
    });
});
//Column Visiable Checkbox

function handleSwalConfirmation() {
    // Check all the checkboxes
    var columnCheckboxes = $('.column_checkboxes input[type="checkbox"]').not('#chkAllColumns');
    columnCheckboxes.prop('checked', true);

    // Toggle visibility for all columns
    var table = $('#sale_reports_table').DataTable();
    table.columns().visible(true);

    // Check the "All" checkbox
    var allColumnsCheckbox = $('#chkAllColumns');
    allColumnsCheckbox.prop('checked', true);

    // Enable submit button after loading
    document.getElementById('submitButton');
// submitButton.disabled = false;
}

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomersExport.init();
});
