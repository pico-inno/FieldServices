@extends('App.main.navBar')

@section('sell_icon', 'active')
@section('sell_show', 'active show')
@section($saleType . '_active_show', 'active ')


@section('styles')
<link href={{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }} rel="stylesheet" type="text/css" />
<style>
    .billDiv tr td {
        padding: 8px 0 !important;
    }

    .saleTableCard .table-responsive {
        min-height: 60vh;
    }

    #allSaleTable tr td:nth-child(5),
    #allSaleTable tr td:nth-child(6),
    #allSaleTable tr td:nth-child(7) {
        text-align: end;

    }

    #sale_table_card .table-responsive {
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
@livewireStyles
@endsection


@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">
    @if ($saleType == 'posSales')
    POS Sale List
    @elseif ($saleType == 'sales')
    Sale list

    @elseif ($saleType == 'ecommerce')
    Ecommerce
    @else
    All Sale
    @endif
</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">sale</li>
    <li class="breadcrumb-item text-dark">
        @if ($saleType == 'posSales')
        POS Sale List
        @elseif ($saleType == 'sales')
        Sale list
        @elseif ($saleType == 'ecommerce')
        Ecommerce
        @else
        All Sale
        @endif
    </li>
</ul>
<!--end::Breadcrumb-->
@endsection
@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="kt_content_container">


        <livewire:sale.AllSaleTable :saleType='$saleType' />
        <div id="fake-div">

        </div>
        <!--end::Modal - New Card-->
        <!--end::Modals-->
    </div>
    <!--end::Container-->
</div>

<div class="modal fade" tabindex="-1" id="folioPosting"></div>
<div class="modal modal-lg fade" tabindex="-1" data-bs-focus="false" id="reservationFolioPosting"></div>
<div class="modal modal-lg fade " tabindex="-1" data-bs-focus="false" id="modal"></div>
<div class="modal modal-lg fade " tabindex="-1" data-bs-focus="false" id="paymentEditModal"></div>
@livewireScripts
@endsection

@push('scripts')
<script>

    var saleType = @json($saleType ?? '')
</script>
<script src="{{ asset('customJs/debounce.js') }}"></script>
{{-- <script src="{{ asset('customJs/sell/saleItemTable.js') }}"></script> --}}
<script src="{{ asset('customJs/sell/payment/payment.js') }}"></script>
<script src="{{ asset('customJs/print/print.js') }}"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>








        let printId = "{{ session('print') }}";
        let layoutId = " {{ session('layoutId') }}";
        let url = `/sell/print/${printId}/Invoice`;
        let name = "{{ session('name') }}";
        if (printId && !name) {
            loadingOn();
            ajaxPrint(url, layoutId);
        }
        if(name && printId){
            loadingOn();
            generateImage(url,layoutId,name);
        }

        function generateImage(url,layoutId,name) {
            loadingOn();
            $.ajax({
                url: url,
                data: {
                    'layoutId': layoutId
                },
                success: function(response) {
                    var newWindow = window.open('', '_blank');
                    newWindow.document.write(response.html);
                    newWindow.document.close();

                    setTimeout(function() {
                        html2canvas(newWindow.document.body, {
                            useCORS: true,
                            allowTaint: true
                        }).then(function(canvas) {
                            var img = canvas.toDataURL('image/png');

                            var downloadLink = document.createElement('a');
                            downloadLink.href = img;
                            downloadLink.download = name +
                                '.png';
                            document.body.appendChild(downloadLink);
                            downloadLink.click();

                            Swal.fire({
                                title: 'Image downloaded!',
                                type: 'success',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Got it!'
                            });
                            newWindow.close();
                            document.body.removeChild(downloadLink);

                            loadingOff();
                        });
                    }, 500);
                },
                error: function(error) {
                    console.error('Error:', error);
                    loadingOff();
                }
            });
        }

        $(document).on('click', '.download-image', function(e) {
            e.preventDefault();
            var url = $(this).data('href');
            var layoutId = $(this).data('layoutId');
            var name = $(this).data('name');
            generateImage(url,layoutId,name);
        });


        


        $(document).on('click', '.print-invoice', function(e) {
            e.preventDefault();
            loadingOn();
            let lid = $(this).data('layoutId');
            let url = $(this).data('href');
            ajaxPrint(url, lid);
        });





        $(document).on('click', '.postToRegisterationFolio', function(e) {
            e.preventDefault();

            loadingOn();
            $('#folioPosting').load($(this).data('href'), function() {
                $('.joinSelect').select2();
                $(this).modal('show');

                loadingOff();
                $('form#postFolioToFolioInvoice').submit(function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var data = form.serialize();
                    $.ajax({
                        method: 'POST',
                        url: $(this).attr('action'),
                        dataType: 'json',
                        data: data,
                        success: function(result) {
                            if (result.success == true) {
                                $('#folioPosting').modal('hide');
                                toastr.success(result.msg);
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                        error: function(result) {
                            toastr.error(result.responseJSON.errors,
                                'Something went wrong');
                        }
                    });
                });
            });
        });

        $(document).on('click', '.post-to-reservation', function(e) {
            loadingOn();
            e.preventDefault();
            $('#reservationFolioPosting').load($(this).data('href'), function() {
                loadingOff();
                $('.joinSelect').select2();
                $(this).modal('show');
                $('form#postToReservationFolio').submit(function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var data = form.serialize();
                    $.ajax({
                        method: 'POST',
                        url: $(this).attr('action'),
                        dataType: 'json',
                        data: data,
                        success: function(result) {
                            if (result.success == true) {
                                $('#reservationFolioPosting').modal('hide');
                                toastr.success(result.msg);
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                        error: function(result) {
                            toastr.error(result.responseJSON.errors,
                                'Something went wrong');
                        }
                    });
                });
            });
        });
</script>
@endpush
