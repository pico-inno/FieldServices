<div class="modal-dialog " id="payemntEdit_container">
    <div class="modal-content">
        <form action="{{route('paymentTransaction.updatetForPurchase',['id' =>$data->id, 'transaction_type' => 'purchase'])}}" method="POST" id="payemntEdit">
            @csrf
            <div class="modal-header py-3">
                <h3 class="modal-title fs-6">Edit Payment </h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row mb-5">
                   <div class="col-6">
                       <table class="table  table-layout-fixed  table-row-bordered">
                               <tbody class="">
                                   <tr>
                                       <th class="text-start">
                                         <span class="fw-semibold fs-7 text-gray-600">Payment Voucher NO:</span>
                                       </th>
                                       <td  class="text-end">
                                         <span class="fw-bold fs-7 text-gray-800">{{$data->payment_voucher_no}} </span>
                                       </td>
                                   </tr>
                               </tbody>
                       </table>
                  </div>
                </div>

                <div class="separator my-5"></div>
                <div class="row mb-6">
                    <div class="{{isUsePaymnetAcc() ? 'col-md-4'  : 'col-md-6' }} col-12 mb-5">
                        <label class="form-label fs-6 fw-semibold required" for="expense_on">
                            Paid On
                        </label>
                        <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input class="form-control form-control-sm" name="payment_date" placeholder="Pick a date"
                                    data-datepicker="datepicker" id="payment_date"
                                    value="{{old('payment_date',$data->payment_date)}}"/>
                        </div>
                    </div>
                    {{-- <div class="col-md-4 mb-5 " >
                        <label for="" class="form-label fs-7 mb-2">Total Expense Amount</label>
                        <input type="text" name="total_expense_amount" id="total_expense_amount" class="form-control form-control-sm quantity" placeholder="Amount" value="1" />
                    </div> --}}
                    @if (isUsePaymnetAcc())
                        <div class="col-md-4 mb-5 fv-row">
                            <label for="payment_account" class="form-label fs-7 mb-2">Payment Account</label>
                            <select name="payment_account_id" id="payment_account" data-control="select2" class="form-select form-select-sm" data-dropdown-parent="#payemntEdit" >
                                <option value="" disabled selected>Please Select Payment Account</option>
                                @foreach ($paymentAccounts as $p)
                                    <option value="{{$p->id}}" @selected($data->payment_account_id==$p->id)>{{$p->name}} ({{$p->account_number}})</option>
                                @endforeach
                            </select>
                            <div class="fs-7 text-gray-400 mt-3">
                                Account current balance :<span id="currentAccBalanceTxt">0</span> <span id="currencySymbol"></span>
                            </div>
                        </div>
                    @endif
                    <div class="{{isUsePaymnetAcc() ? 'col-md-4'  : 'col-md-6' }} col-12 mb-5 fv-row">
                        <label for="payment_amount" class="form-label fs-7 mb-2">Payment Amount</label>
                        <input type="text" name="payment_amount" id="payment_amount" class="form-control form-control-sm input_number" value="{{$data->payment_amount}}">
                    </div>
                    <div class="col-md-12 mb-5 ">
                        <label for="" class="form-label fs-7 mb-2">Note</label>
                        <textarea class="form-control" data-kt-autosize="true" name="note">{{$data->note}}</textarea>
                    </div>
                </div>
            <div class="modal-footer py-3 ">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                {{-- <button type="submit" class="btn btn-primary btn-sm" data-kt-withdrawl-action="submit">Save</button> --}}
                <button type="submit" class="btn btn-primary" id="editSubmit" data-kt-withdrawl-action="submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
            </div>

        </form>
    </div>
</div>
<script>

    $(document).ready(function(){
            numberOnly();
            var currentBalance=0;
            let accounts=@json($paymentAccounts ?? []);
            let payment_amount=isNullOrNan($('#payment_amount').val());
            let paidAccountId=@json($data->payment_account_id ?? '');
            function getBalanceOnAcc(paymentAccId) {
                let selectedAccount=accounts.filter(function(a){
                    return paymentAccId==a.id;
                })[0];
                selectedAccountCurrentBalance=selectedAccount.current_balance;
                $('#currentAccBalanceTxt').text(selectedAccountCurrentBalance);
                let adjustAmount=paidAccountId ==paymentAccId ? payment_amount : 0;
                currentBalance=isNullOrNan(selectedAccountCurrentBalance)+adjustAmount;
                $('#currencySymbol').text(selectedAccount.currency.symbol);
                console.log(currentBalance);
            }
            let paymentAccountId=$('#payment_account').val();
            if(paymentAccountId){
                getBalanceOnAcc(paymentAccountId);
            }
            $('#payment_account').change(function(){
                if(accounts.length ){
                    let paymentAccId=$(this).val();
                    getBalanceOnAcc(paymentAccId);
                    paidAllValidator.init();
                }

            })
                    // user update validation
            var paidAllValidator = function () {
                // Shared variables
                console.log('here-');
                const element = document.getElementById("payemntEdit_container");
                const form = element.querySelector("#payemntEdit");
                // let value={account->current_balance}};
                // Init add schedule modal
                var initPaidAll = () => {

                    // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/

                    // Submit button handler
                    const submitButton = element.querySelectorAll('#editSubmit');
                    console.log(submitButton);
                    submitButton.forEach((btn) => {

                        btn.addEventListener('click', function (e) {
                                var validator =validationField(form);
                                if (validator) {
                                    validator.validate().then(function (status) {
                                        if (status == 'Valid') {
                                            e.currentTarget=true;
                                            btn.setAttribute('data-kt-indicator', 'on');
                                            return true;
                                        } else {
                                            e.preventDefault();

                                            btn.setAttribute('data-kt-indicator', 'off');
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
                        initPaidAll();
                    }
                };
            }();
            // On document ready
            KTUtil.onDOMContentLoaded(function () {
                    paidAllValidator.init();
            });

            function validationField(form) {
                $('.fv-plugins-message-container').remove();
                let accountId=$('#payment_account').val();
                var rule={
                    notEmpty: { message: "* Amount is required" },
                };
                if(accountId){
                    rule.lessThan={
                        max: currentBalance,
                        message: 'Insufficient Balance Amount',
                    };
                }
                var validationFields = {
                        payment_amount:{
                            validators:rule,
                        }
                        // payment_amount:paidAmountValidator,
                };
                return  FormValidation.formValidation(
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
    })

    function isNullOrNan(val,d=0){
        let v=parseFloat(val);

        if(v=='' || v==null || isNaN(v)){
            return d;
        }else{
            return v;
        }
    }
</script>
