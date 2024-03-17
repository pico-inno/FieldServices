<div class="table-responsive mb-4">
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
                <th><i class="fa-solid fa-trash text-danger"></i></th>
            </tr>
            <!--end::Table row-->
        </thead>
        <!--end::Table head-->
        <!--begin::Table body-->
        <tbody class="fw-semibold text-gray-700 " id="price_list_body">
            <h4>Editing Id : {{$editingId}}</h4>
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
                        <div wire:loading>Saving</div>
                        @if ($editingId != $item['id'])
                            <tr class="price_list_row cursor-pointer" @click="alert('hello world')" wire:dblclick='editMode({{$item->id}})' wire:key='{{$index}}'>
                                <td wire:dblclick='editMode({{$item->id}})'>
                                    <div class="fv-row">
                                        <select name="apply_type[]" class="form-select form-select-sm rounded-0 fs-7" disabled data-control="select2"
                                            data-hide-search="true" data-placeholder="Please select">
                                            <option></option>
                                            <option value="All" @selected($item->applied_type === 'All') disabled>All</option>
                                            <option value="Category" @selected($item->applied_type === 'Category')>Category</option>
                                            <option value="Product" @selected($item->applied_type === 'Product')>Product</option>
                                            <option value="Variation" @selected($item->applied_type === 'Variation')>Variations</option>
                                        </select>
                                    </div>
                                </td>
                                <td wire:dblclick='editMode({{$item->id}})'>
                                    <div class="fv-row">
                                        <select name="apply_value[]" class="form-select form-select-sm rounded-0 fs-7" disabled data-control="select2"
                                            data-hide-search="false" data-placeholder="Please select">
                                            @if ($item->applied_type != 'All')
                                                <option value="{{$item->applied_value}}" selected>{{getOptionName($item->applied_type,$item->applied_value)}}</option>
                                            @endif
                                        </select>
                                    </div>
                                </td>
                                <td wire:dblclick='editMode({{$item->id}})'>
                                    <div class="fv-row">
                                        <input type="text" class="form-control form-control-sm rounded-0" disabled name="min_qty[]"
                                            value="{{old('min_qty[]',$item->min_qty * 1)}}">
                                    </div>
                                </td>
                                <td wire:dblclick='editMode({{$item->id}})'>
                                    <div class="fv-row">
                                        <select name="cal_type[]" class="form-select form-select-sm rounded-0 fs-7" disabled data-control="select2"
                                            data-hide-search="true" data-placeholder="Please select">
                                            <option></option>
                                            <option value="fixed" @selected($item->cal_type === "fixed")>Fix</option>
                                            <option value="percentage" @selected($item->cal_type === "percentage")>Percentage</option>
                                        </select>
                                    </div>
                                </td>
                                <td wire:dblclick='editMode({{$item->id}})'>
                                    <div class="fv-row">
                                        <input type="text" class="form-control form-control-sm rounded-0" disabled name="cal_val[]"
                                            value="{{old('cal_val[]',$item->cal_value * 1)}}">
                                    </div>
                                </td>
                                <td wire:dblclick='editMode({{$item->id}})'>
                                    <input type="text" disabled name="start_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date" value="{{ old('start_date[]', $item->from_date) }}" placeholder="Select date" autocomplete="off" />
                                </td>
                                <td wire:dblclick='editMode({{$item->id}})'>
                                    <input type="text" disabled name="end_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date" value="{{ old('end_date[]', $item->to_date) }}" placeholder="Select date" autocomplete="off" />
                                </td>
                                <td wire:dblclick='editMode({{$item->id}})'><button type="button"  class="btn btn-light-danger btn-sm delete_each_row"><i class="fa-solid fa-trash"></i></button></td>
                            </tr>
                        @else
                            <tr class="price_list_row  cursor-pointer" >
                                <td>
                                    <div class="fv-row">
                                        <input type="hidden" name="price_list_detail_id[]" value="{{ $item->id }}">
                                        <select name="apply_type[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
                                            data-hide-search="true" data-placeholder="Please select">
                                            <option></option>
                                            <option value="All" @selected($item->applied_type === 'All') disabled>All</option>
                                            <option value="Category" @selected($item->applied_type === 'Category')>Category</option>
                                            <option value="Product" @selected($item->applied_type === 'Product')>Product</option>
                                            <option value="Variation" @selected($item->applied_type === 'Variation')>Variations</option>
                                        </select>
                                    </div>
                                </td>
                                {{-- <td>
                                    <select name="apply_value[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2" data-hide-search="false" data-placeholder="Please select">

                                    </select>
                                </td> --}}
                                <td>
                                    <div class="fv-row">
                                        <select name="apply_value[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
                                            data-hide-search="false" data-placeholder="Please select">
                                            @if ($item->applied_type != 'All')
                                                <option value="{{$item->applied_value}}" selected>{{getOptionName($item->applied_type,$item->applied_value)}}</option>
                                            @endif
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="fv-row">
                                        <input type="text" class="form-control form-control-sm rounded-0" name="min_qty[]"  wire:change="minQtyChange($event.target.value,{{$item->id}})"
                                            value="{{old('min_qty[]',$item->min_qty * 1)}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="fv-row">
                                        <select name="cal_type[]" class="form-select form-select-sm rounded-0 fs-7" data-control="select2"
                                            data-hide-search="true" data-placeholder="Please select">
                                            <option></option>
                                            <option value="fixed" @selected($item->cal_type === "fixed")>Fix</option>
                                            <option value="percentage" @selected($item->cal_type === "percentage")>Percentage</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="fv-row">
                                        <input type="text" class="form-control form-control-sm rounded-0" name="cal_val[]"
                                            value="{{old('cal_val[]',$item->cal_value * 1)}}">
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="start_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date" value="{{ old('start_date[]', $item->from_date) }}" placeholder="Select date" autocomplete="off" />
                                </td>
                                <td>
                                    <input type="text" name="end_date[]" class="form-control form-control-sm rounded-0 fs-7 select_date" value="{{ old('end_date[]', $item->to_date) }}" placeholder="Select date" autocomplete="off" />
                                </td>
                                <td><button type="button" class="btn btn-light-danger btn-sm delete_each_row"><i class="fa-solid fa-trash"></i></button></td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            @endif
        </tbody>
        <!--end::Table body-->
    </table>
    <script>

    </script>
</div>
