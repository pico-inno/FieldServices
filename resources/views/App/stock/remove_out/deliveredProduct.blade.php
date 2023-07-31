@extends('App.main.navBar')


@section('inventory_icon', 'active')
@section('inventroy_show', 'active show')
@section('stockout_here_show','here show')
@section('outgoing_stockout_active_show', 'active ')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Delivered Product</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Stockin</li>
        <li class="breadcrumb-item text-muted"><a href="{{route('stock-out.outgoing.index')}}">Outgoing List</a></li>
        <li class="breadcrumb-item text-dark">{{$dispensed_data->sales_voucher_no}}</li>
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
            <form action="{{route('stock-out.store')}}" method="POST">
                @csrf
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-5 flex-wrap">
                                <div class="mb-7 mt-3 col-12 col-md-6">
                                    <div class="text-group">
                                        <h3 class="text-primary-emphasis fw-semibold fs-5 mb-5">
                                            <input type="text" class="d-none" name="deliver_product" value="deliver_form">
                                            Supplier Name : <span class="text-gray-600 fw-semibold">{{$dispensed_data->customer->first_name}}</span>
                                            <input type="text" class="d-none" name="customers" value="{{$dispensed_data->customer->id}}">
                                        </h3>
                                        <h3 class="text-primary-emphasis fw-semibold fs-5 mb-5">
                                            Purchase Voucher No : <span class="text-gray-600 fw-semibold">{{$dispensed_data->sales_voucher_no}}</span>
                                            <input type="text" class="d-none" name="sale_voucher_no" value="{{$dispensed_data->sales_voucher_no}}">
                                        </h3>

                                    </div>
                                </div>
                                <div class="mb-7 mt-3 col-12 col-md-6">
                                    <div class="text-group">
                                        <h3 class="text-primary-emphasis fw-semibold fs-5 mb-5">
                                            Status : <span class=" fw-semibold text-warning">{{$dispensed_data->status}}</span>
                                        </h3>
                                        <h3 class="text-primary-emphasis fw-semibold fs-5 mb-5">
                                            Business Location : <span class="text-gray-600 fw-semibold">{{$dispensed_data->businessLocation->name}}</span>
                                            <input type="text" class="d-none" name="business_location_id" value="{{$dispensed_data->business_location_id}}">
                                        </h3>
                                    </div>
                                </div>
                                <!--begin::Input group-->
                                <div class="mb-7 mt-3 col-12 col-md-3">
                                    <label class="form-label fs-6 fw-semibold required">Stockout Person:</label>
                                    <div class="input-group flex-nowrap">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-user text-muted"></i>
                                        </div>
                                        <select name="stockout_person" class="form-select form-select-sm  fw-bold rounded-start-0"
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="Select person" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach($stockin_persons as $stockin_person)
                                                <option @selected(\Illuminate\Support\Facades\Auth::id() == $stockin_person->id)
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
                                        Stockout Date
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
                                <!--end::Input group-->


                                <div class="col-12 mb-7">
                                    <label class="form-label fs-6">
                                        Note
                                    </label>
                                    <textarea class="form-control form-control-sm" name="note" id="" cols="30" rows="4"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mt-6" id="stockout_table">
                                    <!--begin::Table head-->
                                    <thead class="">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0 ">
                                        {{--                                        <th class="min-w-35px">#</th>--}}
                                        <th class="min-w-200px">Product</th>
                                        <th class="min-w-100px">Ordered</th>
{{--                                        <th class="min-w-100px">Reserve</th>--}}
                                        <th class="min-w-100px">Delivered</th>
                                        <th class="min-w-100px">Out</th>
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
                        <button type="submit" class="btn btn-primary btn-lg save_btn">Save</button>
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

        $(document).ready(function() {
            var detailID = {{$dispensed_data->id}};
            $.ajax({
                url: '/stockout/filter-sale-order/data',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { data: detailID }
            })
                .done(function(response) {
console.log(response);
                    response.forEach(item => {
                        append_row(item, unique_name_id, detailID);
                        $('select[name="business_location_id"]').val(item.business_location_id).trigger('change');
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


        function append_row(item,unique_name_id,selectedTag) {
            let variation = item.product.product_type === 'variable' ? item.product_variation.variation_template_value.name : '';
            let demandQuantity = Number(item.quantity).toFixed(2);
            let deliveredQuantity = item.delivered_quantity == null ? '0.00' : Number(item.delivered_quantity).toFixed(2);
            let outableQty = Number(demandQuantity - deliveredQuantity).toFixed(2);


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




    {{--    @include('App.stock.in.include.quickSearchProduct')--}}
@endpush

