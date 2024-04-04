<table >
    <thead>
        <tr >
            <th  >Opening Date</th>
            <th>Voucher No</th>
            <th >Opening Person</th>
            <th >Opening Amount</th>
            <th >Opening Location</th>
        </tr>
    </thead>
    <tbody >
        @if(count($datas)===0)
        <tr>
            <td>
                NO Data Found
            </td>
        </tr>
        @endif
        @foreach ($datas as $data)
            <tr class="">
                <td class="fw-semibold ">{{fdate($data['opening_date'])}}</td>
                <td class="fw-semibold ">{{$data['opening_stock_voucher_no']}}</td>
                <td class="fw-semibold">{{$data['username']}}</td>
                <td class="fw-semibold  text-end">{{formatNumber($data['total_opening_amount'])}}</td>
                <td class="fw-semibold  text-end">{{$data['locationName']}}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr >
            <th colspan="2">
                Total
            </th>

            <th colspan="2">
                {{formatNumber($datas->sum('total_opening_amount'))}}
            </th>
        </tr>
    </tfoot>
    </table>
