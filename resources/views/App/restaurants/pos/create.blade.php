
<div class="modal-dialog  ">
    <div class="modal-content" id="pos_create_model">
        <form action="{{route('posStore')}}" method="POST" id="pos_create_form">
            @csrf
            <div class="modal-header">
                <h3 class="modal-title">Register Pos</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row mb-6">
                    <div class="col-md-12 mb-5 fv-row">
                            <input class="form-control form-control border border-1 border-top-0 border-right-0 border-left-0 rounded-0 border-gray-300 fs-4" name="name" placeholder="Pos Register Name"
                                id="name"
                                value=""/>
                    </div>
                    <div class="col-12 mb-5 p-3 user-select-none">
                        <div class="form-check form-check-custom">
                            <input type="checkbox" class="form-check-input form-check-sm border-gray-400 me-3" name="use_for_res" id="use_for_res" value="1">
                            <label class="fs-6 fw-semibold form-label mt-3 cursor-pointer" for="use_for_res">
                                <span >Use For Restaurant</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-6 mb-5 fv-row">
                        <label for="employeeTagify" class="required form-label">Employee</label>
                        <input class="form-control form-control-sm" name="employee_id"  id="employeeTagify"/>

                    </div>
                    @if (isUsePaymnetAcc())
                        <div class="col-6 mb-5 fv-row">
                            <label for="paymentAccTagify" class="required form-label">Payment Account</label>
                            <input class="form-control form-control-sm" name="payment_account_id"  id="paymentAccTagify"/>
                        </div>
                    @endif

                    {{-- <div class="col-6 mb-5">
                        <label for="status" class="required form-label">Status</label>
                        <select name="status" id="status" class="form-select form-select-sm" data-control="select2">
                            <option value="active">Active</option>
                        </select>
                    </div> --}}
                    <div class="col-6 mb-5 fv-row">
                        <label for="printer" class="required form-label">Printer Id</label>
                        <select name="printer_id" id="printer" class="form-select form-select-sm" data-control="select2" placeholder="Select Printer" data-placeholder="Select Printer">
                            <option value="0">Browser Base Printing</option>
                            @foreach ($printers as $printer)
                                <option value="{{$printer->id}}">{{$printer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 mb-5">
                        <label for="location" class=" form-label">Description</label>
                        <textarea name="description" class="form-control form-control-sm" id="" cols="30" rows="3"></textarea>
                    </div>
                </div>
                <div class="row">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
<script>

(function () {
    $('#printer').select2();
    var employeeInput = document.querySelector('#employeeTagify');
    let employee=@json($employee).map((e)=>{
        return {'value':e.username,'id':e.id};
    });
    // Init Tagify script on the above inputs
    tagify = new Tagify(employeeInput, {
        whitelist: employee,
        placeholder: "Type something",
        enforceWhitelist: true,
        dropdown: {
            maxItems: 20,           // <- mixumum allowed rendered suggestions
            classname: "tags-look", // <- custom classname for this dropdown, so it could be targeted
            enabled: 0,             // <- show suggestions on focus
            closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
        }
    });


        var paymentInput = document.querySelector('#paymentAccTagify');
        let paymentAccounts=@json($paymentAccounts).map((pa)=>{
            let accountNumber=pa.account_number ?"("+pa.account_number+")":'';
            return {'value':pa.name+accountNumber,'id':pa.id};
        })
        // Init Tagify script on the above inputs
        tagify = new Tagify(paymentInput, {
            whitelist:paymentAccounts,
            placeholder: "Type payment account",
            enforceWhitelist: true,
            dropdown: {
                maxItems: 20,           // <- mixumum allowed rendered suggestions
                classname: "account-tags", // <- custom classname for this dropdown, so it could be targeted
                enabled: 0,             // <- show suggestions on focus
                closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
            }
        });


            // user update validation
    var posValidator = function () {
        // Shared variables

        const element = document.getElementById("pos_create_model");
        const form = element.querySelector("#pos_create_form");
        // let value={account->current_balance}};
        // Init add schedule modal
        var initPaidAll = () => {

            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/

            // Submit button handler
            const submitButton = element.querySelectorAll('#submit');

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
        posValidator.init();
    });

    function validationField(form) {
        $('.fv-plugins-message-container').remove();
        let accountId=$('#payment_account').val();
        let paidAmountValidator;

        var validationFields = {
                name:{
                    validators: {
                        notEmpty: { message: "* Pos Register Name is required" }
                    },
                },
                employee_id:{
                    validators: {
                        notEmpty: { message: "* Employee is required" }
                    },
                },
                payment_account_id:{
                    validators: {
                        notEmpty: { message: "* Payment Account is required" }
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

})();

</script>
