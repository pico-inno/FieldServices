@extends('App.main.navBar')

@section('styles')
    <style>
        /* adjust modal width */
        .modal-xl {
            max-width: 93%;
        }

        th{
            padding: 3rem;
            margin: 3rem;
        }

        tr{
            margin: 3rem;
        }


    </style>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body pt-15">
                            <!--begin::Summary-->
                            <div class="d-flex flex-center flex-column mb-5">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-100px symbol-circle mb-7">
                                    <img src="assets/media/avatars/300-1.jpg" alt="image" />
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <a href="#" class="fs-3 text-gray-8 fw-bold mb-1">Max Smith</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="fs-5 fw-semibold text-muted mb-6">Software Enginer</div>
                                <!--end::Position-->
                                <!--begin::Info-->
                                <div class="d-flex flex-wrap flex-center">
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-75px">6,900</span>
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
                                            <span class="svg-icon svg-icon-3 svg-icon-success">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="13" y="6" width="13"
                                                        height="2" rx="1" transform="rotate(90 13 6)"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </div>
                                        <div class="fw-semibold text-muted">Earnings</div>
                                    </div>
                                    <!--end::Stats-->
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-50px">130</span>
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr065.svg-->
                                            <span class="svg-icon svg-icon-3 svg-icon-danger">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="11" y="18" width="13"
                                                        height="2" rx="1" transform="rotate(-90 11 18)"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </div>
                                        <div class="fw-semibold text-muted">Tasks</div>
                                    </div>
                                    <!--end::Stats-->
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-50px">500</span>
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
                                            <span class="svg-icon svg-icon-3 svg-icon-success">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="13" y="6" width="13"
                                                        height="2" rx="1" transform="rotate(90 13 6)"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </div>
                                        <div class="fw-semibold text-muted">Hours</div>
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Summary-->
                            <!--begin::Details toggle-->
                            <div class="d-flex flex-stack fs-4 py-3">
                                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse"
                                    href="#kt_customer_view_details" role="button" aria-expanded="false"
                                    aria-controls="kt_customer_view_details">Details
                                    <span class="ms-2 rotate-180">
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </span>
                                </div>
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit customer details">
                                    {{-- <a href="{{ route('contacts#editCustomerPage') }}" class="btn btn-sm btn-light-primary">Edit</a> --}}
                                </span>
                            </div>
                            <!--end::Details toggle-->
                            <div class="separator separator-dashed my-3"></div>
                            <!--begin::Details content-->
                            <div id="kt_customer_view_details" class="collapse show">
                                <div class="py-5 fs-6">
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Contact ID</div>
                                    <div class="text-gray-600">ID-45453423</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Contact Type</div>
                                    <div class="text-gray-600">Customer</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Name</div>
                                    <div class="text-gray-600">Hein Htike</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Business Name</div>
                                    <div class="text-gray-600">HHZBUSINESS</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Mobile</div>
                                    <div class="text-gray-600">091234567</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Billing Email</div>
                                    <div class="text-gray-600">
                                        <a href="#" class="text-gray-600 text-hover-primary">info@keenthemes.com</a>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Billing Address</div>
                                    <div class="text-gray-600">101 Collin Street,
                                        <br />Melbourne 3000 VIC
                                        <br />Australia
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Tax Number</div>
                                    <div class="text-gray-600">TX-8674</div>
                                    <!--begin::Details item-->
                                </div>
                            </div>
                            <!--end::Details content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->

                </div>
                <!--end::Sidebar-->
                <!--begin::Content-->
                <div class="flex-lg-row-fluid ms-lg-15">
                    <!--begin:::Tabs-->
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">

                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-kt-countup-tabs="true" data-bs-toggle="tab"
                                href="#kt_customer_view_ledger">Ledger</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                                href="#kt_customer_view_sales">Sales</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                                href="#kt_customer_view_subscriptions">Subscriptions</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                                href="#kt_customer_view_documentAndNotes">Documents and Note</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                                href="#kt_customer_view_payment">Payments</a>
                        </li>
                        <!--end:::Tab item-->
                         <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                                href="#kt_customer_view_activities">Activities</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item ms-auto">
                           {{-- <a href="{{ route('contacts#discountPage') }}" class="btn btn-sm btn-primary">Add Discount</a> --}}
                        </li>
                        <!--end:::Tab item-->
                    </ul>
                    <!--end:::Tabs-->
                    <!--begin:::Tab content-->
                    <div class="tab-content" id="myTabContent">
                        <!--begin:::Ledger-->
                        <div class="tab-pane fade show active" id="kt_customer_view_ledger" role="tabpanel">
                            <!--begin::Earnings-->
                            <div class="card mb-6 mb-xl-9">
                                <div class="card-body">
                                    <!--first row-->
                                    <div class="row align-items-center d-flex">
                                        <div class="form-group col">
                                            <label class="fs-3 mb-5" for="daterange">Date Range :</label>
                                            <input type="date" class="form-control" id="daterange"
                                                name="daterange">
                                        </div>
                                        <div class="form-group col">
                                            <label class="fs-3 mb-5">Choose Business Location :</label>
                                            <select class="form-select form-select-lg"
                                                aria-label=".form-select-lg example">
                                                <option value="allLocations">All Locations :</option>
                                                <option value="months">Demo Business</option>
                                                <option value="days">Yankin (Yankinshop)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- second row -->
                                    <div class="row align-items-center d-flex mt-10">
                                        <div class="form-group col">
                                            <label class="mb-5 fs-3" for="businessLocation">Business Location : </label>
                                            <textarea class="form-control" name="businessLocation" id="businessLocation" cols="30" rows="4">၅၅လမ်း၊ ၁၃၂ လမ်း နှင့် ၁၃၃လမ်းကြား၊ ပြည်ကြီးတံခွန်မြို့နယ်၊ မန္တလေးမြို့။, Mandalay, Mandalay, 05031 Myanmar
                                                            </textarea>
                                        </div>
                                        <div class="form-group col text-center">
                                            <label class="fs-3 mb-5">Statement Date : </label>
                                            <div>01-03-2022 To 31-03-2022</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Earnings-->
                            <!--begin::first card-->
                            <div class="card mb-6 mb-xl-9">
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5"
                                        id="kt_customers_table">
                                        <!--begin::Table head-->
                                        <thead>
                                            <!--begin::Table row-->
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="w-10px pe-2">
                                                    <div
                                                        class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            data-kt-check="true"
                                                            data-kt-check-target="#kt_customers_table .form-check-input"
                                                            value="1" />
                                                    </div>
                                                </th>
                                                <th class="min-w-125px">Date</th>
                                                <th class="min-w-125px">Transaction</th>
                                                <th class="min-w-125px">Amount</th>
                                                <th class="min-w-125px">Balance</th>
                                            </tr>
                                            <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-semibold text-gray-600">
                                            <tr>
                                                <!--begin::Checkbox-->
                                                <td>
                                                    <div
                                                        class="form-check form-check-sm form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="1" />
                                                    </div>
                                                </td>
                                                <!--end::Checkbox-->
                                                <!--begin::Name=-->
                                                <td>
                                                    <a href="../../demo7/dist/apps/customers/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">14 Dec 2020, 8:43
                                                        pm</a>
                                                </td>
                                                <!--end::Name=-->
                                                <!--begin::Email=-->
                                                <td>
                                                    <a href="#" class="text-gray-600 text-hover-primary mb-1">
                                                        Purchase PO2023/0012 Due 14-02-2023</a>
                                                </td>
                                                <!--end::Email=-->
                                                <!--begin::Payment method=-->
                                                <td>Ks 80,000</td>
                                                <!--end::Payment method=-->
                                                <!--begin::Date=-->
                                                <td>Ks 80,000</td>
                                                <!--end::Date=-->
                                                <!--begin::Action=-->

                                            </tr>
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::first card-->
                            <!--begin::second card-->
                            <div class="card mb-6 mb-xl-9">
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5"
                                        id="kt_customers_table">
                                        <!--begin::Table head-->
                                        <thead>
                                            <!--begin::Table row-->
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="w-10px pe-2">
                                                    <div
                                                        class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            data-kt-check="true"
                                                            data-kt-check-target="#kt_customers_table .form-check-input"
                                                            value="1" />
                                                    </div>
                                                </th>
                                                <th class="min-w-125px">Current</th>
                                                <th class="min-w-125px text-success">1-30 DAYS PAST DUE</th>
                                                <th class="min-w-125px text-warning">30-60 DAYS PAST DUE</th>
                                                <th class="min-w-125px text-warning">60-90 DAYS PAST DUE</th>
                                                <th class="min-w-125px text-danger">OVER 90 DAYS PAST DUE</th>
                                                <th class="min-w-125px">AMOUNT DUE</th>
                                            </tr>
                                            <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-semibold text-gray-600">
                                            <tr>

                                                <td>
                                                    <div
                                                        class="form-check form-check-sm form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="1" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="../../demo7/dist/apps/customers/view.html"
                                                        class="text-gray-800 text-hover-primary mb-1">Ks 0 </a>
                                                </td>
                                                <td class="text-primary">Ks 80,000</td>
                                                <td class="text-warning">Ks 0</td>
                                                <td class="text-warning">Ks 0</td>
                                                <td class="text-danger">Ks 0</td>
                                                <td>Ks 80,000</td>

                                            </tr>
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::second card-->

                        </div>
                        <!--end:::Ledger-->

                        <!--begin:::Sales-->
                        <div class="tab-pane fade" id="kt_customer_view_sales" role="tabpanel">
                            <!--begin::first card-->
                            <div class="card mb-6 mb-xl-9">
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-4 mt-5">
                                            <label class="fs-3 mb-5" for="daterange">Payment Status :</label>
                                            <select name="" class="form-select from-control" id="">
                                                <option value="all">All</option>
                                                <option value="paid">Paid</option>
                                                <option value="due">Due</option>
                                                <option value="partial">Partial</option>
                                                <option value="overdue">Overdue</option>
                                            </select>

                                        </div>
                                        <div class="form-group col-8 mt-5">
                                            <label class="fs-3 mb-5" for="daterange">Date Range :</label>
                                           <div class="row d-flex align-items-center">
                                                <div class="col">
                                                    <input type="date" class="form-control " id="daterange" name="daterange">
                                                </div>
                                                <div class="col offset-1 d-flex align-items-center">
                                                    <input type="checkbox" class=" me-3" name="" id="Subscriptions">
                                                    <span class="fs-3" for="Subscriptions"> Subscriptions</span>
                                                </div>
                                           </div>
                                        </div>

                                    </div>

                                    <div class="d-flex align-items-center mt-7 mb-10 row">
                                        <div class="col-3">
                                            Show <select name="show" id="" class="p-1">
                                                <option value="25">25</option>
                                                <option value="25">50</option>
                                                <option value="25">100</option>
                                                <option value="25">200</option>
                                                <option value="25">500</option>
                                                <option value="25">1000</option>
                                                <option value="25">All</option>
                                            </select> entries
                                        </div>

                                        <div class="col-6">
                                            <div class="btn-group me-2  border" style="hover:" role="group" aria-label="First group">
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Export to Excel </button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Print</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Column
                                                    Visibility</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Export to PDF</button>
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <input type="text" placeholder="Search..."
                                                class="border-out form-control w-75">
                                        </div>
                                    </div>
                                    <!--begin::Table-->
                                    <div style="overflow-x: auto;">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 "
                                            id="kt_customers_table">
                                            <!--begin::Table head-->
                                            <thead>
                                                <!--begin::Table row-->
                                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                    <th style="min-width: 85px;">Action</th>
                                                    <th class="min-w-75px">Date</th>
                                                    <th class="min-w-50px">Invoice No</th>
                                                    <th class="min-w-50px">Customer Name</th>
                                                    <th class="min-w-50px">Contact Number</th>
                                                    <th class="min-w-50px">Location</th>
                                                    <th class="min-w-50px">Payment Status</th>
                                                    <th class="min-w-50px">Payment Method</th>
                                                    <th class="min-w-50px">Total Amount</th>
                                                    <th class="min-w-50px">Total Paid</th>
                                                    <th class="min-w-50px">Sell Due</th>
                                                    <th class="min-w-50px">Sell Return Due</th>
                                                    <th class="min-w-50px">Shipping Status</th>
                                                    <th class="min-w-50px">Total Items</th>
                                                    <th class="min-w-50px">Added By</th>
                                                    <th class="min-w-50px">Sell Note</th>
                                                    <th class="min-w-50px">Staff Note</th>
                                                    <th class="min-w-50px">Shipping Details</th>
                                                </tr>
                                                <!--end::Table row-->
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-semibold text-gray-600">
                                                <tr>
                                                    <td>
                                                        <a href="#"
                                                            class="btn btn-sm p-2 btn-light btn-active-light-primary"
                                                            data-kt-menu-trigger="click"
                                                            data-kt-menu-placement="bottom-end">Actions
                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                            <span class="svg-icon ms-0 svg-icon-5 m-0">
                                                                <svg width="24" height="24" viewBox="0 0 24 24"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                        fill="currentColor" />
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->
                                                        </a>
                                                        <!--begin::Menu-->
                                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4"
                                                            data-kt-menu="true">
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#purchase_view" data-bs-toggle="modal" class="menu-link px-3">View</a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">Edit</a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">Delete</a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">Edit Shipping</a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">Print Invoice</a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">Packing Slip</a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                            <hr>
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">View Payments</a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">Sell Return</a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">Invoice URL</a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">New Sale Notification</a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                        </div>
                                                        <!--end::Menu-->
                                                    </td>
                                                    <td>14-02-2023 16:59</td>
                                                    <td>INV-2023-0000051</td>
                                                    <td>Hein Htike</td>
                                                    <td>09456787667</td>
                                                    <td>Demo Business</td>
                                                    <td>Paid</td>
                                                    <td>Cash</td>
                                                    <td>Ks 80,000</td>
                                                    <td>Ks 80,000</td>
                                                    <td>Ks 0</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>2.00</td>
                                                    <td>Demo</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>

                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::first card-->
                        </div>
                        <!--end:::Sales-->

                        <!--begin:::subscriptions-->
                        <div class="tab-pane fade" id="kt_customer_view_subscriptions" role="tabpanel">
                            <!--begin::first card-->
                            <div class="card mb-6 mb-xl-9">
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                   <div class="form-group col mt-5">
                                        <label class="fs-3 mb-5" for="daterange">Date Range :</label>
                                        <input type="date" class="form-control" id="daterange" name="daterange">
                                    </div>
                                    <div class="d-flex align-items-center mt-7 mb-10 row">
                                        <div class="col-3">
                                            Show <select name="show" id="" class="p-1">
                                                <option value="25">25</option>
                                                <option value="25">50</option>
                                                <option value="25">100</option>
                                                <option value="25">200</option>
                                                <option value="25">500</option>
                                                <option value="25">1000</option>
                                                <option value="25">All</option>
                                            </select> entries
                                        </div>

                                        <div class="col-6">
                                            <div class="btn-group me-2 " role="group" aria-label="First group">
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Export to Excel</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Print</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Column
                                                    Visibility</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Export to PDF</button>
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <input type="text" placeholder="Search..."
                                                class="border-out form-control w-75">
                                        </div>
                                    </div>
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5"
                                        id="kt_customers_table">
                                        <!--begin::Table head-->
                                        <thead>
                                            <!--begin::Table row-->
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-50px">Date</th>
                                                <th class="min-w-50px">Subscription No.</th>
                                                <th class="min-w-50px">Customer name</th>
                                                <th class="min-w-50px">Subscription Interval</th>
                                                <th class="min-w-50px">No. of Repetitions</th>
                                                <th class="min-w-50px">Generated Invoices</th>
                                                <th class="min-w-50px">Last generated</th>
                                                <th class="min-w-50px">Upcoming invoice</th>
                                                <th class="min-w-50px">Action</th>
                                            </tr>
                                            <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-semibold text-gray-600">
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <div class="text-center w-100">no data available in table</div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::first card-->
                        </div>
                        <!--end:::subscriptions-->

                        <!--begin:::Document and notes-->
                        <div class="tab-pane fade" id="kt_customer_view_documentAndNotes" role="tabpanel">
                            <!--begin::first card-->
                            <div class="card mb-6 mb-xl-9">
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <div class="form-group col mt-5">
                                        <label class="fs-3 mb-5" for="daterange">Date Range :</label>
                                        <input type="date" class="form-control " id="daterange" name="daterange">
                                    </div>
                                    <div class="d-flex align-items-center mt-7 mb-10 row">
                                        <div class="col-3">
                                            Show <select name="show" id="" class="p-1">
                                                <option value="25">25</option>
                                                <option value="25">50</option>
                                                <option value="25">100</option>
                                                <option value="25">200</option>
                                                <option value="25">500</option>
                                                <option value="25">1000</option>
                                                <option value="25">All</option>
                                            </select> entries
                                        </div>

                                        <div class="col-6">
                                            <div class="btn-group me-2 " role="group" aria-label="First group">
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Export to Excel</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Print</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Column
                                                    Visibility</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Export to PDF</button>
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <input type="text" placeholder="Search..."
                                                class="border-out form-control w-75">
                                        </div>
                                    </div>
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5"
                                        id="kt_customers_table">
                                        <!--begin::Table head-->
                                        <thead>
                                            <!--begin::Table row-->
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-50px">Action</th>
                                                <th class="min-w-50px">Heading</th>
                                                <th class="min-w-50px">Added By</th>
                                                <th class="min-w-50px">Created At</th>
                                                <th class="min-w-50px">Updated At</th>
                                            </tr>
                                            <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-semibold text-gray-600">
                                            <tr class="text-center">
                                                {{-- <td>A040 နောက်ကြိုးရှုတ် (0357)</td>
                                                <td>0357</td>
                                                <td>100.00 Pc(s)</td>
                                                <td>22.00 Pc(s)</td>
                                                <td>22.00 Pc(s)</td>
                                                <td>0.0000</td>
                                                <td>56.00 Pc(s)</td>
                                                <td>Ks 156,800</td> --}}
                                                {{-- <td class="text-center border w-100">no data available in table</td> --}}
                                            </tr>
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <div class="text-center w-100">no data available in table</div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::first card-->
                        </div>
                        <!--end:::Purchase-->

                        <!--begin:::payment-->
                        <div class="tab-pane fade" id="kt_customer_view_payment" role="tabpanel">
                            <!--begin::first card-->
                            <div class="card mb-6 mb-xl-9">
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <div class="form-group col mt-5">
                                        <label class="fs-3 mb-5" for="daterange">Date Range :</label>
                                        <input type="date" class="form-control " id="daterange" name="daterange">
                                    </div>
                                    <div class="d-flex align-items-center mt-7 mb-10 row">
                                        <div class="col-3">
                                            Show <select name="show" id="" class="p-1">
                                                <option value="25">25</option>
                                                <option value="25">50</option>
                                                <option value="25">100</option>
                                                <option value="25">200</option>
                                                <option value="25">500</option>
                                                <option value="25">1000</option>
                                                <option value="25">All</option>
                                            </select> entries
                                        </div>

                                        <div class="col-6">
                                            <div class="btn-group me-2 " role="group" aria-label="First group">
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8 ">Export to Excel</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Print</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Column
                                                    Visibility</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Export to PDF</button>
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <input type="text" placeholder="Search..."
                                                class="border-out form-control w-75">
                                        </div>
                                    </div>
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5"
                                        id="kt_customers_table">
                                        <!--begin::Table head-->
                                        <thead>
                                            <!--begin::Table row-->
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-50px">Padid on</th>
                                                <th class="min-w-50px">Reference No.</th>
                                                <th class="min-w-50px">Amount</th>
                                                <th class="min-w-50px">Payment Method</th>
                                                <th class="min-w-50px">Payment For</th>
                                                <th class="min-w-50px">Action</th>
                                            </tr>
                                            <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-semibold text-gray-600">
                                            <tr>
                                                <td>14-03-2023 10:51 PM</td>
                                                <td>PP2022/0001</td>
                                                <td>50000.0000</td>
                                                <td>Cash</td>
                                                <td>PO2022/0003 (Purchase)</td>
                                                <td>
                                                    <a href="#"
                                                        class="btn btn-sm p-2 btn-light btn-active-light-primary"
                                                        data-kt-menu-trigger="click"
                                                        data-kt-menu-placement="bottom-end">Actions
                                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                        <span class="svg-icon ms-0 svg-icon-5 m-0">
                                                            <svg width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                    </a>
                                                    <!--begin::Menu-->
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4"
                                                        data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#view_payment" data-bs-toggle="modal" class="menu-link px-3">View</a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            {{-- <a href="{{ route('contacts#editPaymentPage') }}" class="menu-link px-3">Edit</a> --}}
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3">Delete</a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                    </div>
                                                    <!--end::Menu-->
                                                </td>

                                            </tr>
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::first card-->
                        </div>
                        <!--end:::payment-->

                        <!--begin:::activities-->
                        <div class="tab-pane fade" id="kt_customer_view_activities" role="tabpanel">
                            <!--begin::first card-->
                            <div class="card mb-6 mb-xl-9">
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <div class="form-group col mt-5">
                                        <label class="fs-3 mb-5" for="daterange">Date Range :</label>
                                        <input type="date" class="form-control " id="daterange" name="daterange">
                                    </div>
                                    <div class="d-flex align-items-center mt-7 mb-10 row">
                                        <div class="col-3">
                                            Show <select name="show" id="" class="p-1">
                                                <option value="25">25</option>
                                                <option value="25">50</option>
                                                <option value="25">100</option>
                                                <option value="25">200</option>
                                                <option value="25">500</option>
                                                <option value="25">1000</option>
                                                <option value="25">All</option>
                                            </select> entries
                                        </div>

                                        <div class="col-6">
                                            <div class="btn-group me-2" role="group" aria-label="First group">
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Export to Excel</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Print</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Column
                                                    Visibility</button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary border fs-8">Export to PDF</button>
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <input type="text" placeholder="Search..."
                                                class="border-out form-control w-75">
                                        </div>
                                    </div>
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5"
                                        id="kt_customers_table">
                                        <!--begin::Table head-->
                                        <thead>
                                            <!--begin::Table row-->
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-50px">Date</th>
                                                <th class="min-w-50px">Action</th>
                                                <th class="min-w-50px">By</th>
                                                <th class="min-w-50px">Note</th>
                                            </tr>
                                            <!--end::Table row-->
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-semibold text-gray-600">
                                            <tr>
                                                <td>12-11-2022 08:10</td>
                                                <td>Added</td>
                                                <td>Demo</td>
                                                <td>Main Customer</td>
                                            </tr>
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::first card-->
                        </div>
                        <!--end:::activities-->
                    </div>
                    <!--end:::Tab content-->

                    {{-- start modals --}}

                    {{-- start modal for view purchase --}}
                    <div class="modal fade fs-3" id="purchase_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="myModalLabel">Purchase Details (Reference No: #PO2022/0003)</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                </div>
                                <div class="modal-body fs-4">
                                    <div class="row">
                                        <div class="col-3">
                                            <div>Supplier: APK</div>
                                            <div>Mobile: 09753498375</div>
                                        </div>
                                        <div class="col-4">
                                            <div>Business: Demo's Business</div>
                                            <div>Location: ၅၅လမ်း၊ ၁၃၂ လမ်း နှင့် ၁၃၃လမ်းကြား၊ ပြည်ကြီးတံခွန်မြို့နယ်၊ မန္တလေးမြို့။ Mandalay,Mandalay,Myanmar</div>
                                        </div>
                                        <div class="col-3">
                                            <div>Reference No: #PO2023/0012</div>
                                            <div>Date: 14-02-2023</div>
                                            <div>Purchase Status: Received</div>
                                            <div>Payment Status: Due</div>
                                            <div>Test:</div>
                                        </div>
                                        <div class="col-2">
                                            <div>Date: 14-02-2023</div>
                                        </div>
                                    </div>

                                    <div class=" my-10">
                                        <div class="table ">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5 p-10" id="kt_customers_table">
                                                <thead class="bg-primary">
                                                    <tr class="text-start text-white fw-bold fs-7 text-uppercase gs-0">
                                                        <th></th>
                                                        <th>No.</th>
                                                        <th>Product Name</th>
                                                        <th>SKU      </th>
                                                        <th>Purchase Quantity</th>
                                                        <th>Unit Cost (Before Discount)</th>
                                                        <th>Discount Percent</th>
                                                        <th>Unit Cost (Before Tax)</th>
                                                        <th>Subtotal (Before Tax)</th>
                                                        <th>Tax</th>
                                                        <th>Unit Cost Price (After Tax)</th>
                                                        <th>Unit Selling Price</th>
                                                        <th>Lot Number</th>
                                                        <th>MFG Date</th>
                                                        <th>EXP Date</th>
                                                        <th>Subtotal</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>

                                                <tbody class="bg-secondary">
                                                    <tr>
                                                        <td></td>
                                                        <td>1.</td>
                                                        <td>luxsoap</td>
                                                        <td>0370</td>
                                                        <td>20.00 Pc(s)</td>
                                                        <td>Ks 4,000</td>
                                                        <td>0 %</td>
                                                        <td>4,000</td>
                                                        <td>Ks 80,000</td>
                                                        <td>Ks 0</td>
                                                        <td>Ks 4,000</td>
                                                        <td>Ks 5,200</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>Ks 280,000</td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5 p-10" id="kt_customers_table">
                                                <thead class="bg-primary">
                                                    <h1>Payment Info :</h1>
                                                    <tr class="text-start text-white fw-bold fs-7 text-uppercase gs-0">
                                                        <th></th>
                                                        <th>No.</th>
                                                        <th>Date</th>
                                                        <th>Reference No</th>
                                                        <th>Amount</th>
                                                        <th>Payment mode</th>
                                                        <th>Payment note</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>

                                                <tbody class="bg-secondary">
                                                    <tr>
                                                        <td></td>
                                                        <td>1.</td>
                                                        <td>14-03-2023</td>
                                                        <td>PP2023/0021</td>
                                                        <td>Ks 50,000</td>
                                                        <td>Cash</td>
                                                        <td>--</td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col">
                                            <div></div>
                                            <table class="table align-middle table-row-dashed fs-6 gy-5 p-10" id="kt_customers_table">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>

                                                <tbody class="border-bottom-1">
                                                    <tr>
                                                        <td></td>
                                                        <td>Net Total Amount:</td>
                                                        <td></td>
                                                        <td>Ks 770,000</td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Discount::</td>
                                                        <td>(+)</td>
                                                        <td>Ks 0</td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Purchase Tax:</td>
                                                        <td>(+)</td>
                                                        <td>0.00</td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Additional Shipping charges:</td>
                                                        <td>(+)</td>
                                                        <td>0</td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>Purchase Total:</td>
                                                        <td></td>
                                                        <td>Ks 770,000</td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                    <div class="row mt-10">
                                        <div class="col">
                                            <div>Shipping Details</div>
                                            <div class="bg-secondary p-3">--</div>
                                        </div>
                                        <div class="col">
                                            <div>Additional Notes</div>
                                            <div class="bg-secondary p-3">--</div>
                                        </div>
                                    </div>

                                    <div class="mt-10">
                                        <div>Activities</div>
                                        <div class="table f">
                                            <table class=" table align-middle table-row-dashed fs-6 gy-5 p-10" id="kt_customers_table">
                                                <thead class="bg-primary">
                                                    <tr class="text-start text-white fw-bold fs-7 text-uppercase gs-0">
                                                        <th></th>
                                                        <th>Date</th>
                                                        <th>Action</th>
                                                        <th>By</th>
                                                        <th>Note</th>
                                                        <th>   </th>
                                                        <th></th>
                                                    </tr>
                                                </thead>

                                                <tbody class="bg-secondary">
                                                    <tr>
                                                        <td></td>
                                                        <td>14-02-2023 16:59</td>
                                                        <td>Added</td>
                                                        <td>Demo</td>
                                                        <td>
                                                            <table class="no-border table table-slim mb-0">
                                                                <tbody><tr>
                                                                    <th class="width-50">Status: </th>
                                                                    <td class="width-50 text-left">
                                                                        <span class="label bg-primary p-2 rounded">Ordered</span>
                                                                    </td>
                                                                </tr>


                                                                <tr>
                                                                <th class="width-50">Total: </th>
                                                                <td class="width-50 text-left">
                                                                    <span class="label bg-primary p-2 rounded">Ks 20,300</span>
                                                                </td>
                                                                </tr>

                                                                <tr>
                                                                    <th class="width-50">Payment status: </th>
                                                                    <td class="width-50 text-left">
                                                                        <span class="label bg-primary p-2 rounded">Due</span>
                                                                    </td>
                                                                </tr>

                                                            </tbody></table>
                                                        </td>

                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary no-print" aria-label="Print"
                                    onclick="$(this).closest('div.modal').printThis();">
                                    <i class="fa fa-print"></i> Print </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- end modal for view purchase --}}

                    {{-- start view payment modal --}}
                    <div class="modal fade" id="view_payment" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">View Payment ( Reference No: PP2022/0001 )</h3>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body fs-3">
                                    <div class="row mb-10">
                                        <div class="col">
                                            Supplier:
                                            <address>
                                                <strong>APK</strong>
                                                APK
                                                <br>Mobile: 09092121
                                            </address>
                                        </div>
                                        <div class="col">
                                            Business:
                                            <address>
                                                <strong>Demo2's bussiness</strong>
                                                Demo Business
                                                <br>၅၅လမ်း၊ ၁၃၂ လမ်း နှင့် ၁၃၃လမ်းကြား၊ ပြည်ကြီးတံခွန်မြို့နယ်၊
                                                မန္တလေးမြို့။
                                                <br>Mandalay,Mandalay,Myanmar
                                            </address>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <br>
                                        <div class="col">
                                            <strong>Amount :</strong>
                                            Ks 20,300<br>
                                            <strong>Payment Method :</strong>
                                            Cash<br>
                                            <strong>Payment Note :</strong>
                                        </div>
                                        <div class="col">
                                            <b>Reference No:</b>
                                            PP2022/0001
                                            <br>
                                            <b>Paid on:</b> 12-11-2022 08:09<br>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary no-print" aria-label="Print"
                                    onclick="$(this).closest('div.modal').printThis();">
                                    <i class="fa fa-print"></i> Print </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- end view payment modal --}}
                </div>
                <!--end::Content-->
            </div>
            <!--end::Layout-->
        </div>
        <!--end::Container-->
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
