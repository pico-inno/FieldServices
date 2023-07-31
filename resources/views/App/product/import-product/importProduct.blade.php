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
                <form action="{{ route('import-product.create') }}" method="POST" enctype="multipart/form-data">
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
                                <button type="submit" class="btn btn-success btn-sm">{{ __('product/import-product.submit') }}</button>
                            </div>
                            <div class="mt-5">
                                <a href="{{ asset('storage/import-product/import_products_csv_template.xls') }}" download class="btn btn-light-primary btn-sm">
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
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800">
                                            <th>Column Number</th>
                                            <th>Column Name</th>
                                            <th>Instruction</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Product Name
                                                <span class="text-muted">(Required)</span>
                                            </td>
                                            <td>Name of the product</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Brand (Optional)</td>
                                            <td>Name of the brand
                                                <br/><span class="text-muted">(If not found new brand with the given name will be created)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                Unit <span class="text-muted">(Required)</span>
                                            </td>
                                            <td>Name of the unit</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                Category <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Name of the Category <br/>
                                                <span class="text-muted">(If not found new category with the given name will be created)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>
                                                Sub category <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Name of the Sub-Category <br/>
                                                <span class="text-muted">(If not found new sub-category with the given name under the
                                                    parent Category will be created)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>
                                                SKU <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>Product SKU. If blank an SKU will be automatically generated</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>
                                                Barcode Type <span class="text-muted">(Optional, Default: C128)</span>
                                            </td>
                                            <td>
                                                Barcode Type for the product. <br/>
                                                <b>Currently supported: C128, C39, EAN-13, EAN-8, UPC-A, UPC-E, ITF-14</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>
                                                Manage Stock? <span class="text-muted">(Required)</span>
                                            </td>
                                            <td>
                                                Enable or disable stock managemant <br />
                                                <strong>1 = Yes</strong> <br />
                                                <strong>0 = No</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>
                                                Alert quantity <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Alert quantity
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>
                                                Expires in <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>Product expiry period (Only in numbers)</td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td>
                                                Expiry Period Unit <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Unit for the expiry period <br/>
                                                <strong>Available Options: days, months</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td>
                                                Applicable Tax  <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Name of the Tax Rate <br /> <br />
                                                If purchase Price (Excluding Tax) is not same as
                                                Purchase Price (Including Tax)
                                                then you must supply the tax rate name.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>13</td>
                                            <td>
                                                Selling Price Tax Type <span class="text-muted">(Required)</span>
                                            </td>
                                            <td>
                                                Selling Price Tax Type <br/>
                                                <strong>Available Options: inclusive, exclusive</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>14</td>
                                            <td>
                                                Product Type <span class="text-muted">(Required)</span>
                                            </td>
                                            <td>
                                                Product Type <br/>
                                                <strong>Available Options: single, variable</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>15</td>
                                            <td>
                                                Variation Name  <span class="text-muted">(Required if product type is variable)</span>
                                            </td>
                                            <td>Name of the variation (Ex: Size, Color etc )</td>
                                        </tr>
                                        <tr>
                                            <td>16</td>
                                            <td>
                                                Variation Values <span class="text-muted">(Required if product type is variable)</span>
                                            </td>
                                            <td>
                                                Values for the variation separated with '|' <br/>
                                                (Ex: Red|Blue|Green)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>17</td>
                                            <td>
                                                Variation SKUs <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>SKUs of each variations separated by "|" if product type is variable</td>
                                        </tr>
                                        <tr>
                                            <td>18</td>
                                            <td>
                                                Purchase Price (Including Tax) <br/>
                                                <span class="text-muted">(Required if Purchase Price Excluding Tax is not given)</span>
                                            </td>
                                            <td>
                                                Purchase Price (Including Tax) (Only in numbers) <br /><br />
                                                For variable products '|' separated values with
                                                the same order as Variation Values
                                                (Ex: 84|85|88)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>19</td>
                                            <td>
                                                Purchase Price (Excluding Tax) <br/>
                                                <span class="text-muted">(Required if Purchase Price Including Tax is not given)</span>
                                            </td>
                                            <td>
                                                Purchase Price (Excluding Tax) (Only in numbers) <br/><br/>
                                                For variable products '|' separated values with
                                                the same order as Variation Values
                                                (Ex: 84|85|88)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>20</td>
                                            <td>
                                                Profit Margin % <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Profit Margin (Only in numbers) <br/>
                                                <span class="text-muted">If blank default profit margin for the
                                                    business will be used</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>21</td>
                                            <td>
                                                Selling Price <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Selling Price (Only in numbers) <br/>
                                                <span class="text-muted">If blank selling price will be calculated
                                                    with the given Purchase Price
                                                    and Applicable Tax</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>22</td>
                                            <td>
                                                Opening Stock <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Opening Stock (Only in numbers) <br/><br/>
                                                For variable products separate stock quantities with '|' <br/>
                                                (Ex: 100|150|200)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>23</td>
                                            <td>
                                                Opening stock location <span class="text-muted">(Optional)</span> <br/>
                                                <span class="text-muted">If blank first business location will be used</span>
                                            </td>
                                            <td>Name of the business location</td>
                                        </tr>
                                        <tr>
                                            <td>24</td>
                                            <td>
                                                Expiry Date <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Stock Expiry Date <br/>
                                                <strong>Format: mm-dd-yyyy; Ex: 11-25-2018</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>25</td>
                                            <td>
                                                Enable Product description, IMEI or Serial Number <br/>
                                                <span class="text-muted">(Optional, Default: 0)</span>
                                            </td>
                                            <td>
                                                <strong>1 = Yes</strong> <br/>
                                                <strong>0 = No</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>26</td>
                                            <td>
                                                Weight <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Optional
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>27</td>
                                            <td>
                                                Rack <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Rack details seperated by '|' for different business locations serially. <br/>
                                                (Ex: R1|R5|R12)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>28</td>
                                            <td>
                                                Row <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Row details seperated by '|' for different business locations serially. <br/>
                                                (Ex: ROW1|ROW2|ROW3)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>29</td>
                                            <td>
                                                Position <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Position details seperated by '|' for different business locations serially. <br/>
                                                (Ex: POS1|POS2|POS3)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>30</td>
                                            <td>
                                                Image <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                Image name with extension. <br/>
                                                (Image name must be uploaded to the server public/uploads/img ) <br/><br/>
                                                Or URL of the image
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>31</td>
                                            <td>
                                                Product Description <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>32</td>
                                            <td>
                                                Custom Field1 <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>33</td>
                                            <td>
                                                Custom Field2 <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>34</td>
                                            <td>
                                                Custom Field3 <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>35</td>
                                            <td>
                                                Custom Field4 <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>36</td>
                                            <td>
                                                Not for selling <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>
                                                <strong>1 = Yes</strong> <br/>
                                                <strong>0 = No</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>37</td>
                                            <td>
                                                Product locations <span class="text-muted">(Optional)</span>
                                            </td>
                                            <td>Comma separated string of business location names where product will be available</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!--end::Main column-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')

@endpush
