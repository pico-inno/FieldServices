
<div class="modal-dialog">
    <div class="modal-content">
        <form action="{{route('expenseReport.update',$expenseReport->id)}}" method="POST" id="reportCreateForm">
            @csrf
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
                                        value="{{$expenseReport->expense_title}}"/>
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
                                            value="{{old('expense_on',$expenseReport->expense_on)}}"/>
                                </div>
                            </div>

                            <div class="col-md-8 mb-5 ">
                                <label for="" class="form-label fs-7 mb-2">Note</label>
                                <textarea class="form-control" data-kt-autosize="true" name="note">{{$expenseReport->note}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button  class="btn btn-primary btn-sm"  type="submit" class="btn btn-primary">Submit
                </button>
            </div>
        </form>
    </div>
</div>


