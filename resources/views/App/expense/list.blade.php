@extends('App.main.navBar')

@section('expense_active', 'active')
@section('list_expense_active', 'active')
@section('expense_active_show', 'active show')

@php
    $currency_id=getSettingValue('currency_id');
@endphp
@section('styles')
		<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <style>

            .table-responsive{
                    min-height: 60vh;
                }
        </style>
@endsection


@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('expense.expense_list')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('expense.expense')}}</li>
        <li class="breadcrumb-item text-dark">LIst</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="kt_content_container">
        <div class="accordion-collapse collapse" id="kt_accordion_1_body_2"  aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_1">
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" >
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Filters</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-5 flex-wrap">
                            <!--begin::Input group-->
                            <div class="mb-5 col-12 col-md-4 ">
                                <label class="form-label fs-6 fw-semibold">Bussiness Location:</label>
                                <select class="form-select  fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                    <option></option>
                                    <option value="Administrator">All</option>
                                    <option value="Analyst">Yangon</option>
                                    <option value="Developer">Mandalay</option>
                                    <option value="Support">YanKin</option>
                                    <option value="Trial">TarMwe</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-5 col-12 col-md-4">
                                <label class="form-label fs-6 fw-semibold">Customer:</label>
                                <select class="form-select  fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="two-step" data-hide-search="true">
                                    <option></option>
                                    <option value="Enabled">DE(202)</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <div class="mb-10 col-6 col-md-4 ">
                                <label class="form-label fs-6 fw-semibold">User:</label>
                                <select class="form-select  fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="Location" data-hide-search="true">
                                    <option></option>
                                    <option value="all">All</option>
                                    <option value="demo">Mg Mg</option>
                                    <option value="Yangon,">Kyaw Kyaw</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-5">

                            <!--begin::Input group-->
                            <div class="mb-10 col-12 col-md-4 ">
                                <label class="form-label fs-6 fw-semibold">date range:</label>
                                <input class="form-control form-control-solid" placeholder="Pick date rage" id="kt_daterangepicker_4" data-dropdown-parent="#filter" />
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1" id="searchInput">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid form-control-sm w-250px ps-15" placeholder="Search {{__('expense.expense')}}" />
                    </div>
                    <button  class="btn btn-primary btn-sm d-none" id="reportAdd" data-href="{{route('expenseReport.create')}}">Report Expense</button>
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-expense-table-toolbar="base">
                        <!--begin::Filter-->
                        <!--end::Filter-->
                        <!--end::Export-->
                        <!--begin::Add customer-->
                        @if(hasCreate('Expense'))
                        <button  class="btn btn-primary btn-sm" data-href={{route('expense.create')}} id="add"  data-bs-toggle="modal"
                        data-bs-target="#addModal">Add</button>
                        @endif
                        <!--end::Add customer-->
                    </div>
                    <!--end::Toolbar-->
                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-expense-table-toolbar="selected">
                        <div class="fw-bold me-5">
                        <span class="me-2" data-kt-expense-table-select="selected_count"></span>Selected</div>
                        <button type="button" class="btn btn-danger btn-sm" data-kt-expense-table-select="delete_selected">Delete Selected</button>
                    </div>
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="expenseTable">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom  me-3">
                                    <input class="form-check-input" data-checked="selectAll" id="selectAll" type="checkbox" data-kt-check="true" data-kt-check-target="#expenseTable .form-check-input" value="" />
                                </div>
                            </th>
                            {{-- <th class="min-w-125px">Date</th> --}}
                            <th class="min-w-125px text-start">Actions</th>
                            <th class="min-w-125px">Expense Voucher No</th>
                            <th class="min-w-125px">Expense Date</th>
                            <th class="min-w-125px">Expense Product</th>
                            <th class="min-w-125px">Quantity</th>
                            <th class="min-w-125px">Payment Status</th>
                            <th class="min-w-125px">Expense Amount</th>
                            <th class="min-w-125px">Paid Amount</th>
                            <th class="min-w-125px">Created By</th>
                            <th class="min-w-125px">note</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-semibold text-gray-600 fs-6" id="expenseTable">
                        <tr>
                            <!--begin::Checkbox-->
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="2" />
                                </div>
                            </td>
                            <!--end::Checkbox-->
                            <!--begin::Name=-->
                            {{-- <td>
                                <a href="../../demo7/dist/apps/ecommerce/customers/details.html" class="text-gray-800 text-hover-primary mb-1">27-11-2022 14:40</a>
                            </td> --}}
                            <!--end::Name=-->
                            <!--begin::Email=-->
                            <td>
                                <a href="#" class="text-gray-600 text-hover-primary mb-1">Flight Tickets</a>
                            </td>
                            <td>
                                <a href="#" class="text-gray-600 text-hover-primary mb-1">10 Units</a>
                            </td>
                            <td>
                                <a href="#" class="text-gray-600 text-hover-primary mb-1">5000 ks</a>
                            </td>

                             <!--begin::Action=-->
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                <span class="svg-icon svg-icon-5 m-0">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon--></a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                     <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="../../demo7/dist/apps/customers/view.html" class="menu-link px-3">View</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="../../demo7/dist/apps/customers/view.html" class="menu-link px-3">Edit</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="../../demo7/dist/apps/customers/view.html" class="menu-link px-3">Print</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="../../demo7/dist/apps/customers/view.html" class="menu-link px-1 ps-2">Convert to Proforma</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="../../demo7/dist/apps/customers/view.html" class="menu-link px-3">Delete</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </td>
                        </tr>
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
        <!--begin::Modals-->
        <!--end::Modals-->
    </div>
    <!--end::Container-->
</div>
<div class="modal modal-lg fade" data-bs-focus="false" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" id="modal">

</div>
<div class="modal modal-lg fade" data-bs-focus="false" tabindex="-1"  data-bs-keyboard="false" id="viewModal">

</div>
<div class="modal modal-lg fade" data-bs-focus="false" tabindex="-1"  data-bs-keyboard="false" id="reportAddModal">

</div>
<div class="modal modal-lg fade " data-bs-focus="false" tabindex="-1" id="modal">

</div>
<div class="modal modal-lg fade " data-bs-focus="false" tabindex="-1" id="paymentEditModal">

</div>
@include('App.expense.create')
{{-- <div class="modal modal-lg fade" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" id="addModal">

</div> --}}
@endsection

@push('scripts')
<script src="{{asset('customJs/expense/index.js')}}"></script>
<script>

$(document).ready(function(){
    var checkList=[];
    var currency_id_list=[];
    var expenseAmount=0;
    var datatable;

$(document).on('click', '#view', function(e){
    e.preventDefault();
    loadingOn();
    $('#viewModal').load($(this).data('href'), function() {
        // $(this).remove();
        $(this).modal('show');
        loadingOff();

    });
});


$(document).on('click', '.edit_payment', function(e){
    e.preventDefault();
    loadingOn();
    $('#paymentEditModal').load($(this).data('href'), function() {
        $(this).modal('show');
        loadingOff();
        $('[data-control="select2"]').select2({minimumResultsForSearch: -1});
        $('[data-control="select2-acc"]').select2();
        $('[data-datepicker="datepicker"]').flatpickr({
            dateFormat: "Y-m-d",
        });
    });
});




    $(document).on('click', '#reportAdd', function(e){
        e.preventDefault();
        let currency_check=currency_id_list.every( (val, i, arr) => val === arr[0] );
        if(!currency_check){
            Swal.fire({
                text: "Please Select Same Currency Expense !.",
                icon: "warning",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            });
            return;
        }
        loadingOn();
        // Convert the data object into a URL-encoded query string
        const queryString = $.param({
            currency_id:currency_id_list[0],
        });
        const urlWithParams = $(this).data('href') + '?' + queryString;
        $('#reportAddModal').load(urlWithParams, function() {
            $('[data-control="select2"]').select2({minimumResultsForSearch: -1});
            $('[data-control="select2-acc"]').select2();
            $('[data-datepicker="datepicker"]').flatpickr({
                dateFormat: "Y-m-d",
            });

            loadingOff();

            $(this).modal('show');
            let parent=$(this);
            parent.find('#total_expense_amount').val(expenseAmount);
            parent.find('#balance_amount').val(expenseAmount);
            parent.find('#expense_count').text(`${checkList.length}  exepnse selected `);

            // Unbind the click event handler before attaching it again
            $(document).off('click', '[data-submit="form-report-save"]').on('click', '[data-submit="form-report-save"]', function(e) {
                e.preventDefault();
                // Show loading indication
                $(this).prop('disabled', true);
                $(this).attr('data-kt-indicator', 'on');
                // Disable button to avoid multiple click
                let formData= parent.find('#reportCreateForm').serialize();

                $.ajax({
                    url: `/expense-report/store`,
                    type: 'POST',
                    data: {
                        data: formData,
                        expenseIds: checkList,
                        currency_id: currency_id_list[0],
                        total_expense_amount: expenseAmount,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(s) {
                        parent.modal('hide');
                        // datatable.ajax.reload();
                        Swal.fire({
                            text: "You have Successfully Reported !.",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            datatable.ajax.reload();
                            const toolbarBase = document.querySelector('[data-kt-expense-table-toolbar="base"]');
                            const toolbarSelected = document.querySelector('[data-kt-expense-table-toolbar="selected"]');
                            toolbarBase.classList.remove('d-none');
                            toolbarSelected.classList.add('d-none');
                            $('[type="checkbox"]').prop('checked', false);
                            checkList=[];
                            expenseAmount=0;
                            $('#searchInput').removeClass('d-none');
                            $('#reportAdd').addClass('d-none');

                            $('[data-submit="form-report-save"]').prop('disabled', false);
                            $('[data-submit="form-report-save"]').attr('data-kt-indicator', 'off');
                        });
                    }
                });
            });
        });
    });


// $(document).on('click', '#add', function(e){
//     e.preventDefault();
//     loadingOn();
//     $('#addModal').load($(this).data('href'), function() {
//         $(this).modal('show');
//         loadingOff();
//     });
// });

const checkEvent=()=>{
    $(document).on('change','tbody [type="checkbox"]',function(){
        if ($(this).prop('checked')){
            CheckIdStore($(this).val(),$(this).data('price'),$(this).data('c'));
            $('#reportAdd').removeClass('d-none');
            $('#searchInput').addClass('d-none');
        }else{
            CheckIdRemove($(this).val(),$(this).data('price'),$(this).data('c'));

            if( checkList.length==0){
                $('#searchInput').removeClass('d-none');
                $('#reportAdd').addClass('d-none');
            }

        }
    })
}


$(document).on('change','#selectAll',function(){
    const allCheckboxes = document.querySelectorAll('tbody [type="checkbox"]');
        // Detect checkboxes state & count
        let checkedState = false;
        let count = 0;
        // Count checked boxes
        allCheckboxes.forEach(c => {
            if (c.checked) {
                checkedState = true;
                count++;
                CheckIdStore($(c).val(),$(c).data('price'),$(c).data('c'));
                $('#reportAdd').removeClass('d-none');
                $('#searchInput').addClass('d-none');
            }else{
                    CheckIdRemove($(c).val(),$(c).data('price'),$(c).data('c'));
                    $('#searchInput').removeClass('d-none');
                    $('#reportAdd').addClass('d-none');

            }
        });
})
    const CheckIdStore=(value,price,currency_id)=>{
        if(!checkList.includes(value)){
            checkList=[...checkList,value];
            expenseAmount+=isNullOrNan(price);
            $('#reportAdd').text('Report Expense ('+checkList.length+') selected');
        }
        currency_id_list=[...currency_id_list,currency_id];
    }
    const CheckIdRemove=(value,price,currency_id)=>{console.log('remove');
        const chcekIndexToRemove = checkList.indexOf(value);
        if (chcekIndexToRemove !== -1) {
            checkList.splice(chcekIndexToRemove, 1);
        }
        const currencyIndexToRemove = currency_id_list.indexOf(currency_id);
        if (currencyIndexToRemove !== -1) {
            currency_id_list.splice(currencyIndexToRemove, 1);
        }
        console.log(currency_id_list);
        $('#reportAdd').text('Report Expense ('+checkList.length+') selected')
        expenseAmount-=isNullOrNan(price);
    }

    var accounts;
    var selectedAccountCurrentBalance;
    if($('#currency_id').val()){
        getAccountByCurrency($('#currency_id').val())
    }
    $(document).on('change','#currency_id',function(e){
        getAccountByCurrency($(this).val())
    })
    function getAccountByCurrency(val) {
        $.ajax({
            url: `/payment-account/get/${val}`,
            type: 'get',
            error:function(e){
                status=e.status;
                if(status==405){
                    warning('Method Not Allow!');
                }else if(status==419){
                    error('Session Expired')
                }else{
                    console.log(e);
                    console.error(' Something Went Wrong! Error Status: '+status )
                };
            },
            success: function(response) {
                accounts=response.accounts;
                let data=accounts.map((account)=>{
                    return {
                        'id':account.id,
                        'text':`${account.name} (${account.account_number})`
                    }
                })
                $('#payment_account_id').empty();
                $('#payment_account_id').select2({
                    data:[{id:'',name:'test'},...data]
                });
                console.log(data);
            }
        })
    }
    $('#payment_account_id').change(function(){
            let paymentAccId=$(this).val();
            let selectedAccount=accounts.filter(function(a){
                return paymentAccId==a.id;
            })[0];
            selectedAccountCurrentBalance=selectedAccount.current_balance;
            $('#currentBalanceTxt').text(selectedAccountCurrentBalance);
        })
// Class definition
var KTExpenseList = function () {
    // Define shared variables
    var table

        // Private functions
        var expenseList = function () {
            datatable = $(table).DataTable({
                pageLength: 30,
                lengthMenu: [10, 20, 30, 50,70],
                'columnDefs': [
                    { orderable: false, targets: 0 },
                ],
                order: [[0, ' ']],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/expense/list/data/',
                },
                columns: [
                    // <th class="min-w-125px">Expense Product</th>
                    //             <th class="min-w-125px">Quantity</th>
                    //             <th class="min-w-125px">Expense Amount</th>
                    //             <th class="min-w-125px text-center">Actions</th>
                    {
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false ,
                        orderable: false,
                    },{
                        data: 'expense_voucher_no',
                        name: 'expense_voucher_no',
                    },{

                        data: 'expense_on',
                        name: 'expense_on',
                    },
                    {
                        name:'Expense Product',
                        data:'expense_product'
                    },
                    {
                        name:'Quantity',
                        data:'quantity'
                    },
                    {
                        name:'payment_status',
                        data:'payment_status',
                    },
                    {
                        name: 'expense_amount',
                        data: 'expense_amount',
                    },
                    {
                        name: 'paid_amount',
                        data: 'paid_amount',
                    },
                    {
                        name: 'created_by',
                        data: 'created_by',
                    },
                    {
                        name: 'note',
                        data: 'note',
                        render:function(txt){
                            return txt?txt.slice(0, 40) + '...':'';
                        }
                    }

                ]

            });

            // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
            datatable.on('draw', function () {
                // handleBusinessLocationFilter();
                function check(){
                    const toolbarBase = document.querySelector('[data-kt-expense-table-toolbar="base"]');
                    const toolbarSelected = document.querySelector('[data-kt-expense-table-toolbar="selected"]');
                    $('tbody [type="checkbox"]').each(function(){
                       if( checkList.length>0){
                            checkList.forEach((val)=>{
                                if(val==$(this).val()){
                                    $(this).attr('checked',true)
                                }
                            })

                            $('#reportAdd').removeClass('d-none');
                            $('#searchInput').addClass('d-none');
                            $('#selectAll').prop('checked',true);

                            $('#selectAll').innerHTML = checkList.length;
                            toolbarBase.classList.add('d-none');
                            toolbarSelected.classList.remove('d-none');
                       }else{
                             $('#selectAll').innerHTML = 0;
                            toolbarBase.classList.remove('d-none');
                            toolbarSelected.classList.add('d-none');

                            $('#searchInput').removeClass('d-none');
                            $('#reportAdd').addClass('d-none');
                       }
                    })
                }
                check();
                toggleToolbars();
                initToggleToolbar();
                handleDeleteRows();
                checkEvent();

            });
        }


        // Init toggle toolbar
        var initToggleToolbar = () => {
            // Toggle selected action toolbar
            // Select all checkboxes
            const checkboxes = table.querySelectorAll('[data-checked="delete"]');
            const selectAll = table.querySelector('#selectAll');
            // Select elements
            const deleteSelected = document.querySelector('[data-kt-expense-table-select="delete_selected"]');

            // Toggle delete selected toolbar
            checkboxes.forEach(c => {
                // Checkbox on click event
                c.addEventListener('click', function () {
                    setTimeout(function () {
                        toggleToolbars();
                    }, 50);
                });
            });
            selectAll.addEventListener('click',function () {
                    setTimeout(function () {
                        toggleToolbars();
                    }, 50);
            })

            // Deleted selected rows
            deleteSelected.addEventListener('click', function () {
                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete selected locations?",
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
                        let idForDelete=[];
                        checkboxes.forEach(c => {
                            if (c.checked) {
                                idForDelete = [...idForDelete,c.value];
                            }
                        });
                        $.ajax({
                            url: `/expense/destory`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                idForDelete,
                            },
                            success: function(s) {
                                datatable.ajax.reload();
                                Swal.fire({
                                    text: "You have successfully deleted selected expense!.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                }).then(function () {
                                    //sth
                                    success(s.success);
                                });
                            }
                        })
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: "Selected locations was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });

                    }




                    const toolbarBase = document.querySelector('[data-kt-expense-table-toolbar="base"]');
                    const toolbarSelected = document.querySelector('[data-kt-expense-table-toolbar="selected"]');
                    toolbarBase.classList.remove('d-none');
                    toolbarSelected.classList.add('d-none');
                    $('[type="checkbox"]').prop('checked', false);
                });
            });
        }


        // Toggle toolbars
        const toggleToolbars = () => {
            // Define variables
            const toolbarBase = document.querySelector('[data-kt-expense-table-toolbar="base"]');
            const toolbarSelected = document.querySelector('[data-kt-expense-table-toolbar="selected"]');
            const selectedCount = document.querySelector('[data-kt-expense-table-select="selected_count"]');

            // Select refreshed checkbox DOM elements
            const allCheckboxes = table.querySelectorAll('tbody [type="checkbox"]');

            // Detect checkboxes state & count
            let checkedState = false;
            let count = 0;

            // Count checked boxes
            allCheckboxes.forEach(c => {
                if (c.checked) {
                    checkedState = true;
                    count++;
                }else{
                    $('#selectAll').prop('checked',false);
                }
            });

            // Toggle toolbars
            if (checkedState) {
                selectedCount.innerHTML = count;
                toolbarBase.classList.add('d-none');
                toolbarSelected.classList.remove('d-none');

            } else {
                toolbarBase.classList.remove('d-none');
                toolbarSelected.classList.add('d-none');
            }

        }
        var handleDeleteRows = () => {
            // Select all delete buttons
            const deleteButtons = document.querySelectorAll('[data-kt-expense-table="delete_row"]');
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
                        text: "Are you sure you want to delete expense" + expenseName + "?",
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
                                    url: `/expense/destory`,
                                    type: 'DELETE',
                                    data: {
                                        idForDelete:[id],
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(s) {
                                        datatable.ajax.reload();
                                        Swal.fire({
                                            text: "You have deleted " + expenseName + "!.",
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn fw-bold btn-primary",
                                            }
                                        }).then(function () {
                                            datatable.ajax.reload();
                                            success(s.success);
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
    return {
        init: function () {
            table = document.querySelector('#expenseTable');

            if (!table) {
                return;
            }
            expenseList();
            initToggleToolbar();
            handleDeleteRows();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTExpenseList.init();
});


var results=[];
const expenseUtilities=()=>{
        autosize($('[data-kt-autosize="true"]'));
        $('[data-td-toggle="datetimepicker"]').flatpickr({
            dateFormat: "Y-m-d",
        });
        $('[data-control="select2"]').select2({minimumResultsForSearch: -1});
        $('.expenseProduct').select2({
            ajax: {
                url: '/expense/product',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                        results=[];
                        data.map(function(d) {
                            let variations=d.product_variations;
                            variations.forEach(function(v) {
                                results.push({
                                    id: v.id,
                                    text: `${d.name} ${v.variation_template_value ?`(${v.variation_template_value.name})`: ''}`,
                                    uom:d.uom
                                });
                            });
                        })
                    return {
                        results
                    };
                },
                cache: true
            },
            placeholder: 'Search for an item',
            minimumInputLength: 3
        })

        $(document).on('change','.expenseProduct',function(){
            let id=$(this).val();
            console.log(id);
            let selectProudct=results.filter((r)=>{
                return r.id==id
            })[0];
            console.log(selectProudct);
            let uoms=selectProudct.uom.unit_category.uom_by_category;
            console.log(uoms);
            let data=uoms.map((u)=>{
                return {id:u.id,text:u.name}
            });
            console.log(data);
            $('#uom_id').empty();
            $('#uom_id').select2({
                data,
                minimumResultsForSearch: -1
            })
            $("#uom_id").val(selectProudct.uom.id).trigger('change');
        })

        var expenseAddValidator = function () {
            // Shared variables

            const element = document.getElementById("addModal");
            const form = element.querySelector("#create_form");
            // Init add schedule modal;
            var expesneAddInit = () => {
                // Submit button handler
                const submitButton = element.querySelectorAll('#expense_save_btn');
                submitButton.forEach((btn) => {
                    btn.addEventListener('click', function (e) {
                        $('.fv-plugins-message-container').remove();
                        var validator = FormValidation.formValidation(
                            form,
                            {
                                fields: {
                                    expense_product_id: {
                                        validators: {
                                            notEmpty: { message: "* Expense Product  is required" },
                                            // lessThan: {
                                            //     max: 30,
                                            //     message: 'The value has to be less than 30',
                                            // }
                                        },
                                    },
                                    uom_id:{
                                        validators: {
                                            notEmpty: { message: "*Uom  is required" },
                                        },
                                    },

                                    currency_id:{
                                        validators: {
                                            notEmpty: { message: "* Currency  is required" },
                                        },
                                    },
                                    payment_account_id:{
                                        validators: {
                                            notEmpty: { message: "* Payment Account  is required" },
                                        },
                                    },
                                    expense_amount:{
                                        validators: {
                                            notEmpty: { message: "* Expense Amount  is required" },
                                        },
                                    },
                                    paid_amount:{
                                        validators:{
                                            lessThan: {
                                                max: selectedAccountCurrentBalance,
                                                message: 'Insufficient Balance Amount',
                                            }
                                        }
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
                                        e.currentTarget=true;
                                        // btn.setAttribute('data-kt-indicator', 'on');
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
                    expesneAddInit();
                }
            };
        }();
        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            expenseAddValidator.init();
        });


}
(()=>{
    expenseUtilities()
})()
function isNullOrNan(val,d=0){
        let v=parseFloat(val);

        if(v=='' || v==null || isNaN(v)){
            return d;
        }else{
            return v;
        }
    }
})
</script>
@endpush


