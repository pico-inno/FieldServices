<div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Current Session :<br> {{fDate($posSession->created_at)}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                    <div class="col-12">
                        <table class="table  table-layout-fixed  table-row-bordered">
                                <tbody class="">
                                    <tr>
                                        <th class="text-start ps-3">
                                            <span class="fw-semibold fs-7 text-gray-600">Total Sale Amout:</span>
                                        </th>
                                        <td  class="text-end pe-3">
                                            <span class="fw-bold fs-7 text-gray-800">{{price($transactions->sum('transaction_amount'),$transactions[0]->currency_id ?? '')}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start ps-3">
                                            @php
                                                $paidAmount=0;
                                                $balanceAmount=0;
                                                $currencyId=$transactions[0]->currency_id ?? null;
                                                $finalAmount=$paidAmount+$posSession->opening_amount;
                                            @endphp
                                        @foreach ($transactions as $transaction)
                                            @php
                                                $paidAmount+=$transaction->sale->paid_amount;
                                                $balanceAmount+=$transaction->sale->balance_amount;
                                            @endphp
                                        @endforeach
                                            <span class="fw-semibold fs-7 text-gray-600">Total Paid Amount:</span>
                                        </th>
                                        <td  class="text-end pe-3">
                                            <span class="fw-bold fs-7 text-gray-800"> {{price($paidAmount,$currencyId)}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start ps-3">
                                            <span class="fw-semibold fs-7 text-gray-600">Opening Amount:</span>
                                        </th>
                                        <td  class="text-end me-3  pe-3">
                                            <span class="fw-bold fs-7 text-gray-800"> {{price($posSession->opening_amount,$currencyId)}}</span>
                                        </td>
                                    <tr>
                                    <tr class="bg-light-primary p-1">
                                        <th class="text-start ps-3">
                                            <span class="fw-semibold fs-7 text-primary">Final In-Hand:</span>
                                        </th>
                                        <td  class="text-end me-3  pe-3">
                                            <span class="fw-bold fs-7 text-gray-800"> {{price($paidAmount+$posSession->opening_amount,$currencyId)}}</span>
                                        </td>
                                    </tr>
                                    <tr class="bg-light-danger p-1">
                                        <th class="text-start ps-3">
                                            <span class="fw-semibold fs-7 text-danger">Total Due Amount:</span>
                                        </th>
                                        <td  class="text-end me-3  pe-3">
                                            <span class="fw-bold fs-7 text-gray-800"> {{price($balanceAmount,$currencyId)}}</span>
                                        </td>
                                    </tr>
                                </tbody>
                        </table>
                    </div>
                    <div class="col-12 mt-10">
                        <div class="p-2 px-3 bg-gray-100">
                            <h2 class="text-primary mb-0">Sell Vouchers</h2>
                        </div>
                        <div class="table-responsive col-12 p-2 mb-5">
                            <table class="table g-3  min-w-500px">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-700 border-bottom border-gray-200 text-end">
                                        <th class="text-start">Sale Voucher No</th>
                                        <th>Sale Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Balance Amount</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if (count($transactions)<=0)
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold text-gray-400 text-center">There is no  sale voucher</td>
                                        </tr>
                                    @endif
                                    @foreach ($transactions as $transaction)
                                        <tr class="fw-semibold text-end">
                                            <td class="text-start">{{$transaction->sale->sales_voucher_no}}</td>
                                            <td>{{price($transaction->sale->total_sale_amount,$transaction->sale->currency_id)}}</td>
                                            <td>{{price($transaction->sale->paid_amount,$transaction->sale->currency_id)}}</td>
                                            <td class=" text-end">
                                                {{price($transaction->sale->balance_amount,$transaction->sale->currency_id)}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-2 px-3 bg-gray-100">
                            <h2 class="text-primary mb-0">Payment Transactions</h2>
                        </div>
                        <div class="table-responsive col-12 p-2">
                            <table class="table g-3  min-w-500px">
                                <thead>
                                    <tr class="fw-bold fs-7 text-gray-700 border-bottom border-gray-200 text-end">
                                        <th class="text-start">Payment Vouncher No</th>
                                        <th class="">Transaction Type</th>
                                        <th>Paid Ref Vounchers</th>
                                        <th>Payment Account</th>
                                        <th>Payment Amount</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if (count($paymentTransactions)<=0)
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold text-gray-400 text-center">There is no payment transaction</td>
                                        </tr>
                                    @endif
                                    @foreach ($paymentTransactions as $pt)
                                    @php
                                        $pyt=$pt->paymentTransaction;
                                    @endphp
                                        <tr class="fw-semibold text-end">
                                            <td class="text-start">{{$pyt->payment_voucher_no}}</td>
                                            <td>{{$pyt->transaction_type}}</td>
                                            <td>{{$pyt->transaction_ref_no}}</td>
                                            <td class=" text-end">
                                                {{$pyt->payment_account->name,$pyt->currency_id}}
                                            </td>
                                            <td class=" text-end">
                                                {{price($pyt->payment_amount,$pyt->currency_id)}}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- <textarea name="closing_note" id="" cols="30" rows="2" class="form-control"></textarea> --}}

            </div>

        <form action="{{route('pos.sessionDestory',$posSession->id)}}" method="POST">
            <div class="col-12 p-5">
                <label for="" class="text-gray-700 mb-2  form-label">Closing Note</label>
                <textarea name="closing_note" id="" cols="30" rows="2" class="form-control form-control-sm"></textarea>
            </div>
            <div class="modal-footer">
                    @csrf
                    <input type="hidden" name="posRegisterId" value="{{$posRegister->id}}">
                    <input type="hidden" name="closeAmount" value="{{$finalAmount}}">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancle</button>
                    <button type="submit" class="btn btn-primary btn-sm">Close Session</button>
            </div>
        </form>
    </div>
</div>
