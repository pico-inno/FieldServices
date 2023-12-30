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

        .logo {

            display: flex;
            justify-content: center;
            align-items: center;
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
// echo printFormat('Voucher No','',$sale['sales_voucher_no']);
$name=getSettingsValue('name') ?? '';
echo '<center>'.$name.'<center>';
echo '<br>';
echo printFormat('Date','',fDate($sale['sold_at'],'',false));
echo printFormat('Customer','',$sale['customer']['prefix'].' '.$sale['customer']['first_name'].' '.$sale['customer']['middle_name'].' '. $sale['customer']['last_name']);
echo '<br><br>';
echo printFormat('Product','Qty','Price');
echo '---------------------------------------------------<br>';
echo '<br>';
foreach ($table_text as  $sd) {
    $variation=$sd['product_variation']?'('.$sd['product_variation']['variation_template_value']['name'].')':'';
    $productName=$sd['product']['name'].$variation;
    echo printFormat($productName,fquantity($sd['quantity']).' '.$sd['uomName'],round($sd['subtotal_with_tax']));
}
echo '<br>';
echo '---------------------------------------------------<br>';
echo printFormat('','Total',fprice($sale['total_sale_amount']));
@endphp
</pre>

</body>

</html>
