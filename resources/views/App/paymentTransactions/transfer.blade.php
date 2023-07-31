
<div class="modal-dialog w-sm-500px" id="transfer_container">
    <div class="modal-content">
        <form action="{{route('paymentTransaction.makeTransfer',$current_acc->id)}}" method="POST" id="transfer_form">
            @csrf
            <div class="modal-header py-3">
                <h3 class="modal-title">Transfer from {{$current_acc->name}}</h3>
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
                            <label class="required fs-6 fw-semibold form-label mb-1 required">Transfer Amount </label>
                            <div class="input-group mb-3">
                                <input  id="transferAmount" type="text" class="form-control form-select-sm fs-3" name="tx_amount" value="{{old('transfer_amount')}}" autocomplete="off" />
                                <label for="" class="input-group-text fw-bold rounded-start-0  fs-4 ">{{$current_acc->currency->symbol}}</label>
                            </div>
                            <span class="text-gray-600 fw-semibold">Current Balance: {{$current_acc->current_balance}} {{$current_acc->currency->symbol}}</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-12">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold form-label mb-1 required">Transfer To </label>
                            <div class="overflow-hidden flex-grow-2">
                                <select  id="rx_account" class="form-select form-select-sm form-select-dark"   name="rx_account_id" data-kt-select2="true" data-placeholder="Select Currency" data-allow-clear="true" data-dropdown-parent="#transferModal">
                                    <option value="" disabled selected>Select Receive Account</option>
                                    @foreach ($accounts as $a)
                                        <option value="{{$a->id}}">{{$a->name}} ({{$a->account_number}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-12">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold form-label mb-1 required">Receive Amount </label>
                            <div class="input-group mb-3">
                                <input  id="rx_amount" type="text" class="form-control form-select-sm fs-3" name="rx_amount" value="{{old('transfer_amount')}}" autocomplete="off" />
                                <label for="" class="input-group-text fw-bold rounded-start-0  fs-4 " id="rx_currency_symbol">-</label>
                            </div>
                            {{-- <span class="text-gray-600 fw-semibold">Current Balance: {{$current_acc->current_balance}} {{$current_acc->currency->symbol}}</span> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer py-3 ">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm" id="transferSubmit">Save</button>
            </div>
        </form>
    </div>
</div>


<script>
    $('[data-kt-select2="true"]').select2();
    var accounts=@json($accounts);
    var currencyDp={{$currencyDp}};
    (
        function () {
            let transferCurrencyRate=@json($current_acc).currency.exchange_rate.rate ?? 1;

            $(document).on('change','#rx_account',function(){
                rxAmountCal(transferCurrencyRate);
            })
            $(document).on('change','#transferAmount',function(){
                rxAmountCal(transferCurrencyRate);
            })
            $(document).on('change','#rx_amount',function(){
                txAmountCal(transferCurrencyRate);
            })
        }

    )();
    function rxAmountCal(transferCurrencyRate){
        let transferAmount=$('#transferAmount').val() ?? 0;
        let rx_id=$('#rx_account').val();
        let rx_account=accounts.filter(a=>{
            return a.id==rx_id
        })[0];
        if(rx_account){
            let rx_currency=rx_account.currency;
            let rx_exchangeRate=rx_currency.exchange_rate ?? 1;
            $('#rx_currency_symbol').text(rx_currency.symbol);
            let result=exchangeCurrency(transferAmount,transferCurrencyRate,rx_exchangeRate.rate);
            $('#rx_amount').val(result.toFixed(currencyDp));
        }
    }
    function txAmountCal(transferCurrencyRate){
        let rx_amount=$('#rx_amount').val();
        let rx_id=$('#rx_account').val();
        let rx_account=accounts.filter(a=>{
            return a.id==rx_id
        })[0];
        if(rx_account && rx_amount){
            let rx_currency=rx_account.currency;
            let rx_exchangeRate=rx_currency.exchange_rate;
            let result=exchangeCurrency(rx_amount,rx_exchangeRate.rate,transferCurrencyRate);
            $('#transferAmount').val(result.toFixed(currencyDp));
        }
    }
    function exchangeCurrency(amount, fromCurrencyRate, toCurrencyRate) {
            // Convert amount to reference currency (Dollar)
            var inDollar = amount / fromCurrencyRate;

            // Convert amount from reference currency to target currency
            var convertedAmount = inDollar * toCurrencyRate;

            return convertedAmount;
        }





   // user update validation
var transferValidation = function () {
    // Shared variables

    const element = document.getElementById("transfer_container");
    const form = element.querySelector("#transfer_form");
    let value={{$current_acc->current_balance}};
    // Init add schedule modal
    var inittransfer = () => {

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    tx_amount: {
                        validators: {
                            notEmpty: { message: "* Transfer Amount is required" },
                            lessThan: {
                                max: value,
                                message: `The value has to be less than ${value}`,
                            }
                        },
                    },
                    rx_account_id:{
                        validators: {
                            notEmpty: { message: "* Receive Account is required" }
                        },
                    },
                    rx_amount:{
                        validators: {
                            notEmpty: { message: "* Receive Amount is required" }
                        },
                    }
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
        const submitButton = element.querySelectorAll('#transferSubmit');
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
            inittransfer();
        }
    };
}();
// On document ready
KTUtil.onDOMContentLoaded(function () {
    transferValidation.init();
});

</script>

</script>
