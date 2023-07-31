<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sale invoice</title>
    <link href={{asset("assets/plugins/custom/vis-timeline/vis-timeline.bundle.css")}} rel="stylesheet"
        type="text/css" />
    <link href={{asset("assets/plugins/global/plugins.bundle.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/css/style.bundle.css")}} rel="stylesheet" type="text/css" />
</head>
<style>

</style>

<body>
    <div id="print-invoice" class="">
        <div class="container">
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid  mb-10 mb-xl-0">
                    <!--begin::Invoice 2 content-->
                    <div class="mt-3">
                        <!--begin::Wrapper-->
                        <div class="m-0">
                            <!--begin::Label-->
                            <div class="fw-bold fs-3 text-gray-800 mb-8">
                                Invoice/
                                {{-- {{$sale['sales_voucher_no']}} --}}
                            </div>
                            <!--end::Label-->
                            <!--begin::Row-->
                            <div class="d-flex flex-nowrap justify-content-between g-5 mb-11">
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Sale By:</div>
                                    <!--end::Label-->
                                    <!--end::Info-->
                                    <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                        <span class="pe-2"><span
                                                class="text-gray-600">
                                                {{-- {{$sale['sold_by']['username']}} --}}
                                            </span>
                                            {{-- <span
                                                class="fs-7 text-danger d-flex align-items-center fw-semibold">{{$sale['sold_at']}}</span>
                                            --}}
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::Col-->
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1 text-end">Date:</div>
                                    <!--end::Label-->
                                    <!--end::Info-->
                                    <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                        <span class="pe-2">
                                            <span class="text-gray-600 fw-semibold">
                                                {{-- {{$sale['sold_at']}} --}}
                                            </span>
                                        </span>
                                        <span class="fs-7 text-danger d-flex align-items-center">
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                            <!--begin::Row-->
                            <div class="d-flex flex-nowrap justify-content-between g-5 mb-12">
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    {{-- <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue For:</div> --}}
                                    <!--end::Label-->
                                    <!--end::Text-->
                                    <div class="fw-bold fs-6 text-gray-800">Hleo
                                        {{-- {{$sale['customer']['prefix']}}{{$sale['customer']['first_name']}}{{$sale['customer']['middle_name']}}{{$sale['customer']['last_name']}} --}}
                                    </div>
                                    <!--end::Text-->
                                    <!--end::Description-->
                                    <div class="fw-semibold fs-7 text-gray-600">8692 Wild Rose Drive
                                        <br />Livonia, MI 48150
                                    </div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Col-->
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    {{-- <div class="fw-semibold fs-7 text-gray-600 mb-1">Issued By:</div> --}}
                                    <!--end::Label-->
                                    <!--end::Text-->
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{-- {{ $location['name'] ?
                                        $location['name'].'.' :''}} --}}
                                    </div>
                                    <!--end::Text-->
                                    <!--end::Description-->
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        {{-- {{ $location['landmark'] ? $location['landmark'].',' :''}}<br>
                                        {{$location['city'] ? $location['city'].',' :'' }}{{ $location['state'] ?
                                        $location['state'].',' :'' }}
                                        {{$location['country'] ? $location['country'].',' :'' }}<br>
                                        {{$location['zip_code'] ? $location['zip_code'].',' :''}} --}}
                                    </div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                            <!--begin::Content-->
                            <div class="flex-grow-1">
                                <!--begin::Table-->
                                <div class=" border-bottom mb-9">
                                    <table class="table mb-3">
                                        <thead>
                                            <tr class="border-bottom border-dark fs-6 fw-bold text-muted">
                                                <th class="min-w-80px pb-2 text-start">Voucher No</th>
                                                <th class="min-w-80px pb-2">Transaction Type</th>
                                                <th class="min-w-80px text-start pb-2 fs-7">Sale Amount</th>
                                                <th class="min-w-80px text-center pb-2 fs-7">Total Item Discount</th>
                                                <th class="min-w-80px text-end pb-2 fs-7">Extra Discount</th>
                                                <th class="min-w-80px pb-2 fs-7 text-end">Total Sale Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600 allTab mainTab">
                                            <!--begin::Table row-->
                                            @php
                                                $saleAmount=0;
                                                $totalItemDiscount=0;
                                                $totalSaleAmount=0;
                                                $totalExtraDiscount=0;
                                            @endphp
                                            @foreach ($folioIds as $id)
                                                @php
                                                    $folioDetails=Modules\HospitalManagement\Entities\hospitalFolioInvoiceDetails::where('folio_invoice_id',$id)->get();

                                                @endphp
                                                @foreach ($folioDetails as $fd)
                                                    @php
                                                        if($fd->transaction_type=='sale'){
                                                            $fd->load('sales');
                                                            $sale=$fd->sales;
                                                            $voucher_no=$sale->sales_voucher_no;
                                                            $type="sale";
                                                            // dd($sale->sale_amount,$sale['extra_discount_type'],$sale->toArray());
                                                        }elseif($fd->transaction_type=="room"){
                                                            $fd->load('roomSales');
                                                            $sale=$fd->roomSales;
                                                            $voucher_no=$sale->room_sales_voucher_no;
                                                            $type="room sale";
                                                        };
                                                        $saleAmount+=$sale->sale_amount;
                                                        $totalItemDiscount+=$sale->total_item_discount;
                                                        $totalExtraDiscount+=DiscAmountCal($sale->sale_amount,$sale->extra_discount_type,$sale->extra_discount_amount ?? 0);
                                                        $totalSaleAmount+=$sale->total_sale_amount;
                                                            // dd($sale->toArray());
                                                    @endphp
                                                    <tr>
                                                        <td class="">{{$voucher_no}}</td>
                                                        <td>{{$type}}</td>
                                                        <td class="text-end">{{$sale->sale_amount}}</td>
                                                        <td class="text-end">
                                                            <a class="btn btn-sm btn-light btn-active-light-primary">{{round($sale['total_item_discount'],2)}} </a>
                                                        </td>
                                                        <td class="min-w-150px text-end ">
                                                            {{round($sale['extra_discount_amount'],2)}} {{$sale['extra_discount_type']=="fixed"?'ks':'%'}}
                                                        </td>
                                                        <td class="text-end">{{$sale->total_sale_amount}}</td>
                                                        {{-- <td class="text-end">{{$sale->paid_amount}}</td>
                                                        <td class="text-end">{{$sale->balance_amount}}</td> --}}
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Table-->
                                <!--begin::Container-->
                                <div class="d-flex justify-content-end">
                                    <!--begin::Section-->
                                    <div class="mw-300px col-6">
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack mb-3">
                                            <!--begin::Accountname-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">saleAmount:</div>
                                            <!--end::Accountname-->
                                            <!--begin::Label-->
                                            <div class="text-end fw-semibold fs-6 text-gray-800">
                                                {{round($saleAmount,2)}}
                                                {{-- {{$sale['currency']['symbol']}} --}}
                                            </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack mb-3">
                                            <!--begin::Accountname-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">Total Item Disccount</div>
                                            <!--end::Accountname-->
                                            <!--begin::Label-->

                                            <div class="text-end fw-semibold fs-6 text-gray-800">
                                                {{round($totalItemDiscount,2)}}
                                                {{-- {{$sale['extra_discount_type']=="fixed"?$sale['currency']['symbol']:'%'}} --}}
                                            </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item--><!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Code-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">Total Extra Discount</div>
                                            <!--end::Code-->
                                            <!--begin::Label-->
                                            <div class="text-end fw-semibold fs-6 text-gray-800">
                                                {{$totalExtraDiscount}}
                                            </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Item-->
                                        <div class="seperator"></div>
                                        <div class="d-flex flex-stack">
                                            <!--begin::Code-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">Total Sale Amount</div>
                                            <!--end::Code-->
                                            <!--begin::Label-->
                                            <div class="text-end fw-semibold fs-6 text-gray-800">
                                               {{$totalSaleAmount}}
                                                 {{-- {{$sale['currency']['symbol']}} --}}
                                                </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item-->
                                        <!--end::Item-->

                                        <!--end::Item-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Container-->
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
