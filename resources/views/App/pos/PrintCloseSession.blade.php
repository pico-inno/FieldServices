
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POS Sesssion Report</title>
    <link href={{asset("assets/css/style.bundle.css")}} rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="col-lg-8 text-center" style="max-width: 600px; margin: 0 auto;">
        <div class="text-center mt-5">
            <h6 class="modal-title">POS Session Report For :<br> {{fDate($posSession->opening_at)}} - {{fDate($posSession->closing_at ?? now())}}</h6>
        </div>

        <div class="mt-10">
            <div class="col-12" id="content">
                <table class="table  table-layout-fixed  table-row-bordered">
                        <tbody class="">
                                @php
                                    $paidAmount=0;
                                    $balanceAmount=0;
                                    $currencyId=$saleTransactions[0]->currency_id ?? null;
                                    $finalAmount=$paidAmount+$posSession->opening_amount;
                                    $transactionIds=[];
                                    $totalSaleAmount=0;
                                @endphp
                                @foreach ($saleTransactions as $transaction)

                                    @php
                                    // dd($transaction->sale->balance_amount);
                                        if(in_array($transaction->sale->id,$transactionIds)){
                                            continue;
                                        }
                                        $transactionIds[]=$transaction->sale->id;
                                        $paidAmount+=$transaction->sale->paid_amount;
                                        $balanceAmount+=$transaction->sale->balance_amount;
                                        $totalSaleAmount+=$transaction->sale->total_sale_amount;
                                    @endphp
                                @endforeach
                            <tr>
                                <th class="text-start ps-3">
                                    <span class="fw-semibold fs-7 text-gray-600">Total Sale Amout:</span>
                                </th>
                                <td  class="text-end pe-3">
                                    <span class="fw-bold fs-7 text-gray-800">{{price($totalSaleAmount,$saleTransactions[0]->currency_id ?? '')}}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-start ps-3">
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
            @if (isUsePaymnetAcc() && count($sumAmountOnPaymentAcc) > 0)
                <div class="col-12 mt-10">
                    <table class="table  table-layout-fixed  table-row-bordered">
                            <thead class="">
                                <h5 class="text-gray-600 ps-3">Payment Accounts</h5>
                            </thead>
                            <tbody class="">
                                    @foreach ($sumAmountOnPaymentAcc as $sumAcc)
                                    @php
                                        $paymentAcc=$sumAcc->paymentAccount;
                                    @endphp
                                    @if ($paymentAcc)
                                        <tr>
                                            <th class="text-start ps-3">
                                                <span class="fw-semibold fs-7 text-gray-600">{{$paymentAcc->name ?? ''}}</span>
                                            </th>
                                            <td  class="text-end pe-3">
                                                <span class="fw-bold fs-7 text-gray-800">{{price($sumAcc->total_amount,$sumAcc->currency_id)}}</span>
                                            </td>
                                        </tr>
                                    @endif
                                    @endforeach
                            </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
