<table >
    <thead>
        <tr >
            <th>Opening Date</th>
            <th>Location Name</th>
            <th>Voucher No</th>
            <th>SKU</th>
            <th>Product Name</th>
            <th >UOM Price</th>
            <th >Qty</th>
            <th >UOM</th>
            <th >Subtotal</th>
            <th >Expired Date</th>
            <th>Opening Person</th>
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
                <td >{{fdate($data['opening_date'])}}</td>
                <th>{{$data->locationName}}</th>
                <td >{{$data->opening_stock_voucher_no}}</td>
                <td>{{$data['variation_sku']}}</td>
                <td >{{$data['productName']}} {{$data['variation_name'] ? '('.$data['variation_name'].')' : ''}}</td>
                <td >{{formatNumber($data->uom_price ?? 0)}}</td>
                <td >{{formatNumber($data['quantity'])}}</td>
                <td> {{$data['uomShortName'] ?? ''}}</td>
                <td >{{formatNumber($data->subtotal)}}</td>
                <td >{{fdate($data->expired_date,false,false)}}</td>
                <td >
                    {{$data->username}}
                </td>
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
