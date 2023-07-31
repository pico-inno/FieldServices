@extends('App.main.navBar')

@section('styles')
    <style>

    </style>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl" id="kt_content_container">
            <div class="card p-5">
                <div class="">
                    <h4 class="modal-title no-print">
                        View Payments
                        (

                        Reference No: PO2022/0001
                        )
                    </h4>
                    <hr>

                </div>

                <div class="fs-4">
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            Supplier:
                            <address>
                                <strong>APK</strong>

                                APK
                                <br>Mobile: 09092121
                            </address>
                        </div>
                        <div class="col-md-4 invoice-col">
                            Business:
                            <address>
                                <strong>Demo2's bussiness</strong>
                                Demo Business
                                <br>၅၅လမ်း၊ ၁၃၂ လမ်း နှင့် ၁၃၃လမ်းကြား၊ ပြည်ကြီးတံခွန်မြို့နယ်၊ မန္တလေးမြို့။
                                <br>Mandalay,Mandalay,Myanmar
                            </address>
                        </div>

                        <div class="col-sm-4 invoice-col">
                            <b>Reference No:</b> #PO2022/0001<br>
                            <b>Date:</b> 12-11-2022<br>
                            <b>Purchase Status:</b> Received<br>
                            <b>Payment Status:</b> Paid<br>
                        </div>
                    </div>

                    <div class="row no-print">
                        <div class="col-md-12 text-right d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-envelope"></i> Payment
                                Paid Notification</button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th></th>
                                            <th>Date</th>
                                            <th>Reference No</th>
                                            <th>Amount</th>
                                            <th>Payment Method</th>
                                            <th>Payment Note</th>
                                            <th>Payment Account</th>
                                            <th class="no-print">Actions</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>12-11-2022 08:09</td>
                                            <td>PP2022/0001</td>
                                            <td><span class="display_currency" data-currency_symbol="true">Ks 20,300</span>
                                            </td>
                                            <td>Cash</td>
                                            <td> </td>
                                            <td></td>
                                            <td>
                                                <a href="#" class="btn btn-sm p-3 btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
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
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4"
                                                    data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="#view_payment" data-bs-toggle="modal"
                                                            class="menu-link px-3">View</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('contacts#editPaymentPage') }}" class="menu-link px-3">Edit</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- start modals --}}

                    {{-- start view payment modal --}}
                    <div class="modal fade" id="view_payment" tabindex="-1">
                        <div class="modal-dialog modal-lg ">
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

                <div class=" d-flex justify-content-end mt-10">
                    <button type="button" class="btn btn-primary no-print" aria-label="Print"
                        onclick="$(this).closest('div.modal').printThis();">
                        <i class="fa fa-print"></i> Print </button>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script></script>
@endpush



