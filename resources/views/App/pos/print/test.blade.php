<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href={{ asset("assets/plugins/global/plugins.bundle.css") }} rel="stylesheet" type="text/css" />
    <link href={{ asset("assets/css/style.bundle.css") }} rel="stylesheet" type="text/css" />

    <title>POS Sell Payment</title>
    <style>
        /* Custom styles for printing */
        /* @media print {
          body {
            visibility: hidden;
          }
          .print-content {
            visibility: visible;
          }
        } */
        .sperator {
            content: " ";
            height: 5px;
            border-bottom: 2px solid rgb(0, 0, 0);
        }

        .sperator-bottom {
            content: " ";
            height: 2px;
            border-bottom: 1px solid rgb(0, 0, 0);
        }

        @media print {
            .invoice {
                max-width: 80mm;
                height: auto;
                border: none;
                margin: 0 auto;
                margin-bottom: 2mm !important;

            }

            html {
                max-width: 80mm;
                margin-bottom: 2mm !important;
            }

            @page {
                width: 80mm;
                margin: 0.7mm;
                margin-bottom: 2mm !important;
            }

        }
    </style>
</head>

<body>
    <div class="container print-content d-none">
        <div class="">
            <div class="">
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="fs-3 text-gray-500">Business :</div>
                        <div class="fs-3 fw-bold">{{ $business_name ?? '' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="fs-3 fw-bold text-gray-500">Location :</div>
                        <div class="fs-3 fw-bold">{{ $totalPriceAndOtherData['business_location'] }}</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="fs-3 fw-bold text-gray-500">User :</div>
                        <div class="fs-3 fw-bold">{{ $user_name }}</div>
                    </div>
                    <div class="col-6">
                        <div class="fs-3 fw-bold"><span class="text-gray-500">Customer :</span> {{
                            $totalPriceAndOtherData['customer_name'] }}</div>
                        <div class="fs-3 fw-bold"><span class="text-gray-500">Mobile: </span> {{
                            $totalPriceAndOtherData['customer_mobile'] }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="fs-1 fw-bold text-gray-500">Sale Invoice No :</div>
                        <div class="fs-1 fw-bold">{{ $invoice_no }}</div>
                    </div>
                    <div class="col-6"></div>
                </div>
            </div>
        </div>

        <div class="border-top"></div>

        <div class="">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-gray-500">
                        <th>No. </th>
                        <th>Product Variation</th>
                        <th>Ref UoM</th>
                        <th>Qty</th>
                        <th>UoM</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice_row as $index => $item)
                    <tr class="text-gray-500">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['product_name'] }} - {{ $item['variation'] ?? 'single product'}}</td>
                        <td>{{ $item['referenceUom'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ $item['uomName'] }}</td>
                        <td>{{ $item['price'] }}</td>
                        <td>{{ $item['subtotal'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-top"></div>

        <div class="">
            <div class="">
                <div class="d-flex justify-content-end mb-3 text-gray-800">
                    <h1 class="me-5 text-gray-500">Total</h1>
                    <span class="fs-3 me-5 text-gray-500">{{ $totalPriceAndOtherData['total'] }}</span>
                </div>
                <div class="d-flex justify-content-end mb-3 text-gray-800">
                    <h1 class="me-5 text-gray-500">Discount</h1>
                    <span class="fs-3 me-5 text-gray-500">{{ $totalPriceAndOtherData['discount'] }}</span>
                </div>
                <div class="d-flex justify-content-end mb-3 text-gray-800">
                    <h1 class="me-5 text-gray-500">Paid</h1>
                    <span class="fs-3 me-5 text-gray-500">{{ $totalPriceAndOtherData['paid'] ?? '0' }}</span>
                </div>
                <div class="d-flex justify-content-end mb-3 text-gray-800">
                    <h1 class="me-5 text-gray-500">Balance</h1>
                    <span class="fs-3 me-5 text-gray-500">{{ $totalPriceAndOtherData['balance'] ?? '0' }}</span>
                </div>
                <div class="d-flex justify-content-end mb-3 text-gray-800">
                    <h1 class="me-5 text-gray-500">Change</h1>
                    <span class="fs-3 me-5 text-gray-500">{{ $totalPriceAndOtherData['change'] ?? '0' }}</span>
                </div>
            </div>
        </div>
        {{-- <div class="border-top mt-3"></div> --}}
        {{-- <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="fs-1">Amount</div>
                    </div>
                    <div class="col-6">
                        <div class="fs-1">Payment Method</div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-6">
                        <div class="fs-1">225.6</div>
                    </div>
                    <div class="col-6">
                        <div class="fs-1">Card</div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-6">
                        <div class="fs-1">225.6</div>
                    </div>
                    <div class="col-6">
                        <div class="fs-1">Bank</div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="invoice">
        <div class="">
            <div class="d-flex justify-content-between mb-15 flex-wrap">
                <span class="fs-6  fw-bold">invoice no: {{ $invoice_no }}</span><br>
                <span class="fs-6 fw-bold">Location:{{ $totalPriceAndOtherData['business_location'] }}</span>
            </div>
            <div class="mt-4">
                <div class="from mb-10">
                    <div class="info fw-light fs-3">
                        <div class="from-info fw-bold">
                            <h6 class="mb-3 fw-bold">User: {{ $user_name }}</h6>
                            <h6 class="fw-bold">Customer: {{ $totalPriceAndOtherData['customer_name'] }}
                            </h6>
                            <h6 class="fw-bold">Phone: {{ $totalPriceAndOtherData['customer_mobile'] }}</h6>
                        </div>
                        <div class="mt-10">
                            <table class="table fw-bold">
                                <thead>
                                    <tr class=" fs-6 fw-bold text-dark">
                                        {{-- <th>No. </th> --}}
                                        <th class="fw-bold">Product</th>
                                        {{-- <th>Ref UoM</th> --}}
                                        <th class="text-end fw-bold">Qty</th>
                                        {{-- <th>UoM</th> --}}
                                        <th class="text-end fw-bold">Price</th>
                                        {{-- <th>Subtotal</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice_row as $index => $item)
                                    <tr class="text-dark fs-7 fw-bold">
                                        {{-- <td>{{ $index + 1 }}</td> --}}
                                        <td class="fw-bold">{{ $item['product_name'] }} <br> {{ $item['variation'] ??
                                            ''}}</td>
                                        {{-- <td>{{ $item['referenceUom'] }}</td> --}}
                                        <td class="text-end fw-bold">{{ $item['quantity'] }} {{ $item['uomName'] }}</td>
                                        <td class="text-end fw-bold"> x{{ $item['price'] }}</td>
                                        {{-- <td>{{ $item['subtotal'] }}</td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="sperator mb-2"></div>
                        <div class="">
                            <table class="table fw-bold ">
                                <tbody>
                                    <tr class="text-dark fs-7 fw-bold">
                                        <td class="text-end fw-bold col-9">Total :</td>
                                        <td class="text-end fw-bold col-3"> {{ $totalPriceAndOtherData['total'] }}</td>
                                    </tr>
                                    <tr class="text-dark fs-7 fw-bold">
                                        <td class="text-end fw-bold">Discount :</td>
                                        <td class="text-end fw-bold"> {{ $totalPriceAndOtherData['discount'] }}</td>
                                    </tr>
                                    <tr class="text-dark fs-7 fw-bold">
                                        <td class="text-end fw-bold">Paid :</td>
                                        <td class="text-end fw-bold"> {{ $totalPriceAndOtherData['paid'] ?? '0' }}</td>
                                    </tr>
                                    <tr class="text-dark fs-7 fw-bold">
                                        <td class="text-end fw-bold">Balance :</td>
                                        <td class="text-end fw-bold"> {{ $totalPriceAndOtherData['balance'] ?? '0' }}
                                        </td>
                                    </tr>
                                    <tr class="text-dark fs-7 fw-bold mb-5">
                                        <td class="text-end fw-bold">Change :</td>
                                        <td class="text-end fw-bold"> {{ $totalPriceAndOtherData['change'] ?? '0' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="sperator-bottom mb-2"></div>
                    </div>
                </div>



            </div>


        </div>
    </div>
    <script src={{ asset("assets/plugins/global/plugins.bundle.js") }}></script>
    <script src={{ asset("assets/js/scripts.bundle.js") }}></script>
</body>

</html>
