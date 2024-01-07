
    // user update validation
var validator = function () {
    // Shared variables

    const element = document.getElementById("invoice-container");
    const form = element.querySelector("#invoice");
    // Init add schedule modal
    var initValidator = () => {

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    name: {
                        validators: {
                            notEmpty: { message: "* Name required" }
                        },
                    },

                    layout: {
                        validators: {
                            notEmpty: { message: "* Name required" }
                        },
                    },
                    'number[label]':{
                        validators: {
                            notEmpty: { message: "* Label for number is required" }
                        },
                    },
                    'description[label]':{
                        validators: {
                            notEmpty: { message: "* Label for description is required" }
                        },
                    },
                    'quantity[label]':{
                        validators: {
                            notEmpty: { message: "* Label for quantity is required" }
                        },
                    },
                    'uom_price[label]':{
                        validators: {
                            notEmpty: { message: "* Label for uom-price is required" }
                        },
                    },
                    'discount[label]':{
                        validators: {
                            notEmpty: { message: "* Label for discount is required" }
                        },
                    },
                    'subtotal[label]':{
                        validators: {
                            notEmpty: { message: "* Label for subtotal is required" }
                        },
                    },
                    'net_sale_amount[label]':{
                        validators: {
                            notEmpty: { message: "* Label for net sale amount is required" }
                        },
                    },
                    'extra_discount_amount[label]':{
                        validators: {
                            notEmpty: { message: "* Label for extra discount amount is required" }
                        },
                    },
                    'total_sale_amount[label]':{
                        validators: {
                            notEmpty: { message: "* Label for total sale amount is required" }
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
        const submitButton = element.querySelectorAll('#submit');
        submitButton.forEach((btn) => {
            btn.addEventListener('click', function (e) {
                    if (validator) {
                        validator.validate().then(function (status) {
                            if (status == 'Valid') {
                                e.currentTarget=true;
                                btn.setAttribute('data-kt-indicator', 'on');
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
            initValidator();
        }
    };
}();
// On document ready
KTUtil.onDOMContentLoaded(function () {
    validator.init();
});
