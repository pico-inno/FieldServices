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
        body{
            font-family: padauk;
        }
        pre {
            margin: 5px;
        }

        .text-center{
            text-align: center;
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
    {{-- <pre> --}}
@php
// echo printFormat('Voucher No','',$sale['sales_voucher_no']);
$name=getSettingsValue('name') ?? '';
$address=getSettingsValue('address') ?? '';
$bcn=getSettingsValue('business_contact_no') ?? '';
$altcn=getSettingsValue('alt_contact_no') ?? '';
$city=getSettingsValue('city')?? '';
$state=getSettingsValue('state')?? '';
$country=getSettingsValue('country')?? '';
echo '<div class="text-center">';

echo '<h2>'.$name.'</h2>';
echo '<span>'.
        implode(', ',[$address,$city,$state,$country])
    .'</span>';
echo '<br>';
echo '<span>'.
        implode(', ',[$bcn,$altcn])
    .'</span>';
echo '</div>';
echo '<pre>';
echo '<br>';

echo printFormat('Date','',fDate($sale['sold_at'],'',false));
echo printFormat('Customer','',$sale['customer']['prefix'].' '.$sale['customer']['first_name'].' '.$sale['customer']['middle_name'].' '. $sale['customer']['last_name']);
echo '<br><br>';
echo printFormat('Product','Qty','Price');
echo '---------------------------------------------------<br>';
echo '<br>';
foreach ($sale_details as  $sd) {
    $variation=$sd['product_variation']?'('.$sd['product_variation']['variation_template_value']['name'].')':'';
    $productName=$sd['product']['name'].$variation;
    echo printFormat($productName,fquantity($sd['quantity']).' '.$sd['uomName'],round($sd['subtotal_with_tax']));
}
echo '<br>';
echo '---------------------------------------------------<br>';
echo printFormat('','Total',fprice($sale['total_sale_amount']));
echo "<br><br><br>";
echo "</pre>";
echo "<div class='text-center'>Thanks For Shopping</div>";
// die;
@endphp

</body>

</html>
