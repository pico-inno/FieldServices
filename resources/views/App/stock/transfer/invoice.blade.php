<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>print invoice</title>
    <link href={{asset("assets/plugins/custom/vis-timeline/vis-timeline.bundle.css")}} rel="stylesheet"
          type="text/css"/>
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href={{asset("assets/plugins/global/plugins.bundle.css")}} rel="stylesheet" type="text/css"/>
    <link href={{asset("assets/css/style.bundle.css")}} rel="stylesheet" type="text/css"/>
</head>
<style>

</style>
<body>
<div id="print-invoice" class="m-10">
    <div class="-header">
        <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 pb-7">{{__('transfer.transfer_details')}} ({{__('transfer.voucher_no')}}: {{$transfer['transfer_voucher_no']}} )</h4>

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
                    Location (From)
                </h3>
                <address class="mt-3 fs-5">
                    {{$transfer['business_location_from']['name']}}<br>
                    {{!empty($transfer['business_location_from']['address']) ? $transfer['business_location_from']['address'].'<br>' : ''}}
                    {{-- {{$transfer['business_location_from']['city']}},{{$transfer['business_location_from']['state']}},{{$transfer['business_location_from']['country']}},{{$transfer['business_location_from']['zip_code']}} --}}
                </address>
            </div>
            <div class="col-4">
                <h3 class="text-primary-emphasis fs-4">
                    Location (To)
                </h3>
                <address class="mt-3 fs-5">
                    {{$transfer['business_location_to']['name']}}<br>
                    {{!empty($transfer['business_location_to']['address']) ? $transfer['business_location_to']['address'].'<br>' : ''}}
                    {{-- {{$transfer['business_location_to']['city']}},{{$transfer['business_location_to']['state']}},{{$transfer['business_location_to']['country']}},{{$transfer['business_location_to']['zip_code']}} --}}
                </address>
            </div>
            <div class="col-4">
                <h3 class="text-primary-emphasis fs-4">
                    Detail:
                </h3>
                <div class="text-group">
                    <h3 class="fw-semibold fs-5">
                        Date : <span class="text-gray-600">{{$transfer['transfered_at']}}</span>
                    </h3>
                    <h3 class="fw-semibold fs-5">
                        Stockin Status : <span class="text-gray-600">{{$transfer['status']}}</span>
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
                    <th class="min-w-175px pb-2">Products</th>
                    <th class="min-w-80px pb-2">Transfer Qty</th>
                    <th class="min-w-80px pb-2">UOM</th>
                    <th class="min-w-100px pb-2">Remark</th>
                </tr>
                <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="fw-semibold text-gray-800 fs-7">
                @foreach ($transfer_details as $key=>$detail)
                    @php
                        $variation_template_value=$detail->toArray()['product_variation']['variation_template_value'];
                    @endphp
                    <tr>
                        <!--begin::Name=-->
                        <td>{{$key+1}}</td>
                        <td>
                            {{$detail->product->name}}<br>
                            {{$variation_template_value ? "(".$variation_template_value['name'].")":''}}
                        </td>
                        <td>{{round($detail->quantity,2)}} ({{$detail->uom->short_name}})</td>
                        <td>{{$detail->uom->name}}</td>
                        <td>{{$detail->remark}}</td>
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





