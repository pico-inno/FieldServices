@extends('App.main.navBar')


@section('inventory_icon', 'active')
@section('inventory_show', 'active show')
@section('stockin_here_show','here show')
@section('upcoming_stockin_active_show', 'active show')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Add Stockin</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('stockinout::stockin.stockin')}}</li>
        <li class="breadcrumb-item text-muted"><a href="{{route('stock-in.upcoming.index')}}">{{__('stockinout::stockin.upcoming_list')}}</a></li>
        <li class="breadcrumb-item text-dark">{{$received_data->purchase_voucher_no}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/businessSetting.css")}}>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
@endsection



@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl" id="kt_content_container">
            <form action="{{route('stock-in.store')}}" method="POST">
                @csrf
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
                    <div class="card border border-primary-subtle border-top-2 border-left-0 border-right-0 border-bottom-0">
                        <div class="card-body">
                            <div class="row mb-5 flex-wrap">
                                <div class="mb-7 mt-3 col-12 col-md-6">
                                    <div class="text-group">
                                        <h3 class="text-primary-emphasis fw-semibold fs-5 mb-5">
                                            <input type="text" class="d-none" name="receive_product" value="receive_form">
                                            {{__('stockinout::stockin.supplier')}} : <span class="text-gray-600 fw-semibold">{{$received_data->supplier->company_name}}</span>
                                        </h3>
                                        <h3 class="text-primary-emphasis fw-semibold fs-5 mb-5">
                                            {{__('stockinout::stockin.purchase_voucher_no')}} : <span class="text-gray-600 fw-semibold">{{$received_data->purchase_voucher_no}}</span>
                                        </h3>

                                    </div>
                                </div>
                                <div class="mb-7 mt-3 col-12 col-md-6">
                                    <div class="text-group">
                                        <h3 class="text-primary-emphasis fw-semibold fs-5 mb-5">
                                            {{__('stockinout::stockin.status')}} : <span class=" fw-semibold text-warning">{{$received_data->status}}</span>
                                        </h3>
{{--                                        <h3 class="text-primary-emphasis fw-semibold fs-5 mb-5">--}}
{{--                                            {{__('stockinout::stockin.business_location')}} : <span class="text-gray-600 fw-semibold">{{$received_data->businessLocation->name}}</span>--}}
{{--                                            <input type="text" class="d-none" name="business_location_id" value="{{$received_data->business_location_id}}">--}}
{{--                                        </h3>--}}
                                    </div>
                                </div>
                                <!--begin::Input group-->
                                <div class="mb-5 col-12 col-md-4">
                                    <label class="form-label fs-6 fw-semibold required">{{__('stockinout::stockin.business_location')}}</label>
                                        <div class="col-12 mb-2 input-group flex-nowrap">
                                            <div class="input-group-text"><i class="fa-solid fa-location-dot"></i></div>
                                            <select name="business_location_id" id="business_location_id" class="form-select form-select-sm rounded-0" data-kt-select2="true"  data-placeholder="{{__('stockinout::stockin.placeholder_location')}}">
                                                <option selected></option>
                                                @foreach ($locations as $location)
                                                    <option value="{{$location->id}}">{{$location->name}}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="input-group-text "  data-bs-toggle="tooltip" data-bs-custom-class="tooltip" data-bs-placement="top" data-bs-html="true" title="<span class='text-primary-emphasis'>{{__('stockinout::stockin.location_usage')}}</span>">
                                                <i class="fa-solid fa-circle-info text-primary"></i>
                                            </button>
                                        </div>
                                </div>
                                <div class="mb-5 col-12 col-md-8"></div>
                                <div class="mb-7 mt-3 col-12 col-md-3">
                                    <label class="form-label fs-6 fw-semibold required">{{__('stockinout::stockin.stockin_person')}}</label>
                                    <div class="input-group flex-nowrap">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-user text-muted"></i>
                                        </div>
                                        <select name="stockin_person" class="form-select form-select-sm  fw-bold rounded-start-0"
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="{{__('stockinout::stockin.placeholder_person')}}" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach($stockin_persons as $stockin_person)
                                                <option @selected(\Illuminate\Support\Facades\Auth::id() ==  $stockin_person->id)
                                                    value="{{$stockin_person->id}}">{{$stockin_person->username}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('stockin_person')
                                    <p class="alert text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-7 mt-3 col-12 col-md-3">
                                    <label class="form-label fs-6 fw-semibold required" for="purchaseDatee">
                                        {{__('stockinout::stockin.date')}}
                                    </label>
                                    <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                        <input class="form-control form-control-sm" name="stockin_date" placeholder="Pick a date"
                                               data-td-toggle="datetimepicker" id="kt_datepicker_1"
                                               value="{{date('Y-m-d')}}"/>
                                    </div>
                                </div>
                                <div class="mb-7 mt-3 col-12 col-md-6">
                                    <label class="form-label fs-6">
                                        {{__('stockinout::stockin.note')}}
                                    </label>
                                    <textarea class="form-control form-control-sm" name="note" id="" cols="30" rows="1"></textarea>
                                </div>
                                <!--end::Input group-->


{{--                                <div class="col-12 mb-7">--}}
{{--                                    <label class="form-label fs-6">--}}
{{--                                        Note--}}
{{--                                    </label>--}}
{{--                                    <textarea class="form-control form-control-sm" name="note" id="" cols="30" rows="4"></textarea>--}}
{{--                                </div>--}}
                            </div>
                            <div class="d-flex justify-content-center align-items-center my-10">
                                <div class="col-6 col-md-1 fs-4 fw-light  text-primary-emphasis">
                                    {{__('stockinout::stockin.products')}}
                                </div>
                                <div class="separator   border-primary-subtle col-md-11 col-6"></div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mt-6" id="stockin_table">
                                    <!--begin::Table head-->
                                    <thead class="">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0 ">
                                        {{--                                        <th class="min-w-35px">#</th>--}}
                                        <th class="min-w-200px">Product</th>
                                        <th class="min-w-100px">Ordered</th>
                                        {{--                                        <th class="min-w-100px">Demand</th>--}}
                                        <th class="min-w-100px">Received</th>
                                        <th class="min-w-100px">In</th>
                                        <th class="min-w-125px">UOM</th>
                                        <th class="min-w-200px">Remark</th>
                                        <th class="text-center"><i class="fa-solid fa-trash text-primary"
                                                                   type="button"></i></th>
                                    </tr>
                                    <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fw-semibold text-gray-600">

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="col-12 text-center mt-2 mb-5">
                        <button type="submit" class="btn btn-primary btn-lg save-btn">Save</button>
                    </div>
                </div>
            </form>

        </div>
        <!--end::Container-->
    </div>

    @include('App.purchase.contactAdd')
    @include('App.purchase.newProductAdd')
@endsection

@push('scripts')
    <script>

        $('[data-td-toggle="datetimepicker"]').flatpickr({
            dateFormat: "Y-m-d",
        });

        var unique_name_id=1;
        let setting=@json($setting);
        var lotControl=setting.lot_control;
        var productsOnSelectData=[];

        $(document).ready(function() {
            var detailID = {{$received_data->id}};
            $.ajax({
                url: '/stockin/filter-purchase-order/data',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { data: detailID }
            })
                .done(function(response) {

                    response.forEach(item => {
                        checkAndStoreSelectedProduct(item);
                        append_row(item, unique_name_id, detailID);
                        // $('select[name="business_location_id"]').val(item.business_location_id).trigger('change');
                        unique_name_id+=1;
                    });
                })
                .fail(function(xhr) {
                    var status = xhr.status;
                    if (status == 405) {
                        warning('Method Not Allowed!');
                    } else {
                        error('Something Went Wrong! Error Status: ' + status);
                    }
                });

        });


        function checkAndStoreSelectedProduct(newSelectedProduct) {
            let newProductData={
                'purchase_detail_id':newSelectedProduct.id,
                'product_id':newSelectedProduct.id,
                // 'product_type':newSelectedProduct.product_type,
                'variation_id':newSelectedProduct.product_variation.id,
                // 'category_id':newSelectedProduct.category_id,
                'uom':newSelectedProduct.uom_data.uom_by_category,
                'purchase_uom_id':newSelectedProduct.purchase_uom_id,
                'quantity':newSelectedProduct.quantity,
            };
            if(productsOnSelectData.length>0){
                let fileterProduct=productsOnSelectData.filter(function(p){
                    return p.purchase_detail_id==newSelectedProduct.id;
                })[0];
                if(fileterProduct){
                    return
                }else{
                    productsOnSelectData=[...productsOnSelectData,newProductData];
                }
            }else{
                productsOnSelectData=[...productsOnSelectData,newProductData];
            }
        }

        var uomsData =[];
        var uomByCategory;
        function append_row(item,unique_name_id,selectedTag) {
            uomsData =[];
            console.log(lotControl);
            uomByCategory= item.uom_data.uom_by_category;

            try {uomByCategory.forEach(function(e){
                    uomsData = [...uomsData,{'id':e.id,'text':e.name}]
                })
            } catch (e) {
                error('400 : Product need to define UOM');
                return;
            }

            let variation = item.product.product_type === 'variable' ? item.product_variation.variation_template_value.name : '';
            let orderedQuantity = Number(item.quantity).toFixed(2);
            let demandQuantity = Number(item.quantity - item.received_quantity).toFixed(2);
            let receivedQuantity = item.received_quantity == null ? '0.00' : Number(item.received_quantity).toFixed(2);

            let qtyInputField = '';

            if (lotControl === 'on') {
                qtyInputField = `
                    <td>
                        <input type="text" class="in_quantity form-control form-control-sm" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#invoice_row_discount_${unique_name_id}" value="0.00" readonly>
                        <input type="hidden" class="in_quantity form-control form-control-sm" name="stockin_details[${unique_name_id}][in_quantity]" value="0.00">
                    </td>`;
            } else {
                qtyInputField = `
                    <td>
                        <input type="text" class="in_quantity form-control form-control-sm" name="stockin_details[${unique_name_id}][in_quantity]" value="0.00">
                    </td>`;
            }

            let newRow = `<tr class='cal-gp'  data-row-id="${unique_name_id}">
            <td class="d-none">
                <span class='text-gray-800 mb-1'>${unique_name_id}</span>
                <input type="hidden" value="${selectedTag}" id="selectedTag" name="stockin_details[${unique_name_id}][purchase_id]">
                <input type="hidden" value="${item.id}"  class="purchase_detail_id" name="stockin_details[${unique_name_id}][purchase_detail_id]">
                <input type="hidden" value="${item.product_id}" class="product_id" name="stockin_details[${unique_name_id}][product_id]">
                <input type="hidden" value="${item.variation_id}" class="variation_id" name="stockin_details[${unique_name_id}][variation_id]">
                <input type="hidden" value="${item.per_ref_uom_price}" name="stockin_details[${unique_name_id}][per_ref_uom_price]">
            </td>
            <td class="ps-0">
                <span class="text-gray-600 mb-1 ">${item.product.name}</><br>
                <span class="text-gray-500 fw-semibold fs-5">${variation}</span>
            </td>
            <td>
                <span class="ordered_quantity_text">${orderedQuantity}</span>
                <input type="hidden" class="ordered_quantity" name="stockin_details[${unique_name_id}][ordered_quantity]" value="${orderedQuantity}">
            </td>
              <td>
                <span class="received_quantity_text">${receivedQuantity}</span>
                <input type="hidden" class="received_quantity" name="stockin_details[${unique_name_id}][received_quantity]" value="${receivedQuantity}">
            </td>

            ${qtyInputField}

            <td>
                <select name="lot_uom_id[]" id="" class="form-select form-select-sm uom-select" data-kt-repeater="uom_select_${unique_name_id}"  data-hide-search="true"  data-placeholder="Select Unit" required>

            </select>
            <input type="hidden" value="${item.purchase_uom_id}" name="stockin_details[${unique_name_id}][purchase_uom_id]">
                        </td>
                        <td>
                            <input type="text" name="stockin_details[${unique_name_id}][remark]" class="form-control form-control-sm">
                            <input type="hidden" class="modal-data-input" name="stockin_details[${unique_name_id}][lot_sertial_details]" value="${item.product.name}">
                        </td>
                        <th class="text-center"><i class="fa-solid fa-trash text-danger deleteRow btn" ></i></th>
                    </tr>`;


            let modalTemplate = `
                                <div class="modal fade lotSerModal" id="invoice_row_discount_${unique_name_id}" tabindex="-1"  tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog mw-800px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Lot/Serials Number for ${item.product.name}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="row mb-5">
                                                    <div class="col-md-5">
                                                    <input type="text" class="form-control form-control-sm sn-input"  value="" placeholder="Serial Number / Lot"/>
                                                    <input type="hidden" class="order_qty_in_lot" value="${orderedQuantity}" />
                                                    </div>
                                                    </div>

                                                <table class="table table-rounded table-striped border gy-7 gs-7" id="lotSerialTable_${unique_name_id}">
                                <thead>
                                    <tr>
                                        <th>Lot/Serial No</th>
                                        <th>Expire Date</th>
                                        <th>IN</th>
                                        <th>UOM/label</th>
                                        <th class="min-w-125px text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="lot_serial_body">
                                    <tr class="lot_serial_details_row">
                                        <td>
                                            <input type="text" class="form-control  form-control-sm" name="lot_serials[]" placeholder="SN">
                                        </td>
                                        <td>

                             <input class="form-control form-control-sm datepicker-input"  name="expire_date[]" placeholder="Pick a date"
                                                                           data-td-toggle="datetimepicker" id="kt_datepicker_2"
                                                                           />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control  form-control-sm" name="number_of_in[]" placeholder="Quantity">
                                        </td>
                                        <td>

                               <select name="lot_uom_id[]" id="" class="form-select form-select-sm uom-select" data-modal-uom-selection="${unique_name_id}" data-kt-repeater="uom_select_${unique_name_id}"  data-hide-search="true"  data-placeholder="Select Unit" required>

                                                    </select>
                                        </td>
                                      <td class="text-center">

                                              <button class="btn btn-sm  btn-light-primary btn-add-row" type="button">
                                                            <i class="ki-duotone ki-plus fs-5"></i>

                                                        </button>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary btn-save-changes">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

            $('#stockin_table tbody').append(newRow);
            $('body').append(modalTemplate);
            initializeDatepickers();
            initializeUomSelects(unique_name_id);
            $(`[data-kt-repeater="uom_select_${unique_name_id}"]`).val(item.purchase_uom_id).trigger('change');
            $(`[data-modal-uom-selection="${unique_name_id}"]`).val(item.purchase_uom_id).trigger('change');


        }


        function optionSelected(value,select){
            var valueToSelect = value;
            var $select = select;
            $select.val(valueToSelect);
            var $option = $select.find('option[value="' + valueToSelect + '"]');
            $option.prop('selected', true);
            $select.trigger('change');
        }


        $(document).on('change','.cal-gp .uom-select',function(){
            // e.preventDefault();
            let parent = $(this).closest('.cal-gp');
            let unique_name_id=parent.attr('data-row-id');
            let productId=parent.find('.product_id').val();
            let variationId=parent.find('.variation_id').val();
            let purchase_detail_id=parent.find('.purchase_detail_id').val();
            let product=productsOnSelectData.filter(p=> p.purchase_detail_id == purchase_detail_id)[0];

            let resultQty=changeQtyOnUom2(product.purchase_uom_id,$(this).val(),product.quantity,product.uom);
            parent.find('.ordered_quantity_text').text(resultQty);

            optionSelected($(this).val(),$(`[data-modal-uom-selection="${unique_name_id}"]`));

            let modalParent=$(`#invoice_row_discount_${unique_name_id}`);
            modalParent.find('.order_qty_in_lot').val(resultQty);

        });

        function changeQtyOnUom2(currentUomId, newUomId, currentQty,uoms) {
            let newUomInfo = uoms.find((uomItem) => uomItem.id == newUomId);
            let currentUomInfo = uoms.find((uomItem) => uomItem.id == currentUomId);
            let refUomInfo = uoms.find((uomItem) => uomItem.unit_type =="reference");
            let currentRefQty = isNullOrNan(getReferenceUomInfoByCurrentUomQty(currentQty,currentUomInfo,refUomInfo).qtyByReferenceUom);
            let currentUomType = currentUomInfo.unit_type;
            let newUomType = newUomInfo.unit_type;
            let newUomRounded = newUomInfo.rounded_amount || 1;
            let newUomValue=newUomInfo.value;
            let currentUomValue=currentUomInfo.value;
            let resultQty;
            let resultPrice;

            if ( newUomType == 'bigger') {
                resultQty = currentRefQty / newUomInfo.value;
            } else if (newUomType == 'smaller') {
                resultQty = currentRefQty * newUomInfo.value;
            } else {
                resultQty = currentRefQty;
            }

            return resultQty;
        }
        function getReferenceUomInfoByCurrentUomQty(qty, currentUom, referenceUom) {
            const currentUomType = currentUom.unit_type;
            const currentUomValue = currentUom.value;
            const referenceUomId = referenceUom.id;
            const referenceRoundedAmount = isNullOrNan(referenceUom.rounded_amount,4) ;
            const referenceValue = referenceUom.value;

            let result;
            if (currentUomType === 'reference') {
                result = qty * referenceValue;
            } else if (currentUomType === 'bigger') {
                result = qty * currentUomValue;
            } else if (currentUomType === 'smaller') {
                result = qty / currentUomValue;
            } else {
                result = qty;
            }
            let roundedResult=result;
            return {
                qtyByReferenceUom: roundedResult,
                referenceUomId: referenceUomId
            };
        }

        function isNullOrNan(val){
            let v=parseFloat(val);

            if(v=='' || v==null || isNaN(v)){
                return 0;
            }else{
                return v;
            }
        }


        function initializeDatepickers() {
            $(".datepicker-input").flatpickr();
        }

        function initializeUomSelects(unique_name_id) {
            const rowData = uomsData.map(uom => ({ id: uom.id, text: uom.text }));
            const selector = `.uom-select[data-kt-repeater="uom_select_${unique_name_id}"]`;

            $(selector).select2({
                minimumResultsForSearch: Infinity,
                data: rowData,
            });

        }

        $(document).ready(function() {


            $(document).on('keypress', '.sn-input', function(){
                if (event.which === 13) {
                event.preventDefault();
                const snInputVal = $(this).val();

                if (!snInputVal) {
                    return;
                }

                const rowContainer = $(this).closest('.modal-content');
                const lotSerialInput = rowContainer.find('[name="lot_serials[]"]').first();

                if (lotSerialInput.val().trim() === '') {
                    lotSerialInput.val(snInputVal);
                    rowContainer.find('[name="number_of_in[]"]').first().val(1);

                } else {

                        const dateValue = rowContainer.find('[name="expire_date[]"]').val();
                        const quantityValue = rowContainer.find('[name="number_of_in[]"]').val();
                        const selectedUom = rowContainer.find('.uom-select').val();

                        const newRow = `
                                <tr class="lot_serial_details_row">
                                    <td>
                                        <input type="text" class="form-control  form-control-sm" name="lot_serials[]" placeholder="SN" value="${snInputVal}">
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm datepicker-input"  name="expire_date[]" placeholder="Pick a date"
                                                        data-td-toggle="datetimepicker" id="kt_datepicker_2"
                                                        />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control  form-control-sm" name="number_of_in[]" placeholder="Quantity" value="1">
                                    </td>
                                    <td>
                                        <select name="lot_uom_id[]" id="" class="form-select form-select-sm uom-select" data-kt-repeater="uom_select_${unique_name_id}"  data-hide-search="true"  data-placeholder="Select Unit" required>

                                    </select>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm  btn-light-danger btn-remove-row">
                                                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>

                                        </button>
                                    </td>
                                </tr>

                                `;

                        rowContainer.find('.lot_serial_body').append(newRow);
                        initializeDatepickers();
                        initializeUomSelects(unique_name_id);
                        $(`[data-kt-repeater="uom_select_${unique_name_id}"]`).val(selectedUom).trigger('change');

                    const modalContainer = $(this).closest('.lotSerModal');
                    const modalId = modalContainer.attr('id');

                    // Call the function after adding a new row with a slight delay
                    setTimeout(function() {
                        eachModalcalculateTotalQuantity(modalId);
                    }, 0);
                    }//end: else condition
                $(this).val('');
                }//end:check enter
            })


            $(document).on('input', '[name="number_of_in[]"]', function() {
                const modalContainer = $(this).closest('.lotSerModal');
                const modalId = modalContainer.attr('id');
                eachModalcalculateTotalQuantity(modalId);
            });


            $(document).on('click', '.btn-add-row', function() {
                const modalContainer = $(this).closest('.lotSerModal');
                const modalId = modalContainer.attr('id');

                const firstRow = $(this).closest('.lot_serial_body').find('.lot_serial_details_row:first');
                const snValue = firstRow.find('[name="lot_serials[]"]').val();
                const dateValue = firstRow.find('[name="expire_date[]"]').val();
                const quantityValue = firstRow.find('[name="number_of_in[]"]').val();
                const selectedUom = firstRow.find('.uom-select').val();
                            const newRow = `
                                 <tr class="lot_serial_details_row">
                                    <td>
                                        <input type="text" class="form-control  form-control-sm" name="lot_serials[]" placeholder="SN">
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm datepicker-input"  name="expire_date[]" placeholder="Pick a date"
                                                        data-td-toggle="datetimepicker" id="kt_datepicker_2"/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control  form-control-sm" name="number_of_in[]" placeholder="Quantity" value="1">
                                    </td>
                                    <td>
                                        <select name="lot_uom_id[]" id="" class="form-select form-select-sm uom-select" data-kt-repeater="uom_select_${unique_name_id}"  data-hide-search="true"  data-placeholder="Select Unit" required></select>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm  btn-light-danger btn-remove-row">
                                            <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        </button>
                                    </td>
                                </tr>`;

                $(this).closest('.lot_serial_body').append(newRow);
                initializeDatepickers();
                initializeUomSelects(unique_name_id);

                $(`[data-kt-repeater="uom_select_${unique_name_id}"]`).val(selectedUom).trigger('change');

                setTimeout(function() {
                    eachModalcalculateTotalQuantity(modalId);
                }, 0); // Using a very short delay
            });


            $(document).on('click', '.btn-remove-row', function() {
                $(this).closest('.lot_serial_details_row').remove();
                const modalContainer = $(this).closest('.lotSerModal');
                const modalId = modalContainer.attr('id');

                setTimeout(function() {
                    eachModalcalculateTotalQuantity(modalId);
                }, 0);

            });


            function eachModalcalculateTotalQuantity(modalId) {
                let totalQuantity = 0;
                const uniqueModal = $('#' + modalId); // Use concatenation instead of template string

                const modalNumberInputs = uniqueModal.find('[name="number_of_in[]"]');
                const $addButton = uniqueModal.find('.btn-add-row');
                const $snInput = uniqueModal.find('.sn-input');

                const orderedQuantity = parseInt(uniqueModal.find('.order_qty_in_lot').val()) || 0;

                modalNumberInputs.each(function() {
                    const value = parseInt($(this).val()) || 0;
                    totalQuantity += value;
                });

                if (totalQuantity >= orderedQuantity) {
                    $addButton.prop('disabled', true);
                    $snInput.prop('disabled', true);
                } else {
                    $addButton.prop('disabled', false);
                    $snInput.prop('disabled', false);
                }
            }



            $(document).on('click', '.btn-save-changes', function() {
                const modalId = $(this).closest('.modal').attr('id');
                const unique_name_id = modalId.substring("invoice_row_discount_".length);

                const modalData = {};
                let totalQuantity = 0;


                $('#lotSerialTable_' + unique_name_id + ' tbody tr.lot_serial_details_row').each(function(index) {
                    const rowInputs = $(this).find('input[name^="lot_serials"], input[name^="expire_date"], input[name^="number_of_in"], select[name^="lot_uom_id"]');
                    const rowData = {};

                    rowInputs.each(function() {
                        const inputName = $(this).attr('name').replace('[]', '');

                        if (inputName === 'number_of_in') {
                            const quantity = parseFloat($(this).val() || 0);
                            totalQuantity += quantity;
                        }

                        rowData[inputName] = $(this).val();
                    });

                    modalData[index + 1] = rowData;
                });

                const parentRow = $('#stockin_table tbody').find(`[data-row-id="${unique_name_id}"]`);
                const hiddenInput = parentRow.find('.modal-data-input'); // Find the hidden input element

                const jsonString = `{${Object.keys(modalData).map(key => `"${key}":${JSON.stringify(modalData[key])}`).join(',')}}`;
                hiddenInput.val(jsonString);

                parentRow.find('.in_quantity').val(totalQuantity);
                console.log(totalQuantity);

                // $(`[data-kt-repeater="uom_select_${unique_name_id}"]`).val(item.purchase_uom_id).trigger('change');
                $('#' + modalId).modal('hide');
            });

        });



        $(document).on('click', '#stockin_table .deleteRow', function (e) {
            e.preventDefault();
            // let id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to remove it!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    var row = $(this).closest('tr');
                    row.remove();
                }
            });
        });

        function inputs(e) {
            let parent = $(e).closest('.cal-gp');
            let orderedQuantity = parent.find('.ordered_quantity');
            let orderedQuantityText = parent.find('.ordered_quantity_text');
            let receivedQuantityText = parent.find('.received_quantity_text');
            let receivedQuantity = parent.find('.received_quantity');
            let inQuantity = parent.find('.in_quantity');

            return {
                parent,
                orderedQuantity,
                orderedQuantityText,
                receivedQuantityText,
                receivedQuantity,
                inQuantity
            }
        }


        $(document).on('input','.cal-gp input',function () {
            checkQty($(this));
        })

        function checkQty(e) {
            const i = inputs(e);
            var orderedQty = Number(i.orderedQuantity.val());
            var receivedQty = Number(i.receivedQuantity.val());
            var inQty = Number(i.inQuantity.val());

            var inAbleQty = orderedQty -receivedQty;


            setTimeout(function() {
                var isQtyInvalid = inQty > inAbleQty;
                var isQtyNull = inQty === 0 || inQty == null;
                i.orderedQuantityText.toggleClass('text-danger', isQtyInvalid);
                i.receivedQuantityText.toggleClass('text-danger', isQtyInvalid);
                i.inQuantity.toggleClass('text-danger', isQtyInvalid || isQtyNull);
                $('.save-btn').prop('disabled', isQtyInvalid || isQtyNull);
            }, 600);

        }






    </script>




    {{--    @include('App.stock.in.include.quickSearchProduct')--}}
@endpush

