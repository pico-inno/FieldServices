
    <div class="modal-dialog" id="edit_payment_accounts">
        <div class="modal-content">
            <form action="{{route('paymentAcc.update',$account->id)}}" method="POST" id="edit_payment_accounts_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Edit Payment Accounts</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="row mb-6">
                        <div class="col-12 col-lg-4 col-md-6 mb-5 fv-row">
                            <label for="name" class="required form-label">Account Name</label>
                            <input type="text" name="name" id="name" class="form-control form-control-sm" value="{{$account->name}}">
                        </div>
                        {{-- <div class="col-12 col-lg-4 col-md-6 mb-5">
                            <label for="account_type" class="required form-label">Account Type</label>
                            <input type="text" name="account_type" id="account_type" class="form-control form-control-sm" value="{{$account->account_type}}">
                        </div> --}}
                        <div class="col-12 col-lg-4 col-md-6 mb-5">
                            <label for="account_number" class=" form-label">Account Number</label>
                            <input type="text" name="account_number" id="account_number" class="form-control form-control-sm" value="{{$account->account_number}}">
                        </div>
                        <div class="col-12 col-lg-4 col-md-6 mb-5 fv-row">
                            <label for="opening_amount" class=" form-label">Opening Amount</label>
                            <input type="text" name="opening_amount" id="opening_amount" class="form-control form-control-sm input_number" value="{{$account->opening_amount}}">
                        </div>
                        <div class="col-12 col-lg-4 col-md-6 mb-5 fv-row">
                            <label for="currency" class="required form-label">Currency</label>
                            <select name="currency_id" id="" data-control="select2" data-dropdown-parent="#add_payment_acounts_modal" class="form-select form-select-sm">
                                <option disabled selected>Select Currency</option>
                                @php
                                    $currencies=App\Models\Currencies::get();
                                @endphp
                                @foreach ($currencies as $c)
                                    <option value="{{$c->id}}" @selected($c->id==$account->currency_id)>{{$c->name}} - {{$c->symbol}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-lg-4 col-md-6 mb-5 fv-row">
                            <label for="name" class="required form-label">Attachment</label>
                            <input type="file" name="qrimage" id="qrimage" accept="image/*" class="form-control form-control-sm">
                        </div>
                        <div class="col-12 col-lg-4 col-md-6 mb-5">
                            <label for="" class="form-label">Descritpion</label>
                            <textarea class="form-control" data-kt-autosize="true" name="description">{{$account->description}}</textarea>
                        </div>
                        <div class="col-12 text-start">
                            <div class="mb-3">
                                <button class="btn  btn-danger btn-sm p-2 mt-2  fs-8 " type="button" id="removeImage"><i class="fa-solid fa-trash fs-8 " ></i>Remove logo</button>
                            </div>
                            @if ($account['qrimage'])
                                <input type="hidden" name="hasImage" id="hasImage"  value="{{ getImage($account['qrimage']) ? 'exist':'' }}" >
                                <img id="qrimageShow" src="data:image/png;base64,{{ getImage($account['qrimage']) }}" class="text-center" alt="Uploaded Image" width="200">
                            @endif
                        </div>
                        {{-- <div class="col-5">
                            <label for="rate" class="form-label">Rate</label>
                            <input type="text" name="rate" id="rate" class="form-control form-control-sm" placeholder="rate" />
                        </div>

                        <div class="col-2 form-check">
                            <label for="default" class="form-label mb-5">Default</label>
                            <div class="form-check">
                                <input class="form-check-input" name="default"  type="checkbox" value="1" id="default" />
                            </div>
                        </div> --}}
                    </div>
                    {{-- <div class="row">
                    </div> --}}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="edit_submit">Save</button>
                </div>
            </form>
        </div>
    </div>


<script>
    autosize($('[data-kt-autosize="true"]'))

    $(document).ready(function(){
            $('#removeImage').click(()=>{
                $('#qrimageShow').remove();
                $('#hasImage').val('');
            })
                    // user update validation
            var paidAllValidator = function () {
                // Shared variables

                const element = document.getElementById("edit_payment_accounts");
                const form = element.querySelector("#edit_payment_accounts_form");
                // let value={account->current_balance}};
                // Init add schedule modal
                var initPaidAll = () => {

                    // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/

                    // Submit button handler
                    const submitButton = element.querySelectorAll('#edit_submit');

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
                let paidAmountValidator;

                var validationFields = {
                        name:{
                            validators: {
                                notEmpty: { message: "* Payment Account Name is required" }
                            },
                        },
                        currency_id:{
                            validators: {
                                notEmpty: { message: "* Currency is required" }
                            },
                        },
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
</script>
