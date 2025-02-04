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
            <div class="card-title px-5 py-2 d-flex justify-content-between align-items-center border border-1 border-gray-200 border-top-0 border-left-0 border-right-0">
                <div class="d-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-filter fs-2 me-3 text-gray-400"></i>
                    <h4 class="reportLabel fs-2 ">Today</h4><h4 class="fs-2 text-gray-600"> &nbsp;Report</h4>
                </div>
                <div class="">
                    <button class="btn btn-sm btn-light-primary refresh" title="Refresh"><i
                            class="fa-solid fa-rotate-right fs-3"></i></button>
                    <button class="btn btn-sm btn-success  clearFilter">OverAll</button>
                </div>
            </div>
            <div class="card-body px-0 py-2 ps-5 row justify-content-between">
                <div class="row d-flex justify-content-start align-items-center gap-2">
                    <div class="col-lg-3 col-md-5 col-12">
                        <input type="text" class="form-control form-control-sm" id="datePicker" value="" placeholder="Pick date rage"
                            data-kt-date-filter="date" data-allow-clear="true" autocomplete="off">
                    </div>
                    <div class="col-lg-3 col-md-5 col-12">
                        <select name="" id="priceCalMethod" class="form-select form-select-sm" data-control="select2" placeholder="Price Cal"
                            data-placeholder="Price Cal" data-hide-search="true">
                            <option value="cogs">Cogs</option>
                            {{-- <option value="avg">Average</option> --}}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-stretch  align-self-stretch mb-5 g-5 ">
            <div class="col-12 col-lg-6">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600 ">
                        <i class="fa-solid fa-sack-dollar fs-6 me-2 text-primary"></i>
                        <span class="text-primary">
                            Gross Profit
                        </span>
                    </span>
                    <div class="fs-2hx mt-5  fw-bold mb-3">
                        <i class="fa-solid fa-spinner fa-spin fs-2hx loader  "></i>
                        <span class="grossProfit"></span>
                        <div class=" fs-9 text-gray-500 fw-semibold mt-4">Total Sale Amount- Total COGS Amount &nbsp;</div>
                    </div>
                    <div class="">
                        <div class="table-responsive">
                            <table class="table table-hover table-rounded table-striped  gy-3 gs-3">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold fs-7">Total Sale Amount</td>
                                        <td class="text-end fw-bold ">
                                            <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                            <span class="tlSAmount"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold fs-7">COGS (-) </td>
                                        <td class="text-end fw-bold ">
                                            <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                            <span class="cogs"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold fs-7 text-primary">Gross Profit (=)</td>
                                        <td class="text-end fw-bold ">
                                            <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                           <span class="grossProfit"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 ">
                <div class="card py-4 px-5 bg- wallet">
                    <span class="text-start fw-bold mt-3 text-gray-600">
                        <i class="fa-solid fa-sack-dollar fs-6 me-2 text-success"></i>
                        <span class="text-success">Net Profit</span>
                    </span>
                    <div class="fs-2hx mt-5  fw-bold mb-3 ">
                        <i class="fa-solid fa-spinner fa-spin fs-2hx loader  "></i>
                        <span class="netProfit"></span>
                        <div class=" fs-9 text-gray-500 fw-semibold mt-4">(Gross Profit -Total Expense)</div>
                    </div>
                    <div class="">
                        <div class="table-responsive">
                            <table class="table table-hover table-rounded table-striped  gy-3 gs-3">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold fs-7">Gross Profit</td>
                                        <td class="text-end fw-bold ">
                                            <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                            <span class="grossProfit"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold fs-7">Total Expense Amount (-) </td>
                                        <td class="text-end fw-bold ">
                                            <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                            <span class="tlExAmount"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold fs-7 text-success">Net Profit (=)</td>
                                        <td class="text-end fw-bold ">
                                            <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                            <span class="netProfit"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                                    <td class="fw-bold fs-7">Total Opening Stock Amount <br> ( Inc: Opening Stock Transactions)</td>
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
                                    <td class="fw-bold fs-7">Total Closing Stock Amount <br><br></td>
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
                                {{-- <tr>
                                    <td class="fw-bold fs-6">Total</td>
                                    <td class="text-end fw-bold ">
                                        <i class="fa-solid fa-ellipsis fa-fade fs-2 loader"></i>
                                        <span class="tlIncome"></span>
                                    </td>
                                </tr> --}}
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
        // getData({from_date,to_date,priceCalMethod});
        // $("#datePicker").data('daterangepicker').setStartDate(start);
        // $("#datePicker").data('daterangepicker').setEndDate(end);
        function cb(start, end,label) {
            $("#datePicker").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
            $('.reportLabel').text(label);
        }

        let datePicker=$("#datePicker").daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                "Today": [moment(), moment()],
                "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                // "The Day Before": [moment().subtract(2, "days"), moment().subtract(1, "days")],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [moment().startOf("month"), moment().endOf("month")],
                "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
            }
        }, cb);

        var start = moment().subtract(1, "M");
        var end = moment();
        cb(start,end);
        let from_date=datePicker.data('daterangepicker').startDate.format('YYYY-MM-DD');
        let to_date=datePicker.data('daterangepicker').endDate.format('YYYY-MM-DD');
        let priceCalMethod= $('#priceCalMethod').val() ;
        getData({from_date,to_date,priceCalMethod})

        $('#datePicker').change(function(){
            intiDataSearch();
            let from_date=datePicker.data('daterangepicker').startDate.format('YYYY-MM-DD');
            let to_date=datePicker.data('daterangepicker').endDate.format('YYYY-MM-DD');
            let priceCalMethod= $('#priceCalMethod').val() ;
            getData({from_date,to_date,priceCalMethod})
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
            $('.cogs').text('');

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
            $('.cogs').text(data.cogs);


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
            $('.reportLabel').text('Overall');
        }

        function refresh() {
            intiDataSearch();
            let priceCalMethod= $('#priceCalMethod').val() ;
            getData();
            clearFilter();
        }
    })
</script>
@endpush
