@extends('App.main.navBar')

@section('products_icon', 'active')
@section('products_show', 'active show')
    @section('combokit_menu_link', 'active')

    @section('styles')

    @endsection
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{$rom->name}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('combokit::combokit.products')}}</li>
        <li class="breadcrumb-item text-muted">
            <a href="{{route('combokit.index')}}" class="text-muted text-hover-primary">{{__('combokit::combokit.combo_kit')}}</a>
        </li>
        <li class="breadcrumb-item text-dark">{{__('combokit::combokit.view')}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection



@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/businessSetting.css")}}>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
    <style>
        .data-table-body tr td{
            padding: 3px;
        }
    </style>
@endsection



@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="rom_container">
        <div class="container-xxl">

            <div>
                <div class="col-12 mt-5 mb-5">
                    <a href="{{route('combokit.index')}}" class="btn btn-secondary me-3"><i class="fa-solid fa-arrow-left fa-fw"></i>{{__('combokit::combokit.back')}}</a>
                    @if($rom->status == null)
                        <a href="{{route('combokit.make-default', $rom->id)}}" class="btn btn-primary">{{__('combokit::combokit.make_default_template')}}</a>
                    @endif
                </div>
                        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5">
                            <div class="card">
                                <div class="card-body">

                                    <div class="row mb-3 flex-wrap">
                                        <div class="col-sm-4">
                                            <div class="text-group">
                                                <h2 class="text-primary-emphasis fw-semibold fs-1 mb-8">
                                                   <span class="text-gray-800 fw-semibold">{{$rom->name}}</span><div class="mx-3 badge badge-light-success fw-bold">{{$rom->status}}</div>
                                                </h2>
                                                <h3 class="text-primary-emphasis fw-semibold fs-6 mb-5">
                                                    {{__('combokit::combokit.product')}} : @if($rom->product !== null) <span class="text-gray-800 fw-semibold">{{$rom->product['name']}} @if(isset($rom->toArray()['product_variation']['variation_template_value']))  ({{$rom->toArray()['product_variation']['variation_template_value']['name']}})  @endif</span> @endif
                                                </h3>
                                                <h3 class="text-primary-emphasis fw-semibold fs-6 mb-5">
                                                    {{__('combokit::combokit.date')}} : <span class="text-gray-600 fw-semibold">{{$rom->created_at}}</span>
                                                </h3>
                                                <h3 class="text-primary-emphasis fw-semibold fs-6 mb-5">
                                                    {{__('combokit::combokit.amount_of_unit')}} : <span class="text-gray-600 fw-semibold">{{$rom->quantity}}@if($rom->product !== null) {{$rom->product['uom']['name']}}@endif</span>
                                                </h3>
                                            </div>
                                        </div>
                                        <!--begin::Input group-->


                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-rounded  border gy-4 gs-4" id="purchase_table">
                                            <!--begin::Table head-->
                                            <thead class="bg-light rounded-3">
                                            <!--begin::Table row-->
                                            <tr class="text-start text-primary fw-bold fs-8 text-uppercase">
                                                <th>#</th>
                                                <th class="min-w-125px" style="max-width: 125px">{{__('combokit::combokit.product')}}</th>
                                                <th class="w-200px">{{__('combokit::combokit.quantity')}}</th>
                                                <th class="w-300px">{{__('combokit::combokit.uom')}}</th>
                                                <th class="w-300px">{{__('combokit::combokit.apply_on_variants')}}</th>
                                            </tr>
                                            <!--end::Table row-->
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-semibold text-gray-600 data-table-body">
                                            @foreach($romdetails as $key=>$pd)
                                                @php
                                                    $product_variation=$pd->toArray()['product_variation'];
                                                     $product = $product_variation['product'];
                                                     $applied_variation = $pd->toArray()['applied_variation'];
//                                                     $aa = $applied_variation['variation_template_value'];
//dd($applied_variation);
                                                @endphp
                                                <tr class='cal-gp'>
                                                    <td>
                                                        {{$key+1}}
                                                   </td>
                                                    <td>
                                                        <span  class="text-gray-600 text-hover-primary">   {{$product['name']}}</span>
                                                        @if(isset($product_variation['variation_template_value']))
                                                            <span class="my-2 d-block">
                                                            ({{$product_variation['variation_template_value']['name']}})
                                                        </span>
                                                        @endif
                                                    </td>
                                                    <td class="fv-row">
                                                        <span> {{number_format($pd->quantity, 2)}}</span>
                                                    </td>
                                                    <td>
                                                        <span> {{$product['uom']['name']}}</span>
                                                    </td>
                                                    <td>
                                                        @if($applied_variation != null)
                                                        {{$applied_variation['variation_template_value']['variation_template']['name']}}: {{ $applied_variation['variation_template_value']['name']}}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
    </div>

    <div class="modal modal-lg fade " tabindex="-1"  data-bs-focus="false"  id="quick_add_product_modal" ></div>
@endsection

@push('scripts')


@endpush


