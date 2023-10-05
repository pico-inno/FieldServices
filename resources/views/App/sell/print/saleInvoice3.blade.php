@php
    $currencyDp=getSettingValue('currency_decimal_places');
    $quantityDp=getSettingValue('quantity_decimal_places');
@endphp
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
    <link href={{asset("assets/css/bootstrap.min.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/css/style.css")}} rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="print-invoice" class="">
        <div class="container-fluid">
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid  mb-10 mb-xl-0">
                    <!--begin::Invoice 2 content-->
                    <div class="mt-3">
                        <!--begin::Wrapper-->
                        <div class="m-0">
                            <!--begin::Label fw-bold text-gray-800 mb-8 -->
                            <div class="fw-bold mb-3 invoice-sales">Invoice/{{$sale['sales_voucher_no']}}</div>
                            <!--end::Label-->
                            <!--begin::Row-->
                            <div class="d-flex flex-nowrap justify-content-between g-5 mb-4">
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold  text-gray-600 mb-1 invoice-sales">Sale By:</div>
                                    <span class="pe-2"><span class="fw-bold text-gray-800 invoice-sales">{{$sale['sold_by']['username']}}</span>
                                        {{-- <span class="fs-7 text-danger d-flex align-items-center fw-semibold">{{$sale['sold_at']}}</span> --}}
                                    <!--end::Label-->
                                    <!--end::Info-->
                                    {{-- <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap"></div> --}}
                                    <!--end::Info-->
                                </div>
                                <!--end::Col-->
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold  text-gray-600 mb-1 text-start invoice-sales">Date:</div>
                                    <span class="pe-2 invoice-sales">
                                        <span class="text-gray-600 fw-semibold">{{$sale['sold_at']}}</span>
                                    </span>
                                    <!--end::Label-->
                                    <!--end::Info-->
                                    <div class="fw-bold  text-gray-800 d-flex align-items-center flex-wrap">
                                        {{-- <span class="pe-2">
                                            <span class="text-gray-600 fw-semibold">{{$sale['sold_at']}}</span>
                                        </span>
                                        <span class="fs-7 text-danger d-flex align-items-center"> --}}
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                            <!--begin::Row-->
                            <div class="d-flex flex-nowrap justify-content-between g-5 mb-3">
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    {{-- <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue For:</div> --}}
                                    <!--end::Label-->
                                    <!--end::Text-->
                                    <div class="fw-bold  text-gray-800 invoice-sales">{{$sale['customer']['prefix']}}{{$sale['customer']['first_name']}}{{$sale['customer']['middle_name']}} {{$sale['customer']['last_name']}} </div>
                                    <!--end::Text-->
                                    <!--end::Description-->
                                        <div class="fw-semibold  text-gray-600 invoice-sales">Yangon,Myanmar</div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Col-->
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    {{-- <div class="fw-semibold fs-7 text-gray-600 mb-1">Issued By:</div> --}}
                                    <!--end::Label-->
                                    <!--end::Text-->
                                    <div class="fw-bold  text-gray-800 invoice-sales"> {{businessLocationName($location)}}</div>
                                    <!--end::Text-->
                                    <!--end::Description-->
                                    <div class="fw-semibold  text-gray-600 invoice-sales">
                                        {!! addresss($address) !!}
                                </div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                            <!--begin::Content-->
                            <div class="flex-grow-1">
                                <!--begin::Table-->
                                <div class="mb-9">

                                   {{-- Begin:English Version Table --}}
                                    <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Discount</th>
                                                    <th>Expense</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                        @foreach ($sale_details as $key=>$sd)

                                                                @php
                                                                    $p=$sd->product;
                                                                    $variation_template_value=$sd->toArray()['product_variation']['variation_template_value'];
                                                                @endphp
                                                                        <tr>
                                                                            <td>{{$key+1}}</td>
                                                                            <td>{{$p->name}} {{$variation_template_value ?'('.$variation_template_value['name'].')':''}}</td>
                                                                            <td>
                                                                                <div>
                                                                                    <span>{{round($sd->quantity,$quantityDp)}}</span>
                                                                                    <span>{{$sd->uom['short_name']}}</span>
                                                                                    <span>( x{{number_format($sd->uom->value,0,'.','')}} {{ getReferenceUomId($sd->uom->id)->short_name}} )</span>
                                                                                </div>
                                                                            </td>

                                                                            <td>{{round($sd->uom_price,$currencyDp)}}</td>
                                                                            <td>{{round($sd->per_item_discount,$currencyDp) ??0}} {{ $sd->discount_type=='percentage'?'%':$sd->currency['symbol'] ?? ''}}</td>
                                                                            <td>{{round($sd->subtotal_with_tax,$currencyDp)}} {{$sd->currency['symbol']?? ''}}</td>
                                                                        </tr>
                                                            @endforeach

                                                                        <tr>
                                                                            <td colspan="5" class="text-end  pe-3">Subtotal: </td>
                                                                            <td>{{round($sale['sale_amount'],$currencyDp)}} {{$sale['currency']['symbol'] ?? ''}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="5" class="text-end  pe-3">Extra Discount </td>
                                                                            <td>{{round($sale['extra_discount_amount'],$currencyDp)}} {{$sale['extra_discount_type']=="fixed"?$sale['currency']['symbol'] ?? '':'%'}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="5" class="text-end  pe-3">Paid Amount </td>
                                                                            <td>{{round($sale['paid_amount'],$currencyDp)}}  {{$sale['currency']['symbol'] ?? ''}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="5" class="text-end  pe-3">Total</td>
                                                                            <td>{{round($sale['total_sale_amount'],$currencyDp)}}  {{$sale['currency']['symbol'] ?? ''}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="5" class="text-end  pe-3">Balance Amount </td>
                                                                            <td>{{round($sale['balance_amount'],$currencyDp)}}  {{$sale['currency']['symbol'] ?? ''}}</td>
                                                                        </tr>
                                            </tbody>
                                    </table>
                                    {{-- End:English Version Table --}}




                                    {{-- Begin:Myanmar Version Table --}}

                                        {{-- <table class="table table-striped table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th>စဥ်</th>
                                                    <th>အမျိုးအစား</th>
                                                    <th>အရေအတွက်</th>
                                                    <th>နှုန်း</th>
                                                    <th>Dis(%)</th>
                                                    <th>သင့်ငွေ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                        @foreach ($sale_details as $key=>$sd)

                                                                @php
                                                                    $p=$sd->product;
                                                                    $variation_template_value=$sd->toArray()['product_variation']['variation_template_value'];
                                                                @endphp
                                                                        <tr>
                                                                            <td>{{$key+1}}</td>
                                                                            <td>{{$p->name}} {{$variation_template_value ?'('.$variation_template_value['name'].')':''}}</td>
                                                                            <td>
                                                                                <div>
                                                                                    <span>{{round($sd->quantity,$quantityDp)}}</span>
                                                                                    <span>{{$sd->uom['short_name']}}</span>
                                                                                    <span>( x{{number_format($sd->uom->value,0,'.','')}} {{ getReferenceUomId($sd->uom->id)->short_name}} )</span>
                                                                                </div>
                                                                            </td>

                                                                            <td>{{round($sd->uom_price,$currencyDp)}}</td>
                                                                            <td>{{round($sd->per_item_discount,$currencyDp) ??0}} {{ $sd->discount_type=='percentage'?'%':$sd->currency['symbol'] ?? ''}}</td>
                                                                            <td>{{round($sd->subtotal_with_tax,$currencyDp)}} {{$sd->currency['symbol']?? ''}}</td>
                                                                        </tr>
                                                            @endforeach

                                                                        <tr>
                                                                            <td colspan="5" class="text-end  pe-3">သင့်ငွေစုစုပေါင်း</td>
                                                                            <td>{{round($sale['sale_amount'],$currencyDp)}} {{$sale['currency']['symbol'] ?? ''}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="5" class="text-end  pe-3">အပိုလျှော့စျေး</td>
                                                                            <td>{{round($sale['extra_discount_amount'],$currencyDp)}} {{$sale['extra_discount_type']=="fixed"?$sale['currency']['symbol'] ?? '':'%'}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="5" class="text-end  pe-3">ပေးဆောင်သောပမာဏ</td>
                                                                            <td>{{round($sale['paid_amount'],$currencyDp)}}  {{$sale['currency']['symbol'] ?? ''}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="5" class="text-end pe-3">စုစုပေါင်း</td>
                                                                            <td>{{round($sale['total_sale_amount'],$currencyDp)}}  {{$sale['currency']['symbol'] ?? ''}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="5" class="text-end pe-3">လက်ကျန်ငွေပမာဏ</td>
                                                                            <td>{{round($sale['balance_amount'],$currencyDp)}}  {{$sale['currency']['symbol'] ?? ''}}</td>
                                                                        </tr>
                                            </tbody>
                                        </table> --}}

                                    {{-- End:Myanmar Version Table --}}



                                </div>
                                <!--end::Table-->
                            </div>
                            <!--end::Content-->




                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Invoice 2 content-->
                </div>
                <!--end::Content-->

            </div>
        </div>

    </div>
</body>
</html>
{{-- @php
    die;
@endphp --}}




