@extends('App.main.navBar')

@section('campaign_icon', 'active')
@section('campaign_show', 'active show')
@section('campaign_list_active', 'active')

@section('styles')

@endsection
@section('title')

@endsection

@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="location">
            <div class="card" data-kt-sticky="true" data-kt-sticky-name="invoice"
                data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '250px', lg: '300px'}"
                data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="false"
                data-kt-sticky-zindex="95">

                <!--begin::Card body-->
                <div class="card-body p-10">
                    <!--begin::Input group-->
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="form-label fw-bold fs-6 text-gray-700">Paper Size</label>
                        <!--end::Label-->

                        <!--begin::Select-->
                        <select name="paperSize" id="paperSize" aria-label="Select a Papersize" data-kt-select2="true"  data-status="filter"  data-kt-select2="true" data-hide-search="false" data-allow-clear="true"  data-hide-search="true"
                            data-placeholder="Select Papersize" class="form-select form-select-solid">

                            <option value="A4"><b>A4</option>
                            <option value="A3"><b>A3</option>

                            <option value="A5"><b>A5</option>
                            <option value="Legal"><b>Legal</option>
                            <option value="80mm"><b>80mm</option>
                        </select>
                        <!--end::Select-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Separator-->
                    <div class="separator separator-dashed mb-8"></div>
                    <!--begin::Input group-->

                    <!--begin::Option-->
                    <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                            Supplier Name
                        </span>

                        <input class="form-check-input" type="checkbox" name="supplierName" />
                    </label>
                    <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                            Phone Number
                        </span>

                        <input class="form-check-input" type="checkbox" name="phone" />
                    </label>
                    <!--end::Option-->

                    <!--begin::Option-->
                    <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                            Purchase Status
                        </span>

                        <input class="form-check-input" type="checkbox" name="purchaseStatus" />
                    </label>

                    <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                            Customer Name
                        </span>

                        <input class="form-check-input" type="checkbox" name="purchaseStatus" />
                    </label>

                    <!--end::Option-->

                    <div class="separator separator-dashed mb-8"></div>


                    <div class="row">
                        <label class="form-label fw-bold fs-6 text-gray-700">Choose Extra Columns</label>
                        <div class="col-md-3">
                            <label class="form-check-label ms-0 fs-6 text-gray-700" for="">Number</label>
                            <input type="checkbox" name="" class="column">
                        </div>
                        <div class="col-md-3">
                            <label class="form-check-label ms-0 fs-6 text-gray-700" for="">Description</label>
                            <input type="checkbox" name=""class="column">
                        </div>
                        <div class="col-md-3">
                            <label class="form-check-label ms-0 fs-6 text-gray-700" for="">Expense</label>
                            <input type="checkbox" name="" class="column">
                        </div>
                        <div class="col-md-3">
                            <label class="form-check-label ms-0 fs-6 text-gray-700" for="">Descount</label>
                            <input type="checkbox" name="" class="column">
                        </div>

                    </div>

                </div>
                <!--end::Container-->
            </div>
            <button type="submit" class="mt-5 btn btn-success">Save</button>

        </div>

    </div>
@endsection

@push('scripts')
<script src={{asset('customJs/Ajax/getAccountByCurrency.js')}}></script>
    <script>
        const paperSizeSelectBox = document.getElementById('paperSize');
        paperSizeSelectBox.addEventListener('change', function() {

            let value = paperSizeSelectBox.value;
            const columns = document.querySelectorAll('.column');

            if (value === "80mm") {
                columns.forEach(column => {
                    column.checked = false;
                });
            } else {
                columns.forEach(column => {
                    column.checked = true;
                });
            }
        })
    </script>
@endpush
