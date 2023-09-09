
var adjustment = function () {


    const element = document.getElementById("adjustment_content");
    const form = element.querySelector("#adjustment_add_form");

    var adjustment = () => {
        // Submit button handler
        const submitButton = $('[data-adjustment-create-action="submit"]');
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
            adjustment();
        }
    };
}();



// On document ready
KTUtil.onDOMContentLoaded(function () {
    adjustment.init();
});


function validationField(form) {

    var validationFields = {
        business_location: {
            validators: {
                notEmpty: { message: "Which location do you want to adjustment?" },
            },
        },
        status: {
            validators: {
                notEmpty: { message: " Status field is required" },
            },

        },
        adjustment_table: {
            validators: {
                callback: {
                    message: "At least one item is required to complete adjustment",
                    callback: function(input) {
                        var table = document.getElementById("adjustment_table");
                        var rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

                        // Check if there is at least one row with data
                        for (var i = 0; i < rows.length; i++) {
                            var cells = rows[i].getElementsByTagName("td");
                            if (cells.length > 0) {
                                return true; // At least one row has data
                            }
                        }

                        return false; // No rows with data found
                    },
                },
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
                    rowSelector: '.fv-row',
                    eleInvalidClass: '',
                    eleValidClass: ''
                })
            },

        }
    );
}
