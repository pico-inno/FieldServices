
<div class="modal-dialog  ">
    <div class="modal-content">
        <form action="{{route('posStore')}}" method="POST" id="add_exchange_rates">
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
                    <div class="col-md-12 mb-5">
                            <input class="form-control form-control border border-1 border-top-0 border-right-0 border-left-0 rounded-0 border-gray-300 fs-4" name="name" placeholder="Pos Register Name"
                                id="name"
                                value=""/>
                    </div>
                    <div class="col-6 mb-5">
                        <label for="employeeTagify" class="required form-label">Employee</label>
                        <input class="form-control form-control-solid" name="employee_id"  id="employeeTagify"/>

                    </div>
                    <div class="col-6 mb-5">
                        <label for="paymentAccTagify" class="required form-label">Payment Account</label>
                        <input class="form-control form-control-solid" name="payment_account_id"  id="paymentAccTagify"/>
                    </div>
                    {{-- <div class="col-6 mb-5">
                        <label for="status" class="required form-label">Status</label>
                        <select name="status" id="status" class="form-select form-select-sm" data-control="select2">
                            <option value="active">Active</option>
                        </select>
                    </div> --}}
                    <div class="col-6 mb-5">
                        <label for="printer" class="required form-label">Printer Id</label>
                        <select name="printer_id" id="printer" class="form-select form-select-sm" data-control="select2">
                            @foreach ($printers as $printer)
                                <option value="{{$printer->id}}">{{$printer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 mb-5">
                        <label for="location" class="required form-label">Description</label>
                        <textarea name="description" class="form-control form-control-sm" id="" cols="30" rows="3"></textarea>
                    </div>
                </div>
                <div class="row">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
<script>

(function () {
    var employeeInput = document.querySelector('#employeeTagify');
    let employee=@json($employee).map((e)=>{
        return {'value':e.username,'id':e.id};
    });
    // Init Tagify script on the above inputs
    tagify = new Tagify(employeeInput, {
        whitelist: employee,
        placeholder: "Type something",
        enforceWhitelist: true
    });


    var paymentInput = document.querySelector('#paymentAccTagify');
    let paymentAccounts=@json($paymentAccounts).map((pa)=>{
        return {'value':pa.name +"("+pa.account_number+")",'id':pa.id};
    })
    // Init Tagify script on the above inputs
    tagify = new Tagify(paymentInput, {
        whitelist:paymentAccounts,
        placeholder: "Type payment account",
        enforceWhitelist: true
    });
})();

</script>
