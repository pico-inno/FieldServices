@extends('App.main.navBar')

@section('styles')
    {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('variations_menu_link', 'active')

@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{ __('product/variation.variation_list') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('product/product.product') }}</li>
        <li class="breadcrumb-item text-dark">{{ __('product/variation.variation') }}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Products-->
            <div class="card  card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">

                            <input type="text" id="search" data-kt-filter="search"
                                   class="form-control form-control-sm form-control-solid w-250px ps-14"
                                   placeholder="Search Report"/>
                        </div>
                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_datatable_example_1_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        @if(hasExport('variation'))
                            <!--begin::Export dropdown-->
                            <button type="button" class="btn btn-light-primary btn-sm" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                Export Report
                            </button>
                            <!--begin::Menu-->
                            <div id="kt_datatable_example_export_menu"
                                 class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                                 data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-export="copy">
                                        Copy to clipboard
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-export="excel">
                                        Export as Excel
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-export="csv">
                                        Export as CSV
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-export="pdf">
                                        Export as PDF
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                            <!--end::Export dropdown-->

                            <!--begin::Hide default export buttons-->
                            <div id="kt_datatable_example_buttons" class="d-none"></div>
                            <!--end::Hide default export buttons-->
                        @endif
                        @if(hasCreate('variation'))
                            <a href="{{ url('variation/add') }}"
                               class="btn btn-light-primary btn-sm">{{ __('product/variation.add_variation') }}</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-resoponsive">
                        <table class="table align-middle Datatable-tb rounded table-row-dashed fs-6 g-5"
                               id="kt_datatable_example">
                            <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase">
                                <th class="min-w-100px">{{ __('product/variation.variation') }}</th>
                                <th class="min-w-100px">{{ __('product/variation.value') }}</th>
                                <th class="">{{ __('product/variation.action') }}</th>
                            </tr>
                            <!--end::Table row-->
                            </thead>
                            <tbody class="fw-semibold text-gray-600">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--end::Products-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')

    <script>
        // success message
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toastr-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        $(document).ready(function () {
            let table = $('.Datatable-tb').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/variation-datas',
                },

                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'value',
                        name: 'value'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        render: function (data) {
                            let updatePermission = <?php echo hasUpdate('variation') ? 'true' : 'false'; ?>;
                            let deletePermission = <?php echo hasDelete('variation') ? 'true' : 'false'; ?>;
                            if (updatePermission || deletePermission) {
                                return `
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-active-light-primary fw-semibold fs-7  dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        ${updatePermission ? `<li><a href="/variation/edit/${data.action}" class="dropdown-item p-2"  >
                                            <i class="fas fa-pen-to-square me-3"></i>{{ __('product/product.edit') }}</a></li>` : ''}
                                        ${deletePermission && !data.filter_variation ? `<li><div class="dropdown-item p-2 delete-confirm cursor-pointer"  data-id="${data.action}" >
                                            <i class="fas fa-trash me-3"></i>{{ __('product/product.delete') }}</div></li>` : ''}
                                    </ul>
                                </div>
                                `;
                            } else {
                                return '';
                            }

                        }
                    }
                ]
            });
            // Search
            $('#search').on('keyup', function () {
                table.search(this.value).draw();
            });
            // DELETE
            $(document).on('click', '.delete-confirm', function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete it!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/variation/delete/' + id,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                _method: 'DELETE',
                            },
                            success: function (res) {
                                toastr.success(res.message)
                                table.ajax.reload();
                            }
                        })
                    }
                });

            });
        });
        // EDIT
        // $(document).on('click', '.edit-brand', function() {
        //     let id = $(this).data('id');
        //     console.log(id)
        //     $.ajax({
        //         url: '/variation/edit/'+id,
        //         type: 'GET',
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //     })
        // });
    </script>
@endpush
