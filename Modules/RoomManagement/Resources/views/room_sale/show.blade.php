<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
           
            <h3 class="modal-title">Room Sale Details (Voucher No : {{$room_sale->room_sales_voucher_no}})</h3>

            <!--begin::Close-->
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa-solid fa-times"></i>
            </div>
            <!--end::Close-->
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4 mb-5">
                    <h4 class="text-gray-700 mb-5">
                        {!! $room_sale->transaction_type == 'registration' ? 'Patient:' : 'Guest:' !!}
                    </h4>
                    
                        @if($room_sale->transaction_type == 'registration')
                        <div class="fs-6 text-gray-700 mb-2">
                            @if($room_sale->hospital_registration->patient)
                            
                                {!! $room_sale->hospital_registration->patient->prefix !!}
                                {!! $room_sale->hospital_registration->patient->first_name !!}
                                {!! $room_sale->hospital_registration->patient->middle_name !!}
                                {!! $room_sale->hospital_registration->patient->last_name !!}
                            @endif
                        </div>
                        <div class="fs-6 text-gray-700">
                            Mobile: {!! $room_sale->hospital_registration->patient->mobile !!} 
                        </div>
                        @elseif($room_sale->transaction_type == 'reservation')
                        
                            @if($room_sale->reservation->contact)
                            <div class="fs-6 text-gray-700 mb-2">
                                {!! $room_sale->reservation->contact->prefix !!}
                                {!! $room_sale->reservation->contact->first_name !!}
                                {!! $room_sale->reservation->contact->middle_name !!}
                                {!! $room_sale->reservation->contact->last_name !!}
                            </div>
                            <div class="fs-6 text-gray-700">
                                Mobile: {!! $room_sale->reservation->contact->mobile !!} 
                            </div>
                            @elseif($room_sale->reservation->company)
                            <div class="fs-6 text-gray-700 mb-2">
                                {!! $room_sale->reservation->company->company_name !!}
                            </div>
                            <div class="fs-6 text-gray-700">
                                Mobile: {!! $room_sale->reservation->company->mobile !!} 
                            </div>
                            @endif
                        @endif
                </div>
                <div class="col-md-4 mb-5">
                    <h4 class="text-gray-700 mb-5">
                        Bussiness Location
                    </h4>
                    <div class="fs-6 text-gray-700">
                        {!! $room_sale->business_location ? $room_sale->business_location->name : '' !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <h4 class="text-gray-700 mb-5">
                        Date
                    </h4>
                    <div class="fs-6 text-gray-700">
                        {!! $room_sale->confirm_at !!}
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-10">
                <table class="table align-middle fs-6 gs-7 gx-7 gy-5" id="kt_datatable_example">
                    <!--begin::Table head-->
                    <thead class="bg-primary">
                        <!--begin::Table row-->
                        <tr class="text-white text-center fw-bold fs-7 text-uppercase">
                            <th>#</th>
                            <th class="min-w-125px">Voucher No.</th>
                            <th class="min-w-125px">Room</th>
                            <th class="min-w-125px">Quantity</th>
                            <th class="min-w-125px">UOM</th>
                            <th class="min-w-125px">Price</th>
                            <th class="min-w-125px">Subtotal</th>
                            <th class="min-w-125px">Discount</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-semibold text-gray-600">
                        @foreach($room_sale->room_sale_details as $room_sale_detail)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$room_sale->room_sales_voucher_no}}</td>
                            <td>
                                <span> 
                                    {{$room_sale_detail->room->name}},
                                    {{$room_sale_detail->room_type->name}},
                                    {{$room_sale_detail->room_rate->rate_name}},
                                </span><br>
                                <span>
                                    {{$room_sale_detail->check_in_date}},
                                <span><br>
                                <span>
                                    {{$room_sale_detail->check_out_date}}
                                </span>
                            </td>
                            <td>{{$room_sale_detail->qty}}</td>
                            <td></td>
                            <td>{{$room_sale_detail->room_fees}}</td>
                            <td>{{$room_sale_detail->subtotal}}</td>
                            <td>{{$room_sale_detail->per_item_discount ?? '0.0000'}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <!--end::Table body-->
                </table>
            </div>
            <div class="row justify-content-end mt-10">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fw-bold text-gray-700 fs-6">
                            <tr >
                                <td>Sale Amount</td>
                                <td>(=)</td>
                                <td>{{$room_sale->sale_amount}}</td>
                            </tr>
                            <tr >
                                <td>Total Item Discount</td>
                                <td>(-)</td>
                                <td>{{$room_sale->total_item_discount}}</td>
                            </tr>
                            <tr >
                                <td>Total Sale Amount</td>
                                <td>(=)</td>
                                <td>{{$room_sale->total_sale_amount}}</td>
                            </tr>
                            <tr >
                                <td>Paid Amount</td>
                                <td>(-)</td>
                                <td>{{$room_sale->paid_amount}}</td>
                            </tr>
                            <tr >
                                <td>Balance Amount</td>
                                <td>(=)</td>
                                <td>{{$room_sale->balance_amount}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>