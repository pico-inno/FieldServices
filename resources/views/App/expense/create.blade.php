
<div class="modal modal-lg fade" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action={{route('expense.store')}} method="POST" id="create_form">
                @csrf
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" >
                    <div class="card">
                        <div class="card-body">
                            <div class="">
                                <h4 class="fs-5">{{__('expense.create_expense')}}</h4>
                            </div>
                            <div class="separator my-5"></div>

                            <div class="row mb-5">
                                <div class="col-md-4 mb-5 fv-row">
                                    <label for="" class="form-label fs-7 mb-2 required">{{__('expense.select_expense_product')}}</label>
                                    <select name="expense_product_id"  class="form-select form-select-sm expenseProduct" data-dropdown-parent="#create_form" >
                                        {{-- <option value="1">hello</option> --}}
                                    </select>
                                </div>
                                <div class="col-md-4 mb-5  fv-row" >
                                    <label for="" class="form-label fs-7 mb-2 required">Quantity</label>
                                    <input type="text" name="quantity" class="form-control form-control-sm quantity" placeholder="Amount" value="1"/>
                                </div>
                                <div class="col-md-4 mb-5  fv-row">
                                    <label for="" class="form-label fs-7 mb-2 required">Select UOM</label>
                                    <select name="uom_id" id="uom_id" class="form-select form-select-sm" data-control="select2" data-hide-search="true" data-placeholder="UOM" placeholder="UOM">
                                    </select>
                                </div>
                                <div class="col-md-4 mb-5">
                                    <label class="form-label fs-6 fw-semibold " for="purchaseDatee">
                                        Expense On
                                    </label>
                                    <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                            <input class="form-control form-control-sm" name="expense_on" placeholder="Pick a date"
                                                data-td-toggle="datetimepicker" id="expense_on"
                                                value="{{old('expense_on',date('Y-m-d'))}}"/>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-5  fv-row">
                                    <label for="" class="form-label fs-7 mb-2 required">Currency</label>
                                    <select name="currency_id" id="currency_id" class="form-select form-select-sm" data-control="select2" data-hide-search="true" data-placeholder="Select Currency" placeholder="Select Currency">
                                        <option value=""></option>
                                       @php
                                            $currencies=App\Models\Currencies::get();
                                        @endphp
                                        @foreach ($currencies as $c)
                                            <option value="{{$c->id}}" @selected($c->id==$currency_id)>{{$c->name}} - {{$c->symbol}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-5  fv-row">
                                    <label for="" class="form-label fs-7 mb-2 required">Expense Amount</label>
                                    <input type="text" name="expense_amount" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4 offset-md-4 mb-5  fv-row">
                                    <label for="" class="form-label fs-7 mb-2 required">Payment Account</label>
                                    <select name="payment_account_id" id="payment_account_id" data-control="select2" class="form-select form-select-sm" placeholder="Select Account" data-placeholder="Select Account" >

                                    </select>
                                    <div class="fs-7 mt-2">
                                        Current Balance Amount:<span class="text-gray-600" id="currentBalanceTxt">0</span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-5  fv-row">
                                    <label for="" class="form-label fs-7 mb-2">Paid Amount</label>
                                    <input type="text" name="paid_amount" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-6 mb-5 ">
                                    <label for="" class="form-label fs-7 mb-2">Expense Descritpion</label>
                                    <textarea class="form-control" data-kt-autosize="true" name="expense_description"></textarea>
                                </div>
                                <div class="col-md-6 mb-5 ">
                                    <label for="" class="form-label fs-7 mb-2">Note</label>
                                    <textarea class="form-control" data-kt-autosize="true" name="note"></textarea>

                                </div>
                                <div class="col-md-12 mb-5 mt-5">
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
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="expense_save_btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--
@push('scripts')

<script>
            // for increase and decrease SERVICE ITEM QUANTITY
        $(document).on('click', '#increase', function() {
            let incVal = $(this).closest('.dialer').find('input[name="quantity"]');
            let value = parseInt(incVal.val()) + 1;
            incVal.val(value);
        })

        $(document).on('click', '#decrease', function() {
            let decVal = $(this).closest('.dialer').find('input[name="quantity"]');
            let value = parseInt(decVal.val()) - 1;
            decVal.val(value >= 1 ? value : 1);
        })

        var results=[];
        $('#expenseProduct').select2({
            ajax: {
                url: '/expense/product',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                        data.map(function(d) {
                            let variations=d.product_variations;
                            results=[];
                            variations.forEach(function(v) {
                                results.push({
                                    id: v.id,
                                    text: `${d.name} ${v.variation_template_value ?`(${v.variation_template_value.name})`: ''}`,
                                    uom:d.uom
                                });
                            });
                        })
                    return {
                        results
                    };
                },
                cache: true
            },
            placeholder: 'Search for an item',
            minimumInputLength: 3
        })
        $(document).on('change','#expenseProduct',function(){
            let id=$(this).val();
            let selectProudct=results.filter((r)=>{
                return r.id==id
            })[0];
            let uoms=selectProudct.uom.unit_category.uom_by_category;
            console.log(uoms);
            let data=uoms.map((u)=>{
                return {id:u.id,text:u.name}
            });
            console.log(data);
            $('#uom_id').empty();
            $('#uom_id').select2({
                data
            })
        })
</script>

@endpush --}}


