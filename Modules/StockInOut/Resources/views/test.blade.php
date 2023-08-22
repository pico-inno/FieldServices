@extends('App.main.navBar')


@section('inventory_icon', 'active')
@section('inventroy_show', 'active show')
@section('stockin_here_show','here show')
@section('stockin_add_active_show', 'active ')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{ucfirst(__('stockinout::stockin.create'))}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('stockinout::stockin.stockin')}}</li>
        <li class="breadcrumb-item text-muted"><a href="{{route('stock-in.index')}}">{{__('stockinout::stockin.list')}}</a></li>
        <li class="breadcrumb-item text-dark">{{__('stockinout::stockin.add')}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/businessSetting.css")}}>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
@endsection



@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl" id="kt_content_container">
            <form action="{{route('stock-in.store')}}" method="POST">
                @csrf
                <!--begin::Repeater-->
                <div id="kt_docs_repeater_nested">
                    <!--begin::Form group-->
                    <div class="form-group">
                        <div data-repeater-list="kt_docs_repeater_nested_outer">
                            <div data-repeater-item>
                                <div class="form-group row mb-5">
                                    <div class="col-md-3">
                                        <label class="form-label">Name:</label>
                                        <input type="text" class="form-control mb-2 mb-md-0" placeholder="Enter full name" name="names[]" />
                                    </div>
                                    <div class="col-md-3">
                                        <div class="inner-repeater">
                                            <div data-repeater-list="kt_docs_repeater_nested_inner" class="mb-5">
                                                <div data-repeater-item>
                                                    <label class="form-label">Number:</label>
                                                    <div class="input-group pb-3">
                                                        <input type="text" class="form-control" placeholder="Enter contact number" name="c_num[]" />
                                                        <button class="border border-secondary btn btn-icon btn-flex btn-light-danger" data-repeater-delete type="button">
                                                            <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn btn-sm btn-flex btn-light-primary" data-repeater-create type="button">
                                                <i class="ki-duotone ki-plus fs-5"></i>
                                                Add Number
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check form-check-custom form-check-solid mt-2 mt-md-11">
                                            <button type="button">
                                                Open Modal
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-flex btn-light-danger mt-3 mt-md-9">
                                            <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                            Delete Row
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Form group-->

                    <!--begin::Form group-->
                    <div class="form-group">
                        <a href="javascript:;" data-repeater-create class="btn btn-flex btn-light-primary">
                            <i class="ki-duotone ki-plus fs-3"></i>
                            Add Row
                        </a>
                    </div>
                    <!--end::Form group-->
                </div>
                <!--end::Repeater-->

                <div class="col-12 text-center mt-2 mb-5">
                    <button type="submit" class="btn btn-primary btn-lg save-btn">{{__('stockinout::stockin.save')}}</button>
                </div>
            </form>
        </div>
        <!--end::Container-->
    </div>








@endsection

@push('scripts')
    <script src="assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
    <script>
        $('#kt_docs_repeater_nested').repeater({
            repeaters: [{
                selector: '.inner-repeater',
                show: function () {
                    $(this).slideDown();
                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            }],

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    </script>
@endpush
