@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
<style>

</style>
@endsection


@section('reports_active', 'active')
@section('reports_active_show', 'active show')
@section('plReport_here_show', 'here show')
@section('plReport_active_show', 'active show')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Reports</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1 fs-5">
    <li class="breadcrumb-item text-dark">Report</li>
    <li class="breadcrumb-item text-dark">Profit / Loss</li>
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
                    <input type="text" class="form-control form-control-sm" id="datePicker" placeholder="Pick date rage" data-kt-date-filter="date" data-allow-clear="true">
                </div>
                <div class="col-8 text-end pe-10">
                    <button class="btn btn-sm btn-light-primary refresh" title="Refresh"><i class="fa-solid fa-rotate-right fs-3"></i></button>
                    <button class="btn btn-sm btn-light-danger btn-active-danger clearFilter">Clear Filter</button>
                </div>
            </div>
        </div>
        <div class="row align-items-stretch  align-self-stretch mb-5 g-5">
            <div class="col-12 col-lg-6">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600 ">
                        <i class="fa-solid fa-sack-dollar fs-6 me-2 text-primary"></i>
                        <span class="text-primary">
                            Gross Profit
                        </span>
                    </span>
                    <div class="fs-2hx mt-5  fw-bold ">
                        <i class="fa-solid fa-spinner fa-spin fs-2hx loader  "></i>
                        <span class="grossProfit"></span>
                        <div class=" fs-9 text-gray-500 fw-semibold mt-4">Total Sale Amount- Total Purchase Amount<br> &nbsp;</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600">
                        <i class="fa-solid fa-sack-dollar fs-6 me-2 text-success"></i>
                        <span class="text-success">Net Profit</span>
                    </span>
                    <div class="fs-2hx mt-5  fw-bold ">
                        <i class="fa-solid fa-spinner fa-spin fs-2hx loader  "></i>
                        <span class="netProfit"></span>
                        <div class=" fs-9 text-gray-500 fw-semibold mt-4">
                            (Total Sale Amount + Closing Stock Ammount + Total Other Incoming Amount) &minus;<br>(Total Purchase Amount + Total Opening Stock Amount+ Total Expense Amount)</div>
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
                                    <td class="fw-bold fs-7">Total Opening Stock Amount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tlOsAmount"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Purchase Amount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tlPAmount"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Expense Amount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tlExAmount"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-6">Total</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tlOutcome"></span>
                                    </td>
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
                                    <td class="fw-bold fs-7">Total Closing Stock Amount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tlCsAmount"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Sale Amount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tlSAmount"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-7">Total Other Income Amount</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tlOIAmount"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-6">Total</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tlIncome"></span>
                                    </td>
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
            let form_data=datePicker.data('daterangepicker').startDate.format('YYYY-MM-DD');
            let to_date=datePicker.data('daterangepicker').endDate.format('YYYY-MM-DD');
            getData({form_data,to_date})
        })
        $('.clearFilter').click(()=>{clearFilter()});
        $('.refresh').click(()=>{refresh()});
        function getData(data=''){
            $.ajax({
                url: `/report/pl/data`,
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
            $('.grossProfit').text('');
            $('.netProfit').text('');
            $('.grossProfit').text('');
            $('.netProfit').text('');
            $('.tlOsAmount').text('');
            $('.tlPAmount').text('');
            $('.tlExAmount').text('');
            $('.tlOutcome').text('');

            $('.tlCsAmount').text('');
            $('.tlSAmount').text('');
            $('.tlOIAmount').text('');
            $('.tlIncome').text('');

            $('.loader').removeClass('d-none');
        }
        function setData(data) {
            $('.loader').addClass('d-none');
            $('.grossProfit').text(data.grossProfit);
            $('.netProfit').text(data.netProfit);
            $('.tlOsAmount').text(data.tlOsAmount);
            $('.tlPAmount').text(data.tlPAmount);
            $('.tlExAmount').text(data.tlExAmount);
            $('.tlOutcome').text(data.tlOutcome);


            $('.tlCsAmount').text(data.tlCsAmount);
            $('.tlSAmount').text(data.tlSAmount);
            $('.tlOIAmount').text(data.tlOIAmount);
            $('.tlIncome').text(data.tlIncome);
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
