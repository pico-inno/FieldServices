@extends('App.main.navBar')

@section('styles')
    <style>

    </style>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <h1 class="mb-10">Print Labels</h1>
            {{-- first card start --}}
            <div class="card">
                <div class="mt-10 ms-10 fs-2">
                    Add products to generate Labels
                </div>

                <div class="card-body">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control" placeholder="Enter products name to print labels"
                            aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <table class="table table-bordered table-striped table-condensed" id="product_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Products</th>
                                <th>No. of labels</th>
                                <th>Lot Number</th>
                                <th>EXP Date</th>
                                <th>Packing Date</th>
                                <th>Selling Price Group</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="">
                                <td></td>
                                <td class="text-enter mt-18">luxsoap</td>
                                <td>
                                    <input type="number" class="form-control" min="1" name="products[0][quantity]"
                                        value="20.0000">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="products[0][lot_number]"
                                        value="">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="products[0][exp_date]" value="">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="products[0][packing_date]"
                                        value="">
                                </td>
                                <td>
                                    <select class="form-control form-select" name="products[0][price_group_id]">
                                        <option selected="selected" value="">None</option>
                                        <option value="3">10pcs price</option>
                                        <option value="4">50pcs price</option>
                                        <option value="5">100pcs price</option>
                                        <option value="15">Wholesale 1</option>
                                        <option value="16">Wholesale 2</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- first card end --}}
            {{-- start second card --}}
            <div class="card p-5 mt-10">
                <div class="box-header">
                    <h3 class="box-title">Information to show in Labels</h3>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <label class="mb-3">
                                                <input type="checkbox" checked="" name="print[name]"
                                                    value="1"> <b>Product Name</b>
                                            </label>
                                        </div>

                                        <div class="input-group">
                                            <span class="input-group-text"><b>Size</b></span>
                                            <input type="text" class="form-control" name="print[name_size]"
                                                value="15">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox">
                                            <label  class="mb-3">
                                                <input type="checkbox" checked="" name="print[variations]"
                                                    value="1"> <b>Product Variation (recommended)</b>
                                            </label>
                                        </div>

                                        <div class="input-group">
                                            <div class="input-group-text"><b>Size</b></div>
                                            <input type="text" class="form-control" name="print[variations_size]"
                                                value="17">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox">
                                            <label  class="mb-3">
                                                <input type="checkbox" checked="" name="print[price]"
                                                    value="1" id="is_show_price"> <b>Product Price</b>
                                            </label>
                                        </div>

                                        <div class="input-group">
                                            <div class="input-group-text"><b>Size</b></div>
                                            <input type="text" class="form-control" name="print[price_size]"
                                                value="17">
                                        </div>

                                    </td>

                                    <td>

                                        <div class="" id="price_type_div">
                                            <div class="form-group">
                                                <label  class="mb-3" for="print[price_type]"><b>Show Price:</b></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-info"></i>
                                                    </span>
                                                    <select class="form-control" id="print[price_type]"
                                                        name="print[price_type]">
                                                        <option value="inclusive" selected="selected">Inc. tax
                                                        </option>
                                                        <option value="exclusive">Exc. tax</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <label  class="mb-3">
                                                <input type="checkbox" checked="" name="print[business_name]"
                                                    value="1"> <b>Business name</b>
                                            </label>
                                        </div>

                                        <div class="input-group">
                                            <div class="input-group-text"><b>Size</b></div>
                                            <input type="text" class="form-control"
                                                name="print[business_name_size]" value="20">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox">
                                            <label  class="mb-3">
                                                <input type="checkbox" checked="" name="print[packing_date]"
                                                    value="1"> <b>Print packing date</b>
                                            </label>
                                        </div>

                                        <div class="input-group">
                                            <div class="input-group-text"><b>Size</b></div>
                                            <input type="text" class="form-control"
                                                name="print[packing_date_size]" value="12">
                                        </div>
                                    </td>

                                    <td>

                                        <div class="checkbox">
                                            <label  class="mb-3">
                                                <input type="checkbox" checked="" name="print[lot_number]"
                                                    value="1"> <b>Print lot number</b>
                                            </label>
                                        </div>

                                        <div class="input-group">
                                            <div class="input-group-text"><b>Size</b></div>
                                            <input type="text" class="form-control"
                                                name="print[lot_number_size]" value="12">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="checkbox">
                                            <label  class="mb-3">
                                                <input type="checkbox" checked="" name="print[exp_date]"
                                                    value="1"> <b>Print expiry date</b>
                                            </label>
                                        </div>

                                        <div class="input-group">
                                            <div class="input-group-text"><b>Size</b></div>
                                            <input type="text" class="form-control"
                                                name="print[exp_date_size]" value="12">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-12">
                        <hr>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label  class="mb-3" for="price_type">Barcode setting:</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-cog"></i>
                                </span>
                                <select class="form-control form-select" name="barcode_setting">
                                    <option value="1">20 Labels per Sheet, Sheet Size: 8.5" x 11", Label Size:
                                        4" x 1", Labels per sheet: 20</option>
                                    <option value="2">30 Labels per sheet, Sheet Size: 8.5" x 11", Label Size:
                                        2.625" x 1", Labels per sheet: 30</option>
                                    <option value="3">32 Labels per sheet, Sheet Size: 8.5" x 11", Label Size:
                                        2" x 1.25", Labels per sheet: 32</option>
                                    <option value="4">40 Labels per sheet, Sheet Size: 8.5" x 11", Label Size:
                                        2" x 1", Labels per sheet: 40</option>
                                    <option value="5">50 Labels per Sheet, Sheet Size: 8.5" x 11", Label Size:
                                        1.5" x 1", Labels per sheet: 50</option>
                                    <option value="6">Continuous Rolls - 31.75mm x 25.4mm, Label Size: 31.75mm
                                        x 25.4mm, Gap: 3.18mm</option>
                                    <option value="7">PW11cm PH 1.7cm 4 column W2.5cm x H1.5cm, 4 column
                                        W2.5cm x H1.5cm</option>
                                    <option value="8" selected="selected">Continus PW11cm PH 1.7cm 4 column
                                        W2.5cm x H1.5cm, </option>
                                    <option value="9">PW 3.5cm PH 2.03cm 1column W3cm x H2cm, </option>
                                    <option value="13">PW 10cm PH 1.9cm 3cols W3.2cm x H1.9cm, </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-4 col-sm-offset-8 mt-10 w-100 d-flex justify-content-end">
                        <button type="button " id="labels_preview"
                            class="btn btn-primary btn-flat btn-block px-15">Preview</button>
                    </div>
                </div>
                </div>
            </div>
            {{-- end second card --}}
        </div>
        <!--end::Container-->
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
