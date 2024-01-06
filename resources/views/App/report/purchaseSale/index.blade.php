@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
<style>

</style>
@endsection


@section('reports_active', 'active')
@section('reports_active_show', 'active show')
@section('spReport_here_show', 'here show')
@section('spReport_active_show', 'active show')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Reports</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1 fs-5">
    <li class="breadcrumb-item text-dark">Report</li>
    <li class="breadcrumb-item text-dark">Purchase & Sale</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->

    <div class="container-xxl" id="kt_content_container">
        <div class="card p-0 mb-5">
            <div class="card-body px-0 py-2 ps-5 row justify-content-between">
                <div class="col-4 d-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-filter fs-2 me-3 text-gray-400"></i>
                    <input type="text" class="form-control form-control-sm" id="datePicker" placeholder="Pick date rage"
                        data-kt-date-filter="date" data-allow-clear="true">
                </div>
                <div class="col-8 text-end pe-10">
                    <button class="btn btn-sm btn-light-primary refresh" title="Refresh"><i class="fa-solid fa-rotate-right fs-3"></i></button>
                    <button class="btn btn-sm btn-light-danger btn-active-danger clearFilter">Clear Filter</button>
                </div>
            </div>
        </div>
        <div class="row align-items-stretch  align-self-stretch mb-5 g-5">
            <div class="col-12 col-lg-4">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600">
                        <i class="fa-solid fa-cart-shopping fs-6 me-2 text-success"></i>
                        <span class="text-success">Purchase</span>
                    </span>
                    <div class="fs-2hx mt-1  fw-bold ">
                        <div class="fs-6 text-gray-500 fw-semibold  mt-2">Total Purchase Amount</div>
                        <i class="fa-solid fa-spinner fa-spin fs-2hx loader  "></i>
                        <span class="tpa"></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600 ">
                        {{-- <i class="fa-solid fa-shop fs-6 me-2 text-primary"></i> --}}
                        <i class="fa-solid fa-square-minus fs-6 me-2 text-info"></i>
                        {{-- <i class="las la-wallet  fs-2 me-2"></i> --}}
                        <span class="text-info">
                           (Sale - Purchase) Amount
                        </span>
                    </span>
                    <div class="fs-2hx mt-1  fw-bold ">
                        <div class="fs-6 text-gray-500 fw-semibold mt-2">&nbsp;</div>
                        <i class="fa-solid fa-spinner fa-spin fs-2hx loader  "></i>
                        <span class="diffTSPA"></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600 ">
                        <i class="fa-solid fa-shop fs-6 me-2 text-primary"></i>
                        {{-- <i class="las la-wallet  fs-2 me-2"></i> --}}
                        <span class="text-primary">
                            Sale
                        </span>
                    </span>
                    <div class="fs-2hx mt-1  fw-bold ">
                        <div class="fs-6 text-gray-500 fw-semibold mt-2">Total Sale Amount</div>
                        <i class="fa-solid fa-spinner fa-spin fs-2hx loader  "></i>
                        <span class="tsa"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-stretch align-self-stretch mb-10 g-5">
            <div class="col-12 col-lg-6">
                <div class="card py-4 px-5 ">
                    <div class="table-responsive">
                        <table class="table table-hover table-rounded table-striped  gy-3 gs-3">
                            <tbody>
                                <tr>
                                    <td class="fw-bold fs-7">Total Purchase Amount Without Discount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tpwd"></span>
                                    </td>
                                    {{-- <td class="text-end fw-bold">{{price(totalPurchaseAmountWithoutDis())}}</td> --}}
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-6">Total Purchase Discount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tpd"></span>
                                    </td>
                                    {{-- <td class="text-end fw-bold">{{price(totalPurchaseDiscountAmt())}}</td> --}}
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-6">Total Purchase Amount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tpa"></span>
                                    </td>
                                    {{-- <td class="text-end fw-bold">{{price(totalPurchaseAmount())}}</td> --}}
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Purchase Due Amount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tpda"></span>
                                    </td>
                                    {{-- <td class="text-end fw-bold">{{price(totalPurchaseDueAmount())}}</td> --}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card py-4 px-5 bg- wallet">
                    <div class="table-responsive">
                        <table class="table table-hover table-rounded table-striped  gy-3 gs-3">
                            <tbody>
                                <tr>
                                    <td class="fw-bold fs-7">Total Sale Amount Without Discount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tswd"></span>
                                    </td>
                                    {{-- <td class="text-end fw-bold">{{price(totalSaleAmountWithoutDis())}}</td> --}}
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Sale Discount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tsd"></span>
                                    </td>
                                    {{-- <td class="text-end fw-bold">{{price(totalSaleDiscount())}}</td> --}}
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-6">Total Sale Amount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tsa"></span>
                                    </td>
                                    {{-- <td class="text-end fw-bold">{{price( totalSaleAmount() )}}</td> --}}
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Sale Due Amount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tsda"></span>
                                    </td>
                                    {{-- <td class="text-end fw-bold">{{price(totalSaleDueAmount())}}</td> --}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!--end::container-->
</div>
<!--end::Content-->




@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        getData();
        var start = moment().subtract(1, "M");
        var end = moment();
        function cb(start, end) {
            $("#datePicker").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));

        }

        let datePicker=$("#datePicker").daterangepicker({
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
        }, cb).val('');

        $('#datePicker').change(function(){
            intiDataSearch();
            let from_date=datePicker.data('daterangepicker').startDate.format('YYYY-MM-DD');
            let to_date=datePicker.data('daterangepicker').endDate.format('YYYY-MM-DD');
            getData({from_date,to_date})
        })
        $('.clearFilter').click(()=>{clearFilter()});
        $('.refresh').click(()=>{refresh()});
        function getData(data=''){
            $.ajax({
                url: `/report/sale-purchase/data`,
                data,
                type: 'GET',
                error:function(e){
                    status=e.status;
                    if(status==405){
                        warning('Method Not Allow!');
                    }else if(status==419){
                        error('Session Expired')
                    }else{
                        console.log(' Something Went Wrong! Error Status: '+status )
                    };
                },
                success: function(results){
                    setData(results);
                }
            })
        }
        function intiDataSearch(params) {
            $('.tsa').text('');
            $('.tpa').text('');
            $('.diffTSPA').text('');

            $('.tpwd').text('');
            $('.tpd').text('');
            $('.tpda').text('');

            $('.tswd').text('');
            $('.tsd').text('');
            $('.tsda').text('');

            $('.tlCsAmount').text('');
            $('.tlSAmount').text('');
            $('.tlOIAmount').text('');
            $('.tlIncome').text('');

            $('.loader').removeClass('d-none');
        }
        function setData(data) {
            $('.loader').addClass('d-none');
            $('.tsa').text(data.tsa);
            $('.tpa').text(data.tpa);
            $('.diffTSPA').text(data.diffTSPA);

            $('.tpwd').text(data.tpwd);
            $('.tpd').text(data.tpd);
            $('.tpda').text(data.tpda);

            $('.tswd').text(data.tswd);
            $('.tsd').text(data.tsd);
            $('.tsda').text(data.tsda);

        }
        function clearFilter(){
            $("#datePicker").val('');
            $("#datePicker").next('input').val('');
            intiDataSearch();
            getData();
        }
        function refresh() {
            intiDataSearch();
            getData();
        }
    })
</script>
@endpush
