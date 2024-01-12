
<div class="modal-dialog w-sm-500px" id="payment_container">
    <div class="modal-content">
        <form action="{{route('paymentTransaction.storeWithdrawl',$account->id)}}" method="POST" id="add_payment_acounts">
            @csrf
            <div class="modal-header py-3">
                <h3 class="modal-title">Withdraw from {{$account->name}}</h3>
                <input type="hidden" name="balanceAmount" id="balanceAmount" value="{{$account->current_balance}}">
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
                            <div class="input-group input-group-sm">
                                <input id="withdrawl_amount" type="text" class="form-control form-control-sm fs-3 input_number" name="withdrawl_amount"
                                    value="{{old('withdrawl_amount')}}" autocomplete="off" />
                                <div class="overflow-hidden flex-grow-2" id="currrencyId">
                                    <select name="withdrawl_currency_id" class=" form-select rounded-start-0"  data-dropdown-parent="#currrencyId"
                                        id="currencySelect">
                                        @foreach ($currencies as $c)
                                            <option value="{{$c->id}}" @selected($account->currency_id ==$c->id)>{{$c->symbol}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <span for="" id="withdrawl_amount_label" class="text-danger-emphasis"></span>
                        </div>
                            <span class="text-gray-600 fw-semibold">Current Balance: <span class="currentBalanceText">{{$account->current_balance}} {{$account->currency->symbol}}</span></span>
                    </div>
                </div>
                <div class="row mb-6 exchangeOper d-none">
                    <div class="col-12">
                        <label class="required fs-6 fw-semibold form-label mb-1 required">Exchange Rate </label>
                        <input type="text" class="form-control form-control-sm " name="rate" id="rate" value="1">
                    </div>
                </div>
                <div class="row mb-6 exchangeOper d-none">
                    <div class="col-12">
                        <label class="required fs-6 fw-semibold form-label mb-1 required">Final Price </label>
                        <input type="text" id="finalPrice" name="" class="form-control form-control-sm " value="" readonly>
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


<script src="{{asset('modules/exchangerate/js/exchangeRate.js')}}"></script>
<script>

    // user update validation
// var depositeValidator = function () {
//     // Shared variables

//     const element = document.getElementById("payment_container");
//     const form = element.querySelector("#add_payment_acounts");
//     let value=$('#balanceAmount').val();
//     // Init add schedule modal
//     var initAddDeposit = () => {

//         // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
//         var validator = FormValidation.formValidation(
//             form,
//             {
//                 fields: {
//                     withdrawl_amount: {
//                         validators: {
//                             notEmpty: { message: "* WithDrawl Amount is required" },
//                             lessThan: {
//                                 max: value,
//                                 message: `The value has to be less than ${value}`,
//                             }
//                         },
//                     },
//                 },

//                 plugins: {
//                     trigger: new FormValidation.plugins.Trigger(),
//                     bootstrap: new FormValidation.plugins.Bootstrap5({
//                         rowSelector: '.fv-row',
//                         eleInvalidClass: '',
//                         eleValidClass: ''
//                     })
//                 },

//             }
//         );
//         // $(document).on('change','#currencySelect',()=>{
//         //     console.log(validator);
//         //     let newPrice=$('#balanceAmount').val();
//         //     validator.fields.withdrawl_amount.validators.lessThan.max =newPrice;
//         //     validator.fields.withdrawl_amount.validators.lessThan.message =`The value has to be less than ${newPrice}`;
//         // })
//         // Submit button handler
//         const submitButton = element.querySelectorAll('#withdrawlSubmit');
//         submitButton.forEach((btn) => {
//             btn.addEventListener('click', function (e) {
//                     if (validator) {
//                         validator.validate().then(function (status) {
//                             if (status == 'Valid') {
//                                 e.currentTarget=true;
//                                 btn.setAttribute('data-kt-indicator', 'on');
//                                 return true;
//                             } else {
//                                 e.preventDefault();
//                                 // Show popup warning. For more info check the plugin's official documentation: https://sweetalert2.github.io/
//                                 Swal.fire({
//                                     text: "Sorry, looks like there are some errors detected, please try again.",
//                                     icon: "error",
//                                     buttonsStyling: false,
//                                     confirmButtonText: "Ok, got it!",
//                                     customClass: {
//                                         confirmButton: "btn btn-primary"
//                                     }
//                                 });
//                             }
//                         });
//                     }

//             });
//         })

//     }

//     // Select all handler

//     return {
//         // Public functions
//         init: function () {
//             initAddDeposit();
//         }
//     };
// }();
// // On document ready
// KTUtil.onDOMContentLoaded(function () {
//     depositeValidator.init();
// });

    (()=>{
        let isValid=true;
        let balanceAmount={{$account->current_balance}};
        let currency_id=@json($account->currency_id ?? 0);
        let exchangeRate=1;
        $('#currencySelect').select2({minimumResultsForSearch: Infinity}).change(async (s)=>{
            let value=s.target.value;
            if (value!= currency_id) {
                $('.exchangeOper').removeClass('d-none');
                await getExchangeRate(value,currency_id).then((result)=>{
                    $('#rate').val(result.rate);
                    $('#balanceAmount').val(balanceAmount / result.rate);
                    $('.currentBalanceText').text(balanceAmount / result.rate);
                    resultPriceCal();
                    checkValidation();
                }).catch(()=>{
                    $('#rate').val(1);
                    resultPriceCal();
                });
            }else{
                $('#balanceAmount').val(balanceAmount);
                $('.exchangeOper').addClass('d-none');
            }
        });
        $(document).on('input','#withdrawl_amount',async function(){
           let result=await resultPriceCal(exchangeRate);
            checkValidation();
        })
        function resultPriceCal(){
            let toCurrency=$('#currencySelect').val();
            let result=$('#withdrawl_amount').val();
            let exchangeRate=$('#rate').val();
            if (toCurrency != currency_id) {
                result=result*exchangeRate;
            }
            $('#finalPrice').val(result)
            return result;
        }
        function checkValidation(){
            let result=$('#finalPrice').val();
            if(result>balanceAmount){
                isValid=false;
                $('#withdrawl_amount_label').text('Insufficient Amout');
            }else if(isNullOrNan(result) == 0){
                isValid=false;
                    $('#withdrawl_amount_label').text('Withdrawl Amount Required!');
            }else{
                isValid=true;
                $('#withdrawl_amount_label').text('');
            }
        }
        $(document).off('click','#withdrawlSubmit').on('click','#withdrawlSubmit',function (e) {
            // e.preventDefault();
            let btn=document.getElementById('withdrawlSubmit');
            checkValidation();
            if (isValid) {
                e.currentTarget=true;
                btn.setAttribute('data-kt-indicator', 'on');
                return true;
            }else{
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
    })();
    // $('#currencySelect')
    $('[data-td-toggle="datetimepicker"]').flatpickr({
        enableTime: true,
        dateFormat: "Y-M-d H:i",
    });
    numberOnly();
</script>
