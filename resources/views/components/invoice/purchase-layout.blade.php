<section class="p-5" id="print-section">
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
    <div class="text-center mb-5">
        <h3 class="text-muted">{!! $layout->header_text !!}</h3>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="container">
        <div class="row">
            <div class="col-8">
                <ul class="list-unstyled">

                        <li class="text-muted">To : <span
                                style="color:#5d9fc5 ;">{{ $purchase->purchase_by->username }}</span>
                        </li>

                    @if ($data_text->supplier_name)
                        <li class="text-muted">From : <span style="color:#5dc561 ;">{{$purchase->supplier->name}}</span></li>
                    @endif
                    @if ($data_text->address)
                        <li class="text-muted">{{ $location->name }}</li>
                    @endif
                    @if ($data_text->phone)
                        <li class="text-muted"><i class="fas fa-phone"></i>{{ $purchase->supplier->mobile }}</li>
                    @endif
                </ul>
            </div>
            <div class="col-4">
                <ul class="list-unstyled">
                    <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                        <span class="fw-bold">Voucher No : </span> {{ $purchase->purchase_voucher_no }}
                    </li>
                    @if ($data_text->date)
                        <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                            <span class="fw-bold">Creation Date:
                            </span>{{ $purchase->created_at->format('j/F/Y') }}
                        </li>
                    @endif
                    @if ($data_text->purchase_status)
                        <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                            <span class="me-1 fw-bold">Status:</span><span class="badge bg-warning text-black fw-bold">
                                {{ $purchase->status }}</span>
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
                            <th scope="col">#</th>
                        @endif
                        @if ($table_text->description->is_show)
                            <th scope="col">{{$table_text->description->label ?? 'Decritpion'}}</th>
                        @endif
                        @if ($table_text->quantity->is_show)
                            <th scope="col">{{$table_text->quantity->label ?? 'Quantity'}}</th>
                        @endif
                        @if ($table_text->uom_price->is_show)
                            <th scope="col " class="text-end">{{$table_text->uom_price->label ?? 'Uom Price'}}</th>
                        @endif
                        @if ($table_text->discount->is_show)
                            <th scope="col " class="text-end">{{$table_text->discount->label ?? 'Discount'}}</th>
                        @endif
                        @if ($table_text->subtotal->is_show)
                            <th scope="col " class="text-end">{{$table_text->subtotal->label ?? 'Subtotal'}}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase_detail as $i=>$p)
                        <tr>
                            @if ($table_text->number->is_show)
                                <td scope="col">{{$i+1}}</td>
                            @endif
                            @if ($table_text->description->is_show)
                                <td scope="col">{{ $p->product->name }}</td>
                            @endif
                            @if ($table_text->quantity->is_show)
                                <td scope="col">{{ fquantity($p->quantity) }} {{$p->purchaseUom->short_name}}</td>
                            @endif
                            @if ($table_text->uom_price->is_show)
                                <td scope="col" class="text-end">{{ price($p->uom_price) }}</td>
                            @endif
                            @if ($table_text->discount->is_show)
                                <td scope="col" class="text-end">{{$p->discount_type=='percentage'?fprice($p->per_item_discount).'%':price($p->per_item_discount) }}</td>
                            @endif
                            @if ($table_text->subtotal->is_show)
                                <td scope="col" class="text-end">{{ price($p->subtotal_with_discount) }}</td>
                            @endif
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="row justify-content-end">
            <div class="col-6">
                <p class="ms-3">
                    {!! $layout->note !!}

                </p>

            </div>
            {{-- @php
            $currecyName=arr($purchase->currency,'name');
            @endphp --}}
            <div class="col-6">
                <table class="table table-borderless">
                    <tbody>
                        <tr class="fs-6 fw-bold">
                            <td colspan="4" class="text-end ">Subtotal</td>
                            <td class=" text-end ">{{ price($purchase->purchase_amount) }}</td>
                        </tr>
                        <tr class="fs-6 fw-bold">
                            <td colspan="4" class="text-end ">Discount Amount</td>
                            <td class=" text-end ">{{ price($purchase->extra_discount_amount) }}</td>
                        </tr>
                        <tr class="fs-6 fw-bold">
                            <td colspan="4" class="text-end ">Total Amount</td>
                            <td class=" text-end ">{{ price($purchase->total_purchase_amount) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-9">
                {!! $layout->footer_text !!}
            </div>
            <div class="col-3">
                {{-- <a href="" class="text-primary">{{ $sale->sold->email }}</a> --}}
            </div>
        </div>

    </div>

</section>
