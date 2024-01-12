
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

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold form-label mb-1 required">Transfer Amount </label>
                            <div class="input-group mb-3">
                                <input  id="transferAmount" type="text" class="form-control form-select-sm fs-3 input_number" name="tx_amount" value="{{old('transfer_amount')}}" autocomplete="off" />
                                <label for="" class="input-group-text fw-bold rounded-start-0  fs-4 ">{{$current_acc->currency->symbol}}</label>
                            </div>
                            <span class="text-gray-600 fw-semibold">Current Balance: {{$current_acc->current_balance}} {{$current_acc->currency->symbol}}</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold form-label mb-1 required">Transfer at </label>
                            <div class="overflow-hidden flex-grow-2">
                                <input type="text" class="form-control form-control-sm" data-td-toggle="datetimepicker" name="payment_date" value="{{now()->format('Y-M-d h:i')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold form-label mb-1 required">Transfer To </label>
                            <div class="overflow-hidden flex-grow-2">
                                <select  id="rx_account" class="form-select form-select-sm form-select-dark"   name="rx_account_id" data-kt-select2="true" data-placeholder="Select Receive Account" data-allow-clear="true" data-dropdown-parent="#transferModal">
                                    <option value="" disabled selected>Select Receive Account</option>
                                    @foreach ($accounts as $a)
                                        <option value="{{$a->id}}">{{$a->name}} ({{$a->account_number}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold form-label mb-1 required">Exchange Rate </label>
                            <input type="text" class="form-control form-control-sm input_number" name="rate" id="exchange_rate">
                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-12">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold form-label mb-1 required">Receive Amount </label>
                            <div class="input-group mb-3">
                                <input  id="rx_amount" type="text" class="form-control form-select-sm fs-3 input_number" name="rx_amount" value="{{old('transfer_amount')}}" autocomplete="off" />
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

@if (hasModule('ExchangeRate') && isEnableModule('ExchangeRate'))
    @php
    $hasModule= true;
    @endphp
    <script src="{{asset('modules/exchangerate/js/exchangeRate.js')}}"></script>
@else
 @php
    $hasModule= false;
 @endphp
@endif
<script>
    numberOnly();
    var exchangeRateValue=0;

    var hasModule=@json($hasModule ?? false);
    $('[data-kt-select2="true"]').select2();
    $('[data-td-toggle="datetimepicker"]').flatpickr({
        enableTime: true,
        dateFormat: "Y-M-d H:i",
    });
    var accounts=@json($accounts);
    var currencyDp={{$currencyDp}};
    var ftxCurreyId={{$current_acc->currency->id ?? 0}};
    (
        function () {
            var rx_accounts=@json($accounts ?? []);
            let exchangeRates=@json($current_acc).currency.exchange_rate;

            $(document).on('change','#rx_account',async function(){
                let rx_acc_id=$(this).val();
                console.log(rx_acc_id);
                let rx_acc=rx_accounts.find((a)=>a.id == rx_acc_id);
                let rx_id=rx_acc.currency_id;
                $('#transferSubmit').text('Loading....')
                $('#transferSubmit').prop('disabled', true);
                if(hasModule){
                    await getExchangeRate(ftxCurreyId,rx_id).then(function(result) {
                        console.log('Exchange Rate:', result);
                        exchangeRateValue=result.rate;
                        $('#exchange_rate').val(exchangeRateValue);
                    })
                    .catch(function(error) {
                        console.error('Error fetching exchange rate:', error);
                    });
                }else{
                    exchangeRateValue=1;
                    $('#exchange_rate').val(exchangeRateValue);
                }

                $('#transferSubmit').text('Save')
                $('#transferSubmit').prop('disabled', false);
                rxAmountCal();
            })
            $(document).on('input','#transferAmount', function(){
                rxAmountCal();
            })
            $(document).on('input','#exchange_rate', function(){
                rxAmountCal();
            })
            $(document).on('input','#rx_amount',function(){
                txAmountCal();
            })
        }

    )();
    function rxAmountCal(){
        let rx_id=$('#rx_account').val();
        let rx_account=accounts.filter(a=>{
            return a.id==rx_id
        })[0];
        if(rx_account){
            let transferAmount=$('#transferAmount').val() ?? 0;
            let rx_currency=rx_account.currency;
            let rx_exchangeRate=isNullOrNan($('#exchange_rate').val() ?? 0);
            $('#rx_currency_symbol').text(rx_currency.symbol);
            let result=transferAmount*rx_exchangeRate;
            $('#rx_amount').val(result.toFixed(currencyDp));
        }
    }
    // function getExchangeRate(fromCurrencyId,toCurrencyId) {
    //     return new Promise(function(resolve, reject) {
    //         $.ajax({
    //             url: `/exchange-rate/get/rate/${fromCurrencyId}/${toCurrencyId}`,
    //             type: 'GET',
    //             success: function(s) {
    //                 $('#exchange_rate').val(s.rate);
    //                 exchangeRateValue = s.rate;
    //                 resolve(s);
    //             },
    //             error: function(error) {
    //                 reject(error);
    //             }
    //         });
    //     });
    // }
    function txAmountCal(){
        let rx_amount=$('#rx_amount').val();
        if(rx_amount){
            let rx_exchangeRate=isNullOrNan($('#exchange_rate').val() ?? 0);
            let result=rx_amount/rx_exchangeRate;
            $('#transferAmount').val(result.toFixed(currencyDp));
        }
    }
    // function exchangeCurrency(amount, fromCurrencyRate, toCurrencyRate) {

    //         toCurrencyRate=toCurrencyRate ?? 1;
    //         fromCurrencyRate=fromCurrencyRate ?? 1;
    //         // Convert amount to reference currency (Dollar)
    //         var inDollar = amount / fromCurrencyRate;

    //         // Convert amount from reference currency to target currency
    //         var convertedAmount = inDollar * toCurrencyRate ?? 1;

    //         return convertedAmount;
    //     }





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
