<div class="card">
    <div class="card-body">
        <div class="d-flex  flex-wrap flex-sm-nowrap col-12 pt-7 my-3  ">
            <div class="col-12 col-md-3 me-sm-5 mb-3 mb-sm-0 ms-3">
                <input type="search"  wire:model.live.debounce.50ms="search" class="form-control form-control-sm" placeholder="Search...."
                    data-filter="input">
            </div>

            <!--begin::Input group-->
            <div class="mb-10 col-4 col-sm12 col-md-3 ">
                <input wire:model.live='filterDate' class="form-control form-control-sm form-control-solid" placeholder="Pick date rage"
                    data-kt-saleItem-table-filter="dateRange" id="kt_daterangepicker_4"
                    data-dropdown-parent="#filter" />
            </div>
            <!--end::Input group-->
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-rounded   table-row-dashed gy-5 gs-5" id="itemReportTable">
                <thead>
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                        <th class="min-w-200px">Product</th>
                        <th class="min-w-175px">Product Sku</th>
                        <th class="min-w-175px">In Date</th>
                        <th class="min-w-175px">IN Voucher No(Transaction)</th>
                        <th class="min-w-175px">Supplier</th>
                        <th class="min-w-175px text-end">Purchase Price</th>
                        <th class="min-w-175px">Customer</th>
                        <th class="min-w-175px">Location</th>
                        <th class="min-w-175px">Out Date</th>
                        <th class="min-w-175px">Sale Voucher No</th>
                        <th class="min-w-175px text-end">Sell Qty</th>
                        <th class="min-w-175px text-end">Selling price</th>
                        <th class="min-w-175px text-end">Subtotal</th>
                        <th class="min-w-175px text-end">Total Cogs</th>
                        <th class="min-w-175px text-end">Total Profits</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                    <tr>
                        <td>{{$data->name}}</td>
                        <td>{{$data->sku}}</td>
                        <td>
                            @if ($data->csbT == 'purchase')
                                 {{fDate($data->purchase_date, false, false)}}
                            @elseif ($data->csbT == 'opening_stock')
                                 {{fDate($data->osDate, false, false)}}
                            @elseif ($data->csbT == 'transfer')
                                 {{fDate($data->transfered_at, false, false)}}
                            @elseif ($data->csbT == 'stock_in')
                                 {{fDate($data->stockin_date, false, false)}}
                            @endif
                        </td>
                        <td>
                            @if ($data->csbT == 'purchase')
                                 {{$data->purchase_voucher_no}}
                            @elseif ($data->csbT == 'opening_stock')
                                 {{$data->opening_stock_voucher_no}}
                            @elseif ($data->csbT == 'stock_in')
                                 {{$data->stockin_voucher_no}}
                            @elseif ($data->csbT == 'transfer')
                                 {{$data->transfer_voucher_no}}
                            @endif
                        </td>
                        <td>
                            @if ($data->csbT == 'purchase')
                                 {{$data->supplier}}
                            @elseif ($data->csbT == 'opening_stock')
                                 {{$data->openingPerson}}
                            @elseif ($data->csbT == 'transfer')
                                 {{$data->transferLocaitonName}}
                            @elseif ($data->csbT == 'stock_in')
                                 {{$data->stockinPersonName}}
                            @endif
                        </td>
                        <td class="text-end">
                            {{formatNumberv2($data->purchase_price)}}
                        </td>
                        <td>{{$data->customer_name}}</td>
                        <td>{{$data->location}}</td>
                        <td>{{fDate($data->sold_at, false, false)}}</td>
                        <td>{{$data->sales_voucher_no}}</td>
                        <td class="text-end">{{formatNumberv2($data->sell_qty)}}</td>
                        <td class=" text-end">{{formatNumberv2($data->selling_price)}}</td>
                        <td class=" text-end">{{formatNumberv2($data->sale_subtotal)}}</td>
                        <td class=" text-end">{{formatNumberv2($data->total_cogs)}}</td>
                        <td class=" text-end">{{formatNumberv2($data->sale_subtotal-$data->total_cogs)}}</td>
                    </tr>

                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold fs-3">
                        <td colspan="11">
                            <h3>Total</h3>
                        </td>
                        <td  class=" text-end">
                            {{$datas->sum('sale_subtotal')}}
                        </td>
                        <td  class=" text-end">
                            {{$datas->sum('total_cogs')}}
                        </td>
                        <td  class=" text-end">
                            {{$datas->sum('sale_subtotal')-$datas->sum('total_cogs')}}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        {{$datas->links()}}
    </div>
</div>

<script>
        $(document).ready(function() {


        // cb(start, end);
        var start = moment().subtract(1, "M");
        var end = moment();

        function cb(start, end) {
            $("#kt_daterangepicker_4").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
            let startDate=$('#kt_daterangepicker_4').data('daterangepicker').startDate.format('YYYY-MM-DD');
            let endDate=$('#kt_daterangepicker_4').data('daterangepicker').endDate.format('YYYY-MM-DD');
            @this.set('filterDate', [startDate,endDate]);
        }

        $("#kt_daterangepicker_4").daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
            "Today": [moment(), moment()],
            "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
            "Last 7 Days": [moment().subtract(6, "days"), moment()],
            "Last 30 Days": [moment().subtract(29, "days"), moment()],
            "This Month": [moment().startOf("month"), moment().endOf("month")],
            "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
            }
        }, cb);
    });
</script>
