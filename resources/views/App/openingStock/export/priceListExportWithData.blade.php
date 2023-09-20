<style>
</style>
<table>
    <thead>
        <tr style="text-align: center;font-weight: bold;">
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
    @foreach ($products as $p)
        @php


            $parentCategory = null;
            $subCategory = null;
            if($p->category_id){
                $parentCategory = App\Models\Product\Category::with('parentCategory', 'childCategory')->find($p->category_id)->name ?? null;
            }
            if($p->sub_category_id){
                $subCategory = App\Models\Product\Category::with('parentCategory', 'childCategory')->find($p->sub_category_id)->name ?? null;
            }
        @endphp
            @if ($p->has_variation=='variable')
                @foreach ($p->productVariations as $v)
                <tr>
                    <td>{{ $parentCategory }}</td>
                    <td>{{$p->name}}</td>
                    <td>{{$v->variationTemplateValue->name}}</td>
                    <td>{{$v->variation_sku}}</td>
                    <td>0</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>


                </tr>
                @endforeach

            @else
            <tr>
                <td>{{ $parentCategory }}</td>
                <td>{{$p->name}}</td>
                <td></td>
                <td>{{$p->productVariations[0]->sku}}</td>
                <td>0</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
            @endif

        @endforeach
    </tbody>
</table>
