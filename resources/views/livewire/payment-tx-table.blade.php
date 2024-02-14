
<div class="card card-flush">
    <!--begin::Card header-->
    <div class="d-flex justify-content-between align-items-start flex-wrap mx-10 mt-5">
        <!--begin::User-->
        <div class="d-flex  justify-content-center align-items-center">
            <!--begin::Name-->
            <a  class="text-primary  fs-5 fw-bold me-1 d-block ">
                Transaction History
            </a>
        </div>
        <!--end::User-->
        <!--begin::Actions-->
        <div class="d-flex ">
            {{-- <input type="text" class="form-control form-control-sm" placeholder="Search " data-filter="input"> --}}
            <button wire:click='export' class="btn btn-sm btn-primary"> Export </button>
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body">

        <div class="modal fade show " tabindex="-1" id="kt_modal_1" wire:loading wire:target='export'>
            <div class="modal-dialog w-md-500px w-100">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class="modal-title text-center">
                            Exporting......
                        </h3>
                        <div class="progress mt-5">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Table-->
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-4" id="">
                <!--begin::Table head-->
                <thead>
                    <!--begin::Table row-->
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-capitalize gs-0">
                        <th class="text-start min-w-100px">Payment Voucher No</th>
                        <th class="text-start pe-3 min-w-100px">Pay Date</th>
                        <th class="text-start pe-3 min-w-100px">Transaction Type</th>
                        <th class="text-end pe-3 min-w-100px">Transaction Reference No</th>
                        <th class="text-end pe-3 min-w-100px">Payment Method</th>
                        <th class="text-end pe-3 min-w-100px">Payment Account</th>
                        <th class="text-end pe-3 min-w-100px">Payment Type</th>
                        <th class="text-end pe-3 min-w-100px">Rate</th>
                        <th class="text-end pe-3 min-w-100px text-primary">Debit</th>
                        <th class="text-end pe-3 min-w-100px text-danger">Credit</th>
                        <th>Balance Amount</th>
                        <th class="text-end pe-3 min-w-100px">note</th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="fw-bold text-gray-600 text-start fs-7 text-end">
                    @foreach ($datas as $data)
                        <tr>
                            <td>
                                {{$data['payment_voucher_no']}}
                            </td>
                            <td class="text-start">
                                {{$data['payment_date']}}
                            </td>
                            <td class="text-start">
                                {{$data['transaction_type']}}
                            </td>
                            <td>
                                {{$data['transaction_ref_no']}}
                            </td>
                            <td>
                                {{$data['payment_method']}}
                            </td>
                            <td>
                                {{$data['payment_account']['name']}}
                            </td>
                            <td>
                                @php
                                    $paymentType=$data['payment_type'];
                                @endphp
                                @if($paymentType == 'withdrawl')
                                    <span class='badge badge-danger'>Withdrawl</span>
                                @elseif($paymentType=='deposit')
                                    <span class='badge badge-primary'>Deposit</span>
                                @elseif($paymentType=='debit')
                                    <span class='badge badge-success'>Debit</span>
                                @elseif($paymentType=='credit')
                                    <span class='badge badge-danger'>Credit</span>
                                @elseif($paymentType=='opening_amount')
                                    <span class='badge badge-info'>Opening Amount</span>
                                @elseif($paymentType=='transfer')
                                    <span class='badge badge-secondary'>Transfer</span>
                                @endif
                            </td>

                            <td>
                                {{$data['exchange_rate']}}
                            </td>
                            <td>
                                @if ($data->payment_type=="debit")
                                    {{price($data->payment_amount, $data->currency_id)}}
                                @else
                                    0
                                @endif
                            </td>
                            <td>
                                @if ($data->payment_type=="credit")
                                    {{price($data->payment_amount, $data->currency_id)}}
                                @else
                                    0
                                @endif
                            </td>
                            <td>
                                @if($data->payment_type=="credit")
                                    {{price($balanceAmount, $data->currency_id)}}
                                    @php
                                        $balanceAmount+=$data->payment_amount;
                                    @endphp
                                @elseif($data->payment_type=="debit")
                                    {{price($balanceAmount, $data->currency_id)}}
                                    @php
                                        $balanceAmount-=$data->payment_amount;
                                    @endphp
                                @endif
                            </td>
                            <td>
                                {{$data['note']}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <!--end::Table body-->
            </table>
        </div>
        <div class="row justify-content-center  justify-content-md-between">
            <div class="col-md-6 col-12 mb-3 ">
                <div class="w-auto">
                    <select name="" id="" wire:model.change="perPage" class="form-select form-select-sm w-auto m-auto m-md-0">
                        @foreach ($aviablePerPages as $page)
                            <option value="{{$page}}">{{$page}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6 col-12 mb-3">
                {{$datas->links()}}
            </div>
        </div>
        <!--end::Table-->
    </div>
    <!--end::Card body-->
</div>
