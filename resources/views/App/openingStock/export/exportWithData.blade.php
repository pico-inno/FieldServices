<style>
</style>
<table>
    <thead>
        <tr style="text-align: center;font-weight: bold;">
            <th style="font-weight: bold;">Product Name</th>
            <th style="font-weight: bold;">Variation Name</th>
            <th  style="font-weight: bold;">Product Variation SKU</th>
            <th  style="font-weight: bold;">Expired date</th>
            <th  style="font-weight: bold;">Unit (uom name)</th>
            <th  style="font-weight: bold;">Quantity</th>
            <th  style="font-weight: bold;">Price</th>
            <th  style="font-weight: bold;">remark</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $p)
        @php
            // if($p->productVariations[0]->variation_template_value_id){
            //     dd($p->productVariations[0]->variation_template_value_id);
            // };
        @endphp
            @if ($p->has_variation=='variable')
                @foreach ($p->productVariations as $v)
                <tr>
                    <td>{{$p->name}}</td>
                    <td>{{isset($v['variationTemplateValue']) ? $v['variationTemplateValue']['name']:''}}</td>
                    <td>{{$v->variation_sku}}</td>
                    <td></td>
                    <td>{{$p->purchaseUOM ? $p->purchaseUOM->name :''}}</td>
                    <td>0</td>
                    <td>{{isset($v['default_purchase_price']) ? $v['default_purchase_price']:0}}</td>
                    <td></td>
                </tr>
                @endforeach

            @else
            <tr>
                <td>{{$p->name}}</td>
                <td></td>
                <td>{{isset($p->productVariations[0]) ? $p->productVariations[0]->variation_sku:''}}</td>
                <td></td>
                <td>{{$p->purchaseUOM ? $p->purchaseUOM->name :''}}</td>
                <td>0</td>
                <td>{{isset($p->productVariations[0]) ? $p->productVariations[0]->default_purchase_price:0}}</td>
                <td></td>
            </tr>
            @endif

        @endforeach
    </tbody>
</table>
