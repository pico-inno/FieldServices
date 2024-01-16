@extends('App.userManagement.userProfile.navBar')
@section('profile_logs_active_show','active')


@section('styles')
    <link href={{asset("assets/plugins/custom/datatables/datatables.bundle.css")}} rel="stylesheet" type="text/css" />
@endsection

@section('profile-content')
    <!--begin::Login sessions-->
    <div class="card mb-5 mb-lg-10">
        <!--begin::Card header-->
        <div class="card-header">
            <!--begin::Heading-->
            <div class="card-title">
                <h3>Account Activity</h3>
            </div>
            <!--end::Heading-->
            <!--begin::Toolbar-->
            <div class="card-toolbar">
                <div class="my-1 me-4">
                    <!--begin::Select-->
                    <select class="form-select form-select-sm form-select-solid w-125px" data-control="select2" data-placeholder="Select Hours" data-hide-search="true">
                        <option value="1" selected="selected">1 Hours</option>
                        <option value="2">6 Hours</option>
                        <option value="3">12 Hours</option>
                        <option value="4">24 Hours</option>
                    </select>
                    <!--end::Select-->
                </div>
            </div>
            <!--end::Toolbar-->
        </div>
        <!--end::Card header-->
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
                        url: '/logs/current/all',
                    },
                    columns: [

                        { data: 'created_at' },
                        { data: 'log_name' },
                        { data: 'description' },
                        { data: 'event' },
                        { data: 'status' },
                        { data: 'created_user' },

                    ],
                    columnDefs: [
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

            }

            return {
                init: function () {
                    table = document.querySelector('#login_logs_table');

                    if (!table) {
                        return;
                    }
                    initLogsList()
                }
            }
        }();


        KTUtil.onDOMContentLoaded(function () {
            KTCustomersList.init();
        });


    </script>
@endpush
