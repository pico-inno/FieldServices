@extends('App.main.navBar')


@section('inventory_icon', 'active')
@section('inventroy_show', 'active show')
@section('stock_in_active_show', 'active ')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('stockinout::stockin.edit_stockin')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('stockinout::stockin.stockin')}}</li>
        <li class="breadcrumb-item text-muted"><a href="{{route('stock-in.index')}}">{{__('stockinout::stockin.list')}}</a></li>
        <li class="breadcrumb-item text-dark">{{$stockin->stockin_voucher_no}}</li>
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
            <form action="{{route('stock-in.update', $stockin->id)}}" method="post">
                @csrf
                @method('put')
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
                    <div class="col-12 my-5 mb-2 input-group flex-nowrap">
                        <div class="input-group-text"><i class="fa-solid fa-location-dot"></i></div>
                        <select disabled name="business_location_id" id="business_location_id" class="form-select rounded-0" data-kt-select2="true"  data-placeholder="{{__('stockinout::stockin.placeholder_location')}}">
                            <option selected></option>
                            @foreach ($locations as $location)
                                <option @selected($location->id ==  $stockin->business_location_id) value="{{$location->id}}">{{$location->name}}</option>
                            @endforeach
                        </select>
                        <button type="button" class="input-group-text "  data-bs-toggle="tooltip" data-bs-custom-class="tooltip" data-bs-placement="top" data-bs-html="true" title="<span class='text-primary-emphasis'>{{__('stockinout::stockin.location_usage')}}</span>">
                            <i class="fa-solid fa-circle-info text-primary"></i>
                        </button>
                        <input type="hidden" value="{{$stockin->business_location_id}}"  name="business_location_id">
                    </div>
                    <div class="card border border-primary-subtle border-top-2 border-left-0 border-right-0 border-bottom-0">

                        <div class="card-body">
                            <div class="row mb-5 flex-wrap">
                                <div class="mb-7 mt-3 col-12 col-md-4">
                                    <label class="form-label fs-6 fw-semibold" for="">
                                        {{__('stockinout::stockin.supplier')}}
                                    </label>
                                    <div class="input-group flex-nowrap">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-user text-muted"></i>
                                        </div>
                                        <select class="form-select form-select-sm  fw-bold rounded-start-0" id="supplierOption" data-kt-select2="true"
                                                data-hide-search="false" data-placeholder="{{__('stockinout::stockin.placeholder_supplier')}}"
                                                data-allow-clear="true" data-kt-user-table-filter="role"
                                                data-hide-search="true">
                                            <option selected></option>
                                        </select>
                                    </div>

                                </div>
                                <!--begin::Input group-->
                                <div class="mb-7 mt-3 col-12 col-md-4">
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
                                                <option @selected($stockin->stockin_person == $stockin_person->id)
                                                    value="{{$stockin_person->id}}">{{$stockin_person->username}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('stockin_person')
                                    <p class="alert text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-7 mt-3 col-12 col-md-4">
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
                                <!--end::Input group-->
                                <div class="mb-7 mt-3 col-12 col-md-6">
                                    <label class="form-label fs-6">
                                        {{__('stockinout::stockin.purchase_order')}}
                                    </label>
                                    <div class="input-group flex-nowrap">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-receipt"></i>
                                        </div>
                                        <input class="form-control form-control-sm d-flex align-items-center fw-bold rounded-start-0 rounded-end-2" placeholder="{{__('stockinout::stockin.placeholder_purchase_order')}}" value="" id="tagify_purchase_ordder" />
                                    </div>
                                </div>
                                <div class="mb-7 mt-3 col-12 col-md-6">
                                    <label class="form-label fs-6">
                                        {{__('stockinout::stockin.note')}}
                                    </label>
                                    <textarea class="form-control form-control-sm" name="note" id="" cols="30" rows="1">{{$stockin->note}}</textarea>
                                </div>
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
                                        <th class="min-w-200px">{{__('stockinout::stockin.product')}}</th>
                                        <th class="min-w-100px">{{__('stockinout::stockin.ordered')}}</th>
                                        <th class="min-w-100px">{{__('stockinout::stockin.received')}}</th>
                                        <th class="min-w-100px">{{__('stockinout::stockin.in')}}</th>
                                        <th class="min-w-125px">{{__('stockinout::stockin.uom')}}</th>
                                        <th class="min-w-200px">{{__('stockinout::stockin.remark')}}</th>
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
{{--                    <div class="card">--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="table-responsive">--}}
{{--                                <table class="table align-middle table-row-dashed fs-6 gy-5 mt-6" id="stockin_table">--}}
{{--                                    <!--begin::Table head-->--}}
{{--                                    <thead class="">--}}
{{--                                    <!--begin::Table row-->--}}
{{--                                    <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0 ">--}}
{{--                                        --}}{{--                                        <th class="min-w-35px">#</th>--}}
{{--                                        <th class="min-w-200px">{{__('stockinout::stockin.product')}}</th>--}}
{{--                                        <th class="min-w-100px">{{__('stockinout::stockin.ordered')}}</th>--}}
{{--                                        <th class="min-w-100px">{{__('stockinout::stockin.received')}}</th>--}}
{{--                                        <th class="min-w-100px">{{__('stockinout::stockin.in')}}</th>--}}
{{--                                        <th class="min-w-125px">{{__('stockinout::stockin.uom')}}</th>--}}
{{--                                        <th class="min-w-200px">{{__('stockinout::stockin.remark')}}</th>--}}
{{--                                        <th class="text-center"><i class="fa-solid fa-trash text-primary"--}}
{{--                                                                   type="button"></i></th>--}}
{{--                                    </tr>--}}
{{--                                    <!--end::Table row-->--}}
{{--                                    </thead>--}}
{{--                                    <!--end::Table head-->--}}
{{--                                    <!--begin::Table body-->--}}
{{--                                    <tbody class="fw-semibold text-gray-600">--}}

{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}


{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="col-12 text-center mt-2 mb-5">
                        <button type="submit" class="btn btn-primary btn-lg save-btn">{{__('stockinout::stockin.update')}}</button>
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
        var purchaseIds = {!! json_encode($purchaseIds) !!};
        var stockin_details = {!! json_encode($stockin_details) !!};
        var tagify;
        const purchaseOrderList = [];
        var unique_name_id = 1;

        function sendAjaxRequest(url, data, successCallback, failureCallback) {
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                error: function (xhr) {
                    var status = xhr.status;
                    if (status == 405) {
                        warning('Method Not Allowed!');
                    } else if (status == 419) {
                        error('Session Expired');
                    } else {
                        error('Something Went Wrong! Error Status: ' + status);
                    }
                    if (failureCallback) {
                        failureCallback();
                    }
                },
                success: function (response) {
                    if (successCallback) {
                        successCallback(response);
                    }
                }
            });
        }

    $(document).ready(function() {
        let locationId = {{$stockin->business_location_id}};

        getPurchaseOrder({{$uniqueContactId}}, locationId);
        getSuppliers(locationId, {{$suppliers->id}});

    });

    function getSuppliers(locationId, supplierId){
        var url = '/stockin/filter-supplier';
        var data = { data: locationId };
        var successCallback = function (response) {
            var selectElement = $("#supplierOption");
            selectElement.empty();
            selectElement.append('<option></option>');

            response.forEach(function(supplier) {
                var selected = (supplier.id === supplierId) ? 'selected' : '';
                var option = $('<option></option>').attr('value', supplier.id).attr('selected', selected).text(supplier.company_name);
                selectElement.append(option);
            });
        };
        sendAjaxRequest(url, data, successCallback);
    }

    function getPurchaseOrder(contactId, locationId) {



        $.ajax({
            url:'/stockin/filter-purchase-order',
            type:'POST',
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                supplierVal: contactId,
                locationId: locationId
            },
            error: function (e){
                status = e.status;
                if (status == 405) {
                    warning('Method Not Allow!');
                } else if (status == 419) {
                    error('Session Expired')
                } else {
                    error(' Something Went Wrong! Error Status: ' + status)
                }
            },
            success: function (response){
                purchaseOrderList.length = 0;
                Array.prototype.push.apply(purchaseOrderList, response);
                if (tagify) {
                    tagify.settings.whitelist.length = 0;
                    Array.prototype.push.apply(tagify.settings.whitelist, purchaseOrderList);
                    tagify.removeAllTags();
                    tagify.dropdown.hide.call(tagify);
                } else {
                    loadTagify(response);
                }
            }
        })


    }

        function loadTagify(e){
            function tagTemplate(tagData) {
                return `
                      <tag title="${(tagData.value)}"
                           contenteditable='false'
                           spellcheck='false'
                           tabIndex="-1"
                           class="${this.settings.classNames.tag} ${tagData.class ? tagData.class : ""} ms-2"
                           ${this.getAttributes(tagData)}>
                        <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                        <div class="d-flex align-items-center">
                          <span class='tagify__tag-text'>${tagData.name}</span>
                        </div>
                      </tag>
                    `;
            }
            function suggestionItemTemplate(tagData) {
                return `
                  <div ${this.getAttributes(tagData)}
                       class='tagify__dropdown__item d-flex align-items-center ${tagData.class ? tagData.class : ""}'
                       tabindex="0"
                       role="option">
                    <div class="d-flex flex-column">
                      <strong>${tagData.name}</strong>
                    </div>
                  </div>
                `;
            }



            tagify = new Tagify(document.querySelector("#tagify_purchase_ordder"), {
                whitelist: e,
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: "",
                    enabled: 0,
                    closeOnSelect: false
                },
                templates: {
                    tag: tagTemplate,
                    dropdownItem: suggestionItemTemplate
                },
            });


            var tagsToAdd = e.filter(tag => purchaseIds.includes(tag.value)).map(tag => tag.value);
            tagify.addTags(tagsToAdd);

            purchaseIds.forEach(function(data) {
                sendAjaxRequest('/stockin/filter-purchase-order/data', { data: data }, function(response) {
                    response.forEach(function(item) {
                        var matchingTransaction = stockin_details.find(function(detail) {
                            return detail.transaction_detail_id === item.id;
                        });

                        if (matchingTransaction) {
                            // console.log(matchingTransaction);
                            item.stockin_detail_id = matchingTransaction.id;
                            item.stockin_quantity = matchingTransaction.quantity;
                            item.remark = matchingTransaction.remark;
                            append_row(item, unique_name_id, data);
                            unique_name_id += 1;
                        }
                    });
                }, function(xhr) {
                    var status = xhr.status;
                    if (status == 405) {
                        warning('Method Not Allowed!');
                    } else {
                        error('Something Went Wrong! Error Status: ' + status);
                    }
                });
            });



            tagify.on('add', function(e) {
                var selectedTag = e.detail.data.value;
                $.ajax({
                    url: '/stockin/filter-purchase-order/data',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { data: selectedTag }
                })
                    .done(function(response) {

                        response.forEach(item => {
                            append_row(item, unique_name_id, selectedTag);
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

            tagify.on('remove', function(e) {
                var removedTag = e.detail.data.value;
                removeRow(removedTag);
            });

        }


$("#supplierOption").change(function (){
    var supplierOptionVal = $(this).val();
    $('#stockin_table tbody tr.cal-gp').remove();
    unique_name_id = 1;
    $.ajax({
        url:'/stockin/filter-purchase-order',
        type:'POST',
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            data: supplierOptionVal
        },
        error: function (e){
            status = e.status;
            if (status == 405) {
                warning('Method Not Allow!');
            } else if (status == 419) {
                error('Session Expired')
            } else {
                error(' Something Went Wrong! Error Status: ' + status)
            }
        },
        success: function (e){
            purchaseOrderList.length = 0;
            Array.prototype.push.apply(purchaseOrderList, e);

            if (tagify) {
                tagify.settings.whitelist.length = 0;
                Array.prototype.push.apply(tagify.settings.whitelist, purchaseOrderList);
                tagify.removeAllTags();
                tagify.dropdown.hide.call(tagify);
            } else {
                initializeTagify();
            }


        }
    })
});
        function initializeTagify() {
            tagify = new Tagify(document.querySelector("#tagify_purchase_ordder"), {
                whitelist: purchaseOrderList,
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: "",
                    enabled: 0,
                    closeOnSelect: false
                },
                templates: {
                    tag: tagTemplate,
                    dropdownItem: suggestionItemTemplate
                },
            });


            tagify.on('add', function (e) {
                var selectedTag = e.detail.data.value;

                var url = '/stockin/filter-purchase-order/data';
                var data = { data: selectedTag };
                var successCallback = function (response) {

                    console.log(response);
                    response.forEach(function (item) {
                        append_row(item, unique_name_id, selectedTag);
                        unique_name_id += 1;
                    });
                };
                sendAjaxRequest(url, data, successCallback);
            });

            tagify.on('remove', function (e) {
                var removedTag = e.detail.data.value;
                removeRow(removedTag);
            });
        }

        function removeRow() {
            $('#stockin_table tbody tr.cal-gp').remove();
            unique_name_id = 1;
        }

        function removeRow(removedTag) {
            $('#stockin_table tbody tr.cal-gp').each(function () {
                var selectedTag = $(this).find('#selectedTag').val();
                if (selectedTag === removedTag) {
                    $(this).remove();
                }
            });
        }





        function append_row(item,unique_name_id,selectedTag) {
            console.log(item);
            let variation = item.product.product_type === 'variable' ? item.product_variation.variation_template_value.name : '';
            let orderedQuantity = Number(item.quantity).toFixed(2);
            let stockinQuantity = Number(item.stockin_quantity ?? 0.00).toFixed(2);
            let receivedQuantity = item.received_quantity == null ? '0.00' : Number(item.received_quantity).toFixed(2);
            let stockin_detail_row = item.stockin_detail_id ? `<input type="hidden" value="${item.stockin_detail_id}" name="stockin_details[${unique_name_id}][stockin_detail_id]">` : '';

            let newRow = `<tr class='cal-gp'>
            <td class="d-none">
                <span class='text-gray-800 mb-1'>${unique_name_id}</span>
                   ${stockin_detail_row}
                <input type="hidden" value="${selectedTag}" id="selectedTag" name="stockin_details[${unique_name_id}][purchase_id]">
                <input type="hidden" value="${item.id}" name="stockin_details[${unique_name_id}][purchase_detail_id]">
                <input type="hidden" value="${item.product_id}" name="stockin_details[${unique_name_id}][product_id]">
                <input type="hidden" value="${item.variation_id}" name="stockin_details[${unique_name_id}][variation_id]">
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
                <span class="received_quantity">${receivedQuantity}</span>
                <input type="hidden" name="stockin_details[${unique_name_id}][received_quantity]" value="${receivedQuantity}">
            </td>
            <td>
                   <input type="hidden" name="stockin_details[${unique_name_id}][old_qty]" value="${stockinQuantity}">
                 <input type="text" class="in_quantity form-control form-control-sm" name="stockin_details[${unique_name_id}][in_quantity]" value="${stockinQuantity}">
            </td>
            <td>
                <span class="text-gray-600">${item.purchase_uom.name}</span>
                <input type="hidden" value="${item.purchase_uom_id}" name="stockin_details[${unique_name_id}][purchase_uom_id]">
            </td>
            <td>
                <input type="text" name="stockin_details[${unique_name_id}][remark]" class="form-control form-control-sm" value="${item.remark ?? ''}">
            </td>
            <th class="text-center"><i class="fa-solid fa-trash text-danger deleteRow btn" ></i></th>
        </tr>`;


            $('#stockin_table tbody').append(newRow);
        }




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
            let demand_quantity = parent.find('.demand_quantity');
            let demand_quantity_text = parent.find('.demand_quantity_text');
            let received_quantity = parent.find('.received_quantity');
            let ordered_quantity_text = parent.find('.ordered_quantity_text');

            return {
                parent,
                demand_quantity,
                demand_quantity_text,
                ordered_quantity_text,
                received_quantity
            }
        }


        // $(document).on('input','.cal-gp input',function () {
        //     checkQty($(this));
        // })

        function checkQty(e) {
            const i = inputs(e);
            var demandQty = Number(i.demand_quantity.val());
            var receivedQty = Number(i.received_quantity.val());



            setTimeout(function() {
                var isQtyInvalid = receivedQty > demandQty;
                var isQtyNull = receivedQty === 0 || receivedQty == null;

                i.received_quantity.toggleClass('text-danger', isQtyInvalid || isQtyNull);
                i.ordered_quantity_text.toggleClass('text-danger', isQtyInvalid);
                $('.save-btn').prop('disabled', isQtyInvalid || isQtyNull);
            }, 600);

        }

    </script>
@endpush

