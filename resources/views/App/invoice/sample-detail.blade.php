@extends('App.main.navBar')

@section('invoice', 'active')
@section('invoice_show', 'active show')

@section('styles')
    <style>
        /* Add other styles as needed */

        @print{
            #print-section{
                background-color:white;
            }
        }
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
                                <a class="btn btn-light text-capitalize generateImg"
                                     data-mdb-ripple-color="dark"><i
                                        class="fa-solid fa-image text-warning"></i>Generate
                                    Image</a>
                            </div>
                            <hr>
                        </div>
                        <div class="container" id="print-section">
                            <div class="row">
                                <div class="col-8">
                                    <ul class="list-unstyled">
                                        <li class="text-muted">To : <span style="color:#5d9fc5 ;">Mr Dean</span>
                                        </li>
                                        @if ($data_text->supplier_name)
                                            <li class="text-muted">From : <span style="color:#5dc561 ;">U Maung
                                                    Maung</span></li>
                                        @endif
                                        @if ($data_text->address)
                                            <li class="text-muted">Yangon,Myanmar</li>
                                        @endif
                                        @if ($data_text->phone)
                                            <li class="text-muted"><i class="fas fa-phone"></i> 09-797957976</li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="col-4">
                                    <ul class="list-unstyled">
                                        <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                                            <span class="fw-bold"> ID:</span>#123-456
                                        </li>
                                        @if ($data_text->date)
                                            <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                                                <span class="fw-bold">Creation Date:
                                                </span>{{ $layout['created_at']->format('j/F/Y') }}
                                            </li>
                                        @endif
                                        @if ($data_text->purchase_status)
                                            <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
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
                                                <th scope="col " class="text-end">{{$table_text->discount->label ?? 'Uom Price'}}</th>
                                            @endif
                                            @if ($table_text->subtotal->is_show)
                                                <th scope="col " class="text-end">{{$table_text->subtotal->label ?? 'Subtotal'}}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (range(1,4) as $r)
                                            <tr>
                                                @if ($table_text->number->is_show)
                                                    <td scope="col">#</td>
                                                @endif
                                                @if ($table_text->description->is_show)
                                                    <td scope="col">Product {{$r}}</td>
                                                @endif
                                                @if ($table_text->quantity->is_show)
                                                    <td scope="col">{{5 * $r}}</td>
                                                @endif
                                                @if ($table_text->uom_price->is_show)
                                                    <td scope="col" class="text-end">{{price(2000 * $r)}} </td>
                                                @endif
                                                @if ($table_text->discount->is_show)
                                                    <td scope="col" class="text-end">1,000 ks</td>
                                                @endif
                                                @if ($table_text->subtotal->is_show)
                                                    <td scope="col" class="text-end">{{price((2000 * $r)-1000)}}</td>
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
                                <div class="col-6">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr class="fs-6 fw-bold">
                                                <td colspan="4" class="text-end ">Subtotal</td>
                                                <td class=" text-end ">{{ price(16000) }}</td>
                                            </tr>
                                            <tr class="fs-6 fw-bold">
                                                <td colspan="4" class="text-end ">Discount Amount</td>
                                                <td class=" text-end ">{{ price(1000) }}</td>
                                            </tr>
                                            <tr class="fs-6 fw-bold">
                                                <td colspan="4" class="text-end ">Total Amount</td>
                                                <td class=" text-end ">{{ price(15000) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row d-none">
                                <div class="col-8">
                                    {!! $layout->note !!}
                                </div>
                                <div class="col-3">
                                    <ul class="list-unstyled">
                                        <li class="text-muted ms-3"><span class="text-black me-4">SubTotal</span>$1110
                                        </li>
                                        <li class="text-muted ms-3 mt-2"><span class="text-black me-4">Tax(15%)</span>$111
                                        </li>
                                    </ul>
                                    <p class="text-black float-start"><span class="text-black me-3"> Total
                                            Amount</span><span style="font-size: 25px;">$1221</span></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-10">
                                    {!! $layout->footer_text !!}
                                </div>
                                <div class="col-2">
                                    <a href="" class="text-primary">piconno@business.com</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="{{ asset('customJs/invoice/print.js') }}"></script>
    <script>
        $('.generateImg').click(()=>{
            convertToImage(document.getElementById('print-section'),'TesingImage');
        })
    </script>
@endpush
