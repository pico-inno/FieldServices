<div>
    @foreach ($variations as $variation)
        <tr>
            <td></td>

            <td>

            </td>
            <td>

            </td>
            <td>{{$product->name}} <br>
                (<span>{{ $variation->variation_name }}</span>)
            </td>
            <td>{{ $variation->variation_sku }}</td>
            <td>
                @php
                    $value_names = '';
                    foreach ($product->locations_product as $location) {
                    $value_names .= $location->location->name . ',';
                    }
                    $value_names = rtrim($value_names, ',');

                @endphp
                @if ($product->locations_product !== null)
                    {{$value_names}}
                @else
                    -
                @endif
            </td>
            <td>

                {{$variation->default_purchase_price}}

            </td>
            <td>

                {{$variation->default_selling_price}}

            </td>

            <td>
                {{$product->product_type ?? ''}}
            </td>
            <td>
                {{$product->categoryName}}
                {{$product->subCategoryName !='' ? ', '.$product->subCategoryName :''}}
            </td>
            <td>
                {{$product->brandName}}
            </td>
            <td>
                {{$product->genericName}}
            </td>
            <td>
                {{$product->manufacturerName ?? ''}}
            </td>
            <td>
                {{$product->product_custom_field1}}
            </td>

            <td>
                {{$product->product_custom_field2}}
            </td>

            <td>
                {{$product->product_custom_field3}}
            </td>

            <td>
                {{$product->product_custom_field4}}
            </td>
        </tr>
    @endforeach
</div>
