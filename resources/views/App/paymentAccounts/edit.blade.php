
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('paymentAcc.update',$account->id)}}" method="POST" id="add_payment_acounts">
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
                        <div class="col-4 mb-5">
                            <label for="name" class="required form-label">Account Name</label>
                            <input type="text" name="name" id="name" class="form-control form-control-sm" value="{{$account->name}}">
                        </div>
                        {{-- <div class="col-4 mb-5">
                            <label for="account_type" class="required form-label">Account Type</label>
                            <input type="text" name="account_type" id="account_type" class="form-control form-control-sm" value="{{$account->account_type}}">
                        </div> --}}
                        <div class="col-4 mb-5">
                            <label for="account_number" class="required form-label">Account Number</label>
                            <input type="text" name="account_number" id="account_number" class="form-control form-control-sm" value="{{$account->account_number}}">
                        </div>
                        <div class="col-4 mb-5">
                            <label for="opening_amount" class="required form-label">Opening Amount</label>
                            <input type="text" name="opening_amount" id="opening_amount" class="form-control form-control-sm" value="{{$account->opening_amount}}">
                        </div>
                        <div class="col-4 mb-5">
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
                        <div class="col-4 mb-5">
                            <label for="" class="form-label">Descritpion</label>
                            <textarea class="form-control" data-kt-autosize="true" name="description">{{$account->description}}</textarea>
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
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>


<script>
    autosize($('[data-kt-autosize="true"]'))
</script>
