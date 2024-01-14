@extends('App.userManagement.userProfile.navBar')
@section('profile_logs_active_show','active')
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
        <div class="card-body p-0">
            <!--begin::Table wrapper-->
            <div class="table-responsive">
                <!--begin::Table-->
                <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9" id="login_logs_table">
                    <!--begin::Thead-->
                    <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                    <tr>
                        <th class="min-w-200px">Timestamp</th>
                        <th class="min-w-100px">Log Type</th>
                        <th class="min-w-150px">Description</th>
                        <th class="min-w-150px">Event</th>
                        <th class="min-w-150px">Status</th>
                        <th class="min-w-150px">By</th>
                    </tr>
                    </thead>
                    <!--end::Thead-->
                    <!--begin::Tbody-->
                    <tbody class="fw-6 fw-semibold text-gray-600">
{{--                    <tr>--}}
{{--                        <td>--}}
{{--                            <a href="#" class="text-hover-primary text-gray-600">USA(5)</a>--}}
{{--                        </td>--}}
{{--                        <td>--}}
{{--                            <span class="badge badge-light-success fs-7 fw-bold">OK</span>--}}
{{--                        </td>--}}
{{--                        <td>Chrome - Windows</td>--}}
{{--                        <td>236.125.56.78</td>--}}
{{--                        <td>2 mins ago</td>--}}
{{--                    </tr>--}}
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

        $(document).ready(function() {
            // Initialize DataTable with server-side processing
            var dataTable = $('#login_logs_table').DataTable({
                serverSide: true,
                processing: true,
                order: [[5, 'desc']],
                ajax: {
                    url: '/logs/current/all',
                    type: 'GET',
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
                    {
                        targets: 5,
                        render: function (data, type, row) {
                            $username = data.username;
                            return $username;
                        }
                    }
                ],
            });
        });


    </script>
@endpush
