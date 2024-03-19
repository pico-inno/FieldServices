@extends('App.main.navBar')

@section('purchases_icon', 'active')
@section('pruchases_show', 'active show')
@section('purchases_list_active_show', 'active ')


@section('styles')
<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<style>
    #purchase_table_card .table-responsive {
        min-height: 60vh;
    }
    .pagination{
    justify-content: center !important;
    }
    @media(min-width:780px){
    .pagination{
    justify-content: end !important;
    }
    }
</style>
@endsection


@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-sm-3 fs-8">{{__('purchase.list_purchase')}}</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">{{__('purchase.purchase')}}</li>
    <li class="breadcrumb-item text-dark">{{__('purchase.list_purchase')}} </li>
</ul>
<!--end::Breadcrumb-->
@endsection
@section('content')


<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="kt_content_container">
        <livewire:purchase.PurchaseTable  />
    </div>
    <!--end::Container-->
</div>
<div class="modal fade purchaseDetail" tabindex="-1">

</div>
<div class="modal modal-lg fade " data-bs-focus="false" tabindex="-1" id="modal"></div>
<div class="modal modal-lg fade " data-bs-focus="false" tabindex="-1" id="paymentEditModal"></div>
{{-- @include('App.purchase.DetailView.purchaseDetail') --}}
@endsection

@push('scripts')
<script src="{{ asset('customJs/debounce.js') }}"></script>
{{-- <script src={{asset('customJs/Purchases/purchaseTable.js')}}></script> --}}
<script src="{{asset('customJs/Purchases/payment/payment.js')}}"></script>
<script src={{asset('customJs/toastrAlert/alert.js')}}></script>
<script>
    $(document).ready(function(){
            let printId="{{session('print')}}";


            const layoutIdSelectBox = $('#layoutIdBox');
            let layoutId = 1;
            layoutIdSelectBox.change(function(e){
                layoutId = e.target.value;
            })

            if(printId){
                let url=`/purchase/print/${printId}/Invoice`;
                loadingOn();
                ajaxPrint(url);
            }
            $(document).on('click', '.print-invoice', function(e) {
                e.preventDefault();
                loadingOn();
                var url = $(this).data('href');
                ajaxPrint(url);

            });
            function ajaxPrint(url){
                $.ajax({
                    url: url,
                    data : {
                        'layoutId' : layoutId
                    },
                    success: function(response) {
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
                        console.log(response.html);
                        // Write the invoice HTML and styles to the iframe document
                        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                        iframeDoc.open();
                        iframeDoc.write(response.html);
                        iframeDoc.close();

                        // Trigger the print dialog
                        iframe.contentWindow.focus();
                        loadingOff();
                        setTimeout(() => {
                            loadingOff();
                            iframe.contentWindow.print();
                        }, 500);
                    }
                });
            }
            $(document).on('click', '.view_detail', function(){

                loadingOn();
                $url=$(this).data('href');
                $('.purchaseDetail').load($url, function() {
                    $(this).modal('show');
                    loadingOff();
                });
            });
          })
</script>
@endpush
