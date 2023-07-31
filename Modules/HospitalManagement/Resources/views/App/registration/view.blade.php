



@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
<style>
    .loading{
        left: 50%;
        top: 40%;
        transform: translate(-50%, -50%);
        background-color: #ffffff;
        color: #000000df;
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        padding: 10px;
        position:absolute;
        font-size: 13px;
        font-weight: 300;
    }
</style>
@endsection
@section('registration_icon','active')
@section('hospital_registration_show','active show')
@section('registration_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Registration</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1 fs-5">
    <li class="breadcrumb-item text-muted">Hospital</li>
    <li class="breadcrumb-item text-dark">Registration</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap mb-5">
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-15 ">
                            <!--begin::User-->
                            <div class="d-flex my-2 justify-content-center align-items-center">
                                <!--begin::Name-->
                                <a  class="text-gray-600  fs-5 fw-bold me-1 d-block text-success">Registration Code : <span class=" text-primary" title="Double click to copy" id="clipboard">{{$data->registration_code}}</span>
                                    <button class="btn btn-icon btn-sm p-0" data-clipboard-target="#clipboard">
                                        <i class="fa-solid fa-copy fs-6 clipboard-icon ki-copy"></i>
                                    </button>
                                    @if ($data->joint_registration_id)
                                    <a href="{{route('registration_view',$data->joint_registration_id)}}">
                                        <span class="badge badge-info ms-2">
                                            <i class="fa-solid fa-link text-gray-100 pe-2"></i> Joined To {{$data->jointRegistration['registration_code']}}
                                        </span>
                                    </a>
                                    @else

                                    @endif

                                </a>
                            </div>
                            <!--end::User-->
                            <!--begin::Actions-->
                            <div class="d-flex my-2">
                                <a   class="btn btn-sm btn-primary me-2" id="editInfoBtn" data-href="{{route('registration_info_edit',$data->id)}}">Edit</a>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Title-->
                        <div class="row g-10 mb-3">
                            <div class="col-md-5 col-12">
                                <div class="mb-10">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-3">Patient Type:</div>
                                    <div class="fw-bold fs-6 text-gray-800">{{$data->registration_type}}</div>
                                </div>
                                <div class="d-flex gap-18 flex-wrap">
                                    <div class="">
                                        <div class="fw-semibold fs-7 text-gray-600 mb-3">Patient Name:</div>
                                        <div class="fw-bold fs-7 text-gray-800">{{$data->patient['prefix']}}{{$data->patient['first_name']}}{{$data->patient['middle_name']}}{{$data->patient['last_name']}}</div>
                                    </div>
                                    <div class="">
                                        <div class="fw-semibold fs-7 text-gray-600 mb-3">Company Name:</div>
                                        <div class="fw-bold fs-7 text-gray-800">{{$data->company ? $data->company['company_name']: '-'}}</div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-7 col-12 ">
                                <div class="d-flex mb-10 gap-10 justify-content-between flex-md-nowrap flex-wrap mb-10">
                                    <div class="">
                                        <div class="fw-semibold fs-7 text-gray-600 mb-3">IPD Check In Date:</div>
                                        <div class="fw-bold fs-7 text-gray-700  flex-wrap">
                                            <span class="pe-2">{{ date_format(date_create($data->ipd_check_in_date),"Y-M-d   (h:i A)")}}</span>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="fw-semibold fs-7 text-gray-600 mb-3">OPD Check In Date:</div>
                                        <div class="fw-bold fs-7 text-gray-700  flex-wrap">
                                            <span class="pe-2">{{ date_format(date_create($data->opd_check_in_date),"Y-M-d   (h:i A)")}}</span>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="fw-semibold fs-7 text-gray-600 mb-3">Check OUT Date:</div>
                                        <div class="fw-bold fs-7 text-gray-700  flex-wrap">
                                            <span class="pe-2">{{ date_format(date_create($data->check_out_date),"Y-M-d   (h:i A)")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-10 gap-8  flex-wrap">
                                    <div class="">
                                        <div class="fw-semibold fs-7 text-gray-600 mb-3">Registration Status:</div>
                                        <div class="fw-bold fs-7 text-gray-700  flex-wrap">
                                            <span class="pe-2">{{$data->registration_status}}</span>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="fw-semibold fs-7 text-gray-600 mb-3">Booking Confirm At:</div>
                                        <div class="fw-bold fs-7 text-gray-700  flex-wrap">
                                            <span class="pe-2">{{ date_format(date_create($data->booking_confirmed_at),"Y-M-d   (h:i A)")}}</span>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="fw-semibold fs-7 text-gray-600 mb-3">Booking Confirm By:</div>
                                        <div class="fw-bold fs-7 text-gray-700  flex-wrap">
                                            <span class="pe-2">{{$data->booking_by['username']??'-'}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Details-->
                <!--begin::Navs-->
                <ul class="nav nav-stretch nav-tabs  nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold">
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4  {{session('updated')=="roomRegister" ? '' :'active'}}" data-bs-toggle="tab" href="#patienInfoTab">Patient Info</a>
                    </li>
                    @if ($data->registration_type=="IPD")
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 {{session('updated')=="roomRegister" ? 'active' :''}}" data-bs-toggle="tab" href="#roomInfoTab">Room Info</a>
                    </li>
                    @endif

                    @php
                    $hospitalRedistration=Modules\HospitalManagement\Entities\hospitalRegistrations::where('joint_registration_id',$data->id)->with('patient');
                    $childRegistrations= $hospitalRedistration->get();
                    $childRegistrationsCount=$hospitalRedistration->count();
                    @endphp
                    @if($childRegistrationsCount>0)
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#JoinedRegistration">Joined Registration</a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#billing">Billing</a>
                    </li>


                </ul>
                <!--begin::Navs-->
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade   {{session('updated')=="roomRegister" ? '' :' active show'}}" id="patienInfoTab" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                        <!--begin::Card body-->
                        <div class="card-body p-9">
                            <!--begin::Row-->
                            <div class="row mb-7 fs-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Full Name</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{$data->patient['prefix']}} {{$data->patient['first_name']}} {{$data->patient['middle_name']}} {{$data->patient['last_name']}}</span>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                            <!--begin::Input group-->
                            <div class="row mb-7 fs-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Date Of Bath</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 d-flex align-items-center">
                                    <span class="fw-bold fs-6 text-gray-800 me-2">{{$data->patient['dob']??'-'}}</span>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-7 fs-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Father Name</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">Mr Johny</a>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-7 fs-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Gender</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">Male</span>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-7 fs-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted">Blood Type</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">AB</span>
                                </div>
                                <!--end::Col-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
            </div>

            @if($data->registration_type=="IPD")
            <div class="tab-pane fade  {{session('updated')=="roomRegister" ? ' active show' :''}}" id="roomInfoTab" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card">
                        <div class="card-header  px-2">
                            <div class="d-flex  justify-content-end align-items-center w-100 me-5">
                                <button class="btn btn-sm  btn-light-info  " id="editRoomBtn" data-href="{{route('registration_room_edit',$data->id)}}">
                                    <i class="fa-regular fa-pen-to-square "></i> Edit Registered Room
                                </button>
                            </div>
                        </div>
                        <div class="card-body px-2">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-row-bordered align-middle gy-4 ">
                                    <thead class="border-bottom border-gray-200 fs-6 text-gray-400 fw-bold bg-light bg-opacity-75">
                                        <tr class="fs-6 fw-bold">
                                            <td class="min-w-150px ps-5">Room</td>
                                            <td class="min-w-150px">Room Type</td>
                                            <td class="min-w-150px">Room Rate</td>
                                            <td class="min-w-150px">Check In Date</td>
                                            <td class="min-w-150px">check out Date</td>
                                            {{-- <td></td> --}}
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        <!--begin::Table row-->
                                        @foreach ($roomDatas as $room)
                                        <tr class="fs-7 fw-semibold">
                                            <td class="ps-5">{{$room->room['name']}}</td>
                                            <td> {{$room->room_type['name']}}</td>
                                            <td> {{$room->rate['rate_name']}}</td>
                                            <td>{{ date_format(date_create($room->check_in_date),"Y-M-d   (h:i A)")}}</td>
                                            <td>{{ date_format(date_create($room->check_out_date),"Y-M-d   (h:i A)")}}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($childRegistrationsCount>0)
            <div class="tab-pane fade" id="JoinedRegistration" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="row ">
                        <div class="card">
                            <div class="card-body px-2">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                            <tr>
                                                <td class="min-w-150px">VRC</td>
                                                <td class="min-w-150px">Patient Name</td>
                                                <td class="min-w-150px">Check In Date</td>
                                                <td class="min-w-150px">Check Out Date</td>
                                                <td class="min-w-150px">Actions</td>
                                                {{-- <td></td> --}}
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                            <!--begin::Table row-->

                                            @foreach ($childRegistrations as $registration)
                                            <tr>
                                                <td>{{$registration->registration_code}}</td>
                                                <td>{{$registration->patient['prefix']}} {{$registration->patient['first_name']}} {{$registration->patient['middle_name']}} {{$registration->patient['last_name']}}</td>
                                                <td>2023-Jun-05 (12:00 PM)</td>
                                                <td>
                                                    2023-Jun-08 (12:00 PM)
                                                </td>
                                                <td class="text-right">
                                                    <a href="{{route('registration_view',$registration->id)}}"  class="btn btn-sm btn-light btn-primary">View</a>
                                                </td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="tab-pane fade" id="billing" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="row ">
                        <div class="col-md-3 col-12 mb-10 ">
                            <div class="card h-auto">
                                <div class="card-body p-5">
                                    <!--begin::Radio group-->
                                    <div class="d-flex d-md-block gap-5 gap-md-0 justify-content-between " id="RegisterationIdList" >

                                        <label class="btn btn-outline btn-outline-dashed  d-flex flex-stack text-start p-6 mb-5 nav-item col-md-12 col-4 ">
                                            <!--end::Description-->
                                            <div class="d-flex align-items-center me-2 active" >
                                                <!--begin::Radio-->
                                                <div class="form-check form-check-sm form-check-custom form-check-solid form-check-primary me-6">
                                                    <input class="form-check-input" name="folioRegister" value={{$data->id}} checked type="checkbox" name="plan" disabled />
                                                </div>
                                                <!--end::Radio-->

                                                <!--begin::Info-->
                                                <div class="flex-grow-1">
                                                    <h2 class="d-flex align-items-center fs-7 fw-bold flex-wrap">
                                                        {{$data->patient['prefix']}}{{$data->patient['first_name']}}{{$data->patient['middle_name']}}{{$data->patient['last_name']}} <span class="text-gray-600 fs-8 ps-3">({{$data->registration_type}})</span>
                                                    </h2>
                                                    <div class="fw-semibold fs-7 opacity-50">
                                                        {{$data->registration_code}}
                                                    </div>
                                                </div>
                                                <!--end::Info-->
                                            </div>
                                            <!--end::Description-->
                                        </label>
                                        <!--end::Radio button-->
                                        <!--begin::Radio button-->
                                        @php
                                        $childRegistration=Modules\HospitalManagement\Entities\hospitalRegistrations::where('joint_registration_id',$data->id)->with('patient')->get();
                                        // dd($childRegistration->toArray());
                                        @endphp

                                        @if (count($childRegistration))
                                        @foreach ($childRegistration as $registration)
                                            <label class="btn btn-outline btn-outline-dashed  d-flex flex-stack text-start p-6 mb-5 col-md-12 col-4 nav-item ">
                                                <!--end::Description-->
                                                <div class="d-flex align-items-center me-2">
                                                    <!--begin::Radio-->
                                                    <div class="form-check form-check-sm form-check-custom form-check-solid form-check-primary me-6">
                                                        <input class="form-check-input folio_select" type="checkbox" name="folioRegister" value="{{$registration->id}}" />
                                                    </div>
                                                    <!--end::Radio-->

                                                    <!--begin::Info-->
                                                    <div class="flex-grow-1">
                                                        <h2 class="d-flex align-items-center fs-5 fw-bold flex-wrap">
                                                            {{$registration->patient['first_name']}}<span class="text-gray-600 fs-8 ps-3">({{$registration->registration_type}})</span>
                                                        </h2>
                                                        <div class="fw-semibold fs-7 opacity-50">
                                                            {{$registration->registration_code}}
                                                        </div>
                                                    </div>
                                                    <!--end::Info-->
                                                </div>
                                                <!--end::Description-->
                                            </label>
                                        @endforeach
                                        @endif
                                    </div>
                                    <!--end::Radio group-->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 tab-content">
                            <div class="tab-pane  fade show active" id="patient1" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <!--begin::Billing History-->
                                    <div class="card mainDiv">
                                        <!--begin::Card header-->
                                        <div class="card-header card-header-stretch border-bottom border-gray-200">
                                            <!--begin::Toolbar-->
                                            <div class="card-toolbar m-0">

                                                <!--begin::Tab nav-->
                                                <ul class="nav nav-stretch nav-line-tabs border-transparent" role="tablist">
                                                    <!--begin::Tab nav item-->
                                                    <li class="nav-item" role="presentation">
                                                        <a id="alltab" class="nav-link fs-5 fw-semibold me-3 active" data-bs-toggle="tab" role="tab" href="#allTab">All</a>
                                                    </li>

                                                    @if($data->registration_type=="IPD")
                                                    <li class="nav-item" role="presentation">
                                                        <a id="kt_billing_6months_tab" class="nav-link fs-5 fw-semibold me-3 " data-bs-toggle="tab" role="tab" href="#roomBillingTab">Room</a>
                                                    </li>
                                                    @endif
                                                    <!--end::Tab nav item-->
                                                    <!--begin::Tab nav item-->
                                                    <li class="nav-item" role="presentation">
                                                        <a id="kt_billing_1year_tab" class="nav-link fs-5 fw-semibold me-3" data-bs-toggle="tab" role="tab" href="#saleFolioTab">Sale</a>
                                                    </li>
                                                    <!--end::Tab nav item-->
                                                    <!--begin::Tab nav item-->
                                                    <li class="nav-item" role="presentation">
                                                        <a id="kt_billing_alltime_tab" class="nav-link fs-5 fw-semibold" data-bs-toggle="tab" role="tab" href="#kt_billing_all">Service</a>
                                                    </li>
                                                    <!--end::Tab nav item-->
                                                </ul>
                                                <!--end::Tab nav-->
                                            </div>
                                            <div class="p-5">
                                                <button class="btn btn-sm btn-success " type="button" >
                                                    Print All
                                                </button>
                                                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                                                    Discharge
                                                </button>
                                            </div>
                                            <!--end::Toolbar-->
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Tab Content-->
                                        <div class="tab-content mb-5 px-2">
                                            <!--begin::Tab panel-->
                                            <div id="allTab" class="card-body p-0 tab-pane fade show active" role="tabpanel" aria-labelledby="allTab">
                                                <!--begin::Table container-->
                                                <div class="table-responsive" style="max-height: 600px;">
                                                    <!--begin::Table-->
                                                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                                                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                            <tr>
                                                                <td></td>
                                                                <td class="min-w-150px">Voucher No</td>
                                                                <td class="min-w-150px">Transaction Type</td>
                                                                <td class="min-w-150px text-end">Sale Amount</td>
                                                                <td class="min-w-150px text-end">Total Item Discount</td>
                                                                <td class="min-w-150px text-end">Extra Discount</td>
                                                                <td class="min-w-150px text-end">Total Sale Amount</td>
                                                                <td class="min-w-150px text-end">Paid Amount </td>
                                                                <td class="min-w-150px text-end">Balance Amount</td>

                                                            </tr>
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600 allTab ">
                                                            <!--begin::Table row-->
                                                            @php
                                                                $folioDetails=Modules\HospitalManagement\Entities\hospitalFolioInvoiceDetails::where('folio_invoice_id',$folio->id)->get();
                                                                // dd($folioDetails->toArray());
                                                                $saleAmount=0;
                                                                $totalItemDiscount=0;
                                                                $totalSaleAmount=0;
                                                                $totalExtraDiscount=0;
                                                            @endphp
                                                           @if ($folioDetails)
                                                                @foreach ($folioDetails as $fd)
                                                                    @php
                                                                        $sale='';
                                                                        $voucher_no='';
                                                                        $type="";
                                                                        if($fd->transaction_type=='sale'){
                                                                            $fd->load('sales');
                                                                            $sale=$fd->sales;
                                                                            if($sale){
                                                                                $voucher_no=$sale->sales_voucher_no;
                                                                                $type="sale";
                                                                            };
                                                                            // dd($sale->sale_amount,$sale['extra_discount_type'],$sale->toArray());
                                                                        }elseif($fd->transaction_type=="room"){
                                                                            $fd->load('roomSales');
                                                                            $sale=$fd->roomSales;
                                                                            if($sale){
                                                                                $voucher_no=$sale->room_sales_voucher_no;
                                                                                $type="room sale";
                                                                            }
                                                                        };
                                                                        if($sale){
                                                                            $saleAmount+=$sale->sale_amount;
                                                                            $totalItemDiscount+=$sale->total_item_discount;
                                                                            $totalExtraDiscount+=DiscAmountCal($sale->sale_amount,$sale->extra_discount_type,$sale->extra_discount_amount ?? 0);
                                                                            $totalSaleAmount+=$sale->total_sale_amount;
                                                                        }
                                                                    @endphp
                                                                <tr>
                                                                    @if ($sale)
                                                                        <td></td>
                                                                        <td class="">{{$voucher_no}}</td>
                                                                        <td>{{$type}}</td>
                                                                        <td class="text-end">{{$sale->sale_amount}}</td>
                                                                        <td class="text-end">
                                                                            <a  class="btn btn-sm btn-light btn-active-light-primary">{{round($sale['total_item_discount'],2)}} </a>
                                                                        </td>
                                                                        <td class="min-w-150px text-end ">
                                                                            {{round($sale['extra_discount_amount'],2)}} {{$sale['extra_discount_type']=="fixed"?'ks':'%'}}
                                                                        </td>
                                                                        <td class="text-end ">{{$sale->total_sale_amount}}</td>
                                                                        <td class="text-end ">{{$sale->paid_amount}}</td>
                                                                        <td class="text-end ">{{$sale->balance_amount}}</td>
                                                                    @endif
                                                                </tr>
                                                                @endforeach
                                                           @endif

                                                        </tbody>
                                                    </table>
                                                    <!--end::Table-->
                                                </div>
                                                <div class="">
                                                    <table class="table mt-5">
                                                        <tfoot>
                                                            <tr>
                                                                <td class="fw-bold text-end" colspan="4">
                                                                    Sale Amount (=):
                                                                </td>
                                                                <td class="text-end pe-3 fw-bold saleAmountForAll">
                                                                    {{round($saleAmount,2)}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold text-end " colspan="4">
                                                                    Total Item Discount (-):
                                                                </td>
                                                                <td  class="text-end pe-3 fw-bold totalItemDiscountForAll">
                                                                    {{$totalItemDiscount}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold text-end" colspan="4">
                                                                    Total Extra Discount (-):
                                                                </td>
                                                                <td colspan="" class="text-end pe-3 fw-bold extraDiscountAmountForAll">
                                                                    {{$totalExtraDiscount}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end">
                                                                    <a class="btn btn-sm btn-secondary printAllTab" data-href="/get/folios/AllTabs/">Print This</a>
                                                                </td>
                                                                <td class="fw-bold text-end " colspan="3">
                                                                    Total Sale Amount (=):
                                                                </td>
                                                                <td colspan="" class="text-end">
                                                                    <a class="btn btn-sm btn-light btn-active-light-primary fw-bold totalSaleAmountForAllTab">{{round($totalSaleAmount,2)}}</a>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <!--end::Table container-->
                                            </div>
                                            @if($data->registration_type=="IPD")
                                            <div id="roomBillingTab" class="card-body p-0 tab-pane fade" role="tabpanel" aria-labelledby="roomBillingTab">
                                                <!--begin::Table container-->
                                                <div class="table-responsive" style="max-height: 600px;">
                                                    <!--begin::Table-->
                                                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                                                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                            <tr>
                                                                <td></td>
                                                                <td class="min-w-150px">Voucher No</td>
                                                                <td class="min-w-150px text-end">Sale Amount</td>
                                                                <td class="min-w-150px text-end">Total Item Discount</td>
                                                                <td class="min-w-150px text-end">Total Sale Amount</td>
                                                                <td class="min-w-150px text-end">Paid Amount</td>
                                                                <td class="min-w-150px text-end">Balance Amount</td>
                                                                {{-- <td></td> --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600 roomSaleTab ">
                                                            <!--begin::Table row-->
                                                            @php
                                                            $folioDetailsForRoom=Modules\HospitalManagement\Entities\hospitalFolioInvoiceDetails::with(['roomSales'=>function($r){
                                                                $r->where('transaction_type','registration');
                                                            }])
                                                            ->where('folio_invoice_id',$folio->id)
                                                            ->where('transaction_type','room')->get();
                                                            $saleAmount=0;
                                                            $totalItemDiscount=0;
                                                            $totalSaleAmount=0;
                                                            // $totalExtraDiscount=0;
                                                            @endphp
                                                            @if ($folioDetailsForRoom)
                                                                @foreach ($folioDetailsForRoom as $fd)
                                                                    @php
                                                                        $roomSale=$fd->toArray()['room_sales'];
                                                                        if($roomSale){
                                                                            $totalSaleAmount+=$roomSale['total_sale_amount'];
                                                                            $totalItemDiscount+=$roomSale['total_item_discount'];
                                                                            $saleAmount+=$roomSale['sale_amount'];
                                                                        }
                                                                    @endphp
                                                                    @if ($roomSale)
                                                                        <tr>
                                                                            <td class="appendChildRow cursor-pointer" data-id="rs_s{{$roomSale['id']}}" >
                                                                                <i class="fa-solid fa-circle-plus fs-3 text-primary d-block fa-icon"></i>
                                                                            </td>
                                                                            <td>{{$roomSale['room_sales_voucher_no']}}</td>
                                                                            <td class=" text-end">{{$roomSale['sale_amount']}}</td>
                                                                            <td class=" text-end"> <a  class="btn btn-sm btn-light btn-active-light-primary">{{round($roomSale['total_item_discount'],2)}} </a></td>
                                                                            <td class=" text-end">{{$roomSale['total_sale_amount']}}</td>
                                                                            <td class=" text-end">{{$roomSale['paid_amount']}}</td>
                                                                            <td class=" text-end">{{$roomSale['balance_amount']}}</td>
                                                                            {{-- <td class=" text-end">{{$roomSale['ipd_check_in_date']}}</td>
                                                                            <td class=" text-end">{{$roomSale['check_out_date']}}</td> --}}
                                                                        </tr>
                                                                        <tr id="rs_s{{$roomSale['id']}}" style="display: none;">
                                                                            <td colspan="7">
                                                                                <table class="table table-row-bordered ">
                                                                                    <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                                                        <tr>
                                                                                            <td class="text-end">Room Rate</td>
                                                                                            <td class=" text-end">Room Type</td>
                                                                                            <td class=" text-end">Room </td>
                                                                                            <td class=" text-end">Room Fees</td>
                                                                                            <td class=" text-end">Subtotal</td>
                                                                                            <td class=" text-center">Discount</td>
                                                                                            <td class="text-center">Check In</td>
                                                                                            <td class="text-center">Check Out</td>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @php
                                                                                        $roomSaleDetails=$roomSale['room_sale_details'];
                                                                                        @endphp
                                                                                       @if ($roomSaleDetails)
                                                                                            @foreach ($roomSaleDetails as $rsd)
                                                                                            <tr>
                                                                                                <td class="min-w-150px text-end">{{$rsd['room_type']['name']}}</td>
                                                                                                <td class="min-w-150px text-end">{{$rsd['room_rate']['rate_name']}}</td>
                                                                                                <td class="min-w-150px text-end">{{$rsd['room']['name']}}</td>
                                                                                                <td class="min-w-150px text-end ">{{$rsd['room_fees']}}</td>
                                                                                                <td class="min-w-150px text-end ">{{$rsd['subtotal']}}</td>
                                                                                                <td class="min-w-150px text-center ">{{round($rsd['per_item_discount'],2)}} {{$rsd['discount_type']=="fixed"?'ks':'%'}}</td>
                                                                                                <td class="min-w-150px text-center">{{date_format(date_create($rsd['check_in_date']),"d-m-y h:i A")}}</td>
                                                                                                <td class="min-w-175px text-center">{{date_format(date_create($rsd['check_out_date']),"d-m-y h:i A")}}</td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                       @endif
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                    <!--end::Table-->
                                                </div>
                                                <div class="">
                                                    <table class="table mt-5">
                                                        <tfoot>
                                                            <tr>
                                                                <td class="fw-bold text-end" colspan="4">
                                                                    Sale Amount (=):
                                                                </td>
                                                                <td class="text-end pe-3 fw-bold saleAmountForRoomSale">
                                                                    {{round($saleAmount,2)}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold text-end " colspan="4">
                                                                    Total Item Discount (-):
                                                                </td>
                                                                <td class="text-end pe-3 fw-bold totalItemDiscountForRoomSale">
                                                                    {{$totalItemDiscount}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end">
                                                                    <a  class="btn btn-sm btn-secondary ">Print This</a>
                                                                </td>
                                                                <td class="fw-bold text-end" colspan="3">
                                                                    Total Sale Amount (=):
                                                                </td>
                                                                <td colspan="" class="text-end totalSaleAmountForRoomSale">
                                                                    <a  class="btn btn-sm btn-light btn-active-light-primary">{{round($totalSaleAmount,2)}}</a>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <!--end::Table container-->
                                            </div>
                                            @endif
                                            <!--end::Tab panel-->
                                            <!--begin::Tab panel-->
                                            <div id="saleFolioTab" class="card-body p-0 tab-pane fade " role="tabpanel" aria-labelledby="saleFolioTab">
                                                <!--begin::Table container-->
                                                <div class="table-responsive" style="max-height: 600px;">
                                                    <!--begin::Table-->
                                                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                                                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                            <tr>
                                                                <td></td>
                                                                <td class="min-w-150px">Voucher No</td>
                                                                <td class="min-w-150px text-end">Sale Amount</td>
                                                                <td class="min-w-150px text-end">Total Item Discount</td>
                                                                <td class="min-w-150px text-end">Extra Discount</td>
                                                                <td class="min-w-150px text-end">Total Sale Amount</td>
                                                                <td class="min-w-150px text-end">Paid Amount</td>
                                                                <td class="min-w-150px text-end">Balance Amount</td>
                                                                <td class="min-w-150px text-center">Sold At</td>
                                                                <td class="min-w-150px text-end">Sold By</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600 SaleTab">
                                                            <!--begin::Table row-->
                                                            @php
                                                                $folioDetailsForSale=Modules\HospitalManagement\Entities\hospitalFolioInvoiceDetails::with('sales')
                                                                ->where('folio_invoice_id',$folio->id)
                                                                ->where('transaction_type','sale')->get();
                                                                $totalSaleAmount=0;
                                                                $saleAmount=0;
                                                                $totalItemDiscount=0;
                                                                $totalExtraDiscount=0;
                                                            @endphp
                                                            @if ($folioDetailsForSale)
                                                                @foreach ($folioDetailsForSale as $index=>$fd)
                                                                    @php
                                                                        $sale=$fd->toArray()['sales'] ?? '';

                                                                       if($sale){
                                                                            $totalSaleAmount+=$sale['total_sale_amount'];
                                                                            $saleAmount+=$sale['sale_amount'];
                                                                            $totalItemDiscount+=$sale['total_item_discount'];
                                                                            $totalExtraDiscount+=DiscAmountCal($sale['sale_amount'],$sale['extra_discount_type'],$sale['extra_discount_amount'] ?? 0);
                                                                       }
                                                                    @endphp
                                                                    @if ($sale)
                                                                        <tr>
                                                                            <td class="appendChildRow cursor-pointer" data-id="s_s{{$index}}" >
                                                                                <i class="fa-solid fa-circle-plus fs-3 text-primary d-block fa-icon"></i>
                                                                            </td>
                                                                            <td>{{$sale['sales_voucher_no']}}</td>
                                                                            <td class=" text-end">{{$sale['sale_amount']}}</td>
                                                                            <td class=" text-end"> <a  class="btn btn-sm btn-light btn-active-light-primary">{{round($sale['total_item_discount'],2)}} </a></td>
                                                                            <td class="min-w-150px text-end ">
                                                                                {{round($sale['extra_discount_amount'],2)}} {{$sale['extra_discount_type']=="fixed"?'ks':'%'}}
                                                                            </td>
                                                                            <td class=" text-end">{{$sale['total_sale_amount']}}</td>
                                                                            <td class=" text-end">{{$sale['paid_amount']}}</td>
                                                                            <td class=" text-end">{{$sale['balance_amount']}}</td>
                                                                            <td class=" text-center"> {{date_format(date_create($fd->sales->sold_at),"d-m-y h:i A")}}</td>
                                                                            <td class=" text-end"> {{$fd->sales->sold->username}}</td>
                                                                        </tr>
                                                                        <tr id="s_s{{$index}}" style="display: none;">
                                                                            <td colspan="7">
                                                                                <table class="table table-row-bordered ">
                                                                                    <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                                                        <tr>
                                                                                            <td class="text-end">Product</td>
                                                                                            <td class=" text-end">quantity</td>
                                                                                            <td class=" text-end">uom </td>
                                                                                            <td class=" text-end">uom price</td>
                                                                                            <td class=" text-end">Subtotal</td>
                                                                                            {{-- <td class=" text-end">Room Fees</td>
                                                                                            <td class=" text-end">Subtotal</td>
                                                                                            <td class=" text-center">Discount</td> --}}
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @php
                                                                                            $saleDetails=$sale['sale_details'];
                                                                                        @endphp
                                                                                        @if ($saleDetails)
                                                                                            @foreach ($saleDetails as $sd)
                                                                                                @php
                                                                                                    $variation=$sd['product_variation']['variation_template_value']
                                                                                                @endphp
                                                                                                <tr>
                                                                                                    {{-- <td>here</td> --}}
                                                                                                    <td class="min-w-150px text-end">
                                                                                                        {{$sd['product']['name']}}
                                                                                                        @if ( $variation)
                                                                                                        ({{$variation['name']}})
                                                                                                        @endif
                                                                                                    </td>
                                                                                                    <td class="min-w-150px text-end">{{$sd['quantity']}}</td>
                                                                                                    <td class="min-w-150px text-end">{{$sd['uom']['name']}}</td>
                                                                                                    <td class="min-w-150px text-end">{{$sd['uom_price']}}</td>
                                                                                                    <td class="min-w-150px text-end">{{$sd['subtotal']}}</td>
                                                                                                    {{-- <td class="min-w-150px text-end ">{{$sd['room_fees']}}</td>
                                                                                                    <td class="min-w-150px text-end ">{{$sd['subtotal']}}</td>
                                                                                                    <td class="min-w-150px text-center ">{{round($sd['per_item_discount'],2)}} {{$rsd['discount_type']=="fixed"?'ks':'%'}}</td> --}}
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        @endif
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            @endif


                                                        </tbody>
                                                    </table>
                                                    <!--end::Table-->
                                                </div>
                                                <div class="">
                                                    <table class="table mt-5">
                                                        <tfoot>
                                                            <tr>
                                                                <td class="fw-bold text-end" colspan="4">
                                                                    Sale Amount (=):
                                                                </td>
                                                                <td class="text-end pe-3 fw-bold saleAmountForSale">
                                                                    {{round($saleAmount,2)}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold text-end " colspan="4">
                                                                    Total Item Discount (-):
                                                                </td>
                                                                <td class="text-end pe-3 fw-bold totalItemDiscountForSale">
                                                                    {{$totalItemDiscount}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold text-end" colspan="4">
                                                                    Total Extra Discount (-):
                                                                </td>
                                                                <td colspan="" class="text-end pe-3 fw-bold extraDiscountAmountForSale">
                                                                    {{$totalExtraDiscount}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end">
                                                                    <a class="btn btn-sm btn-secondary ">Print This</a>
                                                                </td>
                                                                <td class="fw-bold text-end" colspan="3">
                                                                    Total Sale Amount(=) :
                                                                </td>
                                                                <td colspan="" class="text-end">
                                                                    <a class="btn btn-sm btn-light btn-active-light-primary fw-bold totalSaleAmountForSale">{{round($totalSaleAmount,2)}}</a>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <!--end::Table container-->
                                            </div>
                                            <!--end::Tab panel-->
                                            <!--begin::Tab panel-->
                                            <div id="kt_billing_all" class="card-body p-0 tab-pane fade" role="tabpanel" aria-labelledby="kt_billing_all">
                                                <!--begin::Table container-->
                                                <div class="table-responsive">
                                                    <!--begin::Table-->
                                                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                                                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                                            <tr>
                                                                <td class="min-w-150px">Date</td>
                                                                <td class="min-w-250px">Description</td>
                                                                <td class="min-w-150px">Amount</td>
                                                                <td class="min-w-150px">Invoice</td>
                                                                <td></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600">
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <td>Nov 01, 2021</td>
                                                                <td>
                                                                    <a href="#">Billing for Ocrober 2023</a>
                                                                </td>
                                                                <td>$123.79</td>
                                                                <td>
                                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                                </td>
                                                                <td class="text-right">
                                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                                </td>
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <td>Aug 10, 2021</td>
                                                                <td>Paypal</td>
                                                                <td>$35.07</td>
                                                                <td>
                                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                                </td>
                                                                <td class="text-right">
                                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                                </td>
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <td>Aug 01, 2021</td>
                                                                <td>
                                                                    <a href="#">Invoice for July 2023</a>
                                                                </td>
                                                                <td>$142.80</td>
                                                                <td>
                                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                                </td>
                                                                <td class="text-right">
                                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                                </td>
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <td>Jul 20, 2021</td>
                                                                <td>
                                                                    <a href="#">Statements for June 2023</a>
                                                                </td>
                                                                <td>$123.79</td>
                                                                <td>
                                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                                </td>
                                                                <td class="text-right">
                                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                                </td>
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <td>Jun 17, 2021</td>
                                                                <td>Paypal</td>
                                                                <td>$23.09</td>
                                                                <td>
                                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                                </td>
                                                                <td class="text-right">
                                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                                </td>
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <td>Jun 01, 2021</td>
                                                                <td>
                                                                    <a href="#">Invoice for May 2023</a>
                                                                </td>
                                                                <td>$123.79</td>
                                                                <td>
                                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">PDF</a>
                                                                </td>
                                                                <td class="text-right">
                                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                                                </td>
                                                            </tr>
                                                            <!--end::Table row-->
                                                        </tbody>
                                                    </table>
                                                    <!--end::Table-->
                                                </div>
                                                <!--end::Table container-->
                                            </div>
                                            <!--end::Tab panel-->
                                        </div>
                                        <!--end::Tab Content-->
                                    </div>
                                    <!--end::Billing Address-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--end::container-->
</div>
<!--end::Content-->



<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h3 class="offcanvas-title" id="offcanvasExampleLabel"> Bill Counter</h3>
        <button class="btn btn-light btn-sm" id="kt_drawer_example_basic_close" data-bs-dismiss="offcanvas"  aria-label="Close">
            <i class="fas fa-close fs-4"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="">
            <form >
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Room Charge :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Room Charge">
                </div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Sale Charge :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Sale Charge">
                </div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Service Charge :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Service Charge">
                </div>
                <div class="col-12 separator my-6 border-gray-300"></div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Net Amount :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Net Amount">
                </div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Discount :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Discount">
                </div>
                <div class="col-12 separator my-6 border-gray-300"></div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Total Amount :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Total Amount">
                </div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Paid Amount :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Payment">
                </div>
                <div class="col-12 separator my-6 border-gray-300"></div>
                <div class="col-12 mb-5 d-flex align-items-center">
                    <label class="form-label text-gray-700 fs-8 min-w-125px">Balance :</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Balance">
                </div>

                <div class="col-12 separator my-6 border-gray-300"></div>
                <div class="col-12 mb-5">
                    <a class="btn btn-sm btn-primary w-100"  data-bs-toggle="modal" data-bs-target="#selectPaymentModal">
                        Payment
                    </a>
                </div>
                <div class="col-12">
                    <button class="btn btn-sm btn-success w-100">
                        Print
                    </button>
                </div>

                {{-- <button class="btn btn-sm btn-primary w-100">
                    Check Out
                </button> --}}
            </form>
        </div>
    </div>
</div>


<div class="modal modal-lg fade" tabindex="-1" role="dialog" id="editInfo">
</div>
<div class="modal modal-lg fade " tabindex="-1" role="dialog" id="editRoom">
</div>
{{-- @include('hospitalmanagement::App.Hospital.registration.Modals.editRegistrationInfo') --}}
{{-- @include('hospitalmanagement::App.Hospital.registration.Modals.editRegisteredRoom') --}}
@include('hospitalmanagement::App.registration.Modals.selectPayment')
@endsection
@push('scripts')

<script src={{asset("assets/plugins/custom/formrepeater/formrepeater.bundle.js")}}></script>
<script>

    $(document).on('click', '#editInfoBtn', function(){
        $('#editInfo').load($(this).data('href'), function(e) {
            $(this).modal('show');
        });
    });
    $(document).on('click', '#editRoomBtn', function(){
        $('#editRoom').load($(this).data('href'), function(e) {
            $(this).modal('show');
        });
    });
    appendChildInit('appendChildRow');



    // var datepickerIds = [ "check_out_date","opd_check_in_date","ipd_check_in_date","edit_check_in_date","edit_check_out_date","check_out_date"];
    // datepickerIds.forEach(function(id) {
        //     $(`#${id}`).flatpickr({
            //         enableTime: true,
            //         dateFormat: "Y-m-d H:i",
            //     });
            // });

            $('#kt_docs_repeater_basic').repeater({
                initEmpty: false,

                defaultValues: {
                    'text-input': 'foo'
                },

                show: function () {
                    $(this).slideDown();
                    var index = $(this).index();
                    $(this).find('[data-repeater-delete]').show(); // or use .prop('disabled', false) to enable the button
                },

                hide: function (deleteElement) {
                    var index = $(this).index();
                    if(index !=0 ){
                        $(this).slideUp(deleteElement);
                    }
                    return;
                }
            });

            // function for clipboard
            clipboard();

            var pricesForAllTab,pricesForSaleTab,pricesforRoomSaleTab;
            $('.folio_select').on('click',function(){
                if ($(this).is(':checked')) {
                    $('.mainDiv').append(`
                    <div class="loading">
                        <td colspan="4"></td>
                        <td class="min-w-100px" colspan="2">
                            <span class="spinner-border spinner-border-sm align-middle me-3 fw-bold fs-8"></span>
                            <span class="fw-semibold">
                                Loading For More Data....
                            </span>
                        </td>
                    </div>`)
                    let registration_id=$(this).val();
                    $.ajax({
                        url: `/get/registration/folios`,
                        type: 'GET',
                        data: {
                            'registration_id':registration_id
                        },
                        error:function(e){
                            status=e.status;
                            if(status==405){
                                warning('Method Not Allow!');
                            }else if(status==419){
                                error('Session Expired')
                            }else{
                                error(' Something Went Wrong! Error Status: '+status )
                            };
                        },
                        success: function(results){

                            $(`.server_tab_${registration_id}`).remove();
                            $('.allTab').append(results.ForAllTab);
                            $('.roomSaleTab').append(results.ForRoomSaleTab);
                            $('.SaleTab').append(results.ForSaleTab);
                            $('.loading').remove();

                            pricesForAllTab=results.pricesforAllTab;
                            pricesForSaleTab=results.pricesforSaleTab;
                            pricesforRoomSaleTab=results.pricesforRoomSaleTab;

                            increaseTabValue('totalSaleAmountForAllTab',pricesForAllTab.total_sale_amount);
                            increaseTabValue('saleAmountForAll',pricesForAllTab.sale_amount);
                            increaseTabValue('totalItemDiscountForAll',pricesForAllTab.total_item_discount);
                            increaseTabValue('extraDiscountAmountForAll',pricesForAllTab.extra_discount_amount);

                            increaseTabValue('totalSaleAmountForSale',pricesForSaleTab.total_sale_amount);
                            increaseTabValue('saleAmountForSale',pricesForSaleTab.sale_amount);
                            increaseTabValue('totalItemDiscountForSale',pricesForSaleTab.total_item_discount);
                            increaseTabValue('extraDiscountAmountForSale',pricesForSaleTab.extra_discount_amount);


                            increaseTabValue('totalSaleAmountForRoomSale',pricesforRoomSaleTab.total_sale_amount);
                            increaseTabValue('saleAmountForRoomSale',pricesforRoomSaleTab.sale_amount);
                            increaseTabValue('totalItemDiscountForRoomSale',pricesforRoomSaleTab.total_item_discount);
                            appendChildInit(`appendChildFromSer_${registration_id}`);
                        },
                        error:function(){
                            $('.loading').remove();
                        }

                        })
                } else {
                    $(`.server_tab_${$(this).val()}`).remove();
                    decreaseTabValue('totalSaleAmountForAllTab',pricesForAllTab.total_sale_amount);
                    decreaseTabValue('saleAmountForAll',pricesForAllTab.sale_amount);
                    decreaseTabValue('totalItemDiscountForAll',pricesForAllTab.total_item_discount);
                    decreaseTabValue('extraDiscountAmountForAll',pricesForAllTab.extra_discount_amount);

                    decreaseTabValue('totalSaleAmountForSale',pricesForSaleTab.total_sale_amount);
                    decreaseTabValue('saleAmountForSale',pricesForSaleTab.sale_amount);
                    decreaseTabValue('totalItemDiscountForSale',pricesForSaleTab.total_item_discount);
                    decreaseTabValue('extraDiscountAmountForSale',pricesForSaleTab.extra_discount_amount);

                    decreaseTabValue('totalSaleAmountForRoomSale',pricesforRoomSaleTab.total_sale_amount);
                    decreaseTabValue('saleAmountForRoomSale',pricesforRoomSaleTab.sale_amount);
                    decreaseTabValue('totalItemDiscountForRoomSale',pricesforRoomSaleTab.total_item_discount);
                }
            })

            function decreaseTabValue(TabDiv,newTabPrice) {
                let tabValue=Number($(`.${TabDiv}`).text());
                $(`.${TabDiv}`).text(tabValue - newTabPrice);
            }
            function increaseTabValue(TabDiv,newTabPrice) {
                let tabValue=Number($(`.${TabDiv}`).text());
                $(`.${TabDiv}`).text(tabValue +newTabPrice);
            }
            function appendChildInit(cls) {
                $(`.${cls}`).on('click',function(){
                    // alert('----');
                    let dataId=$(this).data('id');
                    $(`#${dataId}`).toggle();
                    $(this).find('i').toggleClass('fa-circle-plus fa-circle-minus');
                })
            }
            function name(params) {

            }


            $(document).on('click', '.printAllTab', function(e) {
                let registrationsId=getValues();
                console.log(registrationsId);
                e.preventDefault();
                var url = $(this).data('href');
                $.ajax({
                    url: url,
                    data:{
                        'registrationId':registrationsId
                    },
                    dataType: 'json',
                    method: 'GET',
                    success: function(response) {
                        var iframe = $('<iframe>', {
                            'height': '0px',
                            'width': '0px',
                            'frameborder': '0',
                            'css': {
                                'display': 'none'
                            }
                        }).appendTo('body')[0];
                        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                        iframeDoc.open();
                        iframeDoc.write(response.html);
                        iframeDoc.close();

                        // Trigger the print dialog
                        iframe.contentWindow.focus();
                        setTimeout(() => {
                            iframe.contentWindow.print();
                        }, 500);
                    }
                });
            });

            function getValues() {
                var form = document.getElementById("RegisterationIdList");
                var checkboxes = form.querySelectorAll('input[type="checkbox"]:checked');
                var selectedValues = [];

                checkboxes.forEach(function(checkbox) {
                    selectedValues.push(checkbox.value);
                });
                return selectedValues;
            }

</script>
@endpush























