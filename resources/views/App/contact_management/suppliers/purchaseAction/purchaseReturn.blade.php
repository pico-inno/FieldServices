@extends('App.main.navBar')

@section('styles')
    <style>

    </style>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <h1 class="mb-10">Purchase Return</h1>
            {{-- first card start --}}
            <div class="card">
                <div class="card-body">
                    <div class="box box-primary">
                        <div class="box-header mb-19">
                            <h3 class="box-title fs-2">Parent Purchase</h3>
                        </div>

                        <div class="box-body fs-3">
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong>Reference No:</strong> PO2022/0003 <br>
                                    <strong>Date:</strong> 13-11-2022
                                </div>
                                <div class="col-sm-4">
                                    <strong>Supplier:</strong> <br>
                                    <strong>Business Location:</strong> Demo Business
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
            {{-- first card end --}}
            {{-- start second card --}}
            <div class="card p-5 mt-10">
                <div class="box box-primary fs-3">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="ref_no" class=" fw-bold mb-3">Reference No:</label>
                                    <input class="form-control" name="ref_no" type="text" value="2023/0002"
                                        id="ref_no">
                                </div>
                            </div>
                            <div class="clearfix mb-10"></div>
                            <hr>
                            <div class="col-sm-12">
                                <table class="table bg-gray" id="purchase_return_table">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th></th>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>Unit Price</th>
                                            <th>Purchase Quantity</th>
                                            <th>Quantity Remaining</th>
                                            <th>Return Quantity</th>
                                            <th>Return Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-secondary">
                                        <tr>
                                            <td></td>
                                            <td>1</td>
                                            <td>
                                                A040 နောက်ကြိုးရှုတ်
                                            </td>
                                            <td><span class="display_currency" data-currency_symbol="true">Ks 2,800</span>
                                            </td>
                                            <td><span class="display_currency" data-is_quantity="true"
                                                    data-currency_symbol="false">100.00</span> Pc(s)</td>
                                            <td><span class="display_currency" data-currency_symbol="false"
                                                    data-is_quantity="true">55.00</span> Pc(s)</td>
                                            <td>
                                                <input type="text" name="returns[30]" value="0.00"
                                                    class="form-control input-sm input_number return_qty input_quantity"
                                                    data-rule-abs_digit="true"
                                                    data-msg-abs_digit="Decimal value not allowed" data-rule-max-value="55"
                                                    data-msg-max-value="Only 55 Pc(s) available">
                                                <input type="hidden" class="unit_price" value="2,800">
                                            </td>
                                            <td>
                                                <div class="return_subtotal">Ks 0</div>

                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>2</td>
                                            <td>
                                                P1120 Half Moon Bag
                                            </td>
                                            <td><span class="display_currency" data-currency_symbol="true">Ks 9,800</span>
                                            </td>
                                            <td><span class="display_currency" data-is_quantity="true"
                                                    data-currency_symbol="false">50.00</span> Pc(s)</td>
                                            <td><span class="display_currency" data-currency_symbol="false"
                                                    data-is_quantity="true">0.00</span> Pc(s)</td>
                                            <td>
                                                <input type="text" name="returns[31]" value="0.00"
                                                    class="form-control input-sm input_number return_qty input_quantity"
                                                    data-rule-abs_digit="true"
                                                    data-msg-abs_digit="Decimal value not allowed" data-rule-max-value="0"
                                                    data-msg-max-value="Only 0 Pc(s) available">
                                                <input type="hidden" class="unit_price" value="9,800">
                                            </td>
                                            <td>
                                                <div class="return_subtotal">Ks 0</div>

                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Total Return Tax: </strong>
                                <span id="total_return_tax">Ks 0</span> <input name="tax_id" type="hidden">
                                <input id="tax_amount" name="tax_amount" type="hidden" value="0">
                                <input id="tax_percent" name="tax_percent" type="hidden" value="0">
                            </div>
                        </div>
                        <div class="row ">
                            <div class="d-flex justify-content-end col-sm-12 text-right">
                                <strong>Return Total: </strong>&nbsp;
                                <span id="net_return">Ks 0</span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary pull-right">Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
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
