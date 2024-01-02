
<div class="modal-dialog">
    <div class="modal-content">
        <form action={{route('expense.update',$expense->id)}} method="POST" id="edit_form">
            @csrf
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" >
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <h4 class="fs-5">{{__('expense.edit_expense')}}</h4>
                        </div>
                        <div class="separator my-5"></div>

                        <div class="row mb-5 dialer">
                            <div class="col-md-4 mb-3">
                                <label for="" class="form-label fs-7 mb-2">{{__('expense.select_expense_product')}}</label>
                                <select name="expense_product_id"  class="form-select form-select-sm expenseProduct" data-dropdown-parent="#edit_form" >
                                    @php
                                        $variation_name=$expense->variationProduct->toArray()['variation_template_value'] ;
                                        $variation_name_text=$variation_name ? '('. $variation_name['name'].')':' ';
                                        $finalText=$expense->variationProduct->product->name.' '.$variation_name_text;
                                    @endphp
                                    <option value="{{$expense->expense_product_id}}">{{$finalText}}</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3 ">
                                <label for="" class="form-label fs-7 mb-2">Quantity</label>
                                <input type="text" name="quantity"  class="form-control form-control-sm quantity input_number" placeholder="Amount" value="{{$expense->quantity}}"/>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="" class="form-label fs-7 mb-2">Select UOM</label>
                                <select name="uom_id" id="uom_id" class="form-select form-select-sm" data-control="select2" data-hide-search="true">
                                    @php
                                        $uoms=$expense->variationProduct->product->uom->unit_category->uomByCategory;
                                    @endphp
                                    @foreach ($uoms as $u)
                                        <option value="{{$u->id}}" @selected($u->id == $expense->uom_id)>{{$u->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-5">
                                <label class="form-label fs-6 fw-semibold required" for="purchaseDatee">
                                    Expense On
                                </label>
                                <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        <input class="form-control form-control-sm" name="expense_on" placeholder="Pick a date"
                                            data-td-toggle="datetimepicker" id="expense_on"
                                            value="{{old('expense_on',$expense->expense_on)}}"/>
                                </div>
                            </div>
                            @if ($expense->paid_amount==0)
                            <div class="col-md-4 mb-3">
                                <label for="" class="form-label fs-7 mb-2">Currency</label>
                                <select name="currency_id" id="" class="form-select form-select-sm" data-control="select2" data-hide-search="true" data-placeholder="Select Currency" placeholder="Select Currency">
                                    @foreach ($currencies as $c)
                                        <option value="{{$c->id}}" @selected($c->id==$expense->currency_id)>{{$c->name}} - {{$c->symbol}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @endif
                            <div class="col-md-4 mb-3">
                                <label for="" class="form-label fs-7 mb-2">Expense Amount</label>
                                <input type="text" name="expense_amount" class="form-control form-control-sm input_number" value="{{$expense->expense_amount}}">
                            </div>
                            <div class="col-md-6 mb-3 ">
                                <label for="" class="form-label fs-7 mb-2">Expense Descritpion</label>
                                <textarea class="form-control" data-kt-autosize="true" name="expense_description">{{$expense->expense_description}}</textarea>
                            </div>
                            <div class="col-md-6 ">
                                <label for="" class="form-label fs-7 mb-2">Note</label>
                                <textarea class="form-control" data-kt-autosize="true" name="note">{{$expense->note}}</textarea>

                            </div>
                            <div class="col-md-12 mb-3">
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
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>

    </div>
</div>

<script>
    numberOnly();
</script>

