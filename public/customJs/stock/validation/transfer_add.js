
// Define form element
const form = document.getElementById('stock_transfer_form');

// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            'from_location': {
                validators: {
                    notEmpty: {
                        message: 'From Location selection is required'
                    }
                }
            },
            'to_location': {
                validators: {
                    notEmpty: {
                        message: 'To Location selection is required'
                    }
                }
            },
            'transfered_person': {
                validators: {
                    notEmpty: {
                        message: 'Transfer Person selection is required'
                    }
                }
            },
            'received_person': {
                validators: {
                    notEmpty: {
                        message: 'Receive Person selection is required'
                    }
                }
            },
            'status': {
                validators: {
                    notEmpty: {
                        message: 'Status selection is required'
                    }
                }
            },
            'transfered_at': {
                validators: {
                    notEmpty: {
                        message: 'Transfer Date is required'
                    }
                }
            },

        },

        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);

// Submit button handler
const submitButton = document.getElementById('transfer_form_save_btn');
submitButton.addEventListener('click', function (e) {

    // Prevent default button action
    e.preventDefault();

    // Validate form before submit
    if (validator) {
        validator.validate().then(function (status) {
            console.log('validated!');

            if (status == 'Valid') {
                submitButton.setAttribute('data-kt-indicator', 'on');

                submitButton.disabled = true;
                form.submit();

            }
        });
    }
});
