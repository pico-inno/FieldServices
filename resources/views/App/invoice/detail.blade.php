@extends('App.main.navBar')

@section('invoice', 'active')
@section('invoice_show', 'active show')

@section('styles')
    <style>
        /* Add other styles as needed */
    </style>

    <!-- Include Bootstrap Stylesheet -->
@endsection
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Invoice Template Detail</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">

        <li class="breadcrumb-item text-muted">Invoice Templates</li>
        <li class="breadcrumb-item text-dark">Detail</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="location">

            <div class="card">
                <div class="card-body">
                    <div class="container mb-5 mt-3">
                        <div class="row d-flex align-items-baseline">
                            <div class="col-8">
                                <p style="color: #7e8d9f;font-size: 20px;">Invoice >> <strong>ID: #123-123</strong></p>
                            </div>
                            <div class="col-4 float-end">
                                <a class="btn btn-light text-capitalize border-0" onclick="print()"
                                    data-mdb-ripple-color="dark"><i class="fas fa-print text-primary"></i> Print</a>
                                <a class="btn btn-light text-capitalize" onclick="convertToImage('print-section','TesingImage')"
                                    data-mdb-ripple-color="dark"><i class="fa-solid fa-image text-warning"></i>Generate
                                    Image</a>
                            </div>
                            <hr>
                        </div>
                        <section class="p-5" id="print-section">
                            <div class="text-center">
                                <h3 class="text-muted">{!! $layout->header_text !!}</h3>
                            </div>
                            @if ($table_text)
                                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css"
                                    rel="stylesheet">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-8">
                                            <ul class="list-unstyled">
                                                <li class="text-muted">To : <span style="color:#5d9fc5 ;">{{ $table_text->customer->name }}</span>
                                                </li>
                                                @if ($layout['data_text']['supplier_name'])
                                                    <li class="text-muted">From : <span style="color:#5dc561 ;">U Maung
                                                            Maung</span></li>
                                                @endif
                                                @if ($layout['data_text']['address'])
                                                    <li class="text-muted">Yangon,Myanmar</li>
                                                @endif
                                                @if ($layout['data_text']['phone'])
                                                    <li class="text-muted"><i class="fas fa-phone"></i> 09-797957976</li>
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="col-4">
                                            <ul class="list-unstyled">
                                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                                                    <span class="fw-bold"> ID:</span>#123-456
                                                </li>
                                                @if ($layout['data_text']['date'])
                                                    <li class="text-muted"><i class="fas fa-circle"
                                                            style="color:#84B0CA ;"></i>
                                                        <span class="fw-bold">Creation Date:
                                                        </span>{{ $layout['created_at']->format('j/F/Y') }}
                                                    </li>
                                                @endif
                                                @if ($layout['data_text']['purchase_status'])
                                                    <li class="text-muted"><i class="fas fa-circle"
                                                            style="color:#84B0CA ;"></i>
                                                        <span class="me-1 fw-bold">Status:</span><span
                                                            class="badge bg-warning text-black fw-bold">
                                                            paid</span>
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
                                                @if ($table_text)
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
                                                @else
                                                    <tr>
                                                        @if ($layout['table_text']['number'])
                                                            <th scope="row">1</th>
                                                        @endif
                                                        <td>Product 1</td>
                                                        <td>5</td>
                                                        <td>3000</td>
                                                        <td>15000</td>
                                                    </tr>
                                                    <tr>
                                                        @if ($layout['table_text']['number'])
                                                            <th scope="row">1</th>
                                                        @endif
                                                        <td>Product 2</td>
                                                        <td>3</td>
                                                        <td>50000</td>
                                                        <td>150000</td>
                                                    </tr>
                                                    <tr>
                                                        @if ($layout['table_text']['number'])
                                                            <th scope="row">1</th>
                                                        @endif
                                                        <td>Product 3</td>
                                                        <td>6</td>
                                                        <td>4500</td>
                                                        <td>23000</td>
                                                    </tr>
                                                @endif

                                            </tbody>

                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <p class="ms-3">Add additional notes and payment information</p>

                                        </div>
                                        <div class="col-3">
                                            <ul class="list-unstyled">
                                                <li class="text-muted ms-3"><span
                                                        class="text-black me-4">SubTotal</span>$1110
                                                </li>
                                                <li class="text-muted ms-3 mt-2"><span
                                                        class="text-black me-4">Tax(15%)</span>$111
                                                </li>
                                            </ul>
                                            <p class="text-black float-start"><span class="text-black me-3"> Total
                                                    Amount</span><span style="font-size: 25px;">$1221</span></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-10">
                                            <p>{!! $layout->footer_text !!}</p>
                                        </div>
                                        <div class="col-2">
                                            <a href="" class="text-primary">piconno@business.com</a>
                                        </div>
                                    </div>

                                </div>
                            @else
                                <div class="container">
                                    <div class="row">
                                        <div class="col-8">
                                            <ul class="list-unstyled">
                                                <li class="text-muted">To : <span style="color:#5d9fc5 ;">David</span>
                                                </li>
                                                @if ($layout['data_text']['supplier_name'])
                                                    <li class="text-muted">From : <span style="color:#5dc561 ;">{{ Auth::user()->name }}</span></li>
                                                @endif
                                                @if ($layout['data_text']['address'])
                                                    <li class="text-muted">Yangon,Myanmar</li>
                                                @endif
                                                @if ($layout['data_text']['phone'])
                                                    <li class="text-muted"><i class="fas fa-phone"></i> 09-797957976</li>
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="col-4">
                                            <ul class="list-unstyled">
                                                <li class="text-muted"><i class="fas fa-circle"
                                                        style="color:#84B0CA ;"></i>
                                                    <span class="fw-bold"> ID:</span>#123-456
                                                </li>
                                                @if ($layout['data_text']['date'])
                                                    <li class="text-muted"><i class="fas fa-circle"
                                                            style="color:#84B0CA ;"></i>
                                                        <span class="fw-bold">Creation Date:
                                                        </span>{{ $layout['created_at']->format('j/F/Y') }}
                                                    </li>
                                                @endif
                                                @if ($layout['data_text']['purchase_status'])
                                                    <li class="text-muted"><i class="fas fa-circle"
                                                            style="color:#84B0CA ;"></i>
                                                        <span class="me-1 fw-bold">Status:</span><span
                                                            class="badge bg-warning text-black fw-bold">
                                                            paid</span>
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
                                                @if ($table_text)
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
                                                @else
                                                    <tr>
                                                        @if ($layout['table_text']['number'])
                                                            <th scope="row">1</th>
                                                        @endif
                                                        <td>Product 1</td>
                                                        <td>5</td>
                                                        <td>3000</td>
                                                        <td>15000</td>
                                                    </tr>
                                                    <tr>
                                                        @if ($layout['table_text']['number'])
                                                            <th scope="row">1</th>
                                                        @endif
                                                        <td>Product 2</td>
                                                        <td>3</td>
                                                        <td>50000</td>
                                                        <td>150000</td>
                                                    </tr>
                                                    <tr>
                                                        @if ($layout['table_text']['number'])
                                                            <th scope="row">1</th>
                                                        @endif
                                                        <td>Product 3</td>
                                                        <td>6</td>
                                                        <td>4500</td>
                                                        <td>23000</td>
                                                    </tr>
                                                @endif

                                            </tbody>

                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <p class="ms-3">Add additional notes and payment information</p>

                                        </div>
                                        <div class="col-3">
                                            <ul class="list-unstyled">
                                                <li class="text-muted ms-3"><span
                                                        class="text-black me-4">SubTotal</span>$1110
                                                </li>
                                                <li class="text-muted ms-3 mt-2"><span
                                                        class="text-black me-4">Tax(15%)</span>$111
                                                </li>
                                            </ul>
                                            <p class="text-black float-start"><span class="text-black me-3"> Total
                                                    Amount</span><span style="font-size: 25px;">$1221</span></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-10">
                                            <p>{!! $layout->footer_text !!}</p>
                                        </div>
                                        <div class="col-2">
                                            <a href="" class="text-primary">piconno@business.com</a>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </section>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
@push('scripts')
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="{{ asset('customJs/invoice/print.js') }}"></script>
@endpush
