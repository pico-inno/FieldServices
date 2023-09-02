<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Padauk:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Padauk, Arial, sans-serif;
        }

        table {
            width: 100%;
        }

        table tr th {
            font-size: 30px;
        }

        table tr td {
            font-size: 27px;
            font-weight: normal;
        }

        .itemsTable th {
            text-align: left;
            margin-bottom: 5px;
            border-bottom: 3px dashed #000;
            /* Add a dotted border under th elements */
        }

        td {
            text-align: left;
        }

        .itemsTable td:last-child {
            text-align: right;
        }
        .vocuherInfo{
            text-align: left;
            margin-bottom: 10px;
        }
        .subInfo{
            margin-top: 40px;
        }
        .subInfo tr td{
            text-align: right;
        }
        .subInfo tr td{
            font-size: 20px;
            font-weight: bold;
        }
        .vocuherInfo tr th{
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table class="vocuherInfo">
        <tr>
            <th style="width: 40%">Voucher No </th>
            <th style="width: 60%">: {{$invoice_no}}</th>
        </tr>
        <tr>
            <th style="width: 40%">Casher </th>
            <th style="width: 60%">: {{Auth::user()->username}}</th>
        </tr>
        <tr>
            <th style="width: 40%">Customer </th>
            <th style="width: 60%">: Walk-In Customer</th>
        </tr>
    </table>
    <table class="itemsTable">
        <tr>
            <th style="width: 50%">Product</th>
            <th style="width: 25%">Qty</th>
            <th style="width: 25% ;text-align: right">Price</th>
        </tr>
        @foreach ($invoice_row as $index => $item)
        <tr style="margin-top: 5px">
            <td>{{ $item['product_name'] }} {{ $item['variation']?"<br>".'('.$item['variation'].')':''}}</td>
            <td>{{ $item['quantity'] }} {{ $item['uomName'] }}</td>
            <td>{{ $item['price'] }}</td>
        </tr>
        @endforeach
    </table>
    <table class="subInfo">
        <tr>
            <td style="width:60%">Total Amount:</td>
            <td style="width: 40%"> {{$voucherData['total']}}</td>
        </tr>
        <tr>
            <td style="width: 60%">Discount Amount: </td>
            <td style="width: 40%"> {{$voucherData['discount']}}</td>
        </tr>
        <tr>
            <td style="width: 60%">Paid Amount:</td>
            <td style="width: 40%"> {{$voucherData['paid']}}</td>
        </tr>
    </table>
</body>

</html>
