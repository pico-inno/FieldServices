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
        @media print {
          body {
            visibility: hidden;
          }
          .print-content {
            visibility: visible;
          }
        }
        
    </style>
</head>
<body>
    <div class="container print-content">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="fs-1 text-light">Business :</div>
                        <div class="fs-1 fw-bold">{{ $business_name }}</div>
                    </div>
                    <div class="col-6">
                        <div class="fs-1 fw-bold text-light">Location :</div>
                        <div class="fs-1 fw-bold">{{ $totalPriceAndOtherData['business_location'] }}</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="fs-1 fw-bold text-light">User :</div>
                        <div class="fs-1 fw-bold">{{ $user_name }}</div>
                    </div>
                    <div class="col-6">
                        <div class="fs-1 fw-bold"><span class="text-light">Customer :</span> {{ $totalPriceAndOtherData['customer_name'] }}</div>
                        <div class="fs-1 fw-bold"><span class="text-light">Mobile: </span> {{ $totalPriceAndOtherData['customer_mobile'] }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="fs-1 fw-bold text-light">Sale Invoice No :</div>
                        <div class="fs-1 fw-bold">{{ $invoice_no }}</div>
                    </div>
                    <div class="col-6"></div>
                </div>
            </div>
        </div>

        <div class="border-top"></div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-light">
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
                            <tr class="text-light">
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
        </div>

        <div class="border-top"></div>

        <div class="card"> 
            <div class="card-body">
                <div class="d-flex justify-content-end mb-3 text-gray-800">
                    <h1 class="me-5 text-light">Total</h1>
                    <span class="fs-3 me-5 text-light">{{ $totalPriceAndOtherData['total'] }}</span>
                </div>
                <div class="d-flex justify-content-end mb-3 text-gray-800">
                    <h1 class="me-5 text-light">Discount</h1>
                    <span class="fs-3 me-5 text-light">{{ $totalPriceAndOtherData['discount'] }}</span>
                </div>
                <div class="d-flex justify-content-end mb-3 text-gray-800">
                    <h1 class="me-5 text-light">Paid</h1>
                    <span class="fs-3 me-5 text-light">{{ $totalPriceAndOtherData['paid'] ?? '0' }}</span>
                </div>
                <div class="d-flex justify-content-end mb-3 text-gray-800">
                    <h1 class="me-5 text-light">Balance</h1>
                    <span class="fs-3 me-5 text-light">{{ $totalPriceAndOtherData['balance'] ?? '0' }}</span>
                </div>
                <div class="d-flex justify-content-end mb-3 text-gray-800">
                    <h1 class="me-5 text-light">Change</h1>
                    <span class="fs-3 me-5 text-light">{{ $totalPriceAndOtherData['change'] ?? '0' }}</span>
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

    <script src={{ asset("assets/plugins/global/plugins.bundle.js") }}></script>
    <script src={{ asset("assets/js/scripts.bundle.js") }}></script>
</body>
</html>