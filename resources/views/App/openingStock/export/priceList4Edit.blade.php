<style>
</style>
<table>
    <thead>
        <tr style="text-align: center;font-weight: bold;">
            <th style="font-weight: bold;">ID(Please Don't touch)</th>
            <th style="font-weight: bold;">Category</th>
            <th style="font-weight: bold;">Product </th>
            <th style="font-weight: bold;">Variation </th>
            <th style="font-weight: bold;">Product Variation SKU</th>
            <th style="font-weight: bold;">Min Quantity</th>
            <th style="font-weight: bold;">Fix or Percentage</th>
            <th style="font-weight: bold;">Value</th>
            <th style="font-weight: bold;">Start Date</th>
            <th style="font-weight: bold;">End Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($priceListDetail as $pl)
            @php
            // dd($priceListDetail->toArray());
            $variationSkuForSingle='';
                if($pl['applied_type']=='Category'){
                    $applyValues=App\Models\Product\Category::where('id',$pl['applied_value'])->first()->name;
                }else if($pl['applied_type']=='Product'){
                    $product=App\Models\Product\Product::where('id',$pl['applied_value'])->first();
                    $applyValues=$product->name;
                    $variationSkuForSingle=$product->productVariations[0]->variation_sku;
                }
                else if($pl['applied_type']=='Variation'){
                    $variationName=App\Models\Product\ProductVariation::whereNotNull('variation_template_value_id')
                                                                    ->select('id','product_id','variation_template_value_id','variation_sku')
                                                                    ->where('id',$pl['applied_value'])
                                                                    ->with('variationTemplateValue')
                                                                    ->first();
                    $product=App\Models\Product\Product::where('id',$variationName->product_id)->first();
                    $applyValues=$product->name;
                    $variationSkuForSingle=$product->productVariations[0]->variation_sku;
                }
            @endphp
            <tr>
                <td>{{$pl['id']}}</td>
                <td>{{$pl['applied_type']=='Category'?$applyValues:''}}</td>
                <td>{{$pl['applied_type']!='Category'?$applyValues:''}}</td>
                <td>{{$pl['applied_type']=='Variation'?$variationName->variationTemplateValue->name:''}}</td>
                <td>{{$pl['applied_type']=='Variation'?$variationName->variation_sku : $variationSkuForSingle}}</td>
                <td>1</td>
                <td>{{$pl['cal_type']}}</td>
                <td>{{$pl['cal_value']}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
