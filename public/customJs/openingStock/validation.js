    // user update validation
    var openingStockValidator = function () {
        // Shared variables

        const element = document.getElementById("openingStock_container");
        const form = element.querySelector("#openingStock_form");
        const table = $('#openingStockTable tbody tr').length-1;
        // Init add schedule modal
        var initAddOpeningStock = () => {
             var validationFields = {
                        contact_id: {
                            validators: {
                                notEmpty: { message: "Customer is required" },
                            },
                        },
                        status: {
                            validators: {
                                notEmpty: { message: " Status field is required" },
                            },
                        },
                        business_location_id: {
                            validators: {
                                notEmpty: { message: "* Location field is required" },
                            },
                        },
                    };
            for (let i = 0; i < table; i++) {
                    validationFields[`opening_stock_details[${i}][uomset_id]`] = {
                        validators: {
                            notEmpty: { message: "Uom Set is required" },
                        },
                    };
                    validationFields[`opening_stock_details[${i}][unit_id]`] = {
                        validators: {
                            notEmpty: { message: "Unit is required" },
                        },
                    };
            }
            var validator = FormValidation.formValidation(
                form,
                {
                    fields:validationFields,

                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    },

                }
            );
            // Submit button handler
            const submitButton = element.querySelectorAll('[data-kt-opening-stock-action="submit"]');
            submitButton.forEach((btn) => {
                btn.addEventListener('click', function (e) {
                    if (validator) {
                        validator.validate().then(function (status) {
                            if (status == 'Valid') {
                                e.currentTarget=true;
                                return true;
                            } else {
                                e.preventDefault();
                                // Show popup warning. For more info check the plugin's official documentation: https://sweetalert2.github.io/
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
            })

        }

        // Select all handler

        return {
            // Public functions
            init: function () {
                initAddOpeningStock();
            }
        };
    }();
    // On document ready
    KTUtil.onDOMContentLoaded(function () {
        openingStockValidator.init();
    });
