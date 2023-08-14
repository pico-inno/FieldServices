@extends('App.main.navBar')

@section('sell_icon', 'active')
@section('sell_show', 'active show')
@section('add_sales_active_show', 'active ')
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Add Sale</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Sale</li>
        {{-- <li class="breadcrumb-item text-muted">add</li> --}}
        <li class="breadcrumb-item text-dark">add </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/businessSetting.css")}}>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
    <style>
        .data-table-body tr td{
            padding: 3px;
        }
        /* label{
            font-size: 50px !important ;
        } */
    </style>
@endsection



@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xl" id="sale_container">
        <form action={{route('crate_sale')}} method="POST" id="sale_form">
            @csrf
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-5 mb-5" >
                <div class="col-12 my-2 input-group flex-nowrap">
                    <div class="input-group-text"><i class="fa-solid fa-location-dot"></i></div>
                    <select name="business_location_id" id="business_location_id" class="form-select rounded-0" data-kt-select2="true"  data-placeholder="Select locations">
                        @foreach ($locations as $l)
                            <option value="{{$l->id}}">{{$l->name}}</option>
                        @endforeach
                    </select>
                    <button type="button" class="input-group-text "  data-bs-toggle="tooltip" data-bs-custom-class="tooltip" data-bs-placement="top" data-bs-html="true" title="<span class='text-primary-emphasis'>Business location from where you went to sell </span>">
                        <i class="fa-solid fa-circle-info text-primary"></i>
                    </button>
                </div>
                <div class="card">
                    <div class="card-body  px-5">

                        <div class="row mb-5 flex-wrap">
                            @error('sale_details')
                                <div class="alert-danger alert">
                                    At least one sale item is required to complete sale!
                                </div>
                            @enderror
                            <div class="mb-sm-10 mb-3 col-12 col-sm-6 mt-3 col-md-3">
                                <label class="form-label fs-7 mb-3 fw-semibold" for="">
                                    Default Selling Price
                                </label>
                                <select name="price_list"  class="form-select form-select-sm price_group priceList " data-kt-select2="true" data-hide-search="true" data-placeholder="Select Selling Price">
                                    <option value="default_selling_price">defalut selling price</option>
                                    @foreach ($priceLists as $PriceList)
                                        <option value="{{$PriceList->id}}">{{$PriceList->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-sm-10 mb-3 mt-3 col-12 col-sm-6 col-md-3 fv-row">
                                <label class="form-label fs-7 mb-3 fw-semibold required">Customer:</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-user text-muted"></i>
                                    </div>
                                    <div class="overflow-hidden  flex-grow-1">
                                        <select class="form-select form-select-sm  fw-bold rounded-0"  name="contact_id" data-kt-select2="true" data-hide-search="false" data-placeholder="Select customer name" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true" >
                                            <option value=""></option>
                                            {{-- <option value="2">Aung Aung</option> --}}
                                            @foreach ($customers as $customer)
                                                <option value="{{$customer->id}}" @selected(old('contact_id')==$customer->id)  priceList={{$customer->pricelist_id}}>{{ $customer->company_name ?? $customer->getFullNameAttribute() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="input-group-text  add_supplier_modal"  data-bs-toggle="modal" type="button" data-bs-target="#add_supplier_modal" data-href="{{ url('purchase/add/supplier')}}">
                                        <i class="fa-solid fa-circle-plus fs-3 text-primary"></i>
                                    </button>

                                </div>
                                @error('contact_id')
                                    <div class="p-2 text-danger">* {{$message}}</div>
                                @enderror
                            </div>

                            <div class="mb-sm-10 mb-3  col-12 mt-3 col-sm-6 col-md-3">
                                <label class="form-label fs-7 mb-3 fw-semibold required" for="">
                                    Currency
                                </label>
                                <select name="currency_id" id="currency_id"  class="form-select form-select-sm" data-kt-select2="true" data-placeholder="Select Currency" data-status="filter" data-hide-search="true" required>
                                    @foreach ($currencies as $c)
                                        <option value="{{$c->id}}" @selected($c->id==$defaultCurrency['id'])>{{$c->name}} - {{$c->symbol}}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- <input type="hidden" name="currency_id" value="{{$defaultCurrency['id']}}"> --}}
                            <div class="mb-sm-10 mb-3  col-12 mt-3 col-sm-6 col-md-3">
                                <label class="form-label fs-7 mb-3 fw-semibold required" for="">
                                    Status
                                </label>
                                <select name="status" id="saleStatus"  class="form-select form-select-sm" data-kt-select2="true" data-status="filter" data-hide-search="true" required>
                                    <option value="quotation"  >Quotation</option>
                                    <option value="draft"  >Draft</option>
                                    <option value="pending"  >Pending</option>
                                    <option value="order" >Ordering</option>
                                    <option value="partial"  >Partial</option>
                                    <option value="delivered"  >Delivered</option>
                                </select>
                            </div>


                        </div>
                        <div class="d-flex justify-content-center align-items-center mt-3 mb-10">
                            <div class="col-6 col-md-2 fs-7  fw-semibold  text-primary">
                                Select Products
                            </div>
                            <div class="separator   border-primary-subtle col-md-10 col-6"></div>
                        </div>
                        <div class="row align-items-center mb-5 justify-content-sm-center">
                            <div class="col-12 col-md-9">
                                <div class="input-group quick-search-form p-0">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </div>
                                    <input type="text" class="form-control form-control-sm rounded-start-0" id="searchInput" placeholder="Search...">
                                    <div class="quick-search-results overflow-scroll  p-3 position-absolute d-none card w-100 mt-18  card  autocomplete shadow" id="autocomplete" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                                </div>
                            </div>
                            <button class="col-md-3 col-12 p-3 mt-lg-0 mt-3 col-md-3 btn-sm btn-primary btn add_new_product_modal  productQuickAdd"   data-href="{{route('product.quickAdd')}}" type="button">
                                <i class="fa-solid fa-plus me-2 text-white"></i> Add new product
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-row-dashed fs-6 gy-4 mt-3" id="sale_table">
                                <!--begin::Table head-->
                                <thead class="bg-light">
                                    <!--begin::Table row-->
                                    <tr class="text-start text-primary fw-bold fs-8 text-uppercase gs-0 ">
                                        <th class="min-w-175px ps-3">Product </th>
                                        <th class="min-w-100px">Quantity </th>
                                        <th class="min-w-125px">UOM </th>
                                        <th class="min-w-80px" style="max-width: 100px;">Price List</th>
                                        <th class="min-w-125px">Uom Price</th>
                                        <th class="min-w-125px">Subtotal</th>
                                        <th class="min-w-125px {{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">Disc </th>
                                        <th class="min-w-125px {{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">Disc Amount </th>
                                        {{-- <th class="min-w-125px">Discount Amount</th> --}}
                                        {{-- <th class="min-w-125px">Subtotal</th> --}}
                                        <th class="pe-1" ><i class="fa-solid fa-trash text-primary" type="button"></i></th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600 fs-6 data-table-body">
                                    <tr class="dataTables_empty text-center">
                                        <td colspan="8 " >There is no data to show</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="separator my-5"></div>
                        <div class="col-6 float-end mt-3">
                            <table class="col-12 ">
                                <tbody>
                                    <tr>
                                        <th class="text-end">Items: <span class="fw-medium fs-5 total_item">0</span></th>
                                        {{-- <th class="d-flex justify-content-center align-items-center">
                                            <span class="min-w-100px fw-semibold">Sale Amount:</span>
                                            <div class="">
                                                <input type="text" name="sale_amount" class="sale_amount_input input_number form-control form-control-sm" value="0" >
                                            </div>
                                        </th> --}}
                                        {{-- <input type="hidden" name="total_line_discount" class="total_line_discount" value="0"> --}}
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-end mb-2">
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Sale Amount:(=)Ks
                                </span>
                                <input type="text" name="sale_amount" class="sale_amount_input input_number form-control form-control-sm" value="0" >
                            </div>
                        </div>
                        <div class="row justify-content-end  {{$setting->enable_line_discount_for_sale == 1 ? '' :'d-none' }}">
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Total Item Discount:(-)Ks
                                </span>
                                <input type="number" name="total_item_discount"  id="total_item_discount" class="form-control form-control-sm fs-7 total_item_discount" value="" placeholder="0" readonly />
                            </div>
                        </div>
                        <div class="row">
                            <div class=" mt-2 col-12 col-md-3 offset-md-1">
                                <label class="form-label fs-7 fw-semibold" for="">
                                    Extra Discount Type
                                </label>
                                <select name="extra_discount_type" id="" class="form-select form-select-sm extra_discount_type" data-control="select2" data-hide-search="true">
                                    <option value="fixed">Fixed</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>
                            <div class="mt-2 col-12 col-md-3 mb-5 mb-md-0">
                                <label class="form-label fs-7 fw-semibold" for="">
                                  Extra Discount Amount
                                </label>
                                 <input type="text" name="extra_discount_amount" class="form-control form-control-sm extra_discount_amount">
                            </div>
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-end align-items-center mb-5 mb-md-0">
                                <span class="min-w-200px pe-2" for="">
                                    Discount :(-)Ks
                                </span>
                                <input type="text" class="form-control form-control-sm input_number max-w-100px extra_discount" id="extra_discount" disabled>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Total Sale Amount:(=)Ks
                                </span>
                                <input type="text" name="total_sale_amount" class="form-control form-control-sm input_number max-w-100px total_sale_amount" value="">
                            </div>
                        </div>
                        {{-- <div class="row justify-content-end">
                            <div class="my-9 fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Paid Amount:(=)Ks
                                </span>
                                <input type="text" name="paid_amount" class="form-control form-control-sm input_number max-w-100px paid_amount_input" id="total_paid_amount" value="0">
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="fs-6 fw-semibold col-12 col-md-6 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Balance Amount:(=)Ks
                                </span>
                                <input type="text" name="balance_amount" class="form-control form-control-sm input_number max-w-100px balance_amount_input" id="total_balance_amount" value="">
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-end mb-3">
                            <div class="fs-6 fw-semibold col-12 col-md-3 d-flex justify-content-between align-items-center mb-5 ">
                                <select name="payment_account" id="payment_accounts" class="form-select form-select-sm" data-kt-select2="true" data-placeholder="select payment account">
                                    <option disabled selected>Select Account</option>
                                    @php
                                        $paymentAccounts=App\Models\paymentAccounts::where('currency_id',$defaultCurrency['id'])->get();
                                    @endphp
                                    @foreach ($paymentAccounts as $p)
                                        <option value="{{$p->id}}">{{$p->name}} ({{$p->account_number}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Paid Amount:(=)Ks
                                </span>
                                <input type="text" name="paid_amount" class="form-control form-control-sm input_number max-w-100px paid_amount_input" id="total_paid_amount" value="0">
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="fs-7 fw-semibold col-12 col-md-5 d-flex justify-content-between align-items-center">
                                <span class="min-w-200px pe-2" for="">
                                    Balance Amount:(=)Ks
                                </span>
                                <input type="text" name="balance_amount" class="form-control form-control-sm input_number max-w-100px balance_amount_input" id="total_balance_amount" value="">
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary save_btn" data-kt-sale-action="submit" name="save" value='save'>Save</button>
                <button type="submit" class="btn btn-success save_btn" data-kt-sale-action="submit" name="save" value='save_&_print'>Save & Print</button>
            </div>
        </form>
    </div>
    <!--end::Container-->
</div>

<div class="modal modal-lg fade " tabindex="-1"  data-bs-focus="false"  id="quick_add_product_modal" ></div>
@include('App.purchase.contactAdd')
@include('App.purchase.newProductAdd')
@include('App.sell.sale.subscribeModel')
@endsection

@push('scripts')

    <script>

        $('#subscribe').change(function() {
            // If the checkbox is checked, show the modal box
            if ($(this).is(':checked')) {
                $('#subscribe_models').modal('show');
            } else {
                // If the checkbox is unchecked, hide the modal box
                $('#subscribe_models').modal('hide');
            }
            });

        var quill = new Quill('#kt_docs_quill_basic', {
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            },
            placeholder: 'Type your text here...',
            theme: 'snow' // or 'bubble'
        });
    </script>
<script>
    $("#kt_datepicker_1").flatpickr({
        dateFormat: "d-m-Y",
    });
    $("#kt_datepicker_2").flatpickr({
        dateFormat: "d-m-Y",
    });
    $("#kt_datepicker_3").flatpickr({
        dateFormat: "d-m-Y",
    });
    // $("#kt_datepicker_2").flatpickr({
    //     dateFormat: "d-m-Y",
    // });
    // $("#kt_datepicker_3").flatpickr({
    //     dateFormat: "d-m-Y",
    // });
    // Init select2
    // $('[data-kt-repeater="select2"]').select2();

    // // Init flatpickr
    // $('[data-kt-repeater="datepicker"]').flatpickr();


var inputElm = document.querySelector('#kt_tagify_users');

const usersList = [
    { value: 1, name: 'Emma Smith', avatar: 'avatars/300-6.jpg', email: 'e.smith@kpmg.com.au' },
    { value: 2, name: 'Max Smith', avatar: 'avatars/300-1.jpg', email: 'max@kt.com' },
    { value: 3, name: 'Sean Bean', avatar: 'avatars/300-5.jpg', email: 'sean@dellito.com' },
    { value: 4, name: 'Brian Cox', avatar: 'avatars/300-25.jpg', email: 'brian@exchange.com' },
    { value: 5, name: 'Francis Mitcham', avatar: 'avatars/300-9.jpg', email: 'f.mitcham@kpmg.com.au' },
    { value: 6, name: 'Dan Wilson', avatar: 'avatars/300-23.jpg', email: 'dam@consilting.com' },
    { value: 7, name: 'Ana Crown', avatar: 'avatars/300-12.jpg', email: 'ana.cf@limtel.com' },
    { value: 8, name: 'John Miller', avatar: 'avatars/300-13.jpg', email: 'miller@mapple.com' }
];

            function tagTemplate(tagData) {
                return `
                    <tag title="${(tagData.title || tagData.email)}"
                            contenteditable='false'
                            spellcheck='false'
                            tabIndex="-1"
                            class="${this.settings.classNames.tag} ${tagData.class ? tagData.class : ""}"
                            ${this.getAttributes(tagData)}>
                        <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                        <div class="d-flex align-items-center">
                            <span class='tagify__tag-text'>${tagData.name}</span>
                        </div>
                    </tag>
                `
            }

        function suggestionItemTemplate(tagData) {
            return `
                <div ${this.getAttributes(tagData)}
                    class='tagify__dropdown__item d-flex align-items-center ${tagData.class ? tagData.class : ""}'
                    tabindex="0"
                    role="option">
                    <div class="d-flex flex-column">
                        <strong>${tagData.name}</strong>
                    </div>
                </div>
            `
        }

    // initialize Tagify on the above input node reference
    var tagify = new Tagify(inputElm, {
        tagTextProp: 'name', // very important since a custom template is used with this property as text. allows typing a "value" or a "name" to match input with whitelist
        enforceWhitelist: true,
        skipInvalid: true, // do not remporarily add invalid tags
        dropdown: {
            closeOnSelect: false,
            enabled: 0,
            classname: 'users-list',
            searchKeys: ['name', 'email']  // very important to set by which keys to search for suggesttions when typing
        },
        templates: {
            tag: tagTemplate,
            dropdownItem: suggestionItemTemplate
        },
        whitelist: usersList
    })

    tagify.on('dropdown:show dropdown:updated', onDropdownShow)
    tagify.on('dropdown:select', onSelectSuggestion)

    var addAllSuggestionsElm;

    function onDropdownShow(e) {
        var dropdownContentElm = e.detail.tagify.DOM.dropdown.content;

        if (tagify.suggestedListItems.length > 1) {
            addAllSuggestionsElm = getAddAllSuggestionsElm();

            // insert "addAllSuggestionsElm" as the first element in the suggestions list
            dropdownContentElm.insertBefore(addAllSuggestionsElm, dropdownContentElm.firstChild)
        }
    }

    function onSelectSuggestion(e) {
        if (e.detail.elm == addAllSuggestionsElm)
            tagify.dropdown.selectAll.call(tagify);
    }

    // create a "add all" custom suggestion element every time the dropdown changes
    function getAddAllSuggestionsElm() {
        // suggestions items should be based on "dropdownItem" template
        return tagify.parseTemplate('dropdownItem', [{
            class: "addAll",
            name: "Add all",
            email: tagify.settings.whitelist.reduce(function (remainingSuggestions, item) {
                return tagify.isTagDuplicate(item.value) ? remainingSuggestions : remainingSuggestions + 1
            }, 0) + " Members"
        }]
        )
    }
    $(document).on('click', '.productQuickAdd', function(){
        $url=$(this).data('href');

        loadingOn();
        $('#quick_add_product_modal').load($url, function() {
            $(this).modal('show');
            loadingOff();
        });
    });
</script>
@include('App.sell.js.saleJs')
<script src={{asset('customJs/Ajax/getAccountByCurrency.js')}}></script>
<script src={{asset('customJs/Purchases/contactAdd.js')}}></script>
<script src="{{asset('customJs/sell/saleValidator.js')}}"></script>
<script>

</script>
@endpush


