<div class="modal-dialog " id="payment_container">
    <div class="modal-content">
            @csrf
            <div class="modal-header py-3">
                <h3 class="modal-title">View Pyament Transaction </h3>
                <!--begin::Close-->
                <div class="btn btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
                <!--end::Close-->
            </div>
            <div class="modal-body">
                <div class="row mb-5">
                    <div class="col-md-6 col-12">
                        <table class="table  table-layout-fixed  table-row-bordered">
                                <tbody class="">
                                    <tr>
                                        <th class="text-start">
                                          <span class="fw-semibold fs-7 text-gray-600">Voucher No:</span>
                                        </th>
                                        <td  class="text-end">
                                          <span class="fw-bold fs-7 text-gray-800">{{$data->sales_voucher_no}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">
                                          <span class="fw-semibold fs-7 text-gray-600">sold_at:</span>
                                        </th>
                                        <td  class="text-end">
                                          <span class="fw-bold fs-7 text-gray-800">{{fDate($data->sold_at)}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">
                                          <span class="fw-semibold fs-7 text-gray-600">Payment Status:</span>
                                        </th>
                                        <td  class="text-end">
                                          <span class="fw-bold fs-7 badge badge-sm badge-success">{{$data->payment_status}}</span>
                                        </td>
                                    </tr>
                                </tbody>
                        </table>
                   </div>
                   <div class="col-md-6 col-12">
                       <table class="table  table-layout-fixed  table-row-bordered">
                               <tbody class="">
                                   <tr>
                                       <th class="text-start">
                                         <span class="fw-semibold fs-7 text-gray-600">Total Sale Amout:</span>
                                       </th>
                                       <td  class="text-end">
                                         <span class="fw-bold fs-7 text-gray-800">{{price($data->total_sale_amount,$data->currency->id)}}</span>
                                       </td>
                                   </tr>
                                   <tr>
                                       <th class="text-start">
                                         <span class="fw-semibold fs-7 text-gray-600">Paid Amount:</span>
                                       </th>
                                       <td  class="text-end">
                                         <span class="fw-bold fs-7 text-gray-800">{{price($data->paid_amount,$data->currency->id)}} </span>
                                       </td>
                                   </tr>
                                   <tr>
                                    <th class="text-start">
                                      <span class="fw-semibold fs-7 text-gray-600">Balance Amount:</span>
                                    </th>
                                    <td  class="text-end me-3">
                                      <span class="fw-bold fs-7 text-gray-800">{{price($data->balance_amount,$data->currency->id)}} </span>
                                    </td>
                                </tr>
                               </tbody>
                       </table>
                  </div>
                </div>

                <div class="separator my-5"></div>
                <div class="row mb-6 table-responsive">
                    <table class="table table-row-bordered">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                               <th class="min-w-100px">Payment Date</th>
                               <th  class="min-w-100px">Payment Voucher No</th>
                               <th  class="min-w-100px">Payment Account</th>
                               <th  class="min-w-100px">Payment Amount</th>
                               <th class="text-end min-w-100px" style="max-width: 100px">
                                    <span class="btn btn-sm pe-3">
                                        <i class="fa-solid fa-trash text-danger"></i>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @if (count($transactions)==0)
                                <tr>
                                    <th colspan="4"  class="text-center text-gray-600">There is no data to show</th>
                                </tr>
                            @endif
                            @foreach ($transactions as $t)
                            <tr class="transactionList">
                                <th class="text-start text-gray-600  fw-semibold">
                                  <span>{{fDate($t->payment_date)}}</span>
                                </th>
                                <th class="text-start text-gray-600 fw-semibold voucher_no">
                                    <span>{{$t->payment_voucher_no}}</span>
                                </th>
                                @php
                                   $paymentAccount=$t->payment_account->name ?? '';
                                @endphp
                                <th class="text-start text-gray-600  fw-semibold">
                                    <span>{{$paymentAccount}}</span>
                                </th>
                                <th class="text-start text-gray-600 fw-semibold">
                                    <span>{{number_format(price($t->payment_amount,$t->currency_id))}} </span>
                                </th>
                                <th class="text-end pe-3">
                                    <button type="button" class="btn btn-sm  pe-2 edit_payment" data-id="{{$t->id}}" data-href="{{route('paymentTransaction.editForSale',$t->id)}}"><i class="fa-regular fa-pen-to-square text-primary"></i></button>
                                    <button type="button" class="btn btn-sm   pe-2" data-id="{{$t->id}}" data-table="delete_payment"><i class="fa-solid fa-trash text-danger"></i></button>
                                </th>
                            </tr>
                            @endforeach

                         </tr>
                        </tbody>
                </table>

                </div>
            <div class="modal-footer py-3 ">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
    </div>
</div>
