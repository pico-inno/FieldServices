    var fields = [
        // 'patient_id',
        // 'registration_status'
    ];
    // user update validation
    var purchase = function () {
        // Shared variables

        const element = document.getElementById("purchase_container");
        const form = element.querySelector("#purchase_form");
        // Select all handler
        // Init add schedule modal
        var purchase = () => {
            // Submit button handler
            const submitButton = element.querySelector('[data-kt-purchase-action="submit"]');
            submitButton.addEventListener('click', function (e) {
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
        purchase.init();
    });


function validationField(form) {
    let status=$('#OrderStatus').val();
    const table = $('#purchase_table tbody tr').length;
    $('.fv-plugins-message-container').remove();

        fields = [];
        let business_location_id;
        let paidAmountValidator;
        let paymentAccountValidator;
        let accountId=$('#payment_accounts').val();
        let totalPurchaseAmount=$('#total_purchase_amount').val();
        let paid_amount=$('#paid_amount').val();
        if(status=='received'){
            business_location_id= {
                validators: {
                    notEmpty: { message: "* Location field is required" },
                }
            }
        }
        if(accountId){
            paidAmountValidator= {
                validators:{
                    lessThan:
                    {
                        max: currentBalance,
                        message: 'Insufficient Balance Amount',
                    }
                }
            }
        }

        if(paid_amount>0){
            paymentAccountValidator= {
                validators:{
                    validators: {
                        notEmpty: { message: "* Payment Account is require to paid" },
                    }
                }
            }
        }
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
                business_location_id,
                payment_account:paymentAccountValidator,
                paid_amount:paidAmountValidator,
                // payment_account:{
                //     validators: {
                //         notEmpty: { message: "* Payment Account field is required" },
                //     },
                // }
            };

            for (let i = 0; i <= table; i++) {
                    validationFields[`purchase_details[${i}][uomset_id]`] = {
                        validators: {
                            notEmpty: { message: "Uom Set is required" },
                        },
                    };
                    validationFields[`purchase_details[${i}][unit_id]`] = {
                        validators: {
                            notEmpty: { message: "Unit is required" },
                        },
                    };

                fields = [...fields, `purchase_details[${i}][uomset_id]`, `purchase_details[${i}][unit_id]`];
                console.log(fields);
            }

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
