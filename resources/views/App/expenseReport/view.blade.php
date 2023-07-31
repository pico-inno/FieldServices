

@extends('App.main.navBar')

@section('expense_active', 'active')
@section('expense_report_active', 'active')
@section('expense_active_show', 'active show')
<!--begin::Heading-->
@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Expense Report</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1 fs-5">
    <li class="breadcrumb-item text-muted">Expense</li>
    <li class="breadcrumb-item text-dark">Report</li>
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
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-5 ">
                            <!--begin::User-->
                            <div class="d-flex my-2 justify-content-center align-items-center">
                                <!--begin::Name-->
                                <h2  class=" fs-2 fw-semibold me-1 d-block ">
                                    {{$report->expense_title}}
                                </h2>
                            </div>
                            <!--end::User-->
                            <!--begin::Actions-->
                            <div class="d-flex my-2">
                                <button class="btn btn-primary btn-sm" id="expenseReportEdit" data-href="{{route('expenseReport.edit',$report->id)}}">Edit</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Title-->
                        <div class="row g-10 mb-3 justify-content-md-between">
                               <div class="col-lg-4 col-md-5 col-12">
                                    <table class="table  table-layout-fixed  table-row-bordered">
                                            <tbody class="">
                                                <tr>
                                                    <th class="text-start">
                                                      <span class="fw-semibold fs-7 text-gray-600">Expense Report No:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                      <span class="fw-bold fs-7 text-gray-800">{{$report->expense_report_no}}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-start">
                                                      <span class="fw-semibold fs-7 text-gray-600">Expense On:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                      <span class="fw-bold fs-7 text-gray-800">{{$report->expense_on}}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-start">
                                                      <span class="fw-semibold fs-7 text-gray-600">Expense Report By:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                      <span class="fw-bold fs-7 text-gray-800">{{$report->reportBy->username }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                    </table>
                               </div>
                                <div class="col-lg-4 col-md-5 col-12">
                                    <table class="table  table-layout-fixed  table-row-bordered">
                                            <tbody class="">
                                                <tr>
                                                    <th class="text-start">
                                                    <span class="fw-semibold fs-7 text-gray-600">Total Expense Amount:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                    <span class="fw-bold fs-7 text-gray-800" id="total_expense_amount">{{$total_expense_amount}}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-start">
                                                    <span class="fw-semibold fs-7 text-gray-600">Paid Amount:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                    <span class="fw-bold fs-7 text-gray-800">{{$total_paid_amount ?? 0}}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-start">
                                                    <span class="fw-semibold fs-7 text-gray-600">Balance Amount:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                    <span class="fw-bold fs-7 text-gray-800" id="balance_amount">{{$total_balance_amount ?? 0}}</span>
                                                    </td>
                                                </tr>

                                            </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-4 col-md-5 col-12">
                                    <table class="table  table-layout-fixed  table-row-bordered">
                                            <tbody class="">
                                                <tr>
                                                    <th class="text-start">
                                                    <span class="fw-semibold fs-7 text-gray-600">Payemnt Status:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                    <span class="fw-bold fs-7" id="total_expense_amount">
                                                        @if ($total_expense_amount == $total_paid_amount)
                                                            <span class="badge badge-success">Paid</span>
                                                        @elseif($total_paid_amount == 0)
                                                            <span class="badge badge-warning">Pending</span>
                                                        @else
                                                            <span class="badge badge-primary">Partial</span>
                                                        @endif

                                                    </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-start">
                                                    <span class="fw-semibold fs-7 text-gray-600">Reproted At:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                    <span class="fw-bold fs-7 text-gray-800">{{$report->created_at }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-start">
                                                    <span class="fw-semibold fs-7 text-gray-600">Updated At:</span>
                                                    </th>
                                                    <td  class="text-end">
                                                    <span class="fw-bold fs-7 text-gray-800" id="balance_amount">{{$report->updated_at }}</span>
                                                    </td>
                                                </tr>

                                            </tbody>
                                    </table>
                                </div>
                                <div class="col-12">
                                    <table class="table  table-layout-fixed  table-row-bordered">
                                        <tbody class="">
                                            <tr>
                                                <th class="text-start">
                                                    <span class="fw-semibold fs-7 text-gray-600">note:</span>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td  class="text-start">
                                                    <span class="fw-semibold fs-7 text-gray-700">{{$report->note}} </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="d-flex justify-content-between align-items-start flex-wrap mx-10 mt-8">
                <!--begin::User-->
                <div class="d-flex  justify-content-center align-items-center">
                    <!--begin::Name-->
                    <a  class="text-primary  fs-5 fw-bold me-1 d-block ">
                        Expense List
                    </a>
                </div>
                <!--end::User-->
                <!--begin::Actions-->
                <div class="d-flex ">
                    <button id="paidAll" class="btn btn-sm btn-primary" style="display: none;" data-bs-toggle="modal" data-bs-target="#paidAllModal">Paid Selected Balance Amount</button>
                    {{-- <input type="text" class="form-control form-control-sm" placeholder="Search " data-filter="input"> --}}
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <form action="{{route('expenseReport.paidAll')}}" method="POST">
                    @csrf
                        <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-4" id="expenseList">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-capitalize gs-0">
                                    <th class="min-w-80px text-start">
                                        <div class="form-check form-check-sm form-check-custom  me-3">
                                            <input type="checkbox" name="check" id="check_all" class="form-check-input">
                                        </div>
                                    </th>
                                    <th class="min-w-80px text-start">Actions</th>
                                    <th class="min-w-125px">Expense Date</th>
                                    <th class="min-w-125px">Expense Product</th>
                                    <th class="min-w-125px">Quantity</th>
                                    <th class="min-w-125px">Payment Status</th>
                                    <th class="min-w-125px">Expense Amount</th>
                                    <th class="min-w-125px">Paid Amount</th>
                                    <th class="min-w-125px">note</th>
                                    <th class="">
                                        <div class="btn btn-sm">
                                            <i class="fa-solid fa-trash text-danger"></i>
                                        </div>
                                    </th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                                <tbody class="fw-bold text-gray-600 text-start fs-7">
                                    <input type="hidden"  class="form-control form-control-sm" id="payment_account_id" name="payment_account_id" value="0">
                                    <input type="hidden"  class="form-control form-control-sm" id="total_paid_amount" name="total_paid_amount" value="0">
                                    @foreach ($expenseTransactions as $key=>$e)
                                    @php
                                        $variation_name=$e->variationProduct->toArray()['variation_template_value'] ;
                                        $variation_name_text=$variation_name ? '('. $variation_name['name'].')':' ';
                                        $finalText=$e->variationProduct->product->name.' '.$variation_name_text;
                                    @endphp

                                    <input type="hidden"  class="form-control form-control-sm" name="expense[{{$key}}][expense_id]" value="{{$e->id}}">
                                    <tr class="odd">
                                        <td>
                                                <div class="form-check form-check-sm form-check-custom  me-3">
                                                    <input type="checkbox" name="check" id="" class="form-check-input check_input"  data-balance_amount="{{$e->balance_amount}}">
                                                </div>
                                        </td>
                                        <td class="sorting_1">
                                            <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7 dropdown-toggle" type="button" id="exchangeRateDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="z-3">
                                                <ul class="dropdown-menu z-10 p-5" aria-labelledby="exchangeRateDropDown" role="menu" style="">
                                                    <a class="dropdown-item cursor-pointer fw-semibold" id="view" data-href="{{route('expense.view',$e->id)}}">View</a>
                                                    <a class="dropdown-item cursor-pointer fw-semibold" id="paymentCreate" data-href="{{route('paymentTransaction.createForExpense',['id'=>$e->id,'currency_id'=>$e->currency_id])}}">Add Payment</a>
                                                    <a class="dropdown-item cursor-pointer fw-semibold" id="viewPayment" data-href="{{route('paymentTransaction.viewForExpense',$e->id)}}">View Payment</a>
                                                </ul>
                                            </div>

                                        </td>
                                        <td>{{$e->expense_on}}</td>
                                        <td>{{$finalText}}</td>
                                        <td>{{$e->quantity}}</td>
                                        <td>
                                            @if ($e->payment_status == 'paid')
                                                <span class="badge badge-success">Paid</span>
                                            @elseif($e->payment_status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-primary">Partial</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="" >
                                                {{$e->expense_amount}}
                                            </div>
                                            {{-- <input type="hidden" name="editPayment[{{$key}}][expense_amount]"  class="form-control form-control-sm edit_input" value="{{$e->expense_amount}}"> --}}
                                        </td>
                                        {{-- <td>
                                            <div class="change_mod_input"  >
                                                {{$e->paid_amount}}
                                            </div>
                                            <div class="change_mod_input" style="display: none;" >
                                                @php
                                                    $accounts=App\Models\paymentAccounts::where('currency_id',$e->currency_id)->get();
                                                    $paymentTransaction=App\Models\paymentsTransactions::where('transaction_id',$e->id)->where('transaction_type','expense')->select('payment_account_id')->first();
                                                    $payment_account_id= $paymentTransaction ?  $paymentTransaction->payment_account_id : '';
                                                @endphp
                                                <select id="" class="mb-3 form-select form-select-sm " data-kt-select2="true" >
                                                    @foreach ($accounts as $a)
                                                        <option value="{{$a->id}}" @selected($a->id==$payment_account_id)>{{$a->name}} ({{$a->account_number}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="hidden" name="editPayment[{{$key}}][paid_amount]" class="form-control form-control-sm edit_input" value="{{$e->paid_amount}}">
                                        </td> --}}
                                        <td> {{$e->note}}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-light-danger btn-sm" data-id="{{$e->id}}" data-table="delete_row">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                {{-- <button type="submit" id="form-submit">Submit</button> --}}
                            </form>
                            <!--end::Table body-->
                        </table>
                    </div>
                    <div class="text-center col-12">
                        <button  class="btn btn-sm btn-primary " id="form-submit" style="display: none;" >Save Change</button>
                    </div>
                    <!--end::Table-->
                </form>
            </div>
            <!--end::Card body-->
        </div>


    </div>
    <!--end::container-->
    <div class="modal modal-lg fade " tabindex="-1" id="paidAllModal"  tabindex="-1" aria-labelledby="paidAllModal" aria-hidden="true">
        <div class="modal-dialog w-sm-500px" id="paidAllContainer">
            <div class="modal-content">
                <form action="" method="POST" id="paidAllForm">
                    @csrf
                    <div class="modal-header py-3">
                        <h3 class="modal-title">Paid All Amount</h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        <div class="row mb-6">
                            <div class="col-12">

                                <div class="form-label mb-5 fs-4">Payment For <span id="price_txt">20000mmk</span></div>
                                <div class="fv-row mb-7">
                                    <label class="required fs-7 fw-semibold form-label mb-1 required">Select Payment Account</label>
                                        @php
                                            $currency=[];
                                            if(count($expenseTransactions)>0){
                                                $currency=$expenseTransactions[0]->currency;
                                            }
                                            $accounts=App\Models\paymentAccounts::where('currency_id',$currency->id ?? 0)->get();
                                        @endphp
                                        <select  id="payment_account_id_from_modal" class="form-select form-select-sm form-select-dark mb-5"  name="paymentAccount" data-kt-select2="true" data-placeholder="Select Currency" data-allow-clear="true" data-kt-user-table-filter="receiver" data-dropdown-parent="#paidAllModal">
                                            <option value="" disabled selected>Pelease Select Account</option>
                                            @foreach ($accounts as $a)
                                                <option value="{{$a->id}}">{{$a->name}} ({{$a->account_number}})</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="total_paid_amount_modal" id="total_paid_amount_modal" value="0">
                            </div>
                                    <div class="text-gray-600 fw-semibold mt-3 fs-7">Current Balance: <span id="current_balance_txt"></span></div>
                        </div>
                    </div>

                    <div class="modal-footer py-3 ">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                        {{-- <button type="submit" class="btn btn-primary btn-sm" data-kt-withdrawl-action="submit">Save</button> --}}
                        <button type="submit" class="btn btn-primary" id="modalSubmit" data-kt-withdrawl-action="submit">
                            <span class="indicator-label">Submit</span>
                            {{-- <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span> --}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</div>
<!--end::Content-->
    <div class="modal modal-lg fade " tabindex="-1" id="modal">

    </div>
    <div class="modal modal-lg fade " tabindex="-1" id="paymentEditModal">

    </div>
    <div class="modal modal-lg fade" tabindex="-1"  data-bs-keyboard="false" id="viewModal">

    </div>

@endsection
@push('scripts')
<script src="{{asset('customJs/expense/index.js')}}"></script>
<script>


let currency=@json($currency);
let accounts=@json($accounts);
$(document).ready(function() {

        var toPaidAmount=0;
        var currentAccBalanceAmount=0;

        $(document).on('change','#payment_account_id_from_modal',function(){
            let account_id=$(this).val();
            let currentAccount=accounts.filter((a)=>{
                return a.id==account_id;
            })[0];
            currentAccBalanceAmount=currentAccount.current_balance;
            $('#current_balance_txt').text(`${currentAccBalanceAmount}  ${currency.symbol}`);
        })
        $(document).on('click','[type="checkbox"]',function() {
            $('#paidAll').toggle();
        })
        $(document).on('click','#check_all',function() {
            if($(this).prop("checked")){
                $('.check_input').prop("checked", true);
            }else{
                $('.check_input').prop("checked", false);
            }
        })

        $(document).on('click','#paidAll',function(){
            // $('.edit_input').toggle().attr('type','number');
            // $('.change_mod_input').toggle();
            const allCheckboxes = document.querySelectorAll('tbody [type="checkbox"]');
            // Detect checkboxes state & count
            let checkedState = false;
            let count = 0;

            // Count checked boxes
            allCheckboxes.forEach(c => {
                if (c.checked) {
                    checkedState = true;
                    count++;
                    toPaidAmount+= isNullOrNan($(c).data('balance_amount'));
                }
            });
            $('#price_txt').text(`${toPaidAmount} ${currency.symbol}`)
            $('#total_paid_amount').val(toPaidAmount);
            $('#total_paid_amount_modal').val(toPaidAmount);
        })
        var paidAllValidator = function () {
            // Shared variables

            const element = document.getElementById("paidAllContainer");
            const form = element.querySelector("#paidAllForm");
            // Init add schedule modal
            var initPaidAll = () => {
                // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/

                // Submit button handler
                const submitButton = element.querySelectorAll('#modalSubmit');
                submitButton.forEach((btn) => {
                    btn.addEventListener('click', function (e) {
                        $('.fv-plugins-message-container').remove();
                            var validator = FormValidation.formValidation(
                                form,
                                {
                                    fields: {
                                        paymentAccount: {
                                            validators: {
                                                notEmpty: { message: "* Payment Account  is required" },
                                                // lessThan: {
                                                //     max: 30,
                                                //     message: 'The value has to be less than 30',
                                                // }
                                            },
                                        },
                                        total_paid_amount_modal:{
                                            validators: {
                                                lessThan: {
                                                    max: currentAccBalanceAmount,
                                                    message: 'Balance amount is not sufficient to pay ',
                                                }
                                            },
                                        }
                                    },

                                    plugins: {
                                        trigger: new FormValidation.plugins.Trigger(),
                                        bootstrap: new FormValidation.plugins.Bootstrap5({
                                            rowSelector: '.fv-row',
                                            eleInvalidClass: '',
                                            eleValidClass: ''
                                        })
                                    },

                                }
                            );

                            if (validator) {
                                validator.validate().then(function (status) {
                                    if (status == 'Valid') {
                                        // e.currentTarget=true;
                                        // btn.setAttribute('data-kt-indicator', 'on');
                                        e.preventDefault();
                                        $('#payment_account_id').val($('#payment_account_id_from_modal').val());
                                        $('#form-submit').click();
                                        return true;
                                    } else {
                                        e.preventDefault();
                                        // Show popup warning. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                                        Swal.fire({
                                            text: "Sorry, looks like there are some errors detected, please try again.",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });
                                    }
                                });
                            }

                    });
                })

            }

            // Select all handler

            return {
                // Public functions
                init: function () {
                    initPaidAll();
                }
            };
        }();
        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            paidAllValidator.init();
        });
    // $('[data-kt-withdrawl-action="submit"]').click(function(e){
    //     e.preventDefault();
    //     $('#payment_account_id').val($('#payment_account_id_from_modal').val());
    //     $('#form-submit').click();

    // })

    function isNullOrNan(val,d=0){
        let v=parseFloat(val);

        if(v=='' || v==null || isNaN(v)){
            return d;
        }else{
            return v;
        }
    }
    $('[data-contorl="select2"]').select2();
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('[data-table="delete_row"]');
        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();
                // Select parent row
                const parent = e.target.closest('tr');
                // Get expense name
                const expenseName = parent.querySelectorAll('td')[2].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure to remove  " + expenseName + " from report?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        let id=d.getAttribute('data-id')
                            $.ajax({
                                url: `/expense-report/${id}/remove/`,
                                type: 'DELETE',
                                data: {
                                    idForDelete:[id],
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(s) {

                                    $('#total_expense_amount').text(s.total_expense_amount);
                                    $('#balance_amount').text(s.balance_amount);
                                    Swal.fire({
                                        text: "You have deleted " + expenseName + "!.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    }).then(function () {
                                        success(s.success);
                                        location.reload();
                                    });
                                }
                            })
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: expenseName + " was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            })
        });
    }


    handleDeleteRows();

})
$(document).on('click', '#view', function(e){
    e.preventDefault();
    loadingOn();
    $('#viewModal').load($(this).data('href'), function() {
        // $(this).remove();
        $(this).modal('show');
        loadingOff();
    });
});
$(document).on('click', '#expenseReportEdit', function(e){
    e.preventDefault();
    loadingOn();
    $('#modal').load($(this).data('href'), function() {
        // $(this).remove();
        $(this).modal('show');
        loadingOff();
    });
});expenseReportEdit
</script>
@endpush







