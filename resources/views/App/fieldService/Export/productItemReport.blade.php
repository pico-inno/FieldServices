<table >
<thead>
    <tr >
        <th  >Product Name
        </th>
        <th>Category Name
        </th>
        <th >Total Qty</th>
        <th >Total Price</th>
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
            <th>
                {{$data['name']}}
            </th>
            <th>
                {{$data['category_name']}}
            </th>
            <th >
                {{formatNumberv2($data['totalQty'])}} {{$data['uom']}}
            </th>
            <th >
                {{formatNumberv2($data['totalPrice'])}} {{$currency['symbol'] ?? ''}}
            </th>
        </tr>
    @endforeach
</tbody>
<tfoot>
    <tr >
        <th colspan="2">
            Total
        </th>
        <th >
            {{$datas->sum('totalQty')}}
        </th>

        <th >
            {{$datas->sum('totalPrice')}} {{$currency['symbol'] ?? ''}}
        </th>
    </tr>
</tfoot>
</table>
