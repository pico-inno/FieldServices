<div>
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" wire:ignore>
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h2>Filters</h2>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-5 flex-wrap">
                    <div class="col-sm-5 col-md-5 col-12 me-sm-5 mb-3">
                        <label for="" class="form-label">Select Product :</label>

                        <x-product-select-component  id="kt_docs_select2_rich_content" class="form-select form-select-lg"  data-placeholder="Select Product" placeholder="Select Product" />

                        {{-- <select id="kt_docs_select2_rich_content" class="form-select form-select-lg"  data-placeholder="Select Product">
                            @foreach ($products as $product)
                                @php
                                    $isImage=$product["image"] ? true:false;
                                    $image=$isImage ?
                                        asset("storage/product-image/".$product['image']) :
                                        asset('assets/media/svg/files/blank-image.svg');
                                @endphp
                                <option value="{{$product['id']}}" selected data-sku="{{$product['sku']}}" data-isImage="{{$isImage}}" data-kt-rich-content-icon="{{$image}}">{{$product['name']}}</option>

                            @endforeach

                        </select> --}}
                    </div>
                    <div class="col-sm-5 col-md-5">
                            <label for="" class="form-label">Select Location :</label>
                            <select wire:model.change="businesslocationFilterId"
                             class="form-select form-select-lg fw-bold locationFilter"
                                data-placeholder="Select option" id="select2"
                                data-kt-saleItem-table-filter="businesslocation" data-hide-search="true">
                                <option value="all">All</option>
                                @foreach ($locations as $l)
                                    <option value="{{ $l['id'] }}">{{$l['name']}}</option>
                                @endforeach
                            </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-flush h-xl-100">
        <!--begin::Card header-->
        <div class="row flex-sm-nowrap col-12 pt-7 my-3 mx-5" >
            <div class="col-2">
                @if ($sortAsc)
                    <button  wire:loading.attr="disabled" wire:target="isAsc" class="btn btn-outline btn-outline-primary btn-sm" type="button" wire:click="isAsc">
                        <i class="fa-solid fa-sort"></i> Ascending
                    </button>
                @else
                    <button wire:loading.attr="disabled" wire:target="isAsc" class="btn btn-outline btn-outline-success btn-sm" type="button" wire:click="isAsc">
                        <i class="fa-solid fa-sort"></i> Descending
                    </button>
                @endif

            </div>
        </div>

        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body">

        <div class="position-absolute w-fit  top-10 bg-white p-3 rounded-1 border border-1 border-gray-500 "
        wire:loading style="top: 40px;left:50%;">
            <h2 class="text-primary">Loading....</h2>
        </div>
        <div class="table-responsive">
             <!--begin::Table-->
             <table class="table align-middle table-row-dashed fs-6 gy-3" >
                <!--begin::Table head-->

                <thead>
                    <!--begin::Table row-->
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="text-start min-w-100px">Date</th>
                        <th class="text-start pe-3 min-w-100px">Reference</th>
                        <th class="text-start pe-3 min-w-100px">Product</th>
                        <th class="text-start pe-3 min-w-100px">From</th>
                        <th class="text-start pe-3 min-w-100px">To</th>
                        <th class="text-end pe-3 min-w-100px">Increase Qty</th>
                        <th class="text-end pe-3 min-w-100px">Decrease Qty</th>
                        <th class="text-end pe-3 min-w-100px">Balance Qty</th>
                        <th class="text-center pe-3 min-w-100px">UOM</th>
                        {{-- <th class="text-end pe-0 min-w-25px">Qty</th> --}}
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                @php

                    $currencyDp=getSettingValue('currency_decimal_places');
                    $quantityDp=getSettingValue('quantity_decimal_places');
                @endphp
                <tbody class="fw-bold text-gray-600 text-start fs-7">
                    @if (count($datas) > 0)
                        @foreach ($datas as  $i=>$data)
                            <tr   class="text-start">
                                <td>
                                    {{fdate($data->created_at,false,true)}}
                                </td>

                                <td >
                                    @if($data->transaction_type=='sale')
                                        <span class='text-info'>{{$data->saleDetail->sale->sales_voucher_no}}</span><br>
                                    @elseif($data->transaction_type=='purchase')
                                        <span class='text-success'>{{$data->purchaseDetail->purchase->purchase_voucher_no}}</span><br>
                                    @elseif($data->transaction_type=='stock_in')
                                        @if (hasModule('StockInOut') && isEnableModule('StockInOut'))
                                            <span class='text-primary'>{{$data->stockInDetail->stockin->stockin_voucher_no}}</span><br>
                                        @endif
                                    @elseif($data->transaction_type=='stock_out')
                                        @if(hasModule('StockInOut') && isEnableModule('StockInOut'))
                                            <span class='text-danger'>{{$data->StockoutDetail->stockOut->stockout_voucher_no}}</span><br>
                                        @endif
                                    @elseif($data->transaction_type=='opening_stock')
                                        <span class='text-dark'>{{$data->openingStockDetail->openingStock->opening_stock_voucher_no}}</span><br>
                                    @elseif($data->transaction_type=='transfer')
                                        <span class='text-info'>{{$data->StockTransferDetail->stockTransfer->transfer_voucher_no  ?? ''}}</span><br>
                                    @elseif($data->transaction_type=='adjustment')
                                        <span class='text-warning'>{{$data->adjustmentDetail->stockAdjustment->adjustment_voucher_no}}</span><br>
                                    @endif
                                </td>
                                <td>
                                    {{$data->name}}
                                </td>
                                <td   class="text-start">
                                    @if($data->transaction_type=='sale' || $data->transaction_type=='stock_out' || $data->transaction_type==='transfer')
                                        {{$data->business_location->name}}
                                    @else
                                        @if($data->transaction_type=='purchase')
                                            {{arr($data->purchaseDetail->purchase->supplier,'company_name','','unknown Supplier')}}
                                        @elseif($data->transaction_type=='stock_in')
                                            {{arr($data->stockInDetail->purchaseDetail->purchase->supplier, 'company_name', '', 'unknown Supplier')}}
                                        @endif
                                    @endif
                                </td>
                                <td class="text-start">
                                    @if($data->transaction_type=='sale' )
                                        @php
                                            $customer=$data->saleDetail->sale->customer;
                                        @endphp
                                        {{$customer ? $customer['first_name'] : ''}}
                                    @else
                                        @if($data->transaction_type=='purchase' || $data->transaction_type=='stock_in' || $data->transaction_type==='transfer')
                                                {{$data->business_location->name}}
                                        @endif
                                    @endif
                                </td>
                                <td class="text-end">
                                    {!! $data->increase_qty >0 ? "<span class='text-success fw-bold'>".number_format( $data->increase_qty,$quantityDp,'.') ."</span>":'-' !!}
                                </td>

                                <td class="text-end">
                                    {!! $data->decrease_qty >0 ? "<span class='text-danger fw-bold'>".number_format( $data->decrease_qty,$quantityDp,'.') ."</span>":'-' !!}
                                </td>
                                <td>


                                    @if ($sortAsc)
                                        @php
                                            $balanceQtyBeforePage=calBalanceQtyForAsc($data->increase_qty,$data->decrease_qty,$balanceQtyBeforePage)
                                        @endphp

                                        {{$balanceQtyBeforePage}}
                                    @else
                                        {{$balanceQtyBeforePage}}
                                        @php
                                        $balanceQtyBeforePage=calBalanceQtyForDesc($data->increase_qty,$data->decrease_qty,$balanceQtyBeforePage)
                                        @endphp
                                    @endif
                                    {{-- @if ($increase_qty >0)
                                    {{$increase_qty+=  $increase_qty}}
                                    @elseif ($decrease_qty >0){
                                        {{$decrease_qty-=  $decrease_qty}}
                                    @endif --}}
                                </td>
                                <td>
                                    @php
                                        $uom=$data->uom->short_name;
                                    @endphp
                                    {!! "<span class='pe-3'>$uom</span>" !!}
                                </td>
                            </tr>
                        @endforeach
                    @elseif($variationId == null)
                    <tr>
                        <td colspan="9">
                            Please Select A product
                        </td>
                    </tr>
                    @else
                        <tr>
                            <td colspan="9">
                                There Is No Stock History For This Product.
                            </td>
                        </tr>
                    @endif

                </tbody>
                <!--end::Table body-->
            </table>
        </div>
        <div class="row justify-content-center  justify-content-md-between">
            <div class="col-md-6 col-12 mb-3 ">
                <div class="w-auto">
                    <select name="" id="" wire:model.change="perPage" class="form-select form-select-sm w-auto m-auto m-md-0">
                        @foreach ($aviablePerPages as $page)
                        <option value="{{$page}}">{{$page}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6 col-12 mb-3">
                {{$datas->links()}}
            </div>
        </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <script >
        $('#select2').select2().on('select2:select', function (e) {
            @this.set('businesslocationFilterId', $('#select2').select2("val"));
        }).on('select2:unselect', function (e) {
            @this.set('businesslocationFilterId','all');
        });
        $('#kt_docs_select2_rich_content').select2().on('select2:select', function (e) {
            @this.set('variationId', $('#kt_docs_select2_rich_content').select2("val"));
        }).on('select2:unselect', function (e) {
            @this.set('variationId', null);
        });

        // $('#kt_docs_select2_rich_content').select2().on('select2:select', function (e) {

        //     @this.set('variationId', $('#kt_docs_select2_rich_content').select2("val"));
        //     // @this.set('businesslocationFilterId', $('#select2').select2("val"));
        // }).on('select2:unselect', function (e) {
        //     // @this.set('businesslocationFilterId','all');

        //     @this.set('variationId', null);
        // });
        // // Format options
        // const optionFormat = (item) => {
        //     if (!item.id) {
        //         return item.text;
        //     }

        //     var span = document.createElement('span');
        //     var template = '';

        //     template += '<div class="d-flex align-items-center">';
        //     template += '<img src="' + item.element.getAttribute('data-kt-rich-content-icon') + '" class="rounded-1 h-30px me-3" alt="' + item.text + '"/>';
        //     template += '<div class="d-flex flex-column">'
        //     template += '<span class="fs-5 fw-bold lh-1">' + item.text + '</span>';
        //     template += '<span class="text-muted fs-9 mt-1">' + item.element.getAttribute('data-sku') + '</span>';
        //     template += '</div>';
        //     template += '</div>';

        //     span.innerHTML = template;

        //     return $(span);
        // }

        // Init Select2 --- more info: https://select2.org/
        // $('#kt_docs_select2_rich_content').select2({
        //     placeholder: "Select an option",
        //     // minimumResultsForSearch: Infinity,
        //     templateSelection: optionFormat,
        //     templateResult: optionFormat,
        //     matcher: function(params, data) {
        //         // If there are no search terms, return all options
        //         if ($.trim(params.term) === '') {
        //             return data;
        //         }

        //         // Do a custom search by name or SKU

        //         var term = params.term.toLowerCase();
        //         var name = data.text.toLowerCase();
        //         var sku = data.element.getAttribute('data-sku');
        //         if (name.indexOf(term) > -1 || sku.indexOf(term) > -1) {
        //             return data;
        //         }

        //         return null; // Return null if there is no match
        //     }
        // }).on('select2:select', function (e) {
        //         @this.set('variationId', $('#kt_docs_select2_rich_content').select2("val"));
        //     });
    </script>
</div>
