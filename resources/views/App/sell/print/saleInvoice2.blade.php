
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sale invoice</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href={{asset("assets/plugins/global/plugins.bundle.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/css/style.bundle.css")}} rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href={{asset("customCss/scrollbar.css")}}>
</head>
<body>
    <div class="container-fluid">
        <div class="col-12 m-auto mt-5 border border-2  ">
            <div class="row  min-h-150px  ">
                <div class="col-12 px-5 fs-4 fw-semibold d-flex align-item-center justify-content-between g-3">
                        <span>Invoice No : {{sprintf('%04d', ($sale['id']+ 1))}}</span>
                        <span>Date : 7/5/2023</span>
                </div>
                <div class="col-5">
                    <div class="customer-info p-3 py-10 :">
                        <div class="info pt-3 fw-semibold">
                            <span class="fs-5">Name :  {{$sale['customer']['first_name']}}</span><br>
                            <span class="fs-5">Phone : {{$sale['customer']['mobile']}}</span>
                            <address class="fs-5">Address: {{$sale['customer']['shipping_address']}}</address>
                        </div>
                    </div>
                </div>
                <div class="col-7">
                    <div class="business-info p-3 py-10 text-end">
                        <h3 class="fs-5">{{ $location['name'] ? $location['name'].','  :''}}</h3>
                        <span class="fs-5 fw-semibold">{{$location['mobile']}}</span>
                        <address class="mt-3 fs-5">
                                <br>
                                {{ $location['address'] ? $location['address'].','  :''}}<br>
                                {{$location['city'] ? $location['city'].',' :'' }}{{ $location['state'] ? $location['state'].',' :'' }}
                                {{$location['country'] ? $location['country'].',' :'' }}{{$location['zip_code'] ? $location['zip_code'].',' :''}}
                        </address>
                    </div>
                </div>
            </div>
            <div class="row mt-0 ">
                <div class="col-12">
                    <table class="table table-row-dashed fs-6 gy-5 mt-0  table-bordered table-border-dark">
                        <thead class="bg-primary">
                            <tr class="bg-primary fw-bold fs-6 text-white text-center text-uppercase fs-7 p-10">
                                <th class="min-w-25px">No</th>
                                <th class="min-w-250px ps-2">Product    </th>
                                <th class="min-w-100px">Quantity</th>
                                <th class="min-w-100px">Unit</th>
                                <th class="min-w-100px">Price</th>
                            </tr>
                        </thead>
                        <tbody  class="fw-semibold text-gray-800 text-center">
                            <tr>
                                <td>1</td>
                                <td class="text-start">Keyboard</td>
                                <td>5</td>
                                <td>box</td>
                                <td>40000</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td class="text-start">Mouse</td>
                                <td>5</td>
                                <td>box</td>
                                <td>40000</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td class="text-start">battery</td>
                                <td>5</td>
                                <td>box</td>
                                <td>40000</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-secondary text-gray-900 text-center fw-bold">
                            <tr>
                                <td colspan="3" class="fs-4 text-capitalize">total amount</td>
                                <td>10000</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <div class="row  px-8 justify-content-end">
                <div class="col-md-5">
                    <div class="business-info p-3 px-10 text-end">
                        <h1 class="  fs-3 text-gray-700">Sale By :
                            <span class=" fw-bold fs-4 ">John Doe</span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
        <script src={{asset("assets/plugins/global/plugins.bundle.js")}}></script>
        <script src={{asset("assets/js/scripts.bundle.js")}}></script>
</html>
