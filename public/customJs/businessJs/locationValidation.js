    // user update validation
    var saleValidator = function () {
        // Shared variables

        const element = document.getElementById("location");
        const form = element.querySelector("#location_form");
        // Init add schedule modal
        var initAddUser = () => {
            var validationFields = {

                        name: {
                            validators: {
                                notEmpty: { message: "* Location field is required" },
                            },
                        },
                    };
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
            const submitButton = element.querySelector('[data-kt-location-action="submit"]');
            submitButton.addEventListener('click', function (e) {
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
