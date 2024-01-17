<style>
</style>
<table>
    <thead>
        <tr style="text-align: center;font-weight: bold;">
            <th style="font-weight: bold;">Product Name</th>
            <th style="font-weight: bold;">Variation Name</th>
            <th  style="font-weight: bold;">SKU</th>
            <th  style="font-weight: bold;">Assign Locations</th>
            <th  style="font-weight: bold;">Purchase Price</th>
            <th  style="font-weight: bold;">Selling Price</th>
            <th  style="font-weight: bold;">Product Type</th>
            <th  style="font-weight: bold;">Category</th>
            <th  style="font-weight: bold;">Brand</th>
            <th  style="font-weight: bold;">Generic</th>
            <th  style="font-weight: bold;">Manufacture</th>
            <th  style="font-weight: bold;">Package</th>
            <th  style="font-weight: bold;">Custom Field 1</th>
            <th  style="font-weight: bold;">Custom Field 2</th>
            <th  style="font-weight: bold;">Custom Field 3</th>
            <th  style="font-weight: bold;">Custom Field 4</th>
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

            if ($p->packaging){
                $package_data = [];
                foreach ($p->packaging as $package){
                    $qty = intval($package->quantity);
                    $uom_name = getUomName($package->uom_id);
                    $data = "$package->packaging_name ($qty $uom_name)";

                    $package_data[] =$data;
                }
            }

              $location = \App\Models\locationProduct::where('product_id',$p->id)
                        ->with(['location:id,name'])
                        ->get()
                        ->pluck('location.name')
                        ->toArray();
            $assign_locations = implode(', ', $location);

        @endphp
            @if ($p->has_variation=='variable')
                @foreach ($p->productVariations as $v)
                <tr>
                    <td>{{$p->name}}</td>
                    <td>{{$v->variationTemplateValue->name}}</td>
                    <td>{{$v->variation_sku}}</td>
                    <td>{{$assign_locations}}</td>
                    <td>{{ $v->default_purchase_price }}</td>
                    <td>{{ $v->default_selling_price}}</td>
                    <td>{{ $p->product_type ?? null }}</td>
                    <td>{{ $parentCategory }}</td>
                    <td>{{ $p->brand->name ?? null }}</td>
                    <td>{{ $p->generic->name ?? null }}</td>
                    <td>{{ $p->manufacturer->name ?? null }}</td>
                    <td>{{implode(',', $package_data)}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>


                </tr>
                @endforeach

            @else
            <tr>
                <td>{{$p->name}}</td>
                <td></td>
                <td>{{$p->sku}}</td>
                <td>{{$assign_locations}}</td>
                <td>{{ $p->productVariations[0]->default_purchase_price ?? 0 }}</td>
                <td>{{  $p->productVariations[0]->default_selling_price ?? 0 }}</td>
                <td>{{ $p->product_type ?? null}}</td>
                <td>{{ $parentCategory }}</td>
                <td>{{ $p->brand->name ?? null }}</td>
                <td>{{ $p->generic->name ?? null }}</td>
                <td>{{ $p->manufacturer->name ?? null }}</td>
                <td>{{implode(',', $package_data)}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
            @endif

        @endforeach
    </tbody>
</table>
