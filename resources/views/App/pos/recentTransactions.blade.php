<div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">POS Sale Recent Transactions</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 p-0">
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#pos_sale_recent_delivered">Delivered</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#pos_sale_recent_draft">Draft</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#pos_sale_recent_order">Order</a>
                        </li>
                        @if ($posRegister->use_for_res == 1)
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#pos_res_recent_order">Restaurant Order</a>
                            </li>
                        @endif
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade" id="pos_sale_recent_delivered" role="tab-panel">
                            <div class="table-responsive">
                                <table class="table  g-5">
                                    <thead>
                                        <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                            <th>Product</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if (count($saleDelivered)<=0)
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold text-gray-400">There is no delivered sale voucher</td>
                                            </tr>
                                        @endif
                                        @foreach ($saleDelivered as $sd)
                                            <tr>
                                                <td>{{$sd->sales_voucher_no}}</td>
                                                <td>{{$sd->customer->first_name}}</td>
                                                <td>{{price($sd->total_sale_amount,$sd->currency_id)}}</td>
                                                <td class="d-flex flex-row">
                                                    <span class="me-5 view_detail"  type="button" data-href="{{ route('saleDetail',$sd->id)}}"><i class="fa-regular fa-eye fs-5"></i></span>
                                                    <a class="me-5 editRecent" href="{{route('pos.edit',['posRegisterId'=>$posRegisterId,'saleId'=>$sd->id])}}"><i class="fas fa-edit fs-5 text-warning cursor-pointer"></i></a>
                                                    {{-- <span class="me-5"><i class="fas fa-trash fs-5 text-danger cursor-pointer"></i></span> --}}
                                                    <span class="me-5 print-invoice" data-href="{{route('print_sale', $sd->id)}}"><i class="fas fa-print fs-5 text-primary cursor-pointer"></i></span>
                                                    <span class="me-5 print-invoice" data-href="{{route('print_sale', $sd->id)}}">Post To Folio</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- <tr>
                                            <td>INV-2023-0000084</td>
                                            <td>Aung Aung</td>
                                            <td>540000</td>
                                            <td class="d-flex flex-row">
                                                <span class="me-5"><i class="fas fa-edit fs-4 text-warning cursor-pointer"></i></span>
                                                <span class="me-5"><i class="fas fa-trash fs-4 text-danger cursor-pointer"></i></span>
                                                <span class="me-5"><i class="fas fa-print fs-4 text-primary cursor-pointer"></i></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>INV-2023-0008555</td>
                                            <td>Zaw Zaw</td>
                                            <td>450000</td>
                                            <td class="d-flex flex-row">
                                                <span class="me-5"><i class="fas fa-edit fs-4 text-warning cursor-pointer"></i></span>
                                                <span class="me-5"><i class="fas fa-trash fs-4 text-danger cursor-pointer"></i></span>
                                                <span class="me-5"><i class="fas fa-print fs-4 text-primary cursor-pointer"></i></span>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pos_sale_recent_draft" role="tab-panel">
                            <div class="table-responsive">
                                <table class="table  g-5">
                                    <thead>
                                        <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                            <th>Product</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($saleDrafts as $sdf)
                                            <tr>
                                                <td>{{$sdf->sales_voucher_no}}</td>
                                                <td>{{$sdf->customer->first_name}}</td>
                                                <td>{{price($sdf->total_sale_amount,$sdf->currency_id)}}</td>
                                                <td class="d-flex flex-row">
                                                    <span class="me-5 view_detail"  type="button" data-href="{{ route('saleDetail',$sdf->id)}}"><i class="fa-regular fa-eye fs-5"></i></span>
                                                    <a class="me-5 editRecent" href="{{route('pos.edit',['posRegisterId'=>$posRegisterId,'saleId'=>$sdf->id])}}"><i class="fas fa-edit fs-5 text-warning cursor-pointer"></i></a>
                                                    {{-- <span class="me-5"><i class="fas fa-trash fs-5 text-danger cursor-pointer"></i></span> --}}
                                                    <span class="me-5 print-invoice" data-href="{{route('print_sale', $sdf->id)}}"><i class="fas fa-print fs-5 text-primary cursor-pointer"></i></span>
                                                    <span class="me-5 print-invoice" data-href="{{route('print_sale', $sdf->id)}}">Post To Folio</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if (count($saleDrafts)<=0)
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold text-gray-400">There is no draft sale</td>
                                            </tr>
                                        @endif
                                        {{-- <tr>
                                            <td>INV-2023-0000084</td>
                                            <td>Aung Aung</td>
                                            <td>540000</td>
                                            <td class="d-flex flex-row">
                                                <span class="me-5"><i class="fas fa-edit fs-4 text-warning cursor-pointer"></i></span>
                                                <span class="me-5"><i class="fas fa-trash fs-4 text-danger cursor-pointer"></i></span>
                                                <span class="me-5"><i class="fas fa-print fs-4 text-primary cursor-pointer"></i></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>INV-2023-0008555</td>
                                            <td>Zaw Zaw</td>
                                            <td>450000</td>
                                            <td class="d-flex flex-row">
                                                <span class="me-5"><i class="fas fa-edit fs-4 text-warning cursor-pointer"></i></span>
                                                <span class="me-5"><i class="fas fa-trash fs-4 text-danger cursor-pointer"></i></span>
                                                <span class="me-5"><i class="fas fa-print fs-4 text-primary cursor-pointer"></i></span>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="pos_sale_recent_order" role="tab-panel">
                            <div class="table-responsive">
                                <table class="table g-5">
                                    <thead>
                                        <tr class="fw-semibold fs-6 text-gray-600 border-bottom border-gray-200">
                                            <th>Product</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold">
                                        @if (count($saleOrders)<=0)
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold text-gray-400">There is no order salek</td>
                                            </tr>
                                        @endif
                                        @foreach ($saleOrders as $so)
                                            <tr>
                                                <td>{{$so->sales_voucher_no}}</td>
                                                <td>{{$so->customer->first_name}}</td>
                                                <td>{{price($so->total_sale_amount,$so->currency_id)}}</td>
                                                <td class="d-flex flex-row">
                                                    <span class="me-5 view_detail"  type="button" data-href="{{ route('saleDetail',$so->id)}}"><i class="fa-regular fa-eye fs-5"></i></span>
                                                    <a class="me-5 editRecent" href="{{route('pos.edit',['posRegisterId'=>$posRegisterId,'saleId'=>$so->id])}}"><i class="fas fa-edit fs-5 text-warning cursor-pointer"></i></a>
                                                    {{-- <span class="me-5"><i class="fas fa-trash fs-5 text-danger cursor-pointer"></i></span> --}}
                                                    <span class="me-5 print-invoice" data-href="{{route('print_sale', $so->id)}}"><i class="fas fa-print fs-5 text-primary cursor-pointer"></i></span>
                                                    @if (class_exists(Modules\Reservation\Entities\FolioInvoiceDetail::class))

                                                            @php
                                                                $isAttachedToFolio= Modules\Reservation\Entities\FolioInvoiceDetail::where('transaction_type','sale')->where('transaction_id',$so->id)->exists();

                                                            @endphp
                                                            @if ($isAttachedToFolio)
                                                                <span class="me-5  badge badge-primary cursor-pointer post-to-reservation" data-href="{{route('postToReservationFolio', $so->id)}}">
                                                                    Edit Posted Folio
                                                                </span>
                                                            @else
                                                                <span class="me-5  badge badge-info cursor-pointer post-to-reservation" data-href="{{route('postToReservationFolio', $so->id)}}">
                                                                Post To Folio
                                                                </span>
                                                            @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- <tr>
                                            <td>INV-2023-0000084</td>
                                            <td>Aung Aung</td>
                                            <td>540000</td>
                                            <td class="d-flex flex-row">
                                                <span class="me-5"><i class="fas fa-edit fs-4 text-warning cursor-pointer"></i></span>
                                                <span class="me-5"><i class="fas fa-trash fs-4 text-danger cursor-pointer"></i></span>
                                                <span class="me-5"><i class="fas fa-print fs-4 text-primary cursor-pointer"></i></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>INV-2023-0008555</td>
                                            <td>Zaw Zaw</td>
                                            <td>450000</td>
                                            <td class="d-flex flex-row">
                                                <span class="me-5"><i class="fas fa-edit fs-4 text-warning cursor-pointer"></i></span>
                                                <span class="me-5"><i class="fas fa-trash fs-4 text-danger cursor-pointer"></i></span>
                                                <span class="me-5"><i class="fas fa-print fs-4 text-primary cursor-pointer"></i></span>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="tab-pane fade  " id="pos_res_recent_order" role="tab-panel">
                            <div class="table-responsive">
                                <table class="table g-5">
                                    <thead>
                                        <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                            <th>Order Voucher No</th>
                                            <th>Order Status</th>
                                            <th>location</th>
                                            <th>services</th>
                                        </tr>
                                    </thead>
                                    @if($restaurantOrder)
                                        <tbody class="fw-semibold">
                                            @if (count($restaurantOrder)<=0)
                                                <tr>
                                                    <td colspan="3" class="text-end fw-bold text-gray-400">There is no res order salek</td>
                                                </tr>
                                            @endif
                                            @foreach ($restaurantOrder as $ro)
                                                <tr>
                                                    <td>{{$ro->order_voucher_no}}</td>
                                                    <td>{{$ro->order_status}}</td>
                                                    <td>{{$ro->location->name}}</td>
                                                    <td>
                                                        @if ($ro->services=='dine_in')
                                                            <span class="badge bade-sm badge badge-primary">Dine In</span>
                                                        @elseif ($ro->services=='delivery')
                                                            <span class="badge bade-sm badge badge-info">Delivary</span>
                                                        @elseif ($ro->services=='take_away')
                                                            <span class="badge bade-sm badge badge-info">Take Away</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
</div>
