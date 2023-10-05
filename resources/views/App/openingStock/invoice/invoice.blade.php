
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>print invoice</title>
    <link href={{asset("assets/plugins/custom/vis-timeline/vis-timeline.bundle.css")}} rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href={{asset("assets/plugins/global/plugins.bundle.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/css/style.bundle.css")}} rel="stylesheet" type="text/css" />
</head>
<style>

</style>
<body>
  <div id="print-invoice" class="m-10">
        <div class="-header">
            <h3 class="-title">Purchase Details (Reference No:)</h3>

            <!--begin::Close-->
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="" aria-label="Close">
                <i class="fas fa-times fs-2"></i>
            </div>
            <!--end::Close-->
        </div>

        <div class="body">
            <div class="row mb-5 d-flex">
                <div class="col-4">
                    <h3 class="text-primary-emphasis fs-4">
                        Supplier:
                    </h3>
                    <address class="mt-3 fs-5">
                        APK <br>
                        Mobile:09092121
                    </address>
                </div>
                <div class="col-4">
                    <h3 class="text-primary-emphasis fs-4">
                        Bussiness:
                    </h3>
                    <address class="mt-3 fs-5">
                       {{$location['name']}}<br>
                        {{arr($location['address'])}}<br>
                        {{$location['city']}},{{$location['state']}},{{$location['country']}},{{$location['zip_code']}}
                    </address>
                </div>
                <div class="col-4">
                    <div class="text-group">
                        {{-- <h3 class="text-primary-emphasis fw-semibold fs-5">
                            Supplier : <span class="text-gray-600">#2023/0003</span>
                        </h3> --}}
                        <h3 class="text-primary-emphasis fw-semibold fs-5">
                            Date : <span class="text-gray-600">{{$openingStocks['opening_date']}}</span>
                        </h3>
                        <h3 class="text-primary-emphasis fw-semibold fs-5">
                            Purchase Status : <span class="text-gray-600">{{$openingStocks['status']}}</span>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="">
                <table class="table align-middle table-row-dashed fs-6 p-10" id="kt_customers_table">
                    <!--begin::Table head-->
                    <thead class="bg-success">
                        <!--begin::Table row-->
                        <tr class="bg-success fw-bold fs-8 text-white text-start text-uppercase">
                            <th class="">#</th>
                            <th class="min-w-120px">Product Name</th>
                            <th class="min-w-120px">UOM</th>
                            <th class="min-w-120px">Quantity</th>
                            <th class="min-w-100px">Unit</th>
                            <th class="min-w-100px">Purchase Price </th>
                            <th class="min-w-100px pe-4"> EXP date</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-semibold text-gray-800 fs-7">
                        @foreach ($openingStockDetail as $key=>$opsd)
                        @php
                            $p=$opsd->product;
                            $variation_template_value=$opsd->toArray()['product_variation']['variation_template_value'];
                        @endphp
                            <tr>
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$p->name}}<br>
                                   {{$variation_template_value ? ($variation_template_value['name']):''}}
                                </td>
                                <td>
                                    {{$opsd->uomset_name}}
                                </td>
                                <td>
                                    {{round($opsd->quantity,2)}}
                                </td>
                                <td>
                                    {{$opsd->unit['name']}}
                                </td>
                                <td>
                                    {{round($opsd->purchase_price,2)}}
                                </td>
                                <td>
                                    {{$opsd->expired_date}}
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                    <!--end::Table body-->
                </table>

            </div>


      </div>
    </div>
</body>
</html>





