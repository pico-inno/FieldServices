{{-- <!DOCTYPE html>
<html>
<style>
    * {
        margin: 0in;
        padding: 0in;
    }
    body {
        font-family: monospace;
        margin: 0in;
        padding: 0in;
        /* margin top of the paper */
        margin-top: 2mm;
        /* margin left of the paper */
        margin-left: 2mm;
        box-sizing: border-box;
    }
    .barcode {
        width: 32mm;
        height: 18.5mm;
        text-align: center;
        border: 1px solid #00000068;
        /* padding of the two row */
        margin-bottom: 0;
        padding: 1mm;
        margin-bottom: 1mm;
        margin-right: 1.5mm;
        display: flex;
        flex-direction: column;
        justify-content: start;
        box-sizing: content-box;
        /* align-items: center; */
    }
    .row {
        display: flex;
        width: 101mm;
        margin-bottom: 0;
        justify-content: center;
        align-items: center;
    }
    .priceTag {
        display: flex;
        justify-content: space-between;
        margin-top: 5px;
    }
</style>

<body class="">
        <div class="row">
            @foreach ($data['product_name'] as $i=>$pn)
                    @for ($x = 0; $x < $data['count'][$i]; $x++)
                        <div class="barcode">
                            <img class="img" src="data:image/png;base64, {{DNS1D::getBarcodePNG($data['product_sku'][$i], 'C128',2,44)}}"
                                alt="barcode" width="100%" />
                            <span style="font-size: 10px;display: block;text-align: center;font-weight: bold">{{$pn}}</span>
                            <div class="priceTag" style="font-size: 10px;font-weight: bold;">
                                <div>Price</div>
                                <div>10000ks</div>
                            </div>
                        </div>
                    @endfor
            @endforeach
        </div>

</body>
<script>
    print();
</script>

</html> --}}
<!DOCTYPE html>
<html>
<style>
    * {
        margin: 0in;
        padding: 0in;
        box-sizing: border-box;
    }

    body {
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        margin: 0in;
        padding: 0in;
        height: {{$templateData->paper_type == 'fixed' ? $templateData->paperHeight.'mm' : 'auto'}};
    }

    .barcode {
        width:{{$templateData->stickerWidth}}mm;
        height:{{$templateData->stickerHeight}}mm;
        max-width:{{$templateData->stickerWidth}}mm;
        max-height:{{$templateData->stickerHeight}}mm;
        margin-right: {{$templateData->columnGap}}mm;
        margin-left: {{$templateData->columnGap}}mm;
        overflow:hidden;
        /* padding:20px; */
        text-align:center;
        padding:5px 5px 10px 5px;
        /* border: 1px solid #00000068; */
        display:flex;
        flex-direction:column;
        /* align-items: spa; */
        /* justify-content:space-between; */
    }

    .row {
        height:auto;
        width: {{$templateData->paperWidth}}mm;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: {{$templateData->rowGap}}mm;
        /* background-color:green; */
        justify-content: end;
        margin-left: {{$templateData->paperMarginLeft}}mm;
        /* padding:1mm; */
    }

    .priceTag {
        display: {{$data['price'] == 'on' ?'flex' :'none'}};
        justify-content: space-between;
        align-items: center;
        margin-top: 3px;
        /* margin-bottom: 3px;
        font-size: 2.5mm; */
    }
    .priceTag-text{
        font-size: {{$data['product_fs']}}px;

    }
    .ft{
        width: 100%;
        font-size: 10px;
        /* font-weight: bold; */
    }
    .business-name{
        font-size: {{$data['product_fs']}}px;
        padding-bottom: 1px;
        display: {{$data['business_name'] == 'on' ?'' :'none'}};
    }
    .product-name{
        font-size: {{$data['product_fs']}}px;
        text-align: center;
        padding:1px 0;
    }
    .date{
        font-size: {{$data['date_fs']}}px;
        display: {{$data['date'] == 'on' ?'' :'none'}};
        text-align: center;
        padding:1px 0;
    }
</style>
@php
    if ($templateData->paper_type == 'fixed') {
        $rowCount=1;
        if($templateData->rowCount){
            $rowCount=$templateData->rowCount != '' ? $templateData->rowCount: 1;
        }
    }else{
        $rowCount=ceil(array_sum($data['count']) / $templateData->columnCount);
    }
    $dataCount=count($data['index']);
    $index=0;
    $items=[];
    foreach ($data['index'] as $i=>$d) {
       $count=$data['count'][$i];
       $counter = 1;
        for ($counter=0; $counter < $count; $counter++) {
            // dd($data->toArray());
            $productName='';
            if($data['product'] == 'on' && $data['variation'] != 'on')
            {
                $productName=$data['product_name'][$i];
            }elseif($data['product'] == 'on' || $data['variation'] == 'on'){
                $variationName=$data['variation_name'][$i] ? '('.$data['variation_name'][$i].')' : '';
                $productName=$data['product_name'][$i].$variationName;
            }
           $items=[
                ...$items,
                [
                    'product_name'=>$productName,
                    'date'=>$data['date'][$i],
                    'product_sku'=>$data['product_sku'][$i],
                ]
            ];
        };
    }
@endphp

<body class="">
        <div class="main">
            @for ($x = 0; $x < $rowCount; $x++)
                <div class="row d-flex">
                    @for ($j = 0; $j < $templateData->columnCount; $j++)
                        @if (isset($items[$index]))
                            <div class="barcode">
                                <div class="ft business-name ">Kodo POS</div>
                                <div class="ft product-name  {{$data['product'] == 'on' || $data['variation'] == 'on' ?'':'d-none'}}">{{$items[$index]['product_name']}}</div>
                                <div class="priceTag ">
                                    <div class="priceTag-text">Price</div>
                                    <div class="priceTag-text">10000ks</div>
                                </div>
                                <div class="ft date ">{{$items[$index]['date']}}</div>
                                <img class="img" src="data:image/png;base64, {{DNS1D::getBarcodePNG($items[$index]['product_sku'], 'C128',2,40,array(0,0,4),true)}}" alt="barcode" width="100%" height="40px" />
                            </div>
                            @php
                                $index++;
                            @endphp
                        @else
                            <div class="barcode border-0">
                            </div>
                        @endif
                    @endfor
                </div>
            @endfor
        </div>

</body>
<script>
    // print();
</script>

</html>
