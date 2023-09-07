"use strict";

// Class definition
var KTUsersAddRole = function () {
    // Shared variables
    const element = document.getElementById('kt_add_role');
    const form = element.querySelector('#kt_modal_add_role_form');
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initAddRole = () => {

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'name': {
                        validators: {
                            notEmpty: {
                                message: 'Role name is required'
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
         const submitButton = element.querySelector('[data-kt-roles-modal-action="submit"]');
         submitButton.addEventListener('click', function (e) {
             // Prevent default button action
             e.preventDefault();

             // Validate form before submit
             if (validator) {
                 validator.validate().then(function (status) {
                     console.log('validated!');

                     if (status == 'Valid') {
                         // Show loading indication
                         submitButton.setAttribute('data-kt-indicator', 'on');

                         // Disable button to avoid multiple click
                         submitButton.disabled = true;
                         form.submit();

                     } else {
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
    const handleSelectAll = () => {
        const selectAll = form.querySelector('#kt_roles_select_all');
        const allCheckboxes = form.querySelectorAll('[type="checkbox"]');

        selectAll.addEventListener('change', e => {

            allCheckboxes.forEach(c => {
                c.checked = e.target.checked;
            });
        });
    }

    const handleFeatureSelectAll = () => {

        const featureSelectAllCheckboxes = form.querySelectorAll('.feature-select-all');

        featureSelectAllCheckboxes.forEach(featureSelectAll => {
            const featureName = featureSelectAll.value; // Get the feature name
            const featureCheckboxes = form.querySelectorAll(`[name^="${featureName}_"]`);


            const isAllFeatureCheckboxesSelected = () => {
                return [...featureCheckboxes].every(c => c.checked);
            };

            featureSelectAll.checked = isAllFeatureCheckboxesSelected();

            // Handle click event for feature checkboxes
            featureCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    featureSelectAll.checked = isAllFeatureCheckboxesSelected();
                });
            });

            // Handle click event for the "Select All" checkbox
            featureSelectAll.addEventListener('change', e => {
                featureCheckboxes.forEach(checkbox => {
                    checkbox.checked = e.target.checked;
                });
            });
        });
    };

    return {
        // Public functions
        init: function () {
            initAddRole();
            handleSelectAll();
            handleFeatureSelectAll();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersAddRole.init();
});
