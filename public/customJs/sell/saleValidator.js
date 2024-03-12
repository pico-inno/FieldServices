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
                    let credit_limit = $('#credit_limit').val();
                    console.log(credit_limit,'credit_limit',$('#total_balance_amount').val() );
                    console.log( $('#total_balance_amount').val() > credit_limit," $('#total_balance_amount').val() > credit_limit");
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
                    } else if (isNullOrNan($('#total_balance_amount').val() )> isNullOrNan(credit_limit)) {
                        console.log($('#total_balance_amount').val() > credit_limit);
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

    $('#sale_form').submit(function() {
        var submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true); // Disables the button

        $('[data-kt-sale-action="submit"]').prop('disabled', true);

        // You can also add a visual indicator that the form is being submitted,
        // for example, changing the button text to "Submitting...".
        submitButton.html('Saving...');

        // Ensure form submission continues after button disabling
        return true;
    });

})
