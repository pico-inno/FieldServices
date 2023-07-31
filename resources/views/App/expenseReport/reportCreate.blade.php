
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="POST" id="reportCreateForm">
                {{-- @csrf --}}
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" >
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="fs-5">Expense Report <span class="badge badge-primary" id="expense_count"></span></h4>
                                    <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-secondary btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times fs-4"></i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <div class="separator my-5"></div>
                            <div class="row mb-5">
                                <div class="col-md-12 mb-5">
                                        <input class="form-control form-control border border-1 border-top-0 border-right-0 border-left-0 rounded-0 border-gray-300 fs-4" name="expense_title" placeholder="Enter Expense Report Title"
                                            id="expense_title"
                                            value=""/>
                                </div>
                                <div class="col-md-4 mb-5 mt-3">
                                    <label class="form-label fs-6 fw-semibold required" for="expense_on">
                                        Expense On
                                    </label>
                                    <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                            <input class="form-control form-control-sm " name="expense_on" placeholder="Pick a date"
                                                data-datepicker="datepicker" id="expense_on"
                                                value="{{old('expense_on',date('Y-m-d'))}}"/>
                                    </div>
                                </div>
{{--
                                <div class="col-md-4 mb-5">
                                    <label for="" class="form-label fs-7 mb-2">Payment Account</label>
                                    <select name="" id="" data-control="select2-acc" class="form-select form-select-sm" data-dropdown-parent="#reportAddModal" >
                                        @php
                                            $paymentAccounts=App\Models\paymentAccounts::where('currency_id',request()->currency_id)->get();
                                        @endphp
                                        @foreach ($paymentAccounts as $p)
                                            <option value="{{$p->id}}">{{$p->name}} ({{$p->account_number}})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-5">
                                    <label for="" class="form-label fs-7 mb-2">Currency</label>
                                    <select name="currency_id" id="" class="form-select form-select-sm" data-control="select2" data-hide-search="true" data-dropdown-parent="#reportAddModal" data-placeholder="Select Currency" placeholder="Select Currency" disabled>
                                        @php
                                            $currency=App\Models\Currencies::where('id',request()->currency_id)->first();
                                        @endphp
                                        <option value="{{$currency->id}}" selected>{{$currency->name}} - {{$currency->symbol}}</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-5  offset-md-8" >
                                    <label for="" class="form-label fs-7 mb-2">Total Expense Amount</label>
                                    <input type="text" name="total_expense_amount" id="total_expense_amount" class="form-control form-control-sm quantity" placeholder="Amount" value="1" />
                                </div>
                                <div class="col-md-4 mb-5 offset-md-8">
                                    <label for="paid_amount" class="form-label fs-7 mb-2">Paid Amount</label>
                                    <input type="text" name="paid_amount" id="paid_amount" class="form-control form-control-sm">
                                </div> --}}
                                {{-- <div class="col-md-4 mb-5 offset-md-8">
                                    <label for="balance_amount" class="form-label fs-7 mb-2">Balance Amount</label>
                                    <input type="text" name="balance_amount" id="balance_amount" class="form-control form-control-sm" >
                                </div> --}}
                                {{-- <div class="col-md-12 mb-5 mt-5">
                                    <label class="fs-7 fw-semibold form-label " for="documents">
                                        <span class="required">Attach Document:</span>
                                    </label>
                                    <div class="input-group browseLogo">
                                        <input type="file" class="form-control form-control-sm" id="documents" name="documents">
                                        <button type="button" class="btn btn-sm btn-danger d-none" id="removeFileBtn"><i class="fa-solid fa-trash"></i></button>
                                        <label class="input-group-text btn btn-primary rounded-end btn-sm" for="documents">
                                            Browse
                                            <i class="fa-regular fa-folder-open"></i>
                                        </label>
                                    </div>
                                    <p class="text-gray-600 mt-3 d-block fs-8">
                                        Max File size: 5MB <br>
                                        Allowed File: .pdf, .csv, .zip, .doc, .docx, .jpeg, .jpg, .png
                                    </p>
                                </div> --}}

                                <div class="col-md-8 mb-5 ">
                                    <label for="" class="form-label fs-7 mb-2">Note</label>
                                    <textarea class="form-control" data-kt-autosize="true" name="note"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button  class="btn btn-primary"  type="button" class="btn btn-primary" data-submit="form-report-save">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>


