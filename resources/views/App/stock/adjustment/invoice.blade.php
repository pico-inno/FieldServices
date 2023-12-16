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
    <title>print invoice</title>
    <link href={{asset("assets/plugins/custom/vis-timeline/vis-timeline.bundle.css")}} rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href={{asset("assets/plugins/global/plugins.bundle.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/css/style.bundle.css")}} rel="stylesheet" type="text/css" />

    <link href={{asset('assets/css/style.css')}} rel="stylesheet" type="text/css" />
    <link href={{asset('assets/css/bootstrap.min.css')}} rel="stylesheet" type="text/css" />
</head>
<style>

</style>
<body>
  <div id="print-invoice" class="m-10">
        <div class="-header">
            <h3 class="mb-4 invoice-purchases">Purchase Details (Reference No: {{$adjustment['adjustment_voucher_no']}} )</h3>

            <!--begin::Close-->
            {{-- <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="" aria-label="Close">
                <i class="fas fa-times fs-2"></i>
            </div> --}}
            <!--end::Close-->
        </div>

        <div class="body p-0">
            <div class="row mb-4 d-flex justify-content-center align-items-center">
                <div class="col-4">
                    <h3 class="text-primary-emphasis invoice-purchases">
                        Create By:
                    </h3>
                    <address class="mt-1 invoice-purchases">
                        {{$adjustment['created_person']['username'] ?? ''}}
                    </address>
                </div>
                <div class="col-4">
                    <h3 class="text-primary-emphasis invoice-purchases">
                        Bussiness Location:
                    </h3>
                    <address class="mt-1 invoice-purchases">
                       {{ $location['name'] ? $location['name'].','  :''}}<br>
{{--                       {{ $location['address'] ? $location['address'].','  :''}}<br>--}}
{{--                        {{$location['city'] ? $location['city'].',' :'' }}{{ $location['state'] ? $location['state'].',' :'' }}--}}
{{--                        {{$location['country'] ? $location['country'].',' :'' }}{{$location['zip_code'] ? $location['zip_code'].',' :''}}--}}
                    </address>
                </div>
                <div class="col-4">
                    <div class="text-group">
                        <h3 class="fw-semibold invoice-purchases">
                            Date : <span class="text-gray-600">{{$adjustment['created_at']}}</span>
                        </h3>
                        <h3 class="9 fw-semibold invoice-purchases">
                            Status : <span class="text-gray-600">{{$adjustment['status']}}</span>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="flex-grow-1 ">
                <div class="mb-3">


                        <!-- Begin:English Version Table -->
                        <table class="table  table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th class="min-w-175px pb-2">{{__('adjustment.product')}}</th>
                                    <th class="min-w-70px text-end pb-2">{{__('adjustment.uom')}}</th>
                                    <th class="min-w-70px text-end pb-2">{{__('adjustment.total_balance')}}</th>
                                    <th class="min-w-80px text-end pb-2">{{__('adjustment.on_ground_qty')}}</th>
                                    <th class="min-w-80px text-end pb-2">{{__('adjustment.adjustment_qty')}}</th>
                                    <th class="min-w-100px text-end pb-2">{{__('adjustment.type')}}</th>
                                    <th class="min-w-100px text-end pb-2">{{__('adjustment.uom_price')}}</th>
                                    <th class="min-w-100px text-end pb-2">{{__('adjustment.total')}}</th>
                                </tr>
                            </thead>
                            <tbody>

                                        @foreach ($adjustment_details as $key=>$pd)
                                                        @php
                                                            $p=$pd->product;
                                                            $variation_template_value=$pd->toArray()['product_variation']['variation_template_value'];
                                                        @endphp
                                                        <tr>
                                                            <td>{{$key+1}}</td>
                                                            <td>{{$p->name}} {{$variation_template_value ?'('.$variation_template_value['name'].')':''}}</td>
                                                            <td>
                                                                {{$pd->uom_name}}
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <span>{{round($pd->balance_quantity,$quantityDp)}}</span>
                                                                    <span> {{$pd->uom->short_name}}</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <span>{{round($pd->gnd_quantity,$quantityDp)}}</span>
                                                                    <span> {{$pd->uom->short_name}}</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <span>{{round($pd->gnd_quantity,$quantityDp)}}</span>
                                                                    <span> {{$pd->uom->short_name}}</span>
                                                                </div>
                                                            </td>

                                                            <td>{{$pd->adjustment_type}}</td>
                                                            <td>{{round($pd->uom_price,$currencyDp)}}</td>
                                                            <td>{{round($pd->subtotal,$currencyDp) ??0}}</td>
                                                        </tr>
                                            @endforeach
                                                        <tr>
                                                            <td colspan="8" class="text-end pe-3">Increase Subtotal</td>
                                                            <td>{{$adjustment['increase_subtotal']}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8" class="text-end pe-3">Decrease Subtotal</td>
                                                            <td>{{$adjustment['decrease_subtotal']}}</td>
                                                        </tr>

                            </tbody>

                        </table>
                        <!-- End:English Version Table  -->


                        <!-- Begin:Myanmar Version Table  -->
                        {{-- <table class="table table-striped table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>စဥ်</th>
                                    <th>အမျိုးအစား</th>
                                    <th>အရေအတွက်</th>
                                    <th>နှုန်း</th>
                                    <th>Dis(%)</th>
                                    <th>ကုန်ကျစရိတ်</th>
                                    <th>သင့်ငွေ</th>
                                </tr>
                            </thead>
                            <tbody>
                                        @foreach ($purchase_details as $key=>$pd)
                                                        @php
                                                            $p=$pd->product;
                                                            $variation_template_value=$pd->toArray()['product_variation']['variation_template_value'];
                                                        @endphp
                                                        <tr>
                                                            <td>{{$key+1}}</td>
                                                            <td>{{$p->name}} {{$variation_template_value ?'('.$variation_template_value['name'].')':''}}</td>
                                                            <td>
                                                            <div>
                                                                <span>{{round($pd->quantity,$quantityDp)}}</span>
                                                                <span>{{$p->uom->short_name}}</span>
                                                            </div>
                                                            </td>

                                                            <td>{{round($pd->uom_price,$currencyDp)}}</td>
                                                            <td>{{round($pd->per_item_discount,$currencyDp) ??0}} {{ $pd->discount_type=='percentage'?'%':$pd->currency['symbol'] ?? ''}}</td>
                                                            <td>{{round($pd->expense,$currencyDp) ??0}}</td>
                                                            <td>{{round($pd->subtotal_with_tax,$currencyDp)}} {{$pd->currency['symbol'] ?? ''}}</td>
                                                        </tr>
                                            @endforeach
                                                        <tr>
                                                            <td colspan="6" class="text-end pe-3">သင့်ငွေစုစုပေါင်း</td>
                                                            <td>{{round($purchase['purchase_amount'],$currencyDp)}} {{$pd->currency['symbol']?? ''}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="6" class="text-end pe-3">လျှော့ငွေစုစုပေါင်း</td>
                                                            <td>{{round($purchase['extra_discount_amount'] ?? 0,$currencyDp)}} &nbsp;{{ $purchase['extra_discount_type']=='percentage'?'%':$purchase['currency']['symbol'] ?? ''}}</td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="6" class="text-end pe-3">အထွေထွေကုန်ကျစရိတ်</td>
                                                            <td>{{round($purchase['purchase_expense'] ?? 0,$currencyDp)}}</td>
                                                        </tr>


                                                        <tr>
                                                            <td colspan="6" class="text-end pe-3">စုစုပေါင်း</td>
                                                            <td>{{round($purchase['total_purchase_amount'],$currencyDp)}}&nbsp;{{$purchase['currency']['symbol'] ?? ''}}</td>
                                                        </tr>
                            </tbody>

                        </table> --}}
                        <!-- End:Myanmar Version Table  -->
                </div>
            </div>

            {{--<div class="col-md-6 col-12 col-xs-12  ">
                <div class="">
                    <table class="table align-middle table-row-dashed fs-6 gy-2 p-5" id="kt_customers_table">
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-800 table-bordere">
                            <tr>
                                <td>
                                  စုစုပေါင်းအသားတင်ပမာဏတန်ဖိုး :
                                </td>
                                <td class="text-end">
                                    (=)
                                </td>
                                <td class="text-end">
                                     {{round($purchase['purchase_amount'],$currencyDp)}}&nbsp;{{$purchase['currency']['symbol'] ?? ''}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                   လျှော့စျေး :
                                </td>
                                <td class="text-end">
                                    (-)
                                </td>
                                <td class="text-end">
                                            {{round($purchase['extra_discount'] ?? 0,$currencyDp)}} &nbsp;{{ $purchase['extra_discount_type']=='percentage'?'%':$purchase['currency']['symbol'] ?? ''}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    ဝယ်ယူမှုကုန်ကျစရိတ် :
                                </td>
                                <td class="text-end">
                                    (+)
                                </td>
                                <td class="text-end">
                                        {{round($purchase['purchase_expense'],$currencyDp)}}&nbsp;{{$purchase['currency']['symbol'] ?? ''}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                  စုစုပေါင်းဝယ်ယူမှု :
                                </td>
                                <td class="text-end">
                                    (=)
                                </td>
                                <td class="text-end">
                                    {{round($purchase['total_purchase_amount'],$currencyDp)}}&nbsp;{{$purchase['currency']['symbol'] ?? ''}}
                                </td>
                            </tr>

                        </tbody>
                        <!--end::Table body-->
                    </table>
                </div>
            </div>--}}
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
                    <h3 class="text-primary-emphasis fs-8">
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
</body>
</html>






