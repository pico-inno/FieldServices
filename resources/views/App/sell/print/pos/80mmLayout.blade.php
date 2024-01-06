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
            font-family: padauk;
        }

        pre {
            margin: 5px;
        }

        .text-center {
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
    {{--
    <pre> --}}
@php

echo '<div class="text-center">';
echo '<p>'.
    $layout->header_text
    .'</p>';
echo '</div>';
echo '<pre>';
echo '<br>';

if($data_text->date){
    echo printFormat('Date','',$sale->created_at->format('j-M-Y'));
}
echo printFormat('Customer','',$sale->customer->getFullNameAttribute());
echo '<br><br>';
$productName=$table_text->description->label ?? 'Decritpion';
$Quantity=$table_text->quantity->label ?? 'Quantity';
$disc=$table_text->discount->label ?? 'Disc';
$price=$table_text->subtotal->label ?? 'Subtotal';



$columnCount=4;
foreach ($table_text as $tt) {
    if($tt->is_show){
        // $columnCount++;
    };
}
if($columnCount == 3){
    echo printFormat($productName,$Quantity,$price);
    echo '---------------------------------------------------<br>';
    echo '<br>';
    foreach ($sale_details as  $sd) {
        $variation=$sd['product_variation']?'('.$sd['product_variation']['variation_template_value']['name'].')':'';
        $productName=$sd['product']['name'].$variation;
        echo printFormat(
            $productName,
            formatNumber($sd['quantity']).' '.$sd['uom']['short_name'],
            price($sd['subtotal_with_tax']));
    }
}elseif($columnCount == 4){
    echo eighty4Column($productName,$Quantity,$disc,$price);
    echo '---------------------------------------------------<br>';
    echo '<br>';
    foreach ($sale_details as  $sd) {
        $variation=$sd['product_variation']?'('.$sd['product_variation']['variation_template_value']['name'].')':'';
        $productName=$sd['product']['name'].$variation;
        echo eighty4Column(
            $productName,
            formatNumber($sd['quantity']).' '.$sd['uom']['short_name'],
            discountTxt($sd->discount_type,$sd->per_item_discount),
            formatNumberv2($sd['subtotal_with_discount']));
        if($sd->discount_type=='percentage'){
            echo printTxtFormat(['','('.formatNumberv2(calPercentage($sd->discount_type,$sd->per_item_discount,$sd->subtotal)).')',''],[1,35,12]);

        }
    }
}

// dd($data_text);

echo '<br>';
echo '---------------------------------------------------<br>';
if($data_text->net_sale_amount->is_show){
    echo printTxtFormat(['',$data_text->net_sale_amount->label,formatNumberv2($sale['sale_amount']-$sale['total_item_discount'])],[1,30,16]);
}
if($data_text->extra_discount_amount->is_show){
    echo printTxtFormat(['',$data_text->extra_discount_amount->label,formatNumberv2($sale['extra_discount_amount'])],[1,30,16]);
    if($sale['extra_discount_type']=='percentage'){
        echo printTxtFormat(['','('.formatNumberv2(calPercentage($sale['extra_discount_type'],$sale['extra_discount_amount'],$sale['sale_amount'])).')',''],[24,23]);
    }
}
if($data_text->total_sale_amount->is_show){
    echo printTxtFormat(['',$data_text->total_sale_amount->label,formatNumberv2($sale['total_sale_amount'])],[1,30,16]);
}
echo "<br>";
echo "</pre>";
echo '<div class="text-center">';
    echo '<p>'.
    $layout->footer_text
    .'</p>';
echo '</div>';
    die;
    @endphp

</body>

</html>
