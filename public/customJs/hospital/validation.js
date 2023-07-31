    // user update validation
    var fields = [
        // 'patient_id',
        // 'registration_status'
    ];
    var validator;
    var hospital = function () {
        const element = document.getElementById("hospital_registration");
        const form = element.querySelector("#hospital_registration_form");
        var initRegistration = () => {
            const submitButton = element.querySelector('[data-kt-hosptial-action="submit"]');
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
                            })

                        }
                    });
                }

            });


        }
        return {
            // Public functions
            init: function () {
                initRegistration();
            }
        };
    }();
    // On document ready
    KTUtil.onDOMContentLoaded(function () {
        hospital.init();
    });
function validationField(form) {
    $('.fv-plugins-message-container').remove();
        var validationFields = {
                patient_id: {
                    validators: {
                        notEmpty: { message: "* Patient field is required" },
                    },
                },
                registration_status: {
                    validators: {
                        notEmpty: { message: "* Patient field is required" },
                    },
                },
        };
    if ($('#registration_type').val() == 'IPD') {
            fields = [];
            let roomRow = $('#room_table tbody tr').length - 1;
                for (let i = 0; i <= roomRow; i++) {
                    let roomTypeId = `roomRegistrationData[${i}][room_type_id]`;
                    let room_rate_id = `roomRegistrationData[${i}][room_rate_id]`;
                    let room_id = `roomRegistrationData[${i}][room_id]`;
                    console.log(room_id);
                    validationFields[roomTypeId] = {
                        validators: {
                            notEmpty: { message: "Room Type is required" },
                        },
                    };
                    validationFields[room_rate_id] = {
                        validators: {
                            notEmpty: { message: "Room Rate required" },
                        },
                    };
                    validationFields[room_id] = {
                        validators: {
                            notEmpty: { message: "Room is required" },
                        },
                    };
                    fields = [...fields, roomTypeId, room_rate_id, room_id];
                }
            console.log(fields);
        }
        return FormValidation.formValidation(
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
