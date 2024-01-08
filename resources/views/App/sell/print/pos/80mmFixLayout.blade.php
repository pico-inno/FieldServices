@php
    $columnCount=0;
    // foreach ($table_text as $key=>$tt) {
    //     if($tt->is_show){
    //         $columnCount++;
    //     }
    // }
@endphp
<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href={{asset("assets/plugins/global/plugins.bundle.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/css/style.bundle.css")}} rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

</head>
<style>
.invoice{
    height: auto;
    padding-left: 5px;
    padding-right: 5px;
    height: auto;
    border: none;
}
.logo-wrapper{
    margin-bottom: 10px;
    display: flex;
    justify-content: center;
}
.logo{
    width: 50px;
    height: 50px;
    text-align: center;
}
.table tr{
    font-size:{{$data_text->tableFontSize ??'16'}}px !important;
}

/* column width */
/* .description{
    width: 10% !important;

} */

/* .quantity{
    width: 20% !important;
}

.uom-price{
    width: 20% !important;
}

.discount{
    width: 20% !important;
}

.subtotal{
    max-width: 10% !important;
} */


</style>
<body>
    <div class="invoice">
        @php
            $logo=$data_text->logo ?? null;
            $url=asset('/storage/logo/invoice/'.$logo);
        @endphp
        {{--
        <pre> --}}

        @if ($logo)
            <div class="text-center mb-1 logo-wrapper">
                <img src="{{$url}}" class="logo" />
            </div>
        @endif
        <div class="text-center mb-8">
            <h3 class="text-muted">{!! $layout->header_text !!}</h3>
        </div>

        <div class="row">
            <div class="col-12 col-md-8">
                <ul class="list-unstyled mb-0">
                    @if ($data_text->customer_name)
                    <li class="text-muted">
                        <span style="">{{ $sale->customer->getFullNameAttribute() }}</span>
                    </li>
                    @endif
                    @if ($data_text->address)
                    <li class="text-muted">{{ $sale->customer->getAddressAttribute() }}</li>
                    @endif
                    @if ($data_text->phone)
                    <li class="text-muted">{{ $sale->customer->mobile}}</li>
                    @endif
                </ul>
            </div>
            <div class="col-12 col-md-4">
                <ul class="list-unstyled mb-0">
                    @if ($data_text->invoice_number)
                        <li class="text-muted">
                            <span class="fw-bold">Voucher No:</span> {{ $sale->sales_voucher_no }}
                        </li>
                    @endif
                    @if ($data_text->date)
                    <li class="text-muted">
                        <span class="fw-bold">Date:
                        </span>{{ $sale->created_at->format('j/F/Y') }}
                    </li>
                    @endif
                    @if ($data_text->purchase_status)
                    <li class="text-muted">
                        <span class="me-1 fw-bold">Status:</span><span class="badge bg-warning text-black fw-bold">
                            {{ $sale->status }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <table class="table">
            <thead class="text-end">
                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-400 text-end">
                    @if ($table_text->number->is_show)
                        <th scope="col" class="min-w-5mm text-start number">#</th>
                    @endif
                    @if ($table_text->description->is_show)
                        @php
                            $desWidth=$table_text->description->width;
                        @endphp
                        <th scope="col" class="min-w-column text-start description" @style(["width:$desWidth% !important"=>$desWidth])>{{$table_text->description->label ?? 'Decritpion'}}</th>
                    @endif
                    @if ($table_text->quantity->is_show)
                        @php
                            $qtyWidth=$table_text->quantity->width;
                        @endphp
                        <th scope="col" class="min-w-column quantity" @style(["width:$qtyWidth% !important"=>$qtyWidth])> {{$table_text->quantity->label ?? 'Quantity'}}</th>
                    @endif
                    @if ($table_text->uom_price->is_show)
                        @php
                            $uomWidth=$table_text->uom_price->width;
                        @endphp
                        <th scope="col " class="min-w-column text-end uom-price" @style(["width:$uomWidth% !important"=>$uomWidth])>{{$table_text->uom_price->label ?? 'Uom Price'}}</th>
                    @endif
                    @if ($table_text->discount->is_show)
                        @php
                            $disWidth=$table_text->discount->width;
                        @endphp
                        <th scope="col " class="min-w-column text-end discount" @style(["width:$disWidth% !important"=>$disWidth])>{{$table_text->discount->label ?? 'Discount'}}</th>
                    @endif
                    @if ($table_text->subtotal->is_show)
                        @php
                            $sutotalWidth=$table_text->subtotal->width;
                        @endphp
                        <th scope="col " class="min-w-column text-end subtotal" @style(["width:$sutotalWidth% !important"=>$sutotalWidth])>{{$table_text->subtotal->label ?? 'Subtotal'}}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($sale_details as $i=>$p)
                    <tr class="fw-semibold">
                        @if ($table_text->number->is_show)
                            <td scope="col" class=" text-start">{{$i+1}}</td>
                        @endif
                        @if ($table_text->description->is_show)
                            <td scope="col" class=" text-start">{{ $p->product->name }}</td>
                        @endif
                        @if ($table_text->quantity->is_show)
                            <td scope="col" class=" text-end">
                                {{-- 5000.00 pcs --}}
                                {{ fquantity($p->quantity) }} {{$p->uom->short_name}}
                            </td>
                        @endif
                        @if ($table_text->uom_price->is_show)
                            <td scope="col" class="text-end">
                                {{ fprice($p->uom_price) }}
                            </td>
                        @endif
                        @if ($table_text->discount->is_show)
                            <td scope="col" class="text-end">{{$p->discount_type=='percentage'?fprice($p->per_item_discount).'%':fprice($p->per_item_discount) }}</td>
                        @endif
                        @if ($table_text->subtotal->is_show)
                            <td scope="col" class="text-end">{{ fprice($p->subtotal_with_discount) }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table table-borderless">
            <tbody>
                @if ($data_text->net_sale_amount->is_show)
                <tr class="fs-6 fw-semibold mb-0">
                    <td colspan="4" class="text-end p-0">{{$data_text->net_sale_amount->label}}</td>
                    <td class=" text-end min-w-column p-0">{{ fprice($sale->sale_amount) }}</td>
                </tr>
                @endif

                @if ($data_text->extra_discount_amount->is_show)
                    <tr class="fs-6 fw-semibold">
                        <td colspan="4" class="text-end p-0">{{$data_text->extra_discount_amount->label}}</td>
                        <td class=" text-end min-w-column p-0">{{ fprice($sale->extra_discount_amount) }}</td>
                    </tr>
                @endif

                @if ($data_text->total_sale_amount->is_show)
                <tr class="fs-6 fw-semibold">
                    <td colspan="4" class="text-end p-0">{{$data_text->total_sale_amount->label}}</td>
                    <td class=" text-end min-w-column p-0">{{ fprice($sale->total_sale_amount) }}</td>
                </tr>
                @endif
            </tbody>
        </table>
        </div>
        {{-- <div class="separator border-gray-400 mb-1"></div> --}}
        <div class="row">
            <div class="col-12">
                {!! $layout->note !!}
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                {!! $layout->footer_text !!}
            </div>
        </div>
    </div>
</body>
</html>

@php
    die;
@endphp
