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

        .text-danger {
            color: #DC4C64 !important;
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
    <div class="pb-12 p-2 mt-3">
        <!--begin::Wrapper-->
        <div class="d-flex flex-column gap-7 gap-md-10">
            <!--begin::Order details-->
            <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                <div class="flex-root d-flex flex-column">
                    <span class="text-muted">{{ __('adjustment.voucher_no') }}</span>
                    <span class="fs-6">{{ $adjustment['adjustment_voucher_no'] }}</span>
                </div>
                <div class="flex-root d-flex flex-column">
                    <span class="text-muted">{{ __('adjustment.date') }}</span>
                    <span class="fs-6">{{ $adjustment['adjustmented_at'] }}</span>
                </div>
                <div class="flex-root d-flex flex-column">
                    <span class="text-muted">{{ __('adjustment.location') }}</span>
                    <span class="fs-6">{{ $adjustment['business_location']['name'] }}</span>
                </div>
                <div class="flex-root d-flex flex-column">
                    <span class="text-muted">{{ __('adjustment_status') }}</span>
                    <span
                        class="fs-6 {{ $adjustment['status'] == 'completed' ? 'text-success' : 'text-warning' }} ">{{ $adjustment['status'] }}</span>
                </div>
            </div>
            <!--end::Order details-->
            <!--begin::Billing & shipping-->
            <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                <div class="flex-root d-flex flex-column">
                    <span class="text-muted"> Condition </span>
                    <span class="fs-6">{{ $adjustment['condition'] }}</span>
                </div>
                <div class="flex-root d-flex flex-column"></div>
                <div class="flex-root d-flex flex-column">
                    <span class="text-muted">{{ __('adjustment.increase_subtotal') }}</span>
                    <span class="fs-6 text-success">{{ $adjustment['increase_subtotal'] ?? '' }}</span>
                </div>
                <div class="flex-root d-flex flex-column">
                    <span class="text-muted">{{ __('adjustment.decrease_subtotal') }}</span>
                    <span class="fs-6 text-danger">{{ $adjustment['decrease_subtotal'] ?? '' }}</span>
                </div>
            </div>
            <!--end::Billing & shipping-->
            <!--begin:Order summary-->
            <div class="d-flex justify-content-between flex-column">
                <!--begin::Table-->
                <div class="table-responsive border-bottom mb-9">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                        <thead>
                            <tr class="border-bottom fs-6 fw-bold text-primary">
                                <th class="min-w-80px pb-2">{{ __('adjustment.product') }}</th>
                                <th class="min-w-80px text-end pb-2">{{ __('adjustment.uom') }}</th>
                                <th class="min-w-80px text-end pb-2">{{ __('adjustment.total_balance') }}</th>
                                <th class="min-w-80px text-end pb-2">{{ __('adjustment.on_ground_qty') }}</th>
                                <th class="min-w-80px text-end pb-2">{{ __('adjustment.adjustment_qty') }}</th>
                                <th class="min-w-80px text-end pb-2">{{ __('adjustment.type') }}</th>
                                <th class="min-w-80px text-end pb-2">{{ __('adjustment.uom_price') }}</th>
                                <th class="min-w-80px text-end pb-2">{{ __('adjustment.total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            <!--begin::Products-->
                            @foreach ($adjustment_details as $detail)
                                <tr>
                                    <!--begin::Product-->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <div class="fw-bold text-muted">{{ $detail['product']->name }}
                                                </div>
                                            </div>
                                            <!--end::Title-->
                                        </div>
                                    </td>
                                    <!--end::Product-->
                                    <!--begin::SKU-->
                                    <td class="text-end text-muted">{{ $detail['uom']->name }}</td>
                                    <!--end::SKU-->
                                    <!--begin::SKU-->
                                    <td class="text-end text-muted">{{ number_format($detail['balance_quantity'], 2) }}
                                        {{ $detail['uom']->short_name ? '(' . $detail['uom']->short_name . ')' : '' }}
                                    </td>
                                    <!--end::SKU-->
                                    <!--begin::Quantity-->
                                    <td class="text-end text-muted">{{ number_format($detail['gnd_quantity'], 2) }}
                                        {{ $detail['uom']->short_name ? '(' . $detail['uom']->short_name . ')' : '' }}
                                    </td>
                                    <!--end::Quantity-->
                                    <!--begin::Quantity-->
                                    <td
                                        class="text-end {{ $detail['gnd_quantity'] < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($detail['adj_quantity'], 2) }}
                                        {{ $detail['uom']->short_name ? '(' . $detail['uom']->short_name . ')' : '' }}
                                    </td>
                                    <!--end::Quantity-->
                                    <td class="text-end text-muted">{{ $detail['adjustment_type'] }}</td>
                                    <!--begin::Total-->
                                    <td class="text-end text-muted">{{ $detail['uom_price'] }}</td>
                                    <!--end::Total-->
                                    <!--begin::Total-->
                                    <td class="text-end text-muted">{{ $detail['subtotal'] }}</td>
                                    <!--end::Total-->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!--end::Table-->
            </div>
            <!--end:Order summary-->
        </div>
        <!--end::Wrapper-->
    </div>
</body>

</html>
