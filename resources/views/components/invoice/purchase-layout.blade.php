<section class="p-5" id="print-section">
    <div class="text-center">
        {{-- <h3 class="text-muted">{!! $layout->header_text !!}</h3> --}}
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="container">
        <div class="row">
            <div class="col-8">
                <ul class="list-unstyled">

                        <li class="text-muted">To : <span
                                style="color:#5d9fc5 ;">{{ $purchase->purchase_by->username }}</span>
                        </li>

                    @if ($layout['data_text']['supplier_name'])
                        <li class="text-muted">From : <span style="color:#5dc561 ;">{{$purchase->supplier->name}}</span></li>
                    @endif
                    @if ($layout['data_text']['address'])
                        <li class="text-muted">{{ $location->name }}</li>
                    @endif
                    @if ($layout['data_text']['phone'])
                        <li class="text-muted"><i class="fas fa-phone"></i>{{ $purchase->supplier->mobile }}</li>
                    @endif
                </ul>
            </div>
            <div class="col-4">
                <ul class="list-unstyled">
                    <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                        <span class="fw-bold">Voucher No : </span> {{ $purchase->purchase_voucher_no }}
                    </li>
                    @if ($layout['data_text']['date'])
                        <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                            <span class="fw-bold">Creation Date:
                            </span>{{ $purchase->created_at->format('j/F/Y') }}
                        </li>
                    @endif
                    @if ($layout['data_text']['purchase_status'])
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
                        @if ($layout['table_text']['number'])
                            <th scope="col">#</th>
                        @endif
                        <th scope="col">Name</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_text as $p)
                        <tr>
                            @if ($layout['table_text']['number'])
                                <th scope="row">1</th>
                            @endif
                            <td>{{ $p->product->name }}</td>
                            <td>{{ $p->quantity }}</td>
                            <td>{{ $p->uom_price }}</td>
                            <td>{{ $p->subtotal }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-7">
                <p class="ms-3">Add additional notes and payment information</p>

            </div>
            <div class="col-5">
                <ul class="list-unstyled">
                    <li class="text-muted ms-3"><span class="text-black me-4">SubTotal
                            </span>{{ $purchase->purchase_amount }} {{ $purchase->currency->name }}
                    </li>
                    <li class="text-muted ms-3 mt-2"><span class="text-black me-4">Discount({{ $purchase->extra_discount_amount }}{{ $purchase->currency->name }})</span>{{ $purchase->extra_discount_amount  }} {{ $purchase->currency->name }}
                    </li>
                </ul>
                <p class="text-black float-start"><span class="text-black me-3"> Total
                        </span><span style="font-size: 25px;">{{ $purchase->total_purchase_amount }} {{ $purchase->currency->name }}</span>
                </p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-9">
                {!! $layout->footer_text !!}
            </div>
            <div class="col-3">
                <a href="" class="text-primary">{{ $purchase->purchase_by->email }}</a>
            </div>
        </div>

    </div>

</section>
