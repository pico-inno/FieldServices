// Define form element
const form = document.getElementById("kt_ecommerce_add_product_form");

// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
var validator = FormValidation.formValidation(
    form,
    {
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: ".fv-row",
                eleInvalidClass: "",
                eleValidClass: ""
            }),
            excluded: new FormValidation.plugins.Excluded({
                excluded: function (field, ele, eles) {
                    if (form.querySelector('[name="' + field + '"]') === null) {
                        return true;
                    }
                },
            }),
        }
    }
);

const addFields = function(index) {
    const namePrefix = "packaging_repeater[" + index + "]";

    console.log(namePrefix + "[packaging_name]");
    // Add validators
    validator.addField(namePrefix + "[packaging_name]", {
        validators: {
            notEmpty: {
                message: "Packaging Name is required"
            }
        }
    });

    validator.addField(namePrefix + "[packaging_quantity]", {
        validators: {
            notEmpty: {
                message: "Quantity For packaging is required"
            }
        }
    });
    validator.addField(namePrefix + "[packaging_uom_id]", {
        validators: {
            notEmpty: {
                message: "Unit For packaging is required"
            }
        }
    });


    // validator.addField(namePrefix + "[primary][]", {
    //     validators: {
    //         notEmpty: {
    //             message: "Required"
    //         }
    //     }
    // });
};

const removeFields = function(index) {
    const namePrefix = "data[" + index + "]";

    validator.addField(namePrefix + "[name]");
    validator.addField(namePrefix + "[email]");
    validator.addField(namePrefix + "[primary][]");
}

// $(form).repeater({
//     initEmpty: false,

//     show: function () {
//         $(this).slideDown();

//         const index = $(this).closest("[data-repeater-item]").index();

//         addFields(index);
//     },

//     hide: function (deleteElement) {
//         $(this).slideUp(deleteElement);
//     }
// });

// Initial fields
addFields(0);

// Submit button handler
const submitButtons = document.querySelectorAll(".submit");
submitButtons.forEach((submitButton) => {
submitButton.addEventListener("click", function (e) {
    // Prevent default button action

    // Validate form before submit
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
})

