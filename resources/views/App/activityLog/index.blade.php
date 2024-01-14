@extends('App.main.navBar')

@section('setting_active','active')
@section('setting_active_show','active show')
@section('activity_logs_list_active','active')
{{--@section('business_settings_nav','active')--}}

@section('styles')

@endsection


@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Activity Logs</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('business_settings.settings')}}</li>
        <li class="breadcrumb-item text-dark">Logs</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::Container-->
    <div class="container-fluid " id="kt_content_container">
        <!--begin::Login sessions-->
        <div class="card mb-5 mb-lg-10">

            <div class="d-flex  flex-wrap flex-sm-nowrap col-12 pt-7 my-3 mx-5">
                <div class="col-sm-2 col-6 me-sm-5 mb-3 mb-sm-0 ms-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Search Logs" data-filter="input">
                </div>
                <div class="col-sm-2 col-6 me-3">
                    <select id="logTypeFilter" class="form-select form-select-sm" data-control="select2" data-placeholder="Filter Log Type" data-filter="logType" data-allow-clear="true">
                        <option value="all" selected>All log type</option>
                        @foreach($uniqueLogNames as $uniqueLogName)
                            <option value="{{$uniqueLogName}}">{{$uniqueLogName}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-2 col-6">
                    <select id="logByFilter" class="form-select form-select-sm" data-control="select2" data-placeholder="Filter log by" data-filter="logBy" data-allow-clear="true">
                        <option value="all" selected>All users</option>
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->username}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!--begin::Card body-->
            <div class="card-body p-5">
                <!--begin::Table wrapper-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9" id="login_logs_table">
                        <!--begin::Thead-->
                        <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                        <tr>
                            <th class="min-w-150px">Timestamp</th>
                            <th class="min-w-100px">Log Type</th>
                            <th class="min-w-275px">Description</th>
                            <th class="min-w-95px">Event</th>
                            <th class="min-w-95px">Status</th>
                            <th class="min-w-120px">By</th>
                        </tr>
                        </thead>
                        <!--end::Thead-->
                        <!--begin::Tbody-->
                        <tbody class="fw-6 fw-semibold text-gray-600">
                        </tbody>
                        <!--end::Tbody-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Table wrapper-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Login sessions-->
    </div>
    <!--end::Container-->
@endsection

@push('scripts')
    <script>
        "use strict";
        var KTCustomersList = function () {

            var datatable;
            var table

            var initLogsList = function () {

                // Init datatable --- more info on datatables: https://datatables.net/manual/
                datatable = $(table).DataTable({

                    order: [[0, ' ']],
                    processing: true,
                    pageLength: 10,
                    lengthMenu: [10, 20, 30, 50,40,80],
                    serverSide: true,
                    ajax: {
                        url: '/logs/all',
                    },
                    columns: [

                        { data: 'created_at' },
                        { data: 'log_name' },
                        { data: 'description' },
                        { data: 'event' },
                        { data: 'status' },
                        { data: 'created_user' },
                        { data: 'created_by' },

                    ],
                    columnDefs: [
                        {
                            targets: 6,
                            visible: false,
                            searchable: true
                        },
                        {
                            targets: 0,
                            render: function (data, type, row) {
                                if (type === 'display' || type === 'filter') {
                                    const dateTime = new Date(data);
                                    const formattedDateTime = dateTime.toLocaleString();
                                    return formattedDateTime;
                                }
                                return data;
                            }
                        },

                        {
                            targets: 4,
                            render: function (data, type, row) {
                                let badgeClass = 'badge-light-success';

                                if (data === 'fail') {
                                    badgeClass = 'badge-light-danger';
                                } else if (data === 'warn') {
                                    badgeClass = 'badge-light-warning';
                                }

                                return `<span class="badge ${badgeClass} fs-7 fw-bold">${data}</span>`;
                            }

                        },
                    ],

                });
                datatable.on('draw', function () {

                    handleLogTypeFilter();
                    handleLogByFilter();

                });
            }

            var handleSearchDatatable = () => {
                const filterSearch = document.querySelector('[data-filter="input"]');
                filterSearch.addEventListener('keyup', function (e) {

                    datatable.column(2).search(e.target.value).draw();
                    // datatable.search(e.target.value).draw();
                });
            }

            var handleLogTypeFilter = () => {
                const filterStatus = document.querySelector('[data-filter="logType"]');
                $(filterStatus).on('change', e => {
                    let value = e.target.value;
                    console.log(value);
                    if (value === 'all') {
                        value = '';
                    }
                    datatable.column(1).search(value).draw();
                });
            }

            var handleLogByFilter = () => {
                const filterStatus = document.querySelector('[data-filter="logBy"]');
                $(filterStatus).on('change', e => {
                    let value = e.target.value;
                    console.log(value);
                    if (value === 'all') {
                        value = '';
                    }
                    datatable.column(6).search(value).draw();
                });
            }

            return {
                init: function () {
                    table = document.querySelector('#login_logs_table');

                    if (!table) {
                        return;
                    }
                    initLogsList()
                    handleSearchDatatable()
                    handleLogByFilter()
                }
            }
        }();


        KTUtil.onDOMContentLoaded(function () {
            KTCustomersList.init();
        });


    </script>
@endpush
