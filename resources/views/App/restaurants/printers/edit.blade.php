
<div class="modal-dialog  " id="printer_div">
    <div class="modal-content">
        <form action="{{route('printerUpdate',$printer->id)}}" method="POST" id="add_printer_form">
            @csrf
            <div class="modal-header">
                <h3 class="modal-title">Edit Printer</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row mb-6">
                    <div class="col-md-12 mb-5 fv-row">
                            <input class="form-control form-control border border-1 border-top-0 border-right-0 border-left-0 rounded-0 border-gray-300 fs-4" name="name" placeholder="Enter Printer Name (eg.Epson)"
                                id="name"
                                value="{{$printer->name}}"/>
                    </div>
                    <div class="col-6 mb-5">
                        <label for="location" class=" form-label">Printer Type</label>
                        <select name="printer_type" id="location" class="form-select form-select-sm" data-control="select2">
                            {{-- @foreach ($locations as $l) --}}
                                <option value="network" @selected($printer->printer_type="network")>network</option>
                            {{-- @endforeach --}}
                        </select>
                    </div>
                    <div class="col-6 mb-5">
                        <label for="location" class=" form-label">Ip Address</label>
                        <input type="text" name="ip_address" class="form-control form-control-sm" value="{{$printer->ip_address}}" placeholder="192.168.00.00">
                    </div>
                    <div class="col-6 mb-5">
                        <label for="location" class=" form-label">Product Category</label>
                        <select name="product_category_id" id="location" class="form-select form-select-sm" data-control="select2">
                            @foreach ($categories as $c)
                                <option value="{{$c->id}}" @selected($c->id==$printer->product_category_id)>{{$c->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="submit">Save</button>
            </div>
        </form>
    </div>
</div>
<script>

    $(document).ready(function(){
                    // user update validation
            var addPrinterValidator = function () {
                // Shared variables

                const element = document.getElementById("printer_div");
                const form = element.querySelector("#add_printer_form");
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
                addPrinterValidator.init();
            });

            function validationField(form) {
                $('.fv-plugins-message-container').remove();
                var validationFields = {
                    name:{
                        validators: {
                            notEmpty: { message: "* Printer Name is Require" }
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
