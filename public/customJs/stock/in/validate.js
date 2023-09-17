var stockin = function () {


    const element = document.getElementById("stockin_content");
    const form = element.querySelector("#stockin_create_form");

    var stockin = () => {
        // Submit button handler
        const submitButton = $('[data-stockin-create-action="submit"]');
        submitButton.on('click', function (e) {
            console.log('start valid');
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
            stockin();
        }
    };
}();



// On document ready
KTUtil.onDOMContentLoaded(function () {
    stockin.init();
});


function validationField(form) {

    var validationFields = {
        business_location_id: {
            validators: {
                notEmpty: { message: "Which location do you want to stockin?" },
            },
        },
        stockin_person:{
            validators: {
                notEmpty: { message: "Stockin Person is required"},
            },
        },


    };


    return  FormValidation.formValidation(
        form,
        {
            fields:validationFields,

            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.validate-check',
                    eleInvalidClass: '',
                    eleValidClass: ''
                })
            },

        }
    );
}