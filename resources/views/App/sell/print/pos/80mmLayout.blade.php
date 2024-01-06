<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Myanmar:wght@300&display=swap" rel="stylesheet">

    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
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


            .zero *{
                margin: 0;
                padding: 0;
            }
        @media print {
            pre {
                max-width: 80mm;
                margin: 0;
            }
            .zero *{
                margin: 0;
                padding: 0;
            }
        }
        .logo-wrapper{
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
        }
        .logo{
            width: 60px;
            height: 60px;
            text-align: center;
        }
    </style>
</head>

<body>
@php
    $logo=$data_text->logo ?? null;
    $url=asset('/storage/logo/invoice/'.$logo);
@endphp
    {{--
    <pre> --}}

<div class="text-center mb-5 logo-wrapper">
    <img src="{{$url}}" class="logo" />
</div>
@php
// labels
$tableDescription=$table_text->description;
$tableQuantity=$table_text->quantity;
$tableUomPrice=$table_text->uom_price;
$tableDisc=$table_text->discount;
$tableSubtotal=$table_text->subtotal;

$productNameLabel=$tableDescription->label ?? 'Descritpion';
$QuantityLabel=$tableQuantity->label ?? 'Quantity';
$unitPriceLabel=$tableUomPrice->label ?? 'Unit Price';
$discLabel=$tableDisc->label ?? 'Disc';
$priceLabel=$tableSubtotal->label ?? 'Subtotal';


$dataTable=[];
$columnCount=0;
foreach ($table_text as $key=>$tt) {
    if($tt->is_show && $tt->label !='No'){
        $lableText=$tt->label;
        $columnCount++;
        if($key=='description'){
            $dataTable[]=[
                'label'=>'productNameLabel',
                'var'=>'productName',
            ];
        }elseif($key=='quantity'){
            $dataTable[]=[
                'label'=>'QuantityLabel',
                'var'=>'quantity',
            ];
        }elseif($key=='uom_price'){
            $dataTable[]=[
                'label'=>'unitPriceLabel',
                'var'=>'uomPrice',
            ];
        }elseif($key=='discount'){
            $dataTable[]=[
                'label'=>'discLabel',
                'var'=>'perItemDiscount',
            ];
        }elseif($key=='subtotal'){
            $dataTable[]=[
                'label'=>'priceLabel',
                'var'=>'subtotalWithDiscount',
            ];
        }
    };
}
echo '<div class="text-center zero">';
echo '<p>'.
    $layout->header_text
    .'</p>';
echo '</div>';
echo '<br><br><br>';
echo '<pre>';
// dd($data_text);
if($data_text->invoice_number){
    echo printFormat('Voucher No','',$sale->sales_voucher_no);
};
if($data_text->date){
    echo printFormat('Date','',$sale->created_at->format('j-M-Y'));
}
if($data_text->customer_name){
echo printFormat('Customer','',$sale->customer->getFullNameAttribute());
}
if($data_text->address){
echo printFormat('address','',$sale->customer->getAddressAttribute());
}
if($data_text->phone){
echo printFormat('mobile','',$sale->customer->mobile);
}
echo '<br><br>';
if($columnCount == 3){
    echo printFormat($productNameLabel,$QuantityLabel,$priceLabel);
}elseif($columnCount == 4){
    echo eighty4Column($productNameLabel,$QuantityLabel,$discLabel,$priceLabel);
}elseif($columnCount=5){
    $label1=$dataTable[0]['label'];
    $label2=$dataTable[1]['label'];
    $label3=$dataTable[2]['label'];
    $label4=$dataTable[3]['label'];
    $label5=$dataTable[4]['label'];
    echo eighty5Column($$label1,$$label2,$$label3,$$label4,$$label5);
}
echo '---------------------------------------------------<br>';
echo '<br>';

foreach ($sale_details as $sd) {
    //datas
    $variation=$sd['product_variation']?'('.$sd['product_variation']['variation_template_value']['name'].')':'';
    $productName=$sd['product']['name'].$variation;
    $quantity=formatNumber($sd['quantity']).' '.$sd['uom']['short_name'];
    $subTotalWithDisount=price($sd['subtotal_with_discount']);
    $uomPrice=formatNumberv2($sd['uom_price']);
    $perItemDiscount=discountTxt($sd->discount_type,$sd->per_item_discount);
    $subtotalWithDiscount=formatNumberv2($sd['subtotal_with_discount']);


    if($columnCount == 3){
            $data1=$dataTable[0]['var'];
            $data2=$dataTable[1]['var'];
            $data3=$dataTable[2]['var'];
            echo printFormat($$data1,$$data2,$$data3);
    }elseif($columnCount == 4){
        $data1=$dataTable[0]['var'];
        $data2=$dataTable[1]['var'];
        $data3=$dataTable[2]['var'];
        $data4=$dataTable[3]['var'];
        echo eighty4Column(
            $$data1,
            $$data2,
            $$data3,
            $$data4);
        if($sd->discount_type=='percentage'){
            echo printTxtFormat(['','('.formatNumberv2(calPercentage($sd->discount_type,$sd->per_item_discount,$sd->subtotal)).')',''],[1,35,12]);
        }
    }elseif($columnCount=5){
        $data1=$dataTable[0]['var'];
        $data2=$dataTable[1]['var'];
        $data3=$dataTable[2]['var'];
        $data4=$dataTable[3]['var'];
        $data5=$dataTable[4]['var'];
        echo eighty5Column(
            $$data1,
            $$data2,
            $$data3,
            $$data4,
            $$data5);
        if($sd->discount_type=='percentage'){
            echo printTxtFormat(['','','('.formatNumberv2(calPercentage($sd->discount_type,$sd->per_item_discount,$sd->subtotal)).')',''],[1,1,37,8]);
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
echo '<div class="text-start">';
    echo '<p>'.$layout->note.'</p>';
    echo '</div>';
    echo '<div class="text-center">';
    echo '<p>'.$layout->footer_text.'</p>';
    echo '</div>';
echo "</pre>";

@endphp
</body>

</html>
