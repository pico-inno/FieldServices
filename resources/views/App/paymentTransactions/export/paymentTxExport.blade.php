
<table class="table align-middle table-row-dashed fs-6 gy-4" id="">
    <thead>
        <tr style="font-weight: bold;">
            <th style="font-weight: bold;">Payment Voucher No</th>
            <th style="font-weight: bold;">Pay Date</th>
            <th style="font-weight: bold;">Transaction Type</th>
            <th style="font-weight: bold;">Transaction Reference No</th>
            <th style="font-weight: bold;" >Payment Method</th>
            <th style="font-weight: bold;">Payment Account</th>
            <th style="font-weight: bold;">Payment Type</th>
            <th style="font-weight: bold;">Rate</th>
            <th style="font-weight: bold;">Debit</th>
            <th style="font-weight: bold;">Credit</th>
            <th style="font-weight: bold;">Balance Amount</th>
            <th style="font-weight: bold;">note</th>
        </tr>
    </thead>
    <tbody class="fw-bold text-gray-600 text-start fs-7 text-end">
        @foreach ($datas as $data)
            <tr>
                <td>
                    {{$data['payment_voucher_no']}}
                </td>
                <td class="text-start">
                    {{$data['payment_date']}}
                </td>
                <td class="text-start">
                    {{$data['transaction_type']}}
                </td>
                <td>
                    {{$data['transaction_ref_no']}}
                </td>
                <td>
                    {{$data['payment_method']}}
                </td>
                <td>
                    {{isset($data['payment_account'])?$data['payment_account']['name']:''}}
                </td>
                <td>
                    @php
                        $paymentType=$data['payment_type'];
                    @endphp
                    @if($paymentType == 'withdrawl')
                        <span class='badge badge-danger'>Withdrawl</span>
                    @elseif($paymentType=='deposit')
                        <span class='badge badge-primary'>Deposit</span>
                    @elseif($paymentType=='debit')
                        <span class='badge badge-success'>Debit</span>
                    @elseif($paymentType=='credit')
                        <span class='badge badge-danger'>Credit</span>
                    @elseif($paymentType=='opening_amount')
                        <span class='badge badge-info'>Opening Amount</span>
                    @elseif($paymentType=='transfer')
                        <span class='badge badge-secondary'>Transfer</span>
                    @endif
                </td>

                <td>
                    {{$data['exchange_rate']}}
                </td>
                <td>
                    @if ($data->payment_type=="debit")
                        {{$data->payment_amount}}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if ($data->payment_type=="credit")
                        {{$data->payment_amount}}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if($data->payment_type=="credit")
                        {{($balanceAmount)}}
                        @php
                            $balanceAmount+=$data->payment_amount;
                        @endphp
                    @elseif($data->payment_type=="debit")
                        {{$balanceAmount}}
                        @php
                            $balanceAmount-=$data->payment_amount;
                        @endphp
                    @endif
                </td>
                <td>
                    {{$data['note']}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
