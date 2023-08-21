<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Custom Print Invoice</title>
    <link href={{asset("assets/plugins/custom/vis-timeline/vis-timeline.bundle.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/plugins/global/plugins.bundle.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/css/style.bundle.css")}} rel="stylesheet" type="text/css" />

    <!-- <link rel="stylesheet" href="invoice.css"> -->
</head>
<style>
    /* body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f4f4f4;
} */

    .invoice {
        font-family: Arial, sans-serif;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        background-color: #fff;
        padding: 0 32px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }

    .customer-date {
        display: flex;
        justify-content: end;
        padding: 2rem 0 3px 0;
        font-size: 12px;
    }

    .customer-table-title {}

    .customer-table-title .customer-table-logo {
        text-align: center;
    }

    .customer-table-logo img {}

    .customer-table-header h2 {
        font-size: 16px;
        text-align: center;
        margin: 0;
        padding-bottom: .1rem 0rem;
    }

    .customer-table-content {
        text-align: center;
        width: 100%;
        margin: 0 auto;
        font-size: 5px;
    }

    .invoice h3 {
        text-align: center;
        font-size: 16px;
        margin: 0;
        padding: .3rem 0rem;
    }

    /**/
    .customer-info {
        font-size: .1rem;
        white-space: nowrap;
    }

    .customer-info-menu {
        display: flex;
        justify-content: space-between;
    }

    .customer-info p,
    .customer-info-menu p {
        margin: 0;
        padding: 2px 0;
    }

    /**/

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
    }

    table thead tr th {
        font-size: .2rem;
        text-align: center;
    }

    table,
    th,
    td {
        border: 1px solid #eee;
    }

    th,
    td {
        font-size: .1rem;
        padding: 10px;
        text-align: center;
        white-space: nowrap;
    }

    tfoot td {
        font-size: .1rem;
        text-align: right;
    }

    tfoot tr td span {
        font-weight: 700;
        font-size: .2rem;
        text-align: center;
    }

    .text-align-center {
        text-align: center;
    }

    .customer-sign {
        display: flex;
        justify-content: space-between;
        padding: 1.5rem 0 0 0;
        font-size: .2rem;
        position: relative;
    }

    .customer-sign .customer-border-top {
        margin: 0 0;
        display: inline-block;
        border-top: 2px solid #444;
        width: 100px;
    }

    .customer-sign .customer-border-top p {
        margin: 0;
        text-align: center;
        padding-top: .1rem;
    }

    .guest-name .customer-border-top p {
        white-space: nowrap;
    }

    .sign-name {
        position: absolute;
        top: 30vh;
    }

    .guest-name {
        position: absolute;
        left: 85%;
        top: 30vh;
    }

    /* @page {
        size: 21cm;
        margin: 0;
    } */
    /*
    @page {
        size: 14.85cm;
        margin: 0;
    } */

    /* @page {
        size: 80mm;
        margin: 0;
    } */


    /* A4 Paper Size */
    @media print and (max-width: 21cm) and (max-height: 29.7cm) {}

    /* A5 Paper Size */
    @media print and (max-width: 14.85cm) and (max-height: 21cm) {}

    /*80mm Paper size*/
    @media print and (max-width: 80mm) and (max-height: 80mm) {}
</style>

<body>
    <div class="invoice">
        {{-- <div class="customer-date">
            <span>2023-08-10</span>
            <span>9:02 AM</span>
        </div> --}}
        <div class="customer-table-title mt-10">
            <div class="customer-table-logo">
                <!-- <img src="logo.png" width="50" height="50" alt=""> -->
            </div>
            <div class="customer-table-header">
                <h2>Hotel Shwe Man Manaw</h2>
            </div>
            <div class="customer-table-content">
                <small class="fs-7">110 St:, 59x58C ST:, Chan Mya Thar si Tsp, Mandalay Division, Myanmar <br> 09 444737174, 09 788323777, 09 98532323777</small>
            </div>
        </div>
        <h3>Invoice</h3>

        <div class="customer-info">
            <div class="customer-name">
                <p class="fs-6">
                    @foreach($reservations as $reservation)
                    <b>
                        @if($reservation->contact)
                        {{$reservation->contact->first_name}}
                        {{$reservation->contact->middle_name}}
                        {{$reservation->contact->last_name}},
                        @elseif($reservation->company)
                        {{$reservation->company->company_name}} 
                        @endif
                    </b>
                    @endforeach
                </p>
            </div>
            <div class="customer-info-menu">
                <div class="customer-info-list-left">
                    <small class="fs-6">Tour Code :</small>
                </div>
                <div class="customer-info-list-right">
                    <small class="fs-6">Room No : </small>
                    @foreach($reservations as $reservation)
                    @foreach($reservation->room_reservations as $room_reservation)
                    <small class="fs-6">
                        {{$room_reservation->room->name}},
                    </small>
                    @endforeach
                    @endforeach
                </div>
            </div>
            <div class="customer-info-menu">
                <div class="customer-info-list-left">
                    <small class="fs-6">A/R Number :</small>
                </div>
                <div class="customer-info-list-right">
                    <small class="fs-6">Arrival : </small>
                    @foreach($reservations as $reservation)
                    <small class="fs-6">
                        {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('j/n/Y') }},
                    </small>
                    @endforeach
                </div>
            </div>
            <div class="customer-info-menu">
                <div class="customer-info-list-left">
                    <small class="fs-6">Company Name :</small>
                </div>
                <div class="customer-info-list-right">
                    <small class="fs-6">Departure : </small>
                    @foreach($reservations as $reservation)
                    <small class="fs-6">
                        {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('j/n/Y') }},
                    </small>
                    @endforeach
                </div>
            </div>
            <div class="customer-info-menu">
                <!-- <div class="customer-info-list-left">
                    <small class="fs-6">Information Invoice :</small>
                </div> -->
                <div class="customer-info-list-left">
                    <small class="fs-6">Folio / Invoice No :</small>
                    @foreach($folioInvoices as $folioInvoice)
                    <small class="fs-6">
                        {{$folioInvoice->folio_invoice_code}},
                    </small>
                    @endforeach
                </div>
                <div class="customer-info-list-right">
                    <small class="fs-6">Person(s) : </small>
                    <small class="fs-6">2 Pax</small>
                </div>
            </div>
            <!-- <div class="customer-info-menu">
                <div class="customer-info-list-left">
                    <small class="fs-6">Tour Code  :</small>
                </div>
                <div class="customer-info-list-right">
                    <small class="fs-6">Room No  : </small>
                    <small class="fs-6">104</small>
                </div>
            </div> -->
            <!-- <div class="customer-info-menu">
                <div class="customer-info-list-left">
                    <small class="fs-6">Folio / Invoice No :</small>
                    <small class="fs-6">{{$folioInvoice->folio_invoice_code}}</small>
                </div>
                <div class="customer-info-list-right">
                    <small class="fs-6">User ID : </small>
                    <small class="fs-6"></small>
                </div>
            </div> -->
            <div class="text-align-end" style="text-align: end;">
                <div class="customer-info-list-right">
                    <small class="fs-6">Cashier No : </small>
                    <small class="fs-6"></small>
                </div>
            </div>

        </div>

        <table class="mt-8">
            <thead>
                <tr>
                    <th class="fs-6">Date</th>
                    <th class="fs-6">Description</th>
                    <th class="fs-6">Reference</th>
                    <th class="fs-6">Charges</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($room_sales))
                @foreach($room_sales as $room_sale)
                @foreach($room_sale->room_sale_details as $room_sale_detail)
                <tr>
                    <td class="fs-6">
                        {{ \Carbon\Carbon::parse($room_sale->confirm_at)->format('j/n/Y') }} , 
                        {{ date_format(date_create($room_sale->confirm_at),"h:i A")}}
                    </td>
                    <td class="fs-6">
                        Room Charges ({{$room_sale_detail->room->name}})
                    </td>
                    <td class="fs-6">{{$room_sale->room_sales_voucher_no}}</td>
                    <td class="fs-6">{{$room_sale_detail->subtotal}}</td>
                </tr>
                @endforeach
                @endforeach
                @endif

                @if(isset($sales))
                @foreach($sales as $sale)
                @foreach($sale->sale_details as $sale_detail)
                <tr>
                    <td class="fs-6">
                        {{ \Carbon\Carbon::parse($sale->sold_at)->format('j/n/Y') }} ,
                        {{ date_format(date_create($sale->sold_at),"h:i A")}}
                    </td>
                    <td class="fs-6">Sky Bar ({{$sale_detail->product->name}})</td>
                    <td class="fs-6">{{$sale->sales_voucher_no}}</td>
                    <td class="fs-6">{{$sale_detail->subtotal}}</td>
                </tr>
                @endforeach
                @endforeach
                @endif
            </tbody>
            <tfoot>
                @php
                $saleAmount = 0;
                $totalItemDiscount = 0;
                $totalExtraDiscount = 0;
                $paidAmount = 0;

                // Loop through room_sales data and update the calculated values
                if(isset($room_sales)) {
                foreach($room_sales as $room_sale) {
                $saleAmount += $room_sale->sale_amount;
                $totalItemDiscount += $room_sale->total_item_discount;
                $paidAmount += $room_sale->paid_amount;
                }
                }

                // Loop through sales data and update the calculated values
                if(isset($sales)) {
                foreach($sales as $sale) {
                $saleAmount += $sale->sale_amount;
                $totalItemDiscount += $sale->total_item_discount;
                $totalExtraDiscount += $sale->extra_discount_amount;
                $paidAmount += $sale->paid_amount;
                }
                }

                // Calculate total sale amount and balance amount
                $totalSaleAmount = $saleAmount - ($totalItemDiscount + $totalExtraDiscount);
                $balanceAmount = $totalSaleAmount - $paidAmount;
                @endphp

                <tr>
                    <td colspan="2"><span class="customer-border fs-6">Total Amount</span></td>
                    <td></td>
                    <td class="text-align-center fs-6">{{$saleAmount}}</td>
                </tr>
                <tr>
                    <td colspan="2"><span class="customer-border fs-6">Paid Amount</span></td>
                    <td></td>
                    <td class="text-align-center fs-6">{{$paidAmount}}</td>
                </tr>
                <tr>
                    <td colspan="2"><span class="customer-border fs-6">Balance Amount</span></td>
                    <td></td>
                    <td class="text-align-center fs-6">{{$balanceAmount}}</td>
                </tr>
                {{--<tr>
                    <td colspan="2"><b class="fs-6">Net Amount (After 13%)</b></td>
                    <td></td>
                    <td class="text-align-center fs-6">{{$totalSaleAmount}}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong class="fs-6">Commercial Tax</strong></td>
                    <td></td>
                    <td class="text-align-center fs-6"><strong>4,443</strong></td>
                </tr>
                <tr>
                    <td colspan="2"><strong class="fs-6">Service Charges</strong></td>
                    <td></td>
                    <td class="text-align-center fs-6"><strong>55,443</strong></td>
                </tr> --}}
            </tfoot>
        </table>

        <div class="customer-sign">
            <span class="sign-name">
                <span class="customer-border-top">
                    <p class="fs-6">Cashier</p>
                </span>
            </span>
            <div class="guest-name">
                <span class="customer-border-top">
                    <p class="fs-6">Guest Signature</p>
                </span>
            </div>
        </div>
    </div>
    <!-- <script src="script.js"></script> -->
</body>

</html>