
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sale invoice</title>
    <link href={{asset("assets/plugins/custom/vis-timeline/vis-timeline.bundle.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/plugins/global/plugins.bundle.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/css/style.bundle.css")}} rel="stylesheet" type="text/css" />
</head>
<style>

</style>
<body>
    <div id="print-invoice" class="m-10">
        <div class="header">
            <h3 class="title">sale  (Invoice No:{{sprintf('%04d', ($sale['id']+ 1))}} )</h3>
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="" aria-label="Close">
                <i class="fas fa-times fs-2"></i>
            </div>
        </div>

        <div class="body">
            <div class="row mb-5 d-flex">
                <div class="col-4">
                    <h3 class="text-primary-emphasis fs-4">
                        Supplier:
                    </h3>
                    <address class="mt-3 fs-5">
                         {{$sale['customer']['first_name']}}<br>
                        Mobile:{{$sale['customer']['mobile']}}
                    </address>
                </div>
                <div class="col-4">
                    <h3 class="text-primary-emphasis fs-4">
                        Bussiness:
                    </h3>
                    <address class="mt-3 fs-5">
                       {{ $location['name'] ? $location['name'].','  :''}}<br>
                       {{ $location['landmark'] ? $location['landmark'].','  :''}}<br>
                        {{$location['city'] ? $location['city'].',' :'' }}{{ $location['state'] ? $location['state'].',' :'' }}
                        {{$location['country'] ? $location['country'].',' :'' }}{{$location['zip_code'] ? $location['zip_code'].',' :''}}
                    </address>
                </div>
                <div class="col-4">
                    <div class="text-group">
                        <h3 class="text-primary-emphasis fw-semibold fs-5">
                            Date : <span class="text-gray-600">{{$sale['sold_at']}}</span>
                        </h3>
                        <h3 class="text-primary-emphasis fw-semibold fs-5">
                            sale Status : <span class="text-gray-600">{{$sale['status']}}</span>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="">
                <table class="table align-middle table-row-dashed fs-6 p-10" id="kt_customers_table">
                    <thead class="bg-success">
                        <tr class="bg-success fw-bold fs-8 text-white text-start text-uppercase">
                            <th class="">#</th>
                            <th class="min-w-50px">Product Name</th>
                            <th class="min-w-50px">Quantity</th>
                            <th class="min-w-50px">Unit</th>
                            <th class="min-w-50px">Price <br> (Before Discount)</th>
                            <th class="min-w-50px">Discount </th>
                            <th class="min-w-50px">Additional expense</th>
                            <th class="min-w-50px">Price <br> (After Discount)</th>
                            <th class="min-w-40px pe-4"> EXP date</th>
                    </thead>
                    <tbody class="fw-semibold text-gray-800 fs-7">
                        @foreach ($sale_details as $key=>$pd)
                        @php
                            $p=$pd->product;
                            $variation_template_value=$pd->toArray()['product_variation']['variation_template_value'];
                        @endphp
                            <tr>
                                <!--begin::Name=-->
                                <td>
                                    {{$key+1}}
                                </td>
                                <!--end::Name=-->
                                <!--begin::Email=-->
                                <td>
                                    {{$p->name}}<br>
                                    {{$variation_template_value ?'('.$variation_template_value['name'].')':''}}
                                </td>
                                <td>
                                    {{round($pd->quantity,2)}}
                                </td>
                                <td>
                                     {{$pd->unit['name']}}
                                </td>
                                <td>
                                    {{round($pd->sale_price_without_discount,2)}}
                                </td>
                                <td>
                                    {{round($pd->discount_amount,2)}} {{$pd->discount_type=="fixed"?'ks':'%'}}
                                </td>
                                 <td>
                                    {{round($pd->addititonal_expense,2)}}
                                </td>
                                {{-- <td>
                                    {{round($pd->item_tax,2)}}
                                </td> --}}
                                <td>
                                    {{round($pd->sale_price,2)}}
                                </td>
                                <td>
                                    {{$pd->expired_date}}
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                    <!--end::Table body-->
                </table>

            </div>

          <div class="col-md-6 col-12 col-xs-12  ">
            <div class="mt-5">
                <table class="table align-middle table-row-dashed fs-6 gy-2 p-5" id="kt_customers_table">
                    <!--begin::Table body-->
                    <tbody class="fw-semibold text-gray-800 table-bordere">
                        <tr>
                            <td>
                                Net Total Amount:
                            </td>
                            <td class="text-end">
                            </td>
                            <td class="text-end">
                                {{round($sale['sale_amount'],2)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Discount:
                            </td>
                            <td class="text-end">
                                (-)
                            </td>
                            <td class="text-end">
                                ks {{round($sale['discount_amount'],2)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                sale Expense:
                            </td>
                            <td class="text-end">
                                (+)
                            </td>
                            <td class="text-end">
                                {{round($sale['sale_expense'],2)}}
                            </td>
                        </tr>
                        <tr>
                            <td>Additional Shipping charges:	</td>
                            <td class="text-end">
                                (+)
                            </td>
                            <td class="text-end">
                                0
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="separator  border-gray-300 "></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                sale Total:
                            </td>
                            <td class="text-end">
                                (=)
                            </td>
                            <td class="text-end">
                            {{round($sale['total_sale_amount'],2)}}
                            </td>
                        </tr>

                    </tbody>
                    <!--end::Table body-->
                </table>

            </div>
        </div>
            {{-- <div class="row mt-3">
                <div class="col-md-6 col-12 col-xs-12">
                    <div class=" mt-10">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <!--begin::Table head-->
                            <thead class="bg-success">
                                <!--begin::Table row-->
                                <tr class="bg-success fw-bold fs-6 text-white text-start text-uppercase fs-7 ">
                                    <th class="ps-3">#</th>
                                    <th class="min-w-100px">Date</th>
                                    <th class="min-w-100px">Reference No</th>
                                    <th class="min-w-100px">Amount</th>
                                    <th class="min-w-100px">Payment mode</th>
                                    <th class="min-w-100px">Payment note</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <tbody class="fw-semibold text-gray-800">
                                <tr>
                                    <td colspan="6" class="text-center">
                                        No payments found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="row mt-5 mb-2">
                <div class="col-md-6">
                    <h3 class="text-primary-emphasis fs-4">
                        Shipping Details:
                    </h3>
                    <div class="mt-3 fs-5">
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, quia?
                        </p>
                    </div>
                </div>
                <div class="col-md-6 mt-2">
                    <h3 class="text-primary-emphasis fs-4">
                        Additional Notes:
                    </h3>
                    <div class="mt-3 fs-5">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam, facere.
                        </p>
                    </div>
                </div>
            </div> --}}
      </div>
    </div>
</body>
</html>





