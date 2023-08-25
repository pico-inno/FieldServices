"use strict";

// Class definition
var KTCreateApp = function () {
	// Elements
	var modal;
	var modalEl;

	var stepper;
	var form;
	var formSubmitButton;
	var formContinueButton;

	// Variables
	var stepperObj;
	var validations = [];

	// Private Functions
	var initStepper = function () {
		// Initialize Stepper
		stepperObj = new KTStepper(stepper);

		// Stepper change event(handle hiding submit button for the last step)
		stepperObj.on('kt.stepper.changed', function (stepper) {
			if (stepperObj.getCurrentStepIndex() === 4) {
				formSubmitButton.classList.remove('d-none');
				formSubmitButton.classList.add('d-inline-block');
				formContinueButton.classList.add('d-none');
			} else if (stepperObj.getCurrentStepIndex() === 5) {
				formSubmitButton.classList.add('d-none');
				formContinueButton.classList.add('d-none');
			} else {
				formSubmitButton.classList.remove('d-inline-block');
				formSubmitButton.classList.remove('d-none');
				formContinueButton.classList.remove('d-none');
			}
		});

		// Validation before going to next page
		stepperObj.on('kt.stepper.next', function (stepper) {
			console.log('stepper.next');

			// Validate form before change stepper step
			var validator = validations[stepper.getCurrentStepIndex() - 1]; // get validator for currnt step

			if (validator) {
				validator.validate().then(function (status) {
					console.log('validated!');

					if (status == 'Valid') {
						stepper.goNext();

						//KTUtil.scrollTop();
					} else {
						// Show error message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
						Swal.fire({
							text: "Sorry, looks like there are some errors detected, please try again.",
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: "Ok, got it!",
							customClass: {
								confirmButton: "btn btn-light"
							}
						}).then(function () {
							//KTUtil.scrollTop();
						});
					}
				});
			} else {
				stepper.goNext();

				KTUtil.scrollTop();
			}
		});

		// Prev event
		stepperObj.on('kt.stepper.previous', function (stepper) {
			console.log('stepper.previous');

			stepper.goPrevious();
			KTUtil.scrollTop();
		});

		formSubmitButton.addEventListener('click', function (e) {
			// Validate form before change stepper step
			var validator = validations[2]; // get validator for last form

			validator.validate().then(function (status) {
				console.log('validated!');

				if (status == 'Valid') {
					// Prevent default button action
					e.preventDefault();

					// Disable button to avoid multiple click
					formSubmitButton.disabled = true;

					// Show loading indication
					formSubmitButton.setAttribute('data-kt-indicator', 'on');

                    let formData = new FormData($('#kt_modal_create_business_form')[0]);
                    $.ajax({
                        data:formData,
                        processData: false,
                        contentType: false,
                        url:'/businessActivate/store',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (results) {

                            if (results.success) {
                                let business=results.business
                                success(results.success);
                                modal.hide();
                                form.reset();
                                stepperObj.goTo(0);
                                $('#createAppBtn').remove();
                                $('.header-text').text(`Congratulations! , ${business.name}`);
                                $('.intro-text').html('Successfully Activated Your Business & Please Wait To Redirect <a href="/">Login</a>');
                                $('.loading').removeClass('d-none');
                                if (business.logo) {
                                    $('.image').html(`<img src="${results.businessLogo}" alt="" width="100px" height="100px">`)
                                }
                                setTimeout(() => {
                                    window.location.replace("/");
                                }, 2000);
                            } else if (results.error) {
                                error(results.error);
                            }

                                    // Hide loading indication
                            formSubmitButton.removeAttribute('data-kt-indicator');

                            // Enable button
                            formSubmitButton.disabled = false;

                            stepperObj.goNext();
                            //KTUtil.scrollTop();


                        }
                    })
				} else {
					Swal.fire({
						text: "Sorry, looks like there are some errors detected, please try again.",
						icon: "error",
						buttonsStyling: false,
						confirmButtonText: "Ok, got it!",
						customClass: {
							confirmButton: "btn btn-light"
						}
					}).then(function () {
						KTUtil.scrollTop();
					});
				}
			});
		});
	}

	// Init form inputs
	var initForm = function() {
		// Expiry month. For more info, plase visit the official plugin site: https://select2.org/
        $(form.querySelector('[name="card_expiry_month"]')).on('change', function() {
            // Revalidate the field when an option is chosen
            validations[3].revalidateField('card_expiry_month');
        });

		// Expiry year. For more info, plase visit the official plugin site: https://select2.org/
        $(form.querySelector('[name="card_expiry_year"]')).on('change', function() {
            // Revalidate the field when an option is chosen
            validations[3].revalidateField('card_expiry_year');
        });
	}

	var initValidation = function () {
		// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		// Step 1
		validations.push(FormValidation.formValidation(
			form,
			{
				fields: {
					"business[name]": {
						validators: {
							notEmpty: {
								message: 'App name is required'
							}
						}
                    },
                    "business[currency]": {
						validators: {
							notEmpty: {
								message: 'Default currency is required'
							}
						}
                    }
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
		));

		// Step 2
		validations.push(FormValidation.formValidation(
			form,
			{
				fields: {
                    "business[finanical_year_start_month]": {
						validators: {
							notEmpty: {
								message: 'Start Month For Finanical year is required'
							}
						}
                    }
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					// Bootstrap Framework Integration
					bootstrap: new FormValidation.plugins.Bootstrap5({
						rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
					})
				}
			}
		));

		// Step 3
		validations.push(FormValidation.formValidation(
			form,
			{
				fields: {
					"businessUser[firstName]": {
						validators: {
							notEmpty: {
								message: 'Frist name is required'
							}
						}
                    },
                    "businessUser[username]": {
						validators: {
							notEmpty: {
								message: 'Username is required'
							}
						}
                    },
                    "businessUser[email]": {
						validators: {
							emailAddress: {
                                message: 'The value is not a valid email address'
                            },
						}
                    },
                    'businessUser[password]': {
                        validators: {
                            notEmpty: {
                                message: 'The password is required'
                            },
                            callback: {
                                message: 'Please enter valid password',
                                callback: function (input) {
                                    if (input.value.length > 0) {
                                        return validatePassword();
                                    }
                                }
                            }
                        }
                    },
                    'confirm_password': {
                        validators: {
                            notEmpty: {
                                message: 'The password confirmation is required'
                            },
                            identical: {
                                compare: function () {
                                    return form.querySelector('[name="businessUser[password]"]').value;
                                },
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },

				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					// Bootstrap Framework Integration
					bootstrap: new FormValidation.plugins.Bootstrap5({
						rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
					})
				}
			}
		));

		// Step 4
		// validations.push(FormValidation.formValidation(
		// 	form,
		// 	{
		// 		fields: {
		// 			'card_name': {
		// 				validators: {
		// 					notEmpty: {
		// 						message: 'Name on card is required'
		// 					}
		// 				}
		// 			},
		// 			'card_number': {
		// 				validators: {
		// 					notEmpty: {
		// 						message: 'Card member is required'
		// 					},
        //                     creditCard: {
        //                         message: 'Card number is not valid'
        //                     }
		// 				}
		// 			},
		// 			'card_expiry_month': {
		// 				validators: {
		// 					notEmpty: {
		// 						message: 'Month is required'
		// 					}
		// 				}
		// 			},
		// 			'card_expiry_year': {
		// 				validators: {
		// 					notEmpty: {
		// 						message: 'Year is required'
		// 					}
		// 				}
		// 			},
		// 			'card_cvv': {
		// 				validators: {
		// 					notEmpty: {
		// 						message: 'CVV is required'
		// 					},
		// 					digits: {
		// 						message: 'CVV must contain only digits'
		// 					},
		// 					stringLength: {
		// 						min: 3,
		// 						max: 4,
		// 						message: 'CVV must contain 3 to 4 digits only'
		// 					}
		// 				}
		// 			}
		// 		},

		// 		plugins: {
		// 			trigger: new FormValidation.plugins.Trigger(),
		// 			// Bootstrap Framework Integration
		// 			bootstrap: new FormValidation.plugins.Bootstrap5({
		// 				rowSelector: '.fv-row',
        //                 eleInvalidClass: '',
        //                 eleValidClass: ''
		// 			})
		// 		}
		// 	}
		// ));
	}

	return {
		// Public Functions
		init: function () {
			// Elements
			modalEl = document.querySelector('#kt_modal_create_business');

			if (!modalEl) {
				return;
			}

			modal = new bootstrap.Modal(modalEl);

			stepper = document.querySelector('#kt_modal_create_business_stepper');
			form = document.querySelector('#kt_modal_create_business_form');
			formSubmitButton = stepper.querySelector('[data-kt-stepper-action="submit"]');
			formContinueButton = stepper.querySelector('[data-kt-stepper-action="next"]');

			initStepper();
			initForm();
			initValidation();
		}
	};
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTCreateApp.init();
});
