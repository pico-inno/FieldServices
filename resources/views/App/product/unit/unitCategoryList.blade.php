@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('units_menu_link', 'active')

@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{ __('product/unit-and-uom.unit_list') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('product/product.uom') }}</li>
        <li class="breadcrumb-item text-dark">{{ __('product/unit-and-uom.unit_list') }}</li>
    </ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Form-->
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin:::Tabs-->
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2" id="tab-bar">
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" id="uom" data-bs-toggle="tab" href="#uom_tab">{{ __('product/product.uom') }}</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 " id="unit_category" data-bs-toggle="tab" href="#unit_category_tab">{{ __('product/unit-and-uom.unit_category') }}</a>
                        </li>
                        <!--end:::Tab item-->

                    </ul>
                    <!--end:::Tabs-->
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade " id="unit_category_tab" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::General options-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    {{-- <div class="card-header">
                                        <div class="card-title">
                                            <h2>All Unit Category</h2>
                                        </div>
                                    </div> --}}
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <div class="card  card-flush">
                                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                                <div class="card-title">
                                                    <!--begin::Search-->
                                                    <div class="d-flex align-items-center position-relative my-1">
                                                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <input type="text" id="unit-category-search" data-kt-filter="search" class="form-control form-control-sm form-control-solid w-250px ps-14" placeholder="Search Report" />
                                                    </div>
                                                    <!--end::Search-->
                                                    <!--begin::Export buttons-->
                                                    <div id="kt_datatable_example_1_export" class="d-none"></div>
                                                    <!--end::Export buttons-->
                                                </div>
                                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                                    <a href="{{ route('unit-category.add') }}" class=" btn btn-light-primary btn-sm">{{ __('product/unit-and-uom.add_unit_category') }}</a>
                                                    <!--begin::Hide default export buttons-->
                                                    <div id="kt_datatable_example_buttons" class="d-none"></div>
                                                    <!--end::Hide default export buttons-->
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table align-middle  rounded table-row-dashed fs-6 g-5 unit-category-table" id="kt_datatable_example">
                                                        <thead>
                                                            <!--begin::Table row-->
                                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase">
                                                                <th class="min-w-100px">{{ __('product/product.name') }}</th>
                                                                <th class="min-w-200px">{{ __('product/product.uom') }}</th>
                                                                <th class="min-w-100px">{{ __('product/product.action') }}</th>
                                                            </tr>
                                                            <!--end::Table row-->
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600">

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::General options-->
                            </div>
                        </div>
                        <!--end::Tab pane-->
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade " id="uom_tab" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::Inventory-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    {{-- <div class="card-header">
                                        <div class="card-title">
                                            <h2>All UOM</h2>
                                        </div>
                                    </div> --}}
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <div class="card  card-flush">
                                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                                <div class="card-title">
                                                    <!--begin::Search-->
                                                    <div class="d-flex align-items-center position-relative my-1">
                                                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <input type="text" id="uom-search" data-kt-filter="search-stock" class="form-control form-control-sm form-control-solid w-250px ps-14" placeholder="Search Report" />
                                                    </div>
                                                    <!--end::Search-->
                                                    <!--begin::Export buttons-->
                                                    <div id="kt_datatable_example_1_export" class="d-none"></div>
                                                    <!--end::Export buttons-->
                                                </div>
                                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                                    <!--begin::Export dropdown-->
                                                    <button type="button" class="btn btn-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        {{ __('product/unit-and-uom.filter_by_category') }}
                                                    </button>
                                                    <!--begin::Menu-->
                                                    <div id="filter_uom_by_cate" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                                                        <div class="menu-scroll" style="max-height: 200px; overflow-y: auto;">
                                                            <div class="menu-item px-3">
                                                                <span  class="menu-link px-3" data-cate-id="all">
                                                                    {{ __('product/product.all') }}
                                                                </span>
                                                            </div>
                                                            @foreach ($unitCategories as $category)
                                                                <div class="menu-item px-3">
                                                                    <span  class="menu-link px-3" data-cate-id="{{ $category->id }}">
                                                                        {{ $category->name }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <!--end::Menu-->
                                                    <!--end::Export dropdown-->
                                                    <a href="{{ route('uom.add') }}" class=" btn btn-light-primary btn-sm">{{ __('product/unit-and-uom.add_uom') }}</a>
                                                    <!--begin::Hide default export buttons-->
                                                    <div id="kt_datatable_example_buttons_stock" class="d-none"></div>
                                                    <!--end::Hide default export buttons-->
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table align-middle  rounded table-row-dashed fs-6 g-5 uom-table" id="kt_datatable_example">
                                                        <thead>
                                                            <!--begin::Table row-->
                                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase">
                                                                <th class="min-w-100px">{{ __('product/product.name') }}</th>
                                                                <th class="min-w-100px">{{ __('product/unit-and-uom.short_name') }}</th>
                                                                <th class="min-w-100px">{{ __('product/unit-and-uom.unit_category') }}</th>
                                                                <th class="min-w-100px">{{ __('product/variation.value') }}</th>
                                                                <th class="min-w-100px">{{ __('product/product.action') }}</th>
                                                            </tr>
                                                            <!--end::Table row-->
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600">


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Inventory-->
                            </div>
                        </div>
                        <!--end::Tab pane-->
                    </div>
                    <!--end::Tab content-->
                </div>
                <!--end::Main column-->
            <!--end::Form-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')
    <script src="{{ asset('customJs/toastrAlert/alert.js') }}"></script>

    {{-- begin:: for alert message --}}
    @if (session('message'))
        <script>
            success("{{session('message')}}");
        </script>
    @endif
    {{-- end:: for alert message --}}

    {{-- begin:: for tab  --}}
    @if (session('toUnitCate'))
        <script>
            let unitCategory = document.querySelector('#unit_category'); // tab
            let uom = document.querySelector('#uom'); // tab

            let unitTab = document.querySelector('#unit_category_tab'); // card
            let uomTab = document.querySelector('#uom_tab'); // card

            uom.classList.remove('active');
            unitCategory.classList.add('active');
            uomTab.classList.remove('active', 'show');
            unitTab.classList.add('active', 'show');
        </script>
    @else
        <script>
            let unitCategory = document.querySelector('#unit_category'); // tab
            let uom = document.querySelector('#uom'); // tab

            let unitTab = document.querySelector('#unit_category_tab'); // card
            let uomTab = document.querySelector('#uom_tab'); // card

            uom.classList.add('active');
            unitCategory.classList.remove('active');
            uomTab.classList.add('active', 'show');
            unitTab.classList.remove('active', 'show');
        </script>
    @endif
    {{-- end:: for tab  --}}


    <script>
        // BEGIN:: FOR UNIT Category TABLE
        $(document).ready(function () {
            let tableCate = $('.unit-category-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/unit-category/datas',
                },

                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'uom',
                        name: 'uom',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        render: function(data){
                            return `
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light btn-active-light-primary fw-semibold fs-7  dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a href="/unit-category/edit/${data}" class="dropdown-item p-2 edit-unit"  >
                                        <i class="fas fa-pen-to-square me-3"></i>{{ __('product/product.edit') }}</a></li>
                                    <li><div class="dropdown-item p-2 unit-category-delete-confirm cursor-pointer"  data-id="${data}" >
                                        <i class="fas fa-trash me-3"></i>{{ __('product/product.delete') }}</div></li>
                                </ul>
                            </div>
                            `;
                        }
                    }
                ]
            });
            // Search
            $('#unit-category-search').on('keyup', function() {
                tableCate.search(this.value).draw();
            });
            // DELETE
            $(document).on('click', '.unit-category-delete-confirm', function(e) {
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
                            url: '/unit-category/delete/'+id,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                _method: 'DELETE',
                            },
                            success: function(res) {
                                if(res.error){
                                    warning(res.error)
                                }
                                if(res.message){
                                    success(res.message);
                                    tableCate.ajax.reload()
                                }
                            }
                        })
                    }
                });

            });
        });

        // BEGIN:: FORM UOM TABLE
        $(document).ready(function () {
            let tableUom = $('.uom-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/unit-category/uom-datas',
                },

                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'short_name',
                        name: 'short_name'
                    },
                    {
                        data: 'unit_category',
                        name: 'unit_category_id',
                    },
                    {
                        data: 'value',
                        name: 'value',
                        render: function(data){
                            return `${data * 1}`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        render: function(data){
                            return `
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light btn-active-light-primary fw-semibold fs-7  dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a href="/uom/edit/${data}" class="dropdown-item p-2 edit-uom" >
                                        <i class="fas fa-pen-to-square me-3"></i>{{ __('product/product.edit') }}</a></li>
                                    <li><div class="dropdown-item p-2 uom-delete-confirm cursor-pointer"  data-id="${data}" >
                                        <i class="fas fa-trash me-3"></i>{{ __('product/product.delete') }}</div></li>
                                </ul>
                            </div>
                            `;
                        }
                    }
                ]
            });
            // Search
            $('#uom-search').on('keyup', function() {
                tableUom.search(this.value).draw();
            });

            // Filter by unit_category
            $('#filter_uom_by_cate').on('click', '.menu-link', function () {
                var cateId = $(this).attr('data-cate-id');
                if (cateId === 'all') {
                    tableUom.column(2).search('').draw();
                } else {
                    tableUom.column(2).search(cateId).draw();
                }
            });

            // DELETE
            $(document).on('click', '.uom-delete-confirm', function(e) {
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
                            url: '/uom/delete/'+id,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                _method: 'DELETE',
                            },
                            success: function(res) {
                                if(res.error){
                                    warning(res.error)
                                }
                                if(res.message){
                                    success(res.message);
                                    tableUom.ajax.reload()
                                }
                            }
                        })
                    }
                });

            });
        });
    </script>
@endpush
{{-- var categoryName = $(this).text().trim();
table.column('unit_category').search(categoryName).draw(); --}}
