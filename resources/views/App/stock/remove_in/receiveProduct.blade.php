@extends('App.main.navBar')


@section('inventory_icon', 'active')
@section('inventroy_show', 'active show')
@section('stockin_here_show','here show')
@section('upcoming_stockin_active_show', 'active ')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Add Stockin</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Stockin</li>
        <li class="breadcrumb-item text-muted"><a href="{{route('stock-in.upcoming.index')}}">Upcoming List</a></li>
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
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-5 flex-wrap">
                                <div class="mb-7 mt-3 col-12 col-md-6">
                                    <div class="text-group">
                                        <h3 class="text-primary-emphasis fw-semibold fs-5 mb-5">
                                            <input type="text" class="d-none" name="receive_product" value="receive_form">
                                            Supplier Name : <span class="text-gray-600 fw-semibold">{{$received_data->supplier->company_name}}</span>
                                        </h3>
                                        <h3 class="text-primary-emphasis fw-semibold fs-5 mb-5">
                                            Purchase Voucher No : <span class="text-gray-600 fw-semibold">{{$received_data->purchase_voucher_no}}</span>
                                        </h3>

                                    </div>
                                </div>
                                <div class="mb-7 mt-3 col-12 col-md-6">
                                    <div class="text-group">
                                        <h3 class="text-primary-emphasis fw-semibold fs-5 mb-5">
                                            Status : <span class=" fw-semibold text-warning">{{$received_data->status}}</span>
                                        </h3>
                                        <h3 class="text-primary-emphasis fw-semibold fs-5 mb-5">
                                            Business Location : <span class="text-gray-600 fw-semibold">{{$received_data->businessLocation->name}}</span>
                                            <input type="text" class="d-none" name="business_location_id" value="{{$received_data->business_location_id}}">
                                        </h3>
                                    </div>
                                </div>
                                <!--begin::Input group-->
                                <div class="mb-7 mt-3 col-12 col-md-3">
                                    <label class="form-label fs-6 fw-semibold required">Stockin Person:</label>
                                    <div class="input-group flex-nowrap">
                                        <div class="input-group-text">
                                            <i class="fa-solid fa-user text-muted"></i>
                                        </div>
                                        <select name="stockin_person" class="form-select form-select-sm  fw-bold rounded-start-0"
                                                data-kt-select2="true" data-hide-search="false"
                                                data-placeholder="Select person" data-allow-clear="true"
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
                                        Stockin Date
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
            console.log(item);
            let variation = item.product.product_type === 'variable' ? item.product_variation.variation_template_value.name : '';
            let orderedQuantity = Number(item.quantity).toFixed(2);
            let demandQuantity = Number(item.quantity - item.received_quantity).toFixed(2);
            let receivedQuantity = item.received_quantity == null ? '0.00' : Number(item.received_quantity).toFixed(2);

            let newRow = `<tr class='cal-gp'>
            <td class="d-none">
                <span class='text-gray-800 mb-1'>${unique_name_id}</span>
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
                <span class="received_quantity_text">${receivedQuantity}</span>
                <input type="hidden" class="received_quantity" name="stockin_details[${unique_name_id}][received_quantity]" value="${receivedQuantity}">
            </td>

            <td>
                <input type="text" class="in_quantity form-control form-control-sm" name="stockin_details[${unique_name_id}][in_quantity]" value="0.00">

            </td>

            <td>
                <span class="text-gray-600">${item.purchase_uom.name}</span>
                <input type="hidden" value="${item.purchase_uom_id}" name="stockin_details[${unique_name_id}][purchase_uom_id]">
            </td>
            <td>
                <input type="text" name="stockin_details[${unique_name_id}][remark]" class="form-control form-control-sm">
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

