
<div class="modal-dialog bg-white">
    <div class="modal-content">
        <form action={{route('expense.update',$expense->id)}} method="POST" id="edit_form">
            @csrf
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" >
                <div class="modal-body">
                    <div class=" d-flex justify-content-between align-items-center">
                        <h4 class="fs-5">Expense View {{$expense->expense_voucher_no}}</h4>
                        <div class="btn btn-icon btn-sm btn-light ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times fs-5"></i>
                        </div>
                    </div>
                    <div class="separator my-5"></div>

                    <div class="row mb-5">
                        <div class="col-6">
                            <table class="table ">
                                <tbody class="">
                                    <tr>
                                        <th class="text-start">
                                            <span class="fw-semibold fs-7 text-gray-600">Expense Product:</span>
                                        </th>
                                        <td  class="text-end">
                                            @php
                                                $variation_name=$expense->variationProduct->toArray()['variation_template_value'] ;
                                                $variation_name_text=$variation_name ? '('. $variation_name['name'].')':' ';
                                                $finalText=$expense->variationProduct->product->name.' '.$variation_name_text;
                                            @endphp
                                            <span class="fw-bold fs-7 text-gray-800">{{$finalText}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">
                                            <span class="fw-semibold fs-7 text-gray-600">expense quantity:</span>
                                        </th>
                                        <td  class="text-end">
                                            <span class="fw-bold fs-7 text-gray-800">{{$expense->quantity}} {{$expense->variationProduct->product->uom->short_name}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">
                                            <span class="fw-semibold fs-7 text-gray-600">expense On:</span>
                                        </th>
                                        <td  class="text-end">
                                            <span class="fw-bold fs-7 text-gray-800">{{$expense->expense_on}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">
                                            <span class="fw-semibold fs-7 text-gray-600">expense By:</span>
                                        </th>
                                        <td  class="text-end">
                                            <span class="fw-bold fs-7 text-gray-800">{{$expense->createdBy->username ??''}}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-6">
                            <table class="table  table-layout-fixed  table-row-bordered">
                                <tbody class="">
                                    <tr>
                                        <th class="text-start">
                                            <span class="fw-semibold fs-7 text-gray-600">Payment Status:</span>
                                        </th>
                                        <td  class="text-end">
                                            @if ($expense->payment_status=='pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif ($expense->payment_status=='partial')
                                                <span class="badge badge-primary">Partial</span>
                                            @elseif ($expense->payment_status=='paid')
                                                <span class="badge badge-success">Paid</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">
                                            <span class="fw-semibold fs-7 text-gray-600">Expense amount:</span>
                                        </th>
                                        <td  class="text-end">
                                            <span class="fw-bold fs-7 text-gray-800">{{$expense->expense_amount}} {{$expense->currency->symbol}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">
                                            <span class="fw-semibold fs-7 text-gray-600">Paid amount:</span>
                                        </th>
                                        <td  class="text-end">
                                            <span class="fw-bold fs-7 text-gray-800">{{$expense->paid_amount}} {{$expense->currency->symbol}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">
                                            <span class="fw-semibold fs-7 text-gray-600">balance amount:</span>
                                        </th>
                                        <td  class="text-end">
                                            <span class="fw-bold fs-7 text-gray-800">{{$expense->balance_amount}} {{$expense->currency->symbol}}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-12">
                            <table class="table  table-layout-fixed  table-row-bordered">
                                <tbody class="">
                                    <tr>
                                        <th class="text-start">
                                            <span class="fw-semibold fs-7 text-gray-600">Expense Description:</span>
                                        </th>
                                        <th class="text-start">
                                            <span class="fw-semibold fs-7 text-gray-600">note:</span>
                                        </th>
                                    </tr>
                                    <tr>

                                        <td  class="text-start">
                                            <span class="fw-semibold fs-7 text-gray-700">{{$expense->expense_description}}</span>
                                        </td>
                                        <td  class="text-start">
                                            <span class="fw-semibold fs-7 text-gray-700">{{$expense->note}} </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>

    </div>
</div>


