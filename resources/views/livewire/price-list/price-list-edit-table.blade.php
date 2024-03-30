<div class="card card-p-4 ">

    <div class="card-body card-flush">

        <div class="card-toolbar d-flex justify-content-end align-items-center" wire:ignore>
            <!--begin::Search-->
            {{-- <div class="d-flex align-items-center position-relative my-1 me-2">
                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                            transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path
                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                            fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
                <input type="search" wire:model.live.debounce.50ms="search" data-kt-saleItem-table-filter="search"
                    class="form-control form-control-sm w-250px ps-15" placeholder="Search" />
            </div> --}}
            <!--end::Search-->
            <div class="text-end ">
                <a href="{{route('export-priceList',$id)}}" class="btn btn-light-primary btn-sm">
                    <span class="fa-solid fa-upload me-3"></span>  Export Price List Data
                </a>
                <button type="button"  class="btn btn-light-success btn-sm" data-bs-toggle="modal" data-bs-target="#priceListModal">
                    <span class="fa-solid fa-download me-3"></span> Update Price List With Excel
                </button>
            </div>
        </div>
        <div class="separator mb-5"></div>
        <br>
        <div class=""  wire:ignore.self>
            {{-- <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                        </svg>
                    </span>
                    <input type="search" id="search" wire:model.live.debounce.50ms='search' data-kt-filter="search" class="form-control form-control-sm form-control-solid w-250px ps-14" placeholder="Search Product" />
                </div>
                <!--end::Search-->
                <!--begin::Export buttons-->
                <div id="kt_datatable_example_1_export" class="d-none"></div>
                <!--end::Export buttons-->
            </div> --}}
            <div class="table-responsive mb-4"  wire:ignore.self>
                <div class="position-absolute w-fit  top-10 bg-white p-3 rounded-1 border border-1 border-gray-500 "
                            wire:loading style="top: 100px;left:50%;">
                            <h2 class="text-dark fs-5">Saving....</h2>
                        </div>
                <table class="table table-row-dashed fs-6 gy-4" id="room_added_table">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-600">
                            <th class="min-w-150px required">Apply Type</th>
                            <th class="min-w-150px required">Apply Value</th>
                            <th class="min-w-100px required">Min Quantity</th>
                            <th class="min-w-100px required">Cal Type</th>
                            <th class="min-w-100px required">Cal Value</th>
                            <th class="min-w-150px">Start Date</th>
                            <th class="min-w-150px">End Date</th>
                            <th>Actions</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-semibold text-gray-700 "  wire:ignore.self id="price_list_body">
                        @php
                            $PriceListDetaildataFromExcel=request()['PriceListDetaildataFromExcel'];
                        @endphp
                        @if ($PriceListDetaildataFromExcel)
                                @foreach ($PriceListDetaildataFromExcel as $pl)
                                {!! App\Http\Controllers\Product\UI\PriceListDetailsUI::detailsUI($pl) !!}
                                @endforeach
                        @elseif ($priceListDetails)
                            @if(count($priceListDetails) >0)
                                @foreach ($priceListDetails as $index=>$item)
                                    {{-- @if ($editingId != $item['id']) --}}
                                        <tr class="price_list_row cursor-pointer user-select-none"
                                        x-data="{ isEdit: false }"
                                        wire:key='{{$index}}'
                                        wire:ignore
                                        @dblclick="isEdit = true"
                                        @click.outside="isEdit = false"
                                        >

                                            <td >
                                                <div class="fv-row">
                                                    <select name="apply_type[]"  x-bind:disabled="!isEdit" class="form-select form-select-sm rounded-0 fs-7 applyType" disabled
                                                        data-hide-search="true" data-placeholder="Please select">
                                                        <option></option>
                                                        <option value="All" @selected($item->applied_type === 'All') disabled>All</option>
                                                        <option value="Category" @selected($item->applied_type === 'Category')>Category</option>
                                                        <option value="Product" @selected($item->applied_type === 'Product')>Product</option>
                                                        <option value="Variation" @selected($item->applied_type === 'Variation')>Variations</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td >
                                                <div class="fv-row">
                                                    {{-- helo --}}
                                                    {{-- {{$item->applied_value}} --}}
                                                    <select name="apply_value[]" x-bind:disabled="!isEdit"
                                                    wire:key='{{$index}}'
                                                    wire:ignore
                                                    class="form-select applyValue form-select-sm rounded-0 fs-7" disabled data-control="select2"
                                                        data-hide-search="false" data-placeholder="Please select">
                                                        @if ($item->applied_type != 'All')
                                                            <option value="{{$item->applied_value}}" @selected($item->applied_value == $item->applied_value ) >{{getOptionName($item->applied_type,$item->applied_value)}}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </td>
                                            <td >
                                                {{-- <div class="text-center justify-content-center align-items-center" x-show="!isEdit" x-text="{{$item->min_qty}}"></div> --}}
                                                <input type="text"   x-bind:disabled="!isEdit"   class="form-control input_number user-select-none form-control-sm rounded-0" disabled name="min_qty[]"
                                                wire:change="minQtyChange($event.target.value,{{$item->id}})"
                                                    value="{{old('min_qty[]',$item->min_qty * 1)}}">
                                            </td>
                                            <td >
                                                <div class="fv-row">
                                                    <select name="cal_type[]" wire:key='{{$index}}'
                                                    wire:ignore
                                                     x-bind:disabled="!isEdit"  class="form-select form-select-sm rounded-0 fs-7 options" data-id="{{$item['id']}}" disabled
                                                        data-hide-search="true" data-placeholder="Please select">
                                                        <option></option>
                                                        <option value="fixed" @selected($item->cal_type === "fixed")>Fix</option>
                                                        <option value="percentage" @selected($item->cal_type === "percentage")>Percentage</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td >
                                                <div class="fv-row">
                                                    <input type="text" x-bind:disabled="!isEdit"
                                                    wire:change="calValChange($event.target.value,{{$item->id}})"
                                                    class="form-control form-control-sm rounded-0" disabled name="cal_val[]"
                                                        value="{{old('cal_val[]',$item->cal_value * 1)}}">
                                                </div>
                                            </td>
                                            <td >
                                                <input type="text" x-bind:disabled="!isEdit" data-id="{{$item['id']}}"  name="start_date[]" class="form-control form-control-sm rounded-0 fs-7 start_date" value="{{ old('start_date[]', $item->from_date) }}" placeholder="Select date" autocomplete="off" />
                                            </td>
                                            <td >
                                                <input type="text" x-bind:disabled="!isEdit" data-id="{{$item['id']}}"  name="end_date[]" class="form-control form-control-sm rounded-0 fs-7 end_date" value="{{ old('end_date[]', $item->to_date) }}" placeholder="Select date" autocomplete="off" />
                                            </td>
                                            <td ><button type="button"  class="btn btn-light-dark btn-sm p-2" @click="isEdit = true"><i class="fa-solid fa-edit fs-6"></i></button></td>
                                            <td ><button type="button"  data-id="{{$item['id']}}"   class="btn btn-light-danger btn-sm deleteData  p-2"><i class="fa-solid fa-trash fs-6"></i></button></td>
                                        </tr>
                                @endforeach
                            @endif
                        @endif
                    </tbody>
                    <!--end::Table body-->
                </table>


            </div>
            <div class="row justify-content-center mb-5 justify-content-md-between">
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
                    {{$priceListDetails->onEachSide(1)->links()}}
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('successfully-saved', () => {
        success('Successfylly Saved');
    });
    $wire.on('error', (event) => {
        error(event.message);
    });

    $('.deleteData').on('click',function(){
        Swal.fire({
            title:'Are You Sure To Delete',
            icon: "question",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Sure,Delete",
            cancelButtonText: 'Nope, cancel it',
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: 'btn btn-danger'
            }
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                // alert($(this).data('id'));
                $wire.dispatch('delete',{'id':$(this).data('id')});
                // Swal.fire("Saved!", "", "success");
            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
            }
        });
    })
    $(`.options`).select2().on('select2:select', function (e) {
            let id=$(this).data('id');
            let value=$(this).select2('val');
            $wire.dispatch('data-change',{field:'cal_type',value,id});
            // @this.set('paymentAccountId', $(`#options`).select2("val"));
        }).on('select2:unselect', function (e) {
            // @this.set('paymentAccountId', '');
        });

        $(`.applyType`).select2().on('select2:select', function (e) {
            let id=$(this).data('id');
            let value=$(this).select2('val');
            $wire.dispatch('data-change',{field:'applied_type',value,id});
            // @this.set('paymentAccountId', $(`#options`).select2("val"));
        }).on('select2:unselect', function (e) {
            // @this.set('paymentAccountId', '');
        });
        $(`.applyValue`).select2().on('select2:select', function (e) {
            let id=$(this).data('id');
            let value=$(this).select2('val');
            $wire.dispatch('data-change',{field:'applied_value',value,id});
            // @this.set('paymentAccountId', $(`#options`).select2("val"));
        }).on('select2:unselect', function (e) {
            // @this.set('paymentAccountId', '');
        });

        $(".start_date").flatpickr({
            // altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                let id = $(this.element).data("id");
                $wire.dispatch('data-change',{field:'from_date',value:dateStr,id});
            }
        });
        $(".end_date").flatpickr({
            // altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                let id = $(this.element).data("id");
                $wire.dispatch('data-change',{field:'to_date',value:dateStr,id});
            }
        });



</script>
@endscript
