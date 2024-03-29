
@extends('App.main.navBar')

@section('reports_active', 'active')
@section('reports_active_show', 'active show')
@section('os_reports_here_show', 'here show')
@section('os_summary_active_show', 'active show')


@section('styles')
<style>
    #purchase_table_card .table-responsive {
        min-height: 60vh;
    }
    .pagination{
    justify-content: center !important;
    }
    @media(min-width:780px){
    .pagination{
    justify-content: end !important;
    }
    }
</style>
@endsection



@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-2">{{__('os/os.opening_stock')}}</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">{{__('report.reports')}}</li>
    <li class="breadcrumb-item text-muted">{{__('os/os.opening_stock')}}</li>
    <li class="breadcrumb-item text-dark">{{__('report/report.opening_stock_summary')}}</li>
</ul>
<!--end::Breadcrumb-->
@endsection
@section('content')


<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="kt_content_container">
        <livewire:OpeningStock.report.summary  />
    </div>
    <!--end::Container-->
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush
