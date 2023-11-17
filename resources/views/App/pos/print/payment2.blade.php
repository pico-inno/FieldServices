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
        pre {
            margin: 5px;
        }

        @media print {
            pre {
                max-width: 80mm;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <pre>
@php
dd($invoice_row);
echo printFormat('Voucher No','',$invoice_no);
echo printFormat('Casher','',Auth::user()->username);
echo printFormat('Customer','',$totalPriceAndOtherData['customer_name']);
echo '<br><br>';
echo printFormat('Product','Qty','Price');
echo '---------------------------------------------------<br>';
echo '<br>';
foreach ($invoice_row as  $item) {
    $variation=$item['variation']?'('.$item['variation'].')':'';
    $productName=$item['product_name'].$variation;
    echo printFormat($productName,$item['quantity'].' '.$item['uomName'],$item['subtotal']);
}
@endphp
</pre>

</body>

</html>
