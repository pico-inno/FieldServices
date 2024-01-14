@extends('App.main.navBar')

@section('styles')


@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('combokit_menu_link', 'active')

@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{__('combokit::combokit.combo_kit')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('combokit::combokit.products')}}</li>
        <li class="breadcrumb-item text-dark">{{__('combokit::combokit.combo_kit_list')}}</li>
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
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                </svg>
                            </span>
                            <input type="text" id="search" data-kt-filter="search" class="form-control form-control-solid w-250px ps-14 form-control-sm" placeholder="{{__('combokit::combokit.search')}}" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_datatable_example_1_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <a href="{{route('combokit.create')}}" class="text-light btn btn-primary btn-sm">{{__('combokit::combokit.create_combo_kit')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-rounded border gy-4 gs-4" id="rom_consume_table">
                            <!--begin::Table head-->
                            <thead class="bg-light rounded-3">
                            <!--begin::Table row-->
                            <tr class="text-start text-primary fw-bold fs-6 text-uppercase">
                                <th class="min-w-100px">{{__('combokit::combokit.name')}}</th>
                                <th class="min-w-100px">{{__('combokit::combokit.product')}}</th>
                                <th class="min-w-100px">{{__('combokit::combokit.rom_type')}}</th>
                                <th class="min-w-100px">{{__('combokit::combokit.status')}}</th>
                                <th class="min-w-100px">{{__('combokit::combokit.created_at')}}</th>
                                <th class="min-w-100px">{{__('combokit::combokit.actions')}}</th>
                            </tr>
                            <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-600 data-table-body">
                            @if($roms->count() >= 1)
                                @foreach($roms as $rom)
                                    <tr>
                                        <td>{{$rom->name}}</td>
                                        <td class="{{ isset($rom->product) ? '' : 'text-danger' }}">{{ optional($rom->product)->name ?? $rom->deleted_pd_name }}</td>
                                        <td>{{$rom->rom_type}}</td>
                                        <td>
                                            <span class="{{ isset($rom->product) ? 'badge badge-light-success fw-bold' : 'badge badge-light-danger fw-bold' }}">
                                                {{ isset($rom->product) ? $rom->status : 'Deleted Product' }}
                                            </span>
                                        </td>
                                        <td>{{$rom->created_at}}</td>
                                        <td>
                                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">{{__('combokit::combokit.actions')}}
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                <span class="svg-icon svg-icon-5 m-0">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
														</svg>
													</span>
                                                <!--end::Svg Icon--></a>
                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                @if($rom->status == null)
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="{{route('combokit.make-default', $rom->id)}}" class="menu-link px-3">{{__('combokit::combokit.make_default')}}</a>
                                                </div>
                                                <!--end::Menu item-->
                                                @endif
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="{{route('combokit.show', $rom->id)}}" class="menu-link px-3">{{__('combokit::combokit.view')}}</a>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="{{route('combokit.edit', $rom->id)}}" class="menu-link px-3">{{__('combokit::combokit.edit')}}</a>
                                                </div>
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3" href="#" onclick="event.preventDefault();
                                           document.getElementById('delete-form-{{$rom->id}}').submit();">{{__('combokit::combokit.delete')}}</a>

                                                    <form id="delete-form-{{$rom->id}}" action="{{route('combokit.destroy',$rom->id)}}" method="POST" class="d-none">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu-->
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class=" text-center">
                                    <td colspan="6" >{{__('combokit::combokit.there_is_no_data_to_show')}}</td>
                                </tr>
                            @endif
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
    <script src="{{ asset('customJs/toastrAlert/alert.js') }}"></script>

    <script>
    @if(session('error-swal'))
        Swal.fire({
        text: '{{session('error-swal')}}',
        icon: "error",
        buttonsStyling: false,
        showCancelButton: false,
        confirmButtonText: "Ok",
        cancelButtonText: "Delete",
        customClass: {
        confirmButton: "btn fw-bold btn-primary",
        }
        });
    @endif

    @if (session('message'))
            success("{{session('message')}}");
    @endif
    </script>
@endpush
