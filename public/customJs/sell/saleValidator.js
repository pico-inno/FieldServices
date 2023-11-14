$(document).ready(function () {
        // user update validation
    var saleValidator = function () {
        // Shared variables

        const element = document.getElementById("sale_container");
        const form = element.querySelector("#sale_form");

        // Init add schedule modal
        var initAddUser = () => {

            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            var validator = FormValidation.formValidation(
                form,
                {
                    fields: {
                        contact_id: {
                            validators: {
                                notEmpty: { message: "* Customer is required" },
                            },
                        },

                        business_location_id: {
                            validators: {
                                notEmpty: { message: "* Location Field is required" },
                            },
                        },
                    },

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
            const submitButton = element.querySelectorAll('[data-kt-sale-action="submit"]');
            submitButton.forEach((btn) => {
                btn.addEventListener('click', function (e) {
                    check();
                    let qtyValidate = productsOnSelectData.find(function (pd) {
                        return pd.validate==false;
                    });
                    if (qtyValidate) {
                        e.preventDefault();
                         Swal.fire({
                                text: "Sorry, Some products are out of stock, please carefully check stock.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                    } else if ( $('#total_balance_amount').val() > credit_limit) {
                        e.preventDefault();
                        $('#credit_limit_message').removeClass('d-none');
                            Swal.fire({
                                text: "Customer's Credit limit is reached.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                    }
                    else {

                        $('#credit_limit_message').addClass('d-none');
                        if (validator) {
                            validator.validate().then(function (status) {
                                console.log(status);
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
                    }

                });
            })

        }

        // Select all handler

        return {
            // Public functions
            init: function () {
                initAddUser();
            }
        };
    }();
    // On document ready
    KTUtil.onDOMContentLoaded(function () {
        saleValidator.init();
    });

    function check()
    {
        let saleRow = document.querySelectorAll('.sale_row');
        saleRow.forEach((sr) => {
            checkStock($(sr));
        })
    }


})
