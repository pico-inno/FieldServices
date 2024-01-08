<section class="" id="print-section">
    <style>

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
        .table tr{
            font-size:{{$data_text->tableFontSize ??'16'}}px !important;
        }
        .headertext{
            font-size:{{(int) $data_text->tableFontSize+ 2 ??'18'}}px !important;
        }
    </style>
    @php
        $logo=$data_text->logo ?? null;
        $url=asset('/storage/logo/invoice/'.$logo);
    @endphp
    @if ($logo)
        <div class="text-center  logo-wrapper">
            <img src="{{$url}}" class="logo" />
        </div>
    @endif
    <div class="text-center mb-3">
        <h3 class="text-muted headertext">{!! $layout->header_text !!}</h3>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="">
        <div class="row">
            <div class="col-8">
                <ul class="list-unstyled">
                    @if ($data_text->customer_name)
                        <li class="text-muted">
                            <span style="">{{ $sale->customer->getFullNameAttribute() }}</span>
                        </li>
                    @endif
                    @if ($data_text->address)
                        <li class="text-muted">{{ $sale->customer->getAddressAttribute() }}</li>
                    @endif
                    @if ($data_text->phone)
                        <li class="text-muted"><i class="fas fa-phone"></i>{{ $sale->customer->mobile}}</li>
                    @endif
                </ul>
            </div>
            <div class="col-4">
                <ul class="list-unstyled">
                    <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                        <span class="fw-bold">Voucher No:</span> {{ $sale->sales_voucher_no }}
                    </li>
                    @if ($data_text->date)
                        <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                            <span class="fw-bold">Date:
                            </span>{{ $sale->created_at->format('j/F/Y') }}
                        </li>
                    @endif
                    @if ($data_text->purchase_status)
                        <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                            <span class="me-1 fw-bold">Status:</span><span class="badge bg-warning text-black fw-bold">
                                {{ $sale->status }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="row my-2 mx-1 justify-content-center">
            <table class="table table-striped table-borderless">
                <thead style="background-color:#84B0CA ;" class="text-white">
                    <tr class="">
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
                        <tr>
                            @if ($table_text->number->is_show)
                                <td scope="col">{{$i+1}}</td>
                            @endif
                            @if ($table_text->description->is_show)
                                <td scope="col">{{ $p->product->name }}</td>
                            @endif
                            @if ($table_text->quantity->is_show)
                                <td scope="col">{{ fquantity($p->quantity) }} {{$p->uom->short_name}}</td>
                            @endif
                            @if ($table_text->uom_price->is_show)
                                <td scope="col" class="text-end">{{ price($p->uom_price) }}</td>
                            @endif
                            @if ($table_text->discount->is_show)
                                <td scope="col" class="text-end">
                                    {{$p->discount_type=='percentage'?price($p->per_item_discount).'%':price($p->per_item_discount) }}
                                    @if ($p->discount_type == 'percentage')
                                    <br>
                                    ({{price(calPercentage($p->discount_type,$p->per_item_discount,$p->uom_price))}})
                                    @endif
                                </td>

                            @endif
                            @if ($table_text->subtotal->is_show)
                                <td scope="col" class="text-end">{{ price($p->subtotal_with_discount) }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row justify-content-end my-2 mx-1">
            <div class="col-6">
                <p class="ms-3">
                    {!! $layout->note !!}

                </p>

            </div>
            @php
                $currecyName=arr($sale->currency,'name');
            @endphp
            <div class="col-6">
                <table class="table table-borderless">
                    <tbody>
                        @if ($data_text->net_sale_amount->is_show)
                        <tr class="fs-6 fw-semibold mb-0">
                            <td colspan="4" class="text-end p-0">{{$data_text->net_sale_amount->label}}</td>
                            <td class=" text-end min-w-column p-0">{{ price($sale->sale_amount - $sale->total_item_discount) }}</td>
                        </tr>
                        @endif

                        @if ($data_text->extra_discount_amount->is_show)
                        <tr class="fs-6 fw-semibold">
                            <td colspan="4" class="text-end p-0">{{$data_text->extra_discount_amount->label}}</td>
                            <td class=" text-end min-w-column p-0">{{ price($sale->extra_discount_amount) }}</td>
                        </tr>
                        @endif

                        @if ($data_text->total_sale_amount->is_show)
                        <tr class="fs-6 fw-semibold">
                            <td colspan="4" class="text-end p-0">{{$data_text->total_sale_amount->label}}</td>
                            <td class=" text-end min-w-column p-0">{{ price($sale->total_sale_amount) }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <div class="row my-2 mx-1">
            <div class="col-9">
                {!! $layout->footer_text !!}
            </div>
            <div class="col-3">
                {{-- <a href="" class="text-primary">{{ $sale->sold->email }}</a> --}}
            </div>
        </div>

    </div>
</section>

