@extends('App.main.navBar')

@section('purchases_icon', 'active')
@section('pruchases_show', 'active show')
{{-- @section('purchases_list_return_active_show', 'active ') --}}
@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Add Purchase Order</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Purchases</li>
        <li class="breadcrumb-item text-dark">return </li>
    </ul>
    <!--end::Breadcrumb-->
@endsection

@section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/bussingessSetting.css")}}>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
@endsection



@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="kt_content_container">
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 mb-5" >
            <div class="card">
                <div class="card-body">
                    <div class="row mb-5 flex-wrap">
                        <!--begin::Input group-->
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required">Supplier:</label>
                            <div class="input-group flex-nowrap">
                                <div class="input-group-text">
                                    <i class="fa-solid fa-user text-muted"></i>
                                </div>
                                <select class="form-select  fw-bold rounded-0" data-kt-select2="true" data-hide-search="false" data-placeholder="Select supplier" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                    <option></option>
                                    <option value="Administrator">Mg Mg</option>
                                    <option value="Analyst">Kyaw Kyaw</option>
                                    <option value="Developer">Aung Aung</option>
                                </select>
                                <button class="input-group-text add_supplier_modal"  data-bs-toggle="modal" type="button" data-bs-target="#add_supplier_modal" data-href="{{ url('purchase/add/supplier')}}">
                                    <i class="fa-solid fa-circle-plus fs-3 text-primary"></i>
                                </button>

                            </div>
                        </div>
                        <!--end::Input group-->
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required">Order Date:</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="orderDate">
                                Order Date:
                            </label>
                            <div class="input-group">
                                <span class="input-group-text " data-td-target="date_picker" data-td-toggle="datetimepicker">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input class="form-control" name="start_date" placeholder="Pick a date"  id="kt_datepicker_1" value="{{date('d-m-Y')}}" />
                            </div>
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="orderDate">
                                Delivery Date:
                            </label>
                            <div class="input-group">
                                    <span class="input-group-text" data-td-target="date_picker" data-td-toggle="datetimepicker">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input class="form-control" name="start_date" placeholder="Pick a date"  id="kt_datepicker_2" value="{{date('d-m-Y')}}" />
                            </div>
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="orderDate">
                                Address:
                            </label>
                            <div class="">
                                ၅၅လမ်း၊ ၁၃၂ လမ်း နှင့် ၁၃၃လမ်းကြား၊ ပြည်ကြီးတံခွန်မြို့နယ်၊ မန္တလေးမြို့။
                            </div>
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Bussiness Location
                            </label>
                            <div class="input-group flex-nowrap">
                                <select class="form-select  fw-bold " data-kt-select2="true" data-hide-search="false" data-placeholder="Select Location" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                    <option></option>
                                    <option value="Administrator">Mandalay</option>
                                    <option value="Analyst">Nay Pyi Taw</option>
                                    <option value="Developer">Yangon</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Pay term:
                            </label>
                            <div class="input-group flex-nowrap">
                                <div class="input-text">
                                    <input type="text" class="form-control rounded-end-0" placeholder="Pay term:">
                                </div>
                                <select class="form-select  fw-bold rounded-start-0 border-gray-500" data-kt-select2="true" data-hide-search="false" data-placeholder="Select Location" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                    <option>Please Select</option>
                                    <option value="Administrator">Months</option>
                                    <option value="Analyst">Days</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4 browseLogo">
                            <label class="fs-6 fw-semibold form-label " for="update_logo">
                                <span class="required">Attach Document:</span>
                            </label>
                            <div class="input-group browseLogo">
                                <input type="file" class="form-control" id="update_logo" name="update_logo">
                                <button type="button" class="btn btn-sm btn-danger d-none" id="removeFileBtn"><i class="fa-solid fa-trash"></i></button>
                                <label class="input-group-text btn btn-primary rounded-end" for="update_logo">
                                    Browse
                                    <i class="fa-regular fa-folder-open"></i>
                                </label>
                            </div>
                            <p class="text-gray-600 mt-3 d-block">
                                Max File size: 5MB <br>
                                Allowed File: .pdf, .csv, .zip, .doc, .docx, .jpeg, .jpg, .png
                            </p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center mb-8">
                        <div class="col-12 col-md-9">
                            <div class="input-group quick-search-form p-0">
                                <div class="input-group-text">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <input type="text" class="form-control rounded-start-0" id="searchInput" placeholder="Search...">
                                <div class="quick-search-results overflow-scroll  p-3 position-absolute d-none card w-100 mt-18  card z-3 autocomplete shadow" id="autocomplete" data-allow-clear="true" style="max-height: 300px;z-index: 100;"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 btn-primary btn add_new_product_modal"   data-bs-toggle="modal" type="button" data-bs-target="#add_new_product_modal" data-href="{{ url('purchase/add/supplier')}}">
                            <i class="fa-solid fa-plus me-2 text-white"></i> Add new product
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 mt-10" id="purchase_table">
                            <!--begin::Table head-->
                            <thead class="">
                                <!--begin::Table row-->
                                <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0 ">
                                    <th class="min-w-50px">#</th>
                                    <th class="min-w-125px">Product Name</th>
                                    <th class="min-w-200px">Order quantity</th>
                                    <th class="min-w-200px">UOM</th>
                                    <th class="min-w-250px">Unit Cost (Before Discount)</th>
                                    <th class="min-w-200px">Discount Percent</th>
                                    <th class="min-w-200px">Unit Cost (Before Tax)</th>
                                    <th class="min-w-200px">Additional Expense</th>
                                    <th class="min-w-200px">Line Total</th>
                                    <th class="text-center" ><i class="fa-solid fa-trash text-primary" type="button"></i></th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-600">
                                <tr class="dataTables_empty text-center">
                                    <td colspan="8 " >There is no data to show</td>
                                </tr>
                            </tbody>
                                {{-- <tr data-id='1'>

                                    <!--end::Action=-->
                                    <!--begin::Name=-->
                                    <td>
                                        <a href="../../demo7/dist/apps/ecommerce/customers/details.html" class="text-gray-800 text-hover-primary mb-1">1</a>
                                    </td>
                                    <!--end::Name=-->
                                    <!--begin::Email=-->
                                    <td>
                                        <a href="#" class="text-gray-600 text-hover-primary mb-1">Demo Product</a>
                                    </td>
                                    <td>
                                            <input type="text" class="form-control mb-3">
                                            <select name="" id="" class="form-select" data-kt-select2="true" data-hide-search="true">
                                                <option value="">box</option>
                                                <option value="">Pieces</option>
                                            </select>

                                    </td>
                                    <td>
                                        <input type="text" class="form-control sum" value="100">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control sum" value="100">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control sum" value="100">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control sum" value="100">
                                    </td>
                                    <th><i class="fa-solid fa-trash text-danger deleteRow btn" ></i></th>
                                </tr>
                                <tr>

                                    <!--end::Action=-->
                                    <!--begin::Name=-->
                                    <td>
                                        <a href="#" class="text-gray-800 text-hover-primary mb-1">2</a>
                                    </td>
                                    <!--end::Name=-->
                                    <!--begin::Email=-->
                                    <td>
                                        <a href="#" class="text-gray-600 text-hover-primary mb-1">Demo Product 2</a>
                                    </td>
                                    <td>
                                            <input type="text" class="form-control mb-3">
                                            <select name="" id="" class="form-select" data-kt-select2="true" data-hide-search="true">
                                                <option value="">box</option>
                                                <option value="">Pieces</option>
                                            </select>

                                    </td>
                                    <td>
                                        <input type="text" class="form-control sum" value="150">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control sum" value="170">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control sum" value="100">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control sum" value="100">
                                    </td>
                                    <th><i class="fa-solid fa-trash text-danger btn deleteRow"></i></th>
                                </tr> --}}
                            <!--end::Table body-->
                        </table>
                    </div>
                    <div class="separator my-5"></div>
                    <div class="col-4 float-end mt-3">
                        <table class="col-12 ">
                        <tbody>
                            <tr>
                                <th >Total Item: </th>
                                <td class="rowcount text-left fs-4"></td>
                            </tr>
                            <tr>
                                <th>Net Total Amount</th>
                                <td class="rowSum text-left fs-4"></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Shipping Details
                            </label>
                            <textarea name="" id="" cols="3" rows="0" class="form-control" placeholder="shipping details"></textarea>
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Shipping Address
                            </label>
                            <textarea name="" id="" cols="3" rows="0" class="form-control" placeholder="shipping address"></textarea>
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Shipping Charges
                            </label>
                            <div class="input-group flex-nowrap">
                                <div class="input-group-text">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div class="input-group">
                                    <input type="text" class="form-control rounded-start-0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Shipping Status
                            </label>
                            <select name="" id="" class="form-select" data-kt-select2="true" data-hide-search="true">
                                <option value="">Please Select</option>
                                <option value="">Ordered</option>
                                <option value="">Packed</option>
                                <option value="">Shipped</option>
                                <option value="">Delivered</option>
                                <option value="">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4">
                            <label class="form-label fs-6 fw-semibold required" for="">
                                Delivered To:
                            </label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mb-7 mt-3 col-12 col-md-4 browseLogo">
                            <label class="fs-6 fw-semibold form-label " for="update_logo">
                                <span class="required">Shipping Documents:</span>
                            </label>
                            <div class="input-group browseLogo">
                                <input type="file" class="form-control" id="update_logo" name="update_logo">
                                <button type="button" class="btn btn-sm btn-danger d-none" id="removeFileBtn"><i class="fa-solid fa-trash"></i></button>
                                <label class="input-group-text btn btn-primary rounded-end" for="update_logo">
                                    Browse
                                    <i class="fa-regular fa-folder-open"></i>
                                </label>
                            </div>
                            <p class="text-gray-600 mt-3 d-block">
                                Max File size: 5MB <br>
                                Allowed File: .pdf, .csv, .zip, .doc, .docx, .jpeg, .jpg, .png
                            </p>
                        </div>
                    </div>
                    <div class="row mb-5" >
                        <div class="col-12 justify-content-center d-flex mt-3 mb-5">
                            <div class="col-6 text-center float-center ">
                                <button class="btn btn-primary  collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2" aria-expanded="false" aria-controls="kt_accordion_1_body_2">
                                    <i class="fa-solid fa-plus me-2"></i> Add additional expense
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row accordion-collapse collapse" id="kt_accordion_1_body_2" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_1">
                        <div class="col-4 offset-3">
                            <label class="fs-6 fw-semibold form-label " for="update_logo">
                                <span class="required">Additional expense name</span>
                            </label>
                            <input type="text" class="form-control mb-3">
                            <input type="text" class="form-control mb-3">
                            <input type="text" class="form-control  mb-3">
                            <input type="text" class="form-control mb-3">
                        </div>
                        <div class="col-4">
                            <label class="fs-6 fw-semibold form-label " for="update_logo">
                                <span class="required">Amount</span>
                            </label>
                            <input type="text" class="form-control mb-3">
                            <input type="text" class="form-control mb-3">
                            <input type="text" class="form-control  mb-3">
                            <input type="text" class="form-control mb-3">
                            <p class="mt-10 d-block  fs-3">
                               Order Total: KS 25,000
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="col-12">
                        <label class="form-label fs-6 fw-semibold required" for="">
                            Additional note
                        </label>
                        <textarea name="" id="" cols="10" rows="5" class="form-control" placeholder="Write Something"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>

@include('App.purchase.contactAdd')
@include('App.purchase.newProductAdd')
@endsection

@push('scripts')
<script src={{asset('customJs/Purchases/contactAdd.js')}}></script>

    <script>
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

</script>
<script src={{asset('customJs/customFileInput.js')}}></script>
<script src="{{asset('customJs/Purchases/purchaseOrderAdd.js')}}"></script>
{{-- <script src="{{asset('customJs/Purchases/purchaseOrderTable.js')}}"></script> --}}
<script>

</script>
@endpush


