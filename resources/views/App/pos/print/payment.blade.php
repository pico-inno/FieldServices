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
echo printFormat('Voucher No','',$sale['sales_voucher_no']);
echo printFormat('Casher','',Auth::user()->username);
echo printFormat('Customer','',$sale->customer->getFullNameAttribute());
echo '<br><br>';
echo printFormat('Product','Qty','Price');
echo '---------------------------------------------------<br>';
echo '<br>';
foreach ($sale_details as  $sd) {
    // dd($sale);
    // die;
    $variation=$sd['product_variation']?'('.$sd['product_variation']['variation_template_value']['name'].')':'';
    $productName=$sd['product']['name'].$variation;
    echo printFormat($productName,$sd['quantity'].' '.$sd['uomName'],$sd['subtotal']);
}
echo '<br>';
echo '---------------------------------------------------<br>';
echo printFormat('','Total',$sale['total_sale_amount']);
die;
@endphp
</pre>

</body>

</html>
