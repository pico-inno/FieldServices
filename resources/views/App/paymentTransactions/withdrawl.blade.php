
<div class="modal-dialog w-sm-500px" id="payment_container">
    <div class="modal-content">
        <form action="{{route('paymentTransaction.storeWithdrawl',$account->id)}}" method="POST" id="add_payment_acounts">
            @csrf
            <div class="modal-header py-3">
                <h3 class="modal-title">Withdraw from {{$account->name}}</h3>

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
                            <label class="required fs-6 fw-semibold form-label mb-1 required">Withdrawl Amount </label>
                            <div class="input-group mb-3">
                                <input  id="withdrawl_amount" type="text" class="form-control form-select-sm fs-3 input_number" name="withdrawl_amount" value="{{old('transfer_amount')}}" autocomplete="off" />
                                <label for="" class="input-group-text fw-bold rounded-start-0  fs-4 ">{{$account->currency->symbol}}</label>
                                {{-- <input type="text" class="form-control form-control-sm " value="{{$account->currency->symbol}}" readonly> --}}
                                {{-- <div class="overflow-hidden flex-grow-2">
                                    <select  id="" class="form-control form-control-sm form-select-dark"  name="" data-kt-select2="true" data-placeholder="Select Currency" data-allow-clear="true" data-kt-user-table-filter="receiver" data-dropdown-parent="#kt-toolbar-filter-sender">
                                        <option selected ></option>
                                    </select>
                                </div> --}}
                            </div>
                            <span class="text-gray-600 fw-semibold">Current Balance: {{$account->current_balance}} {{$account->currency->symbol}}</span>
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-12">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold form-label mb-1 required">Withdrawl at </label>
                            <div class="overflow-hidden flex-grow-2">
                                <input type="text" class="form-control form-control-sm" data-td-toggle="datetimepicker"
                                    name="payment_date" value="{{now()->format('Y-M-d h:i')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer py-3 ">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                {{-- <button type="submit" class="btn btn-primary btn-sm" data-kt-withdrawl-action="submit">Save</button> --}}
                <button type="submit" class="btn btn-primary" id="withdrawlSubmit" data-kt-withdrawl-action="submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </form>
    </div>
</div>



<script>
    $('[data-td-toggle="datetimepicker"]').flatpickr({
        enableTime: true,
        dateFormat: "Y-M-d H:i",
    });
    numberOnly();
    // user update validation
var depositeValidator = function () {
    // Shared variables

    const element = document.getElementById("payment_container");
    const form = element.querySelector("#add_payment_acounts");
    let value={{$account->current_balance}};
    // Init add schedule modal
    var initAddDeposit = () => {

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    withdrawl_amount: {
                        validators: {
                            notEmpty: { message: "* WithDrawl Amount is required" },
                            lessThan: {
                                max: value,
                                message: `The value has to be less than ${value}`,
                            }
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
        const submitButton = element.querySelectorAll('#withdrawlSubmit');
        console.log(submitButton);
        submitButton.forEach((btn) => {
            btn.addEventListener('click', function (e) {
                    if (validator) {
                        validator.validate().then(function (status) {
                            if (status == 'Valid') {
                                e.currentTarget=true;
                                btn.setAttribute('data-kt-indicator', 'on');
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
