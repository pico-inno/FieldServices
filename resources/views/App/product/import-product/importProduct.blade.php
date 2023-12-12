@extends('App.main.navBar')

@section('styles')
    {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('import_products_menu_link', 'active')


@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">{{ __('product/import-product.import_product') }}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{ __('product/product.product') }}</li>
        <li class="breadcrumb-item text-dark">{{ __('product/import-product.import_product') }}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    @if(session('failures'))
    <div class="modal fade" data-bs-backdrop="static"  data-bs-keyboard="false"  aria-hidden="true" tabindex="-1" id="error_modal">
        <div class="modal-dialog modal-dialog-scrollable mw-850px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Error Found in excel</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="table-responsive table-striped">
                        <div class="table-body">
                            <table class="table table-row-dashed table-row-gray-300">
                                <thead>
                                <tr class="fw-bold fs-5 text-danger border-bottom border-gray-200">
                                    <th>Row No</th>
                                    <th>Reason</th>
                                    <th>Values</th>
                                </tr>
                                </thead>
                                <tbody style="max-height: 300px; overflow-y: auto;">
                                @foreach (session('failures') as $failure)
                                    <tr>
                                        <td class="text-danger">{{ $failure->row() }}</td>
                                        <td class="text-danger">{{ implode(', ', $failure->errors()) }}</td>
                                        <td class="">
                                            Product Name : <span class="text-gray-700">{{ $failure->values()['name'] }}</span><br>
                                            Sku  : <span class="text-gray-700">{{ $failure->values()['sku_leave_blank_to_auto_generate_sku'] }}</span> <br>
                                            UOM/ PurchaseUOM : <span class="text-gray-700">{{$failure->values()['uom']}}/ {{$failure->values()['purchase_uom']}}</span>
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
    @endif
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                @if (isset($errors) && $errors->any())
                    <div>
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        @endforeach
                    </div>
                @endif
                @if(isset($errorMessage))
                    <div class="alert alert-danger">
                        {{ $errorMessage }}
                    </div>
                @endif
                @if(session('error-swal'))
                    <div class="alert alert-danger">
                        {{ session('error-swal') }}
                    </div>
                @endif
                <form id="importForm"  action="{{ route('import-product.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('product/import-product.import_product') }}</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row mb-5">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="">
                                        <label class=" form-label" for="formFileSm">{{ __('product/import-product.file_to_import') }}</label>

                                        <input class="form-control form-control-sm" id="formFileSm" type="file" name="import-products"/>
                                        @error('import-products')

                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <button id="submitBtn" type="submit" class="btn btn-primary btn-sm me-10">
                                    <span id="indicator-label">{{ __('product/import-product.submit') }}</span>
                                    <span id="loadingSpinner" class="indicator-progress" style="display: none;">
                                        Products importing... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                    <input type="hidden" value="0" id="checkBtn">
                                </button>
                            </div>

                            <div class="mt-5">
                                <a href="{{route('download-importProductExcel')}}" download class="btn btn-light-primary btn-sm">
                                    <i class="fas fa-download"></i>{{ __('product/import-product.download_template_file') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>{{ __('product/import-product.instructions') }}</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <strong>Follow the instructions carefully before importing the file.</strong>
                        <p class="mt-4">The columns of the file should be in the following order.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped g-3">
                                <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Column Number</th>
                                    <th>Column Name</th>
                                    <th>Instruction</th>
                                </tr>
                                </thead>
                                @php
                                    $Instructions = [
                                        [
                                            'name' => 'Product Name',
                                            'is_req' => true,
                                            'instruction' => 'Name of the product',
                                        ],
                                        [
                                            'name' => 'Brand',
                                            'is_req' => false,
                                            'instruction' => 'Name of the brand ',
                                            'note'=>'If not found new brand with the given name will be created'
                                        ],
                                        // [
                                        //     'name' => 'Unit Category',
                                        //     'is_req' => true,
                                        //     'instruction' => 'Unit Category (eg . mass, unit, weight,...)',
                                        // ],
                                        [
                                            'name' => 'UoM',
                                            'is_req' => true,
                                            'instruction' => 'Default UOM ',
                                        ],
                                        [
                                            'name' => 'Purchase UoM',
                                            'is_req' => true,
                                            'instruction' => 'Default UOM For Purchase',
                                        ],
                                        [
                                            'name' => 'Category',
                                            'is_req' => false,
                                            'instruction' => 'Name of the Category',
                                            'note'=>'If not found new category with the given name will be created'
                                        ],

                                        [
                                            'name' => 'Manufacture',
                                            'is_req' => false,
                                            'instruction' => 'Name of the Manufacture',
                                            'note'=>'If not found new Manufacture with the given name will be created.'
                                        ],
                                        [
                                            'name' => 'Generic',
                                            'is_req' => false,
                                            'instruction' => 'Name of the Generic ',
                                            'note'=>'If not found new generic with the given name under the parent Category will be created'
                                        ],
                                        [
                                            'name' => 'SKU',
                                            'is_req' => false,
                                            'instruction' => 'Product SKU. ',
                                            'note'=>'If blank an SKU will be automatically generated.',
                                        ],
                                        // [
                                        //     'name' => 'Product Type',
                                        //     'is_req' => true,
                                        //     'instruction' => 'Type Of Product.',
                                        //     'note'=>'Current Proudct types are Consumeable,Storable and Serivce'
                                        // ],

                                        // [
                                        //     'name' => 'Has Variation',
                                        //     'is_req' => true,
                                        //     'instruction' => 'Has product variation? (Single or Variable)',
                                        // ],
                                        // [
                                        //     'name' => 'Barcode Type (Optional, Default: C128)',
                                        //     'is_req' => false,
                                        //     'instruction' => 'Barcode Type for the product. ',
                                        //     'note'=>'Currently supported: C128, C39, EAN-13, EAN-8, UPC-A, UPC-E, ITF-14',
                                        // ],
                                        // [
                                        //     'name' => 'Manage Stock?',
                                        //     'is_req' => true,
                                        //     'instruction' => 'Enable or disable stock management. 1 = Yes, 0 = No',
                                        // ],
                                        // [
                                        //     'name' => 'Alert quantity',
                                        //     'is_req' => false,
                                        //     'instruction' => 'Alert quantity',
                                        // ],
                                        // [
                                        //     'name' => 'Expires in',
                                        //     'is_req' => false,
                                        //     'instruction' => 'Product expiry period (Only in numbers)',
                                        // ],
                                        // [
                                        //     'name' => 'Expiry Period Unit',
                                        //     'is_req' => false,
                                        //     'instruction' => 'Unit for the expiry period. Available Options: days, months',
                                        // ],
                                        // [
                                        //     'name' => 'Applicable Tax',
                                        //     'is_req' => false,
                                        //     'instruction' => 'Name of the Tax Rate. If purchase Price (Excluding Tax) is not the same as Purchase Price (Including Tax), then you must supply the tax rate name.',
                                        // ],
                                        // [
                                        //     'name' => 'Selling Price Tax Type',
                                        //     'is_req' => true,
                                        //     'instruction' => 'Selling Price Tax Type. Available Options: inclusive, exclusive',
                                        // ],
                                        [
                                            'name' => 'Product Type',
                                            'is_req' => true,
                                            'instruction' => 'Product Type. Available Options: Consumeable , Storable , Serivce',
                                        ],
                                        [
                                            'name' => 'Has Variation',
                                            'is_req' => true,
                                            'instruction' => 'Has product variation? (Single or Variable)',
                                        ],
                                        [
                                            'name' => 'Variation Name (Required if product type is variable)',
                                            'is_req' => true,
                                            'instruction' => 'Name of the variation (Ex: Size, Color, etc.)',
                                        ],
                                        [
                                            'name' => 'Variation Values (Required if product type is variable)',
                                            'is_req' => true,
                                            'instruction' => 'Values for the variation separated with \'|\'. (Ex: Red|Blue|Green)',
                                        ],
                                        [
                                            'name' => 'Variation SKUs',
                                            'is_req' => false,
                                            'instruction' => 'SKUs of each variation separated by "|" if product type is variable',
                                        ],
                                        [
                                            'name' => 'Purchase Price ',
                                            'is_req' => false,
                                            'instruction' => 'Default Purchase Price',
                                        ],
                                        // [
                                        //     'name' => 'Purchase Price (Excluding Tax) (Required if Purchase Price Including Tax is not given)',
                                        //     'is_req' => true,
                                        //     'instruction' => 'Purchase Price (Excluding Tax) (Only in numbers). For variable products, "|" separated values with the same order as Variation Values (Ex: 84|85|88)',
                                        // ],
                                        [
                                            'name' => 'Profit Margin %',
                                            'is_req' => false,
                                            'instruction' => 'Profit Margin (Only in numbers).',
                                            'note'=>'If blank, the default profit margin for the business will be used'
                                        ],
                                        [
                                            'name' => 'Selling Price',
                                            'is_req' => false,
                                            'instruction' => 'Selling Price (Only in numbers). ',
                                            'note'=>'If blank, selling price will be calculated with the given Purchase Price and Applicable Tax'
                                        ],
                                        [
                                            'name' => 'Can Sale',
                                            'is_req' => true,
                                            'instruction' => 'Can Product use in sale .Option : 0 or 1',
                                            'note'=>"If blank or 0, Product can't search in sale voucher creation",
                                        ],
                                        [
                                            'name' => 'Can Purchase',
                                            'is_req' => true,
                                            'instruction' => 'Can Product use in sale .Option : 0 or 1',
                                            'note'=>"If blank or 0, Product can't search in purchase voucher  creation",
                                        ],
                                        [
                                            'name' => 'Can Expense',
                                            'is_req' => false,
                                            'instruction' => 'Can Product use as Expense Product .Option : 0 or 1',
                                            'note'=>"If blank or 0, Product can't search in Expense voucher  creation",
                                        ],

                                        [
                                            'name' => 'Product Description',
                                            'is_req' => false,
                                            'instruction' => 'Description of product',
                                        ],
                                        // [
                                        //     'name' => 'Opening Stock',
                                        //     'is_req' => false,
                                        //     'instruction' => 'Opening Stock (Only in numbers). For variable products, separate stock quantities with \'|\'. (Ex: 100|150|200)',
                                        // ],
                                        // [
                                        //     'name' => 'Opening stock location',
                                        //     'is_req' => false,
                                        //     'instruction' => 'Name of the business location. If blank, the first business location will be used',
                                        // ],
                                        // [
                                        //     'name' => 'Expiry Date',
                                        //     'is_req' => false,
                                        //     'instruction' => 'Stock Expiry Date. Format: mm-dd-yyyy; Ex: 11-25-2018',
                                        // ],
                                        // [
                                        //     'name' => 'Enable Product description, IMEI or Serial Number (Optional, Default: 0)',
                                        //     'is_req' => false,
                                        //     'instruction' => '1 = Yes, 0 = No',
                                        // ],
                                        // [
                                        //     'name' => 'Weight',
                                        //     'is_req' => false,
                                        //     'instruction' => 'Optional',
                                        // ],
                                        // [
                                        //     'name' => 'Rack',
                                        //     'is_req' => false,
                                        //     'instruction' => 'Rack details separated by \'|\' for different business locations serially. (Ex: R1|R5|R12)',
                                        // ],
                                        // [
                                        //     'name' => 'Row',
                                        //     'is_req' => false,
                                        //     'instruction' => 'Row details separated by \'|\' for different business locations serially. (Ex: ROW1|ROW2|ROW3)',
                                        // ],
                                        // [
                                        //     'name' => 'Position',
                                        //     'is_req' => false,
                                        //     'instruction' => 'Position details separated by \'|\' for different business locations serially. (Ex: POS1|POS2|POS3)',
                                        // ],
                                    ];
                                @endphp
                                <tbody >
                                @foreach ($Instructions as $i=>$ins)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>
                                            {{$ins['name']}}
                                            <span></span>
                                            <span class="text-muted {{$ins['is_req']?'required':''}}">
                                                        {{$ins['is_req']?'(Required)':'(Optional)'}}
                                                    </span>
                                        </td>
                                        <td>
                                            {{$ins['instruction']}}
                                            @if(isset($ins['note']))
                                                <br>
                                                <span class="text-gray-600">
                                                            {{$ins['note']}}
                                                        </span>
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </>
            <!--end::Main column-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->

    <div class="modal fade" id="import_process_modal" data-bs-backdrop="static"  data-bs-keyboard="false"  aria-hidden="true"  tabindex="-1" aria-modal="true" role="dialog">
        <!--begin::Modal dialog-->
        <div class="modal-dialog mw-700px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header pb-0 border-0 d-flex justify-content-end">

                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-10 pt-0 pb-15">
                    <!--begin::Heading-->
                    <div class="text-center mb-13">
                        <!--begin::Title-->
                        <h1 class="d-flex justify-content-center align-items-center mb-3">Importing Product<span class="loading-dots"></span></h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <div class="text-muted fw-semibold fs-5">Please wait until the import process is complete.</div>
                        <!--end::Description-->
                    </div>
                    <!--end::Heading-->
                    <!--begin::Users-->
                    <div class="mh-475px scroll-y me-n7 pe-7">

                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                    </div>
                    <!--end::Users-->
                </div>
                <!--end::Modal Body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>

@endsection

@push('scripts')

    <script>
        $(document).ready(function() {
            $('#error_modal').modal('show');
        });
    </script>
    <script>


    </script>


    <script>
        var interval;

        function startLoadingDots() {
            var dots = 0;
            interval = setInterval(function () {
                $('.loading-dots').text(Array(dots % 4).fill('.').join(''));
                dots++;
            }, 500);
        }

        @if(session('success-swal'))
        Swal.fire({
            text: '{{session('success-swal')}}',
            icon: "success",
            buttonsStyling: false,
            showCancelButton: false,
            confirmButtonText: "Ok, got it.",
            customClass: {
                confirmButton: "btn btn-primary",
            }
        })
        clearInterval(interval);
        @endif

        $(document).ready(function () {
            $('#importForm').submit(function (event) {

                $('#submitBtn').prop('disabled', true);

                $('#loadingSpinner').css('display', 'inline-block');
                $('#indicator-label').css('display', 'none');
                $('#import_process_modal').modal('show');
                startLoadingDots();
            });

        });


    </script>



@endpush
