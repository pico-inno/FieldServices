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
$lineDisc=getSettingsValue('enable_line_discount_for_sale') ?? 1;
echo '<center>'.$name.'<center>';
echo '<br>';
echo printFormat('Date','',fDate($sale['created_now'],'',false));
echo printFormat('Customer','',$sale->customer->getFullNameAttribute());
echo '<br><br>';
if($lineDisc==0){
    echo printFormat('Product','Qty','Price');
    echo '---------------------------------------------------<br>';
    echo '<br>';
    foreach ($sale_details as  $sd) {
        $variation=$sd['product_variation']?'('.$sd['product_variation']['variation_template_value']['name'].')':'';
        $productName=$sd['product']['name'].$variation;
        echo printFormat($productName,fquantity($sd['quantity']).' '.$sd['uomName'],fprice($sd['subtotal']));
    }
}else{

    echo eighty5Column('Product','Qty','Price','Disc','Subtotal');
    echo '---------------------------------------------------<br>';
    echo '<br>';
    foreach ($sale_details as  $sd) {
        $variation=$sd['product_variation']?'('.$sd['product_variation']['variation_template_value']['name'].')':'';
        $productName=$sd['product']['name'].$variation;
        echo eighty5Column(
                        $productName,
                        formatNumber($sd['quantity']).' '.$sd['uomName'],
                        formatNumber($sd['uom_price']),
                        calPercentage($sd['discount_type'],$sd['per_item_discount'],$sd['subtotal']),
                        fprice($sd['subtotal_with_discount']));
        $discTxt=discountTxt($sd['discount_type'],$sd['per_item_discount']);
        echo $discTxt ? eighty5Column('','','','('.$discTxt.')','') :'';
    }
}
echo '<br>';
echo '---------------------------------------------------<br>';


// echo printFormat('','Amount',fprice($sale['sale_amount']));
// echo printFormat('','Discount',calPercentage($sale['extra_discount_type'],$sale['extra_discount_amount'],$sale['sale_amount']));
echo printFormat('','Total',fprice($sale['total_sale_amount']));
@endphp
</pre>

</body>

</html>
