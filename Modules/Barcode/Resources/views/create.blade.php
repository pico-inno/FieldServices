@extends('App.main.navBar')

@section('barcode_active','active')
@section('barcode_active_show','active show')
@section('barcode_template_list_active_show','here show')
@section('location_add_nav','active')

@section('styles')
<style>

    .card{
        position: relative;
    }
    .wrap{
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background-color:rgba(193, 194, 193, 0.619);
        justify-content:  center;
        align-items: center;
    }
    .text{
        text-align: center;
        font-size: 30px;
        margin-top: 20px
    }
    .marinDivWrap {
        font-family: monospace;
        margin: 0in;
        padding: 0in;
        background-color: #f9f9f9;
        /* margin top of the paper */
        padding-top: 2mm;
        /* margin left of the paper */
        padding-left: 2mm;
        box-sizing: border-box;
    }

    .barcode {
        width: 32mm;
        height: 18.5mm;
        text-align: center;
        border: 1px solid #00000068;
        /* padding of the two row */
        margin-bottom: 0;
        padding: 1mm;
        margin-bottom: 1mm;
        margin-right: 1.5mm;
        display: flex;
        flex-direction: column;
        justify-content: start;
        box-sizing: content-box;
        /* align-items: center; */
    }

    .mainDiv {
        display: flex;
        width: 101mm;
        margin-bottom: 0;
        justify-content: center;
        align-items: center;
    }

    .priceTag {
        display: flex;
        justify-content: space-between;
        margin-top: 5px;
    }
</style>

@endsection
@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-2">Add Barcode Template</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Setting</li>
    <li class="breadcrumb-item text-muted">
        <a href="" class="text-muted text-hover-primary">Barcode</a>
    </li>
    <li class="breadcrumb-item text-dark">Add Barcode </li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Container-->
    <div class="container-xxl" id="location">

        <!--begin::Modals-->
        <div class="card">
            <div class="card-body user-select-none">
                <!--begin::Form-->
                <form class="form" action="{{route('barcode.store')}}" method="POST" id="location_form">
                    @csrf

                    <div class="d-flex">
                        <div class="col-6">
                            <div class="mb-3 ">
                                <div class="mb-10">
                                    <h3 class="text-gray-700">Template Info</h3>
                                </div>
                                <div class="mb-7">
                                    <label for="name" class="form-label text-gray-700">Template Name</label>
                                    <x-forms.input class="mt-2" placeholder="Eg : 32x19 template" id="name" name="name">
                                    </x-forms.input>
                                </div>
                                <div class="mb-7">
                                    <label for="description" class="form-label text-gray-700">Description</label>
                                    <x-forms.input class="mt-2" placeholder="description" name="description">
                                    </x-forms.input>
                                </div>
                            </div>
                            <div class="mt-10">
                                <div class="mb-10">
                                    <h3 class="text-gray-700">Layout</h3>
                                </div>
                                <div class="mb-7">
                                    <div class="row mb-10">
                                        <div class="fv-row col-12  d-flex mb-5 mt-3  align-items-end">
                                            <!--begin::Label-->
                                            <div class="">
                                                <label class=" fs-6 fw-semibold mb-2">Paper Type :</label>
                                            </div>
                                            <div class=" ms-5 min-w-100px">
                                                <x-forms.nob-select placeholder="Select Location Type" class="min-w-200px" attr="data-hide-search='true'" name="paper_type" id="paper_type">
                                                    <option value="cf">Continous feed or rolls</option>
                                                    <option value="fixed">fixed</option>
                                                </x-forms.nob-select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-6 pe-3 mt-5">
                                            <label for="description" class="form-label text-gray-700 required">
                                                <i class="fa-solid fa-arrow-up-from-bracket mx-2"></i>
                                                Margin Top Of The Paper (mm)
                                            </label>
                                            <x-forms.input class="mt-2" placeholder="Eg : 2" id="name"
                                                name="paperMarginTop"></x-forms.input>
                                        </div>
                                        <div class="col-6 ps-3  mt-5">
                                            <label for="description" class="form-label text-gray-700 required">
                                                <i class="fa-solid fa-right-to-bracket fa-flip-both mx-2"></i>
                                                Margin Left Of The Paper (mm)
                                            </label>
                                            <x-forms.input class="mt-2" placeholder="Eg : 2" id="name"
                                                name="paperMarginLeft"></x-forms.input>
                                        </div>
                                        <div class="col-6 pe-3 mt-10">
                                            <label for="description" class="form-label text-gray-700 required">
                                                <i class="fa-solid fa-left-right mx-2"></i>
                                                Paper width (mm)
                                            </label>
                                            <x-forms.input class="mt-2" placeholder="Eg : 32" id="name"
                                                name="paperWidth"></x-forms.input>
                                        </div>
                                        <div class="col-6 ps-3  mt-10 paper_fix d-none">
                                            <label for="description" class="form-label text-gray-700">
                                                <i class="fa-solid fa-arrows-up-down mx-2"></i>
                                                Paper height (mm)
                                            </label>
                                            <x-forms.input class="mt-2" placeholder="Eg : 2" id="name"
                                                name="paperHeight"></x-forms.input>
                                        </div>
                                        <div class="col-6 pe-3 mt-10">
                                            <label for="description" class="form-label text-gray-700 required">
                                                <i class="fa-solid fa-arrows-left-right-to-line mx-2"></i>
                                                Sticker width (mm)
                                            </label>
                                            <x-forms.input class="mt-2" placeholder="Eg : 32" id="name"
                                                name="stickerWidth"></x-forms.input>
                                        </div>
                                        <div class="col-6 ps-3  mt-10">
                                            <label for="description" class="form-label text-gray-700">
                                                <i class="fa-solid fa-arrows-left-right-to-line fa-rotate-90 mx-2"></i>
                                                Sticker height (mm)
                                            </label>
                                            <x-forms.input class="mt-2" placeholder="Eg : 2" id="name"
                                                name="stickerHeight"></x-forms.input>
                                        </div>
                                        <div class="col-6 pe-3 mt-10 paper_fix d-none">
                                            <label for="description" class="form-label text-gray-700 required">
                                                <i class="fa-solid fa-arrows-left-right-to-line mx-2"></i>
                                                Row Count
                                            </label>
                                            <x-forms.input class="mt-2" placeholder="Eg : 32" id="name" name="rowCount">
                                            </x-forms.input>
                                        </div>
                                        <div class="col-6 ps-3  mt-10">
                                            <label for="description" class="form-label text-gray-700">
                                                <i class="fa-solid fa-arrows-left-right-to-line fa-rotate-90 mx-2"></i>
                                                Column Count
                                            </label>
                                            <x-forms.input class="mt-2" placeholder="Eg : 2" id="name"
                                                name="columnCount"></x-forms.input>
                                        </div>
                                        <div class="col-6 pe-3 mt-10">
                                            <label for="description" class="form-label text-gray-700 required">
                                                <i class="fa-solid fa-arrows-up-to-line mx-2"></i>
                                                Row Gap (mm)
                                            </label>
                                            <x-forms.input class="mt-2" placeholder="Eg : 32" id="name" name="rowGap">
                                            </x-forms.input>
                                        </div>
                                        <div class="col-6 ps-3  mt-10">
                                            <label for="description" class="form-label text-gray-700">
                                                <i class="fa-solid fa-arrows-up-to-line fa-rotate-270 mx-2"></i>
                                                Column Gap (mm)
                                            </label>
                                            <x-forms.input class="mt-2" placeholder="Eg : 2" id="name" name="columnGap">
                                            </x-forms.input>
                                        </div>
                                        <div class="col-6 pe-3 mt-10">
                                            <label for="description" class="form-label text-gray-700 required">
                                                <i class="fa-solid fa-barcode mx-2"></i>
                                                Barcode Height (mm)
                                            </label>
                                            <x-forms.input class="mt-2" placeholder="Eg : 32" id="name"
                                                name="barcodeHeight"></x-forms.input>
                                        </div>
                                        <div class="col-6 ps-3  mt-10">
                                            <label for="description" class="form-label text-gray-700">
                                                <i class="fa-solid fa-maximize mx-2"></i>
                                                Inner Padding Of Sticker (mm)
                                            </label>
                                            <x-forms.input class="mt-2" placeholder="Eg : 2" id="name"
                                                name="barcodeInnerPadding"></x-forms.input>
                                        </div>
                                    </div>
                                    <div class="col-6 mt-10">
                                        <button class="btn btn-sm btn-primary col-6">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card col-6 shadow-none p-2 ps-5 rounded rounded-1">
                            <div class="wrap">
                                <div class="text">
                                    Comming Sooon ! ....
                                </div>
                            </div>
                            {{-- <div class="card-header"> --}}
                                <div class="card-title fw-bold fs-3 ">
                                    Barcode Paper Preview
                                </div>
                                {{--
                            </div> --}}
                            <div class="card-body border border-1 border-gray-300 rounded rounded-1 p-0 d-flex align-items-center justify-content-center">
                                <div class="marinDivWrap bg-light">
                                    @foreach (array(1,2,3,4,5,6) as $i)
                                        <div class="mainDiv rounded rounded-1">
                                            <div class="barcode bg-white border-gray-300">
                                                <img class="img"
                                                    src="data:image/png;base64, {{DNS1D::getBarcodePNG('1111111', 'C128',2,55)}}"
                                                    alt="barcode" width="100%" />
                                                <span
                                                    style="font-size: 10px;display: block;text-align: center;font-weight: bold">Product</span>
                                                <div class="priceTag" style="font-size: 10px;font-weight: bold;">
                                                    <div>Price</div>
                                                    <div>10000ks</div>
                                                </div>
                                            </div>
                                            <div class="barcode bg-white border-gray-300">
                                                <img class="img" src="data:image/png;base64, {{DNS1D::getBarcodePNG('1111111', 'C128',2,55)}}" alt="barcode"
                                                    width="100%" />
                                                <span style="font-size: 10px;display: block;text-align: center;font-weight: bold">Product</span>
                                                <div class="priceTag" style="font-size: 10px;font-weight: bold;">
                                                    <div>Price</div>
                                                    <div>10000ks</div>
                                                </div>
                                            </div>
                                            <div class="barcode bg-white border-gray-300">
                                                <img class="img" src="data:image/png;base64, {{DNS1D::getBarcodePNG('1111111', 'C128',2,55)}}" alt="barcode"
                                                    width="100%" />
                                                <span style="font-size: 10px;display: block;text-align: center;font-weight: bold">Product</span>
                                                <div class="priceTag" style="font-size: 10px;font-weight: bold;">
                                                    <div>Price</div>
                                                    <div>10000ks</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>


                            </div>
                        </div>

                    </div>
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>

@endsection

@push('scripts')
<script>
    $('#paper_type').change(function(){
        let value=$(this).val();
        if(value =='fixed'){
            $('.paper_fix').removeClass('d-none');
        }else{
            $('.paper_fix').addClass('d-none');
        }
    })
</script>
@endpush
