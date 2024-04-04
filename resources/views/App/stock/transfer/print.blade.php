<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>print invoice</title>
    <link href={{ asset('assets/plugins/custom/vis-timeline/vis-timeline.bundle.css') }} rel="stylesheet"
        type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href={{ asset('assets/plugins/global/plugins.bundle.css') }} rel="stylesheet" type="text/css" />
    <link href={{ asset('assets/css/style.bundle.css') }} rel="stylesheet" type="text/css" />
    <style>
        .text-muted {
            color: #9FA6B2 !important;
        }

        .text-success {
            color: #14A44D !important;
        }

        .text-warning {
            color: #E4A11B !important;
        }

        .text-primary {
            color: #3B71CA !important;
        }
    </style>
</head>


<body>
    <div class="pb-12">
        <div id="print-invoice" class="m-10">
            <div class="d-flex flex-column gap-7 gap-md-10">
                <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                    <div class="flex-root d-flex flex-column">
                        <span class="text-muted mb-2">Voucher No</span>
                        <span class="fs-5">{{ $transfer['transfer_voucher_no'] }}</span>
                    </div>
                    <div class="flex-root d-flex flex-column">
                        <span class="text-muted mb-2">Transfer Date</span>
                        <span class="fs-5">{{ $transfer['transfered_at'] }}</span>
                    </div>
                    <div class="flex-root d-flex flex-column">
                        <span class="text-muted mb-2">Location (From)</span>
                        <span class="fs-5">{{ $transfer['business_location_from']['name'] }}</span>
                    </div>
                    <div class="flex-root d-flex flex-column">
                        <span class="text-muted mb-2">Location (To)</span>
                        <span class="fs-5">{{ $transfer['business_location_to']['name'] }}</span>
                    </div>
                    <div class="flex-root d-flex flex-column">
                        <span class="text-muted mb-2">Status</span>
                        <span
                            class="fs-5
                            @if ($transfer['status'] == 'completed') text-success
                            @elseif($transfer['status'] == 'in_transit')
                                text-warning
                            @else
                                text-primary @endif
                        ">{{ $transfer['status'] }}</span>
                    </div>
                </div>
                <div class="d-flex justify-content-between flex-column">

                    <div class="table-responsive border-bottom mb-9">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                            <thead>
                                <tr class="border-bottom fs-6 fw-bold text-primary">
                                    <th class="min-w-175px pb-2">Products</th>
                                    <th class="min-w-80px text-end pb-2">Transfer Qty</th>
                                    <th class="min-w-80px text-end pb-2">UOM</th>
                                    <th class="min-w-100px text-end pb-2">Remark</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">

                                @foreach ($transfer_details as $key => $detail)
                                    @php
                                        $variation_template_value = $detail->toArray()['product_variation'][
                                            'variation_template_value'
                                        ];
                                    @endphp
                                    <tr>

                                        <td>
                                            <div class="d-flex align-items-center">

                                                <div class="ms-5">
                                                    <div class="fw-bold">{{ $detail->product->name }}
                                                        <span class="text-gray-500 fw-semibold fs-5">
                                                            {{ $variation_template_value ? '(' . $variation_template_value['name'] . ')' : '' }}</span>
                                                    </div>

                                                </div>

                                            </div>
                                        </td>

                                        <td class="text-end">{{ round($detail->quantity) }}
                                            {{ $detail->uom->short_name }}</td>

                                        <td class="text-end">{{ $detail->uom->name }}</td>

                                        <td class="text-end">{{ $detail->remark }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
