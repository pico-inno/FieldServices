    var fields = [
        // 'patient_id',
        // 'registration_status'
    ];
    // user update validation
    var priceListVal = function () {
        // Shared variables

        const element = document.getElementById("kt_content_container");
        const form = element.querySelector("#priceList_form");
        // Select all handler
        // Init add schedule modal
        var purchase = () => {
            // Submit button handler
            const submitButton = $('#submit');
            submitButton.on('click', function (e) {
                validator = validationField(form);
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
        return {
            // Public functions
            init: function () {
                purchase();
            }
        };
    }();
    // On document ready
    KTUtil.onDOMContentLoaded(function () {
        priceListVal.init();
    });


function validationField(form) {
    $('.fv-plugins-message-container').remove();

        fields = [];
        var validationFields = {
                name: {
                    validators: {
                        notEmpty: { message: "Price List name is required" },
                    },
                },
                base_price: {
                    validators: {
                        notEmpty: { message: " Base Price  is required" },
                    },
                },
                currency_id: {
                    validators: {
                        notEmpty: { message: " Currency  is required" },
                    },
                },

                "apply_type[]":{
                    validators: {
                        notEmpty: { message: "Type is required" },
                    },
                },
                "apply_value[]":{
                    validators: {
                        notEmpty: { message: "Value is required" },
                    },
                },
                "min_qty[]":{
                    validators: {
                        notEmpty: { message: "Qty is required" },
                    },
                },
                "cal_type[]":{
                    validators: {
                        notEmpty: { message: "Type is required" },
                    },
                },
                "cal_val[]":{
                    validators: {
                        notEmpty: { message: "Value is required" },
                    },
                }
            };

            return  FormValidation.formValidation(
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
}


function removeField() {
    fields.forEach((e) => {
        try {
            validator.removeField(e, 'Valid');
        } catch (error) {
            throw error;
        }
    })
}
