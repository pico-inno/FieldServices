
<div class="modal-dialog">
    <div class="modal-content">
        <form action="{{route('exchangeRate.store')}}" method="POST" id="add_exchange_rates">
            @csrf
            <div class="modal-header">
                <h3 class="modal-title">Add Exchange Rates</h3>

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
                        <select name="currency_id" id="currency" class="form-select form-select-sm" data-control="select2">
                            @php
                                $currencies=App\Models\Currencies::get();
                            @endphp
                            <option selected disabled>Select Currency</option>
                            @foreach ($currencies as $c)
                                <option value="{{$c->id}}">{{$c->name}}-{{$c->symbol}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-5">
                        <label for="rate" class="form-label">Rate</label>
                        <input type="text" name="rate" id="rate" class="form-control form-control-sm" placeholder="rate" />
                    </div>

                    <div class="col-2 form-check">
                        <label for="default" class="form-label mb-3">Default</label>
                        <div class="form-check">
                            <input class="form-check-input" name="default"  type="checkbox" value="1" id="default" />
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

<script>
    $('[data-control="select2"]').select2();
    $(document).on('click','#default',function(){
        defaulter($(this));
    })

    $(document).on('change','#currency',function(){
        let filterRate=rates.filter((r)=>{
                return r.currency_id==$(this).val();
        })[0];
        let checkCk=$('#default').prop('checked');
        if(filterRate){
            if(!checkCk){
                if(filterRate.default){
                    $('#default').prop('checked',true);
                    defaulter($('#default'));
                }else{
                    $('#default').prop('checked',false);
                    $('#rate').val(filterRate.rate);
                };
            }else{
                if(filterRate.default){
                    $('#default').prop('checked',true);
                    defaulter($('#default'));
                }else{
                    $('#rate').val(filterRate.rate);
                    $('#default').prop('checked',false);
                    $('#rate').prop('disabled', false);
                };
            }
        }else{
            if(checkCk){
                $('#rate').val(1);
            }else{
                $('#default').prop('checked',false);
                $('#rate').val('');
            }
        }
    })
    function defaulter(default_Event){
        if (default_Event.prop('checked')) {
            $('#rate').val(1);
            $('#rate').prop('disabled', true);
        } else {
            $('#rate').val('');
            $('#rate').prop('disabled', false);
        }
    }
</script>
