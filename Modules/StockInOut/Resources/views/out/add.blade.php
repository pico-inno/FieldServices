@extends('App.main.navBar')


@section('inventory_icon', 'active')
@section('inventroy_show', 'active show')
@section('stockout_here_show','here show')
@section('stockout_add_active_show', 'active ')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{ucfirst(__('stockinout::stockout.create'))}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('stockinout::stockout.stockout')}}</li>
        <li class="breadcrumb-item text-muted"><a href="{{route('stock-out.index')}}">{{__('stockinout::stockout.list')}}</a></li>
        <li class="breadcrumb-item text-dark">{{__('stockinout::stockout.add')}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    {{--    <link rel="stylesheet" href={{asset("customCss/bussingessSetting.css")}}>--}}
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
@endsection



@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl" id="kt_content_container">
            <form action="{{route('stock-out.store')}}" method="POST">
                @csrf
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
                    <div class="col-12 my-5 mb-2 input-group flex-nowrap">
                        <div class="input-group-text"><i class="fa-solid fa-location-dot"></i></div>
                        <select name="business_location_id" id="business_location_id" class="form-select rounded-0" data-kt-select2="true"  data-placeholder="{{__('stockinout::stockout.placeholder_location')}}">
                            <option selected></option>
                            @foreach ($locations as $location)
                                <option @selected($location->id == old('business_location_id')) value="{{$location->id}}">{{$location->name}}</option>
                            @endforeach
                        </select>
                        <button type="button" class="input-group-text "  data-bs-toggle="tooltip" data-bs-custom-class="tooltip" data-bs-placement="top" data-bs-html="true" title="<span class='text-primary-emphasis'>{{__('stockinout::stockout.location_usage')}}</span>">
                            <i class="fa-solid fa-circle-info text-primary"></i>
                        </button>
                    </div>
                    <div class="card border border-primary-subtle border-top-2 border-left-0 border-right-0 border-bottom-0">
                        <div class="card-body">
                            <div class="row mb-5 flex-wrap">

                                <div class="mb-7 mt-3 col-12 col-md-4">
                                    <label class="form-label fs-6 fw-semibold" for="">
                                        {{__('stockinout::stockout.customer')}}
                                    </label>
                                    <div class="input-group flex-nowrap">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-user text-muted"></i>
                                        </div>
                                        <select name="customers" class="form-select form-select-sm  fw-bold rounded-start-0" id="customerOption" data-kt-select2="true"
                                                data-hide-search="false" data-placeholder="{{__('stockinout::stockout.placeholder_customer')}}"
                                                data-allow-clear="true" data-kt-user-table-filter="role"
                                                data-hide-search="true">
                                            <option selected></option>
{{--                                            @foreach($customers as $customer)--}}
{{--                                                <option @selected($customer->id == old('customers')) value="{{$customer->id}}">{{$customer->first_name}}</option>--}}
{{--                                            @endforeach--}}
                                        </select>
                                    </div>
                                    @error('customers')
                                    <p class="alert text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
{{--                                <div class="mb-7 mt-3 col-12 col-md-3">--}}
{{--                                    <label class="form-label fs-6 fw-semibold required" for="">--}}
{{--                                        Bussiness Location--}}
{{--                                    </label>--}}
{{--                                    <div class="input-group flex-nowrap">--}}
{{--                                        <select name="business_location_id" class="form-select form-select-sm fw-bold "--}}
{{--                                                data-kt-select2="true" data-hide-search="false"--}}
{{--                                                data-placeholder="Select Location" data-allow-clear="true"--}}
{{--                                                data-kt-user-table-filter="role" data-hide-search="true">--}}
{{--                                            <option></option>--}}
{{--                                            @foreach ($locations as $location)--}}
{{--                                                <option @selected($location->id == old('business_location_id')) value="{{$location->id}}">{{$location->name}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                    @error('business_location_id')--}}
{{--                                    <p class="alert text-danger">{{ $message }}</p>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
                                <!--begin::Input group-->
                                <div class="mb-7 mt-3 col-12 col-md-4">
                                    <label class="form-label fs-6 fw-semibold required">{{__('stockinout::stockout.stockout_person')}}</label>
                                    <div class="input-group flex-nowrap">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-user text-muted"></i>
                                        </div>
                                        <select name="stockout_person" class="form-select form-select-sm fw-bold rounded-start-0"
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="{{__('stockinout::stockout.placeholder_person')}}" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach($stockout_persons as $stockout_person)
                                                <option @selected($stockout_person->id == (old('stockout_person') || \Illuminate\Support\Facades\Auth::id()))
                                                    value="{{$stockout_person->id}}">{{$stockout_person->username}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('stockout_person')
                                    <p class="alert text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                                <div class="mb-7 mt-3 col-12 col-md-4">
                                    <label class="form-label fs-6 fw-semibold required" for="purchaseDatee">
                                        {{__('stockinout::stockout.date')}}
                                    </label>
                                    <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                        <input class="form-control form-control-sm" name="stockout_date" placeholder="Pick a date"
                                               data-td-toggle="datetimepicker" id="kt_datepicker_1"
                                               value="{{date('Y-m-d')}}"/>
                                    </div>
                                </div>

                                <div class="mb-7 mt-3 col-12 col-md-6">
                                    <label class="form-label fs-6">
                                        {{__('stockinout::stockout.sale_order')}}
                                    </label>
                                    <div class="input-group flex-nowrap">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-receipt"></i>
                                        </div>
                                        <input name="sale_voucher_no" class="form-control form-control-sm d-flex align-items-center rounded-end-2" placeholder="{{__('stockinout::stockout.placeholder_sale_order')}}" value="" id="tagify_purchase_ordder" />

                                    </div>
                                    @error('sale_voucher_no')
                                    <p class="alert text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-7 mt-3 col-12 col-md-6">
                                    <label class="form-label fs-6">
                                        {{__('stockinout::stockout.note')}}
                                    </label>
                                    <textarea class="form-control form-control-sm" name="note" id="" cols="30" rows="1"></textarea>
                                </div>
{{--                                <div class="col-12 mb-7">--}}
{{--                         --}}
{{--                                </div>--}}
                            </div>

                            <div class="d-flex justify-content-center align-items-center my-10">
                                <div class="col-6 col-md-1 fs-4 fw-light  text-primary-emphasis">
                                   {{__('stockinout::stockout.products')}}
                                </div>
                                <div class="separator   border-primary-subtle col-md-11 col-6"></div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mt-6" id="stockout_table">
                                    <!--begin::Table head-->
                                    <thead class="">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0 ">
                                        {{--                                        <th class="min-w-35px">#</th>--}}

                                        <th class="min-w-200px">{{__('stockinout::stockout.product')}}</th>
                                        <th class="min-w-100px">{{__('stockinout::stockout.ordered')}}</th>
                                        <th class="min-w-100px">{{__('stockinout::stockout.delivered')}}</th>
                                        <th class="min-w-100px">{{__('stockinout::stockout.out')}}</th>
                                        <th class="min-w-125px">{{__('stockinout::stockout.uom')}}</th>
                                        <th class="min-w-200px">{{__('stockinout::stockout.remark')}}</th>
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
                        <button type="submit" class="btn btn-primary btn-lg save_btn">{{__('stockinout::stockout.save')}}</button>
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

        var inputElm = document.querySelector('#tagify_purchase_ordder');
        let unique_name_id=1;
        const saleOrderList = [];
        var tagify;


        $("#business_location_id").change(function (){
            var locationId = $(this).val();

            removeAllRow();
            tagify.removeAllTags();
            $.ajax({
                url:'/stockout/filter-customer',
                type:'POST',
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    data: locationId
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
                    console.log(e);
                    var selectElement = $("#customerOption");

                    selectElement.empty();

                    selectElement.append('<option selected></option>');
                    e.forEach(function(customer) {
                        selectElement.append('<option value="' + customer.id + '">' + customer.first_name + '</option>');
                    });


                }
            })

        });


        $("#customerOption").change(function (){
            var customerOptionVal = $(this).val();
            removeAllRow();
            $.ajax({
                url:'/stockout/filter-sale-order',
                type:'POST',
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    data: customerOptionVal
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
                    console.log(e);
                    saleOrderList.length = 0;
                    Array.prototype.push.apply(saleOrderList, e);

                    if (tagify) {
                        tagify.settings.whitelist.length = 0;
                        Array.prototype.push.apply(tagify.settings.whitelist, saleOrderList);
                        tagify.removeAllTags();
                        tagify.dropdown.hide.call(tagify);
                    } else {
                        initializeTagify();
                    }


                }
            })
        });


        function initializeTagify() {
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

            if (tagify) {
                tagify.destroy();
                removeAllRow();
            }

            tagify = new Tagify(inputElm, {
                tagTextProp: 'name',
                enforceWhitelist: true,
                skipInvalid: true,
                dropdown: {
                    closeOnSelect: true,
                    enabled: 0,
                    classname: 'users-list',
                    searchKeys: ['name']
                },
                templates: {
                    tag: tagTemplate,
                    dropdownItem: suggestionItemTemplate
                },
                whitelist: saleOrderList
            });

            tagify.on('add', function(e) {
                var selectedTag = e.detail.data.value;
                $.ajax({
                    url: 'stockout/filter-sale-order/data',
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

        initializeTagify();

        function removeAllRow() {
            $('#stockout_table tbody tr.cal-gp').remove();
            unique_name_id = 1;
        }

        function removeRow(removedTag) {
            $('#stockout_table tbody tr.cal-gp').each(function() {
                var selectedTag = $(this).find('#selectedTag').val();
                if (selectedTag === removedTag) {
                    $(this).remove();
                }
            });
        }

        function append_row(item,unique_name_id,selectedTag) {
            console.log(item);
            let variation = item.product.product_type === 'variable' ? item.product_variation.variation_template_value.name : '';
            let demandQuantity = Number(item.quantity).toFixed(2);
            let deliveredQuantity = item.delivered_quantity == null ? '0.00' : Number(item.delivered_quantity).toFixed(2);
            // let reserveQty = Number(demandQuantity - deliveredQuantity).toFixed(2);

            let newRow = `<tr class='cal-gp'>
            <td class="d-none">
                <span class='text-gray-800 mb-1'>${unique_name_id}</span>
                <input type="hidden" value="${selectedTag}" id="selectedTag" name="stockout_details[${unique_name_id}][sale_id]">
                <input type="hidden" value="${item.id}" name="stockout_details[${unique_name_id}][sale_detail_id]">
                <input type="hidden" value="${item.product_id}" name="stockout_details[${unique_name_id}][product_id]">
                <input type="hidden" value="${item.variation_id}" name="stockout_details[${unique_name_id}][variation_id]">

            </td>
            <td class="ps-0">
                <span class="text-gray-600 mb-1 ">${item.product.name}</><br>
                <span class="text-gray-500 fw-semibold fs-5">${variation}</span>
            </td>
            <td>
                <span class="demand_quantity_text">${demandQuantity}</span>
                <input type="hidden" class="demand_quantity" name="stockout_details[${unique_name_id}][demand_quantity]" value="${demandQuantity}">
            </td>

            <td>
                <span class="delivered_quantity_text">${deliveredQuantity}</span>
                <input type="hidden" class="delivered_quantity" name="stockout_details[${unique_name_id}][delivered_quantity]" value="${deliveredQuantity}">
            </td>
            <td>
                <input type="text" class="out_quantity form-control form-control-sm" name="stockout_details[${unique_name_id}][out_quantity]" value="0.00">
            </td>
            <td>
                <span class="text-gray-600">${item.uom.name}</span>
                <input type="hidden" value="${item.uom_id}" name="stockout_details[${unique_name_id}][uom_id]">
            </td>
            <td>
                <input type="text" name="stockout_details[${unique_name_id}][remark]" class="form-control form-control-sm">
            </td>
            <th class="text-center"><i class="fa-solid fa-trash text-danger deleteRow btn" ></i></th>
        </tr>`;


            $('#stockout_table tbody').append(newRow);
        }

        $(document).on('click', '#stockout_table .deleteRow', function (e) {
            console.log('work');
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
            let demand_quantity_text = parent.find('.demand_quantity_text');
            let demand_quantity = parent.find('.demand_quantity');
            let delivered_quantity_text = parent.find('.delivered_quantity_text');
            let delivered_quantity = parent.find('.delivered_quantity');
            let out_quantity = parent.find('.out_quantity');

            return {
                parent,
                demand_quantity_text,
                demand_quantity,
                delivered_quantity_text,
                delivered_quantity,
                out_quantity

            }
        }


        $(document).on('input','.cal-gp input',function () {
            checkQty($(this));
        })


        function checkQty(e) {
            const i = inputs(e);
            var demandQty = Number(i.demand_quantity.val());
            var deliveredQty = Number(i.delivered_quantity.val());
            var outQty = Number(i.out_quantity.val());

            var outAbleQty = demandQty - deliveredQty;

            setTimeout(function() {
                var isQtyInvalid = outQty > outAbleQty;
                var isQtyNull = outQty === 0 || outQty == null;
                i.out_quantity.toggleClass('text-danger', isQtyInvalid || isQtyNull);
                i.demand_quantity_text.toggleClass('text-danger', isQtyInvalid);
                i.delivered_quantity_text.toggleClass('text-danger', isQtyInvalid);
                $('.save_btn').prop('disabled', isQtyInvalid || isQtyNull);
            }, 600)


        }






    </script>

@endpush

