
<table>
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>UOM</th>
            <th>package Qty </th>
            <th>Pkg</th>
            <th>Category</th>
            <th>Outlet</th>
            <th>PG</th>
            <th>Campaign</th>
            <th> Date</th>
        </tr>
    </thead>
    <tbody class="fw-semibold text-gray-600 fs-6 fw-semibold" id="allSaleTable">

        @foreach ($datas as $data)
        <tr class="">
            <td class="text-start">{{$data['name']}}</td>
            <td>{{$data['quantity']}}</td>
            <td>{{$data['uom']}}</td>
            <td>{{$data['pkgQty']}}</td>
            <td>{{$data['pkg']}}</td>
            <td class="text-start">{{$data['category_name']}}</td>
            <td>{{$data['outlet']}}</td>
            <td>{{$data['pg_fs']}}{{$data['pg_ls']}}</td>
            <td>{{$data['campaignName']}}</td>
            <td>{{fdate($data['sold_at'])}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td style="font-weight: bold;font-size: 15px">
                Total
            </td>
            <td style="font-weight: bold;font-size: 15px">
                {{$datas->sum('quantity')}}
            </td>

        </tr>
    </tfoot>
</table>
