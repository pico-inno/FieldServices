
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('exchangeRate.update',$currentRate->id)}}" method="POST" id="add_exchange_rates">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Edit Exchange Rates</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="row mb-6">
                        <div class="col-5">
                            <label for="currency" class="required form-label">Currency</label>
                            <select name="currency_id" id="currency" class="form-select form-select-sm" data-kt-select2="true">
                                @php
                                    $currencies=App\Models\Currencies::get();
                                @endphp
                                @foreach ($currencies as $c)
                                    <option value="{{$c->id}}" @selected($c->id==$currentRate->currency_id)>{{$c->name}}-{{$c->symbol}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5">
                            <label for="rate" class="form-label">Rate</label>
                            <input type="text" name="rate" id="rate" value="{{$currentRate->rate}}" class="form-control form-control-sm" placeholder="rate" />
                        </div>

                        <div class="col-2 form-check">
                            <label for="default" class="form-label mb-3">Default</label>
                            <div class="form-check">
                                <input class="form-check-input" name="default" type="checkbox" value="1" id="default" @checked($currentRate->default==1) />
                            </div>
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
