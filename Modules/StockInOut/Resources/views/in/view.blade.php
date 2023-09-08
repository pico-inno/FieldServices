@extends('App.main.navBar')


@section('inventory_icon', 'active')
@section('inventory_show', 'active show')
@section('stockin_here_show','here show')
@section('stockin_active_show', 'active show')
@section('stock_in_active_show', 'active show')


@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Stockin Details</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('stockinout::stockin.stockin')}}</li>
        <li class="breadcrumb-item text-muted"><a href="{{route('stock-in.index')}}">{{__('stockinout::stockin.list')}}</a></li>
        <li class="breadcrumb-item text-dark">{{$stockin->stockin_voucher_no}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('styles')
    {{-- css file for this page --}}
@endsection

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!-- begin::Invoice 3-->
            <div class="card">
                <!-- begin::Body-->
                <div class="card-body py-20">
                    <!-- begin::Wrapper-->
                    <div class="mw-lg-950px mx-auto w-100">
                        <!-- begin::Header-->
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="pb-12">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column gap-7 gap-md-10">
                                <!--begin::Order details-->
                                <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">{{__('stockinout::stockin.voucher_no')}}</span>
                                        <span class="fs-5">{{$stockin->stockin_voucher_no}}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">{{__('stockinout::stockin.date')}}</span>
                                        <span class="fs-5">{{$stockin->stockin_date}}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">{{__('stockinout::stockin.business_location')}}</span>
                                        <span class="fs-5">{{$location}}</span>
                                    </div>
                                    <div class="flex-root d-flex flex-column">
                                        <span class="text-muted">{{__('stockinout::stockin.stockin_person')}}</span>
                                        <span class="fs-5">{{$stockin_person}}</span>
                                    </div>
                                </div>
                                <!--end::Order details-->
              
                                <!--begin:Order summary-->
                                <div class="d-flex justify-content-between flex-column">
                                    <!--begin::Table-->
                                    <div class="table-responsive border-bottom mb-9">
                                        <table class="table border-1 Datatable-tb align-middle  rounded table-row-dashed fs-6 g-5" id="kt_datatable_example">
                                            <!--begin::Table head-->
                                            <thead>
                                                <!--begin::Table row-->
                                                <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0">
                                            
                                                    <th></th>
                                        
                                        

                                                <th class="min-w-175px pb-2">{{__('stockinout::stockin.products')}}</th>
                                                <th class="min-w-70px text-start pb-2">{{__('stockinout::stockin.received_qty')}}</th>
                                                <th class="min-w-80px text-start pb-2">{{__('stockinout::stockin.uom')}}</th>
                                                <th class="min-w-100px text-start pb-2">{{__('stockinout::stockin.remark')}}</th>
                                                
                                                </tr>
                                                <!--end::Table row-->
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-semibold text-gray-600">

                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end:Order summary-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Body-->
                        <!-- begin::Footer-->

                            <!-- begin::Actions-->
                            {{-- <div class="my-1 me-5"> --}}
                                <!-- begin::Pint-->
                                {{-- <a href="{{route('stock-in.invoice.print', $stockin->id)}}" type="button" class="btn btn-success my-1 me-12 print-invoice">{{__('stockinout::stockin.print_invoice')}}
                                </a> --}}
                                <!-- end::Pint-->
                            {{-- </div> --}}
                            <!-- end::Actions-->
                            <!-- begin::Action-->

                        <!-- end::Footer-->
                    </div>
                    <!-- end::Wrapper-->
                </div>
                <!-- end::Body-->
            </div>
            <!-- end::Invoice 1-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@push('scripts')


<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script>
   var stockinId=@json($stockin->id);
    $(document).ready(function () {
        let table = $('.Datatable-tb').DataTable({
            dom: '<"datatable-scroll"t><"datatable-footer">',
            processing: true,
            serverSide: true,
            ajax: {
                url: '/stockin/view/details',
                type:'POST',
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                data: { stockinId: stockinId },
            },

            columns: [
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": `
                                        <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px">
                                            <i id="toggle" class="fas fa-plus"></i>
                                        </button>
                                    `
                },

     
                {
                    data: 'productName',
                    name: 'productName',
    
                },

                {
                    data: 'receivedQty',
                    name: 'receivedQty'
                },
                {
                    data: 'uomName',
                    name: 'uomName',
        
                },
                {
                    data: 'remark',
                    name: 'remark'
                },

            ]
        });


        // Sub Table for Variation
        $('.Datatable-tb tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var rowData = row.data();

  console.log(rowData);

  
            // for '+' and '-' click button
            let toggle = tr.find('#toggle');

            if ( row.child.isShown() ) {
                toggle.removeClass('fa-minus').addClass('fa-plus');

                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');

            }else {
                toggle.removeClass('fa-plus').addClass('fa-minus');


                let currentBanalce = JSON.parse(rowData.current_banalce);
                console.log(currentBanalce);

let childRows = `
    <table class="table">
        <thead>
            <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0">
                <th></th>
                <th class="min-w-175px text-end pb-2">Lot Serial No</th>
                <th class="min-w-175px text-end pb-2">Expired Date</th>
                <th class="min-w-175px text-end pb-2">Quantity</th>
                <th class="min-w-175px text-end pb-2">UOM</th>
            </tr>
        </thead>
        <tbody>
            ${currentBanalce.map(item => format(item)).join('')}
        </tbody>
    </table>
`;

row.child(childRows).show();
tr.addClass('shown');

            }
        });

         /* Formatting function for row details - modify as you need */
         function format(item) {
    return `
        <tr class="fw-semibold text-gray-600">
            <td class="pb-2"></td>
            <td class="pb-2 text-end">${item.lot_serial_no}</td>
            <td class="min-w-80px text-end pb-2">${item.expired_date}</td>
            <td class="min-w-100px text-end pb-2">${parseFloat(item.ref_uom_quantity).toFixed(2)}</td>
            <td class="min-w-100px text-end pb-2">${item.uom.name}</td>
        </tr>
    `;
}
    });

</script>

    <script>
        // print invoice
        $(document).on('click', '.print-invoice', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            console.log(url);
            $.ajax({
                url: url,
                success: function (response) {
                    // Open a new window with the invoice HTML and styles
                    // Create a hidden iframe element and append it to the body
                    var iframe = $('<iframe>', {
                        'height': '0px',
                        'width': '0px',
                        'frameborder': '0',
                        'css': {
                            'display': 'none'
                        }
                    }).appendTo('body')[0];
                    console.log(response);
                    // Write the invoice HTML and styles to the iframe document
                    var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    iframeDoc.open();
                    iframeDoc.write(response.html);
                    iframeDoc.close();

                    // Trigger the print dialog
                    iframe.contentWindow.focus();
                    setTimeout(() => {
                        iframe.contentWindow.print();
                        console.log('hello');
                    }, 500);
                }
            });
        });


    </script>
@endpush
