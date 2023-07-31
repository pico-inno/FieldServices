
<div class="modal-dialog w-sm-500px" id="payment_container">
    <div class="modal-content">
        <form action="{{route('paymentTransaction.depositStore',$account->id)}}" method="POST" id="add_payment_acounts">
            @csrf
            <div class="modal-header py-3">
                <h3 class="modal-title">Deposit to {{$account->name}}</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row mb-6">
                    <div class="col-12">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold form-label mb-1 required">Deposit Amount </label>
                            <div class="input-group mb-3">
                                <input  id="deposit_amount" type="text" class="form-control form-select-sm fs-3" name="deposit_amount" value="{{old('transfer_amount')}}" autocomplete="off" />
                                <label for="" class="input-group-text fw-bold rounded-start-0  fs-4 ">{{$account->currency->symbol}}</label>
                            </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer py-3 ">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm" data-kt-sale-action="submit">Save</button>
            </div>
        </form>
    </div>
</div>



<script>
        // user update validation
    var depositeValidator = function () {
        // Shared variables

        const element = document.getElementById("payment_container");
        const form = element.querySelector("#add_payment_acounts");

        // Init add schedule modal
        var initAddDeposit = () => {

            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            var validator = FormValidation.formValidation(
                form,
                {
                    fields: {
                        deposit_amount: {
                            validators: {
                                notEmpty: { message: "* Deposite is required" },
                            },
                        },
                    },

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
            const submitButton = element.querySelectorAll('[data-kt-sale-action="submit"]');
            submitButton.forEach((btn) => {
                btn.addEventListener('click', function (e) {
                    
                        if (validator) {
                            validator.validate().then(function (status) {
                                console.log(status);
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

        }

        // Select all handler

        return {
            // Public functions
            init: function () {
                initAddDeposit();
            }
        };
    }();
    // On document ready
    KTUtil.onDOMContentLoaded(function () {
        depositeValidator.init();
    });

</script>
