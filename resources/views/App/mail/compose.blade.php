

    @extends('App.main.navBar')
    @section('mail_active','active')
    @section('compose_active', 'active')
    @section('mail_active_show', 'active show')
    @section('mail_drop_active_show','active show')
    @section('compose_active', 'active ')

    @section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">Send SMS</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Send</li>
        {{-- <li class="breadcrumb-item text-muted">add</li> --}}
        <li class="breadcrumb-item text-dark">SMS </li>
    </ul>
    <!--end::Breadcrumb-->
    @endsection

    @section('styles')
    <link href="{{asset("assets/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href={{asset("customCss/businessSetting.css")}}>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
    <style>
        .data-table-body tr td {
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
        <!--begin::Card-->
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <h2 class="card-title m-0">Compose Message</h2>
                    <!--begin::Toggle-->
                    <a href="#" class="btn btn-sm btn-icon btn-color-primary btn-light btn-active-light-primary d-lg-none"
                        data-bs-toggle="tooltip" data-bs-dismiss="click" data-bs-placement="top" title="Toggle inbox menu"
                        id="kt_inbox_aside_toggle">
                        <i class="ki-outline ki-burger-menu-2 fs-3 m-0"></i>
                    </a>
                    <!--end::Toggle-->
                </div>
                <div class="card-body p-0">
                    <!--begin::Form-->
                    <form id="kt_inbox_compose_form" action="{{route('mail.send')}}" method="POST">
                        @csrf
                        <!--begin::Body-->
                        <div class="d-block">
                            <!--begin::To-->
                            <div class="d-flex align-items-center border-bottom px-8 min-h-50px">
                                <!--begin::Label-->
                                <div class="text-dark fw-bold w-75px">To:</div>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-transparent border-0" name="compose_to" value=""
                                    data-kt-inbox-form="tagify" id="contactTagify" />
                                <!--end::Input-->
                                <!--begin::CC & BCC buttons-->
                                <div class="ms-auto w-75px text-end">
                                    <span class="text-muted fs-bold cursor-pointer text-hover-primary me-2"
                                        data-kt-inbox-form="cc_button">Cc</span>
                                    <span class="text-muted fs-bold cursor-pointer text-hover-primary"
                                        data-kt-inbox-form="bcc_button">Bcc</span>
                                </div>
                                <!--end::CC & BCC buttons-->
                            </div>
                            <!--end::To-->
                            <!--begin::CC-->
                            <div class="d-none align-items-center border-bottom ps-8 pe-5 min-h-50px" data-kt-inbox-form="cc">
                                <!--begin::Label-->
                                <div class="text-dark fw-bold w-75px">Cc:</div>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-transparent border-0" name="compose_cc" value=""
                                    data-kt-inbox-form="tagify" />
                                <!--end::Input-->
                                <!--begin::Close-->
                                <span class="btn btn-clean btn-xs btn-icon" data-kt-inbox-form="cc_close">
                                    <i class="ki-outline ki-cross fs-5"></i>
                                </span>
                                <!--end::Close-->
                            </div>
                            <!--end::CC-->
                            <!--begin::BCC-->
                            <div class="d-none align-items-center border-bottom inbox-to-bcc ps-8 pe-5 min-h-50px"
                                data-kt-inbox-form="bcc">
                                <!--begin::Label-->
                                <div class="text-dark fw-bold w-75px">Bcc:</div>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-transparent border-0" name="compose_bcc"
                                    value="" data-kt-inbox-form="tagify" />
                                <!--end::Input-->
                                <!--begin::Close-->
                                <span class="btn btn-clean btn-xs btn-icon" data-kt-inbox-form="bcc_close">
                                    <i class="ki-outline ki-cross fs-5"></i>
                                </span>
                                <!--end::Close-->
                            </div>
                            <!--end::BCC-->
                            <!--begin::Subject-->
                            <div class="border-bottom">
                                <input class="form-control form-control-transparent border-0 px-8 min-h-45px" name="compose_subject"
                                    placeholder="Subject" />
                            </div>
                            <!--end::Subject-->
                            <!--begin::Message-->
                            <input type="hidden" name="body" id="quill-content">
                            <div id="kt_docs_quill_basic" name="quill" class="bg-transparent border-0 h-350px px-3"></div>
                            <!--end::Message-->
                            <!--begin::Attachments-->
                            <div class="dropzone dropzone-queue px-8 py-4" id="kt_inbox_reply_attachments"
                                data-kt-inbox-form="dropzone">
                                <div class="dropzone-items">
                                    <div class="dropzone-item" style="display:none">
                                        <!--begin::Dropzone filename-->
                                        <div class="dropzone-file">
                                            <div class="dropzone-filename" title="some_image_file_name.jpg">
                                                <span data-dz-name="">some_image_file_name.jpg</span>
                                                <strong>(
                                                    <span data-dz-size="">340kb</span>)</strong>
                                            </div>
                                            <div class="dropzone-error" data-dz-errormessage=""></div>
                                        </div>
                                        <!--end::Dropzone filename-->
                                        <!--begin::Dropzone progress-->
                                        <div class="dropzone-progress">
                                            <div class="progress bg-gray-300">
                                                <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0"
                                                    aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress=""></div>
                                            </div>
                                        </div>
                                        <!--end::Dropzone progress-->
                                        <!--begin::Dropzone toolbar-->
                                        <div class="dropzone-toolbar">
                                            <span class="dropzone-delete" data-dz-remove="">
                                                <i class="ki-outline ki-cross fs-2"></i>
                                            </span>
                                        </div>
                                        <!--end::Dropzone toolbar-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Attachments-->
                        </div>
                        <!--end::Body-->
                        <!--begin::Footer-->
                        <div class="d-flex flex-stack flex-wrap gap-2 py-5 ps-8 pe-5 border-top">
                            <!--begin::Actions-->
                            <div class="d-flex align-items-center me-3">
                                <!--begin::Send-->
                                <button class="btn btn-primary">
                                    Send
                                </button>
                                <div class="btn-group me-4 d-none">
                                    <!--begin::Submit-->
                                    <span class="btn btn-primary fs-bold px-6" data-kt-inbox-form="send">
                                        <span class="indicator-label">Send</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </span>
                                    <!--end::Submit-->
                                    <!--begin::Send options-->
                                    <span class="btn btn-primary btn-icon fs-bold w-30px pe-0 d-none" role="button">
                                        <span class="lh-0" data-kt-menu-trigger="click" data-kt-menu-placement="top-start">
                                            <i class="ki-outline ki-down fs-4 m-0"></i>
                                        </span>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                                            data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3">Schedule send</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3">Save & archive</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3">Cancel</a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </span>
                                    <!--end::Send options-->
                                </div>
                                <!--end::Send-->
                                <!--begin::Upload attachement-->
                                <span class="btn btn-icon btn-sm btn-clean btn-active-light-primary me-2 d-none"
                                    id="kt_inbox_reply_attachments_select" data-kt-inbox-form="dropzone_upload">
                                    <i class="ki-outline ki-paper-clip fs-2 m-0"></i>
                                </span>
                                <!--end::Upload attachement-->
                                <!--begin::Pin-->
                                <span class="btn btn-icon btn-sm btn-clean btn-active-light-primary d-none">
                                    <i class="ki-outline ki-geolocation fs-2 m-0"></i>
                                </span>
                                <!--end::Pin-->
                            </div>
                            <!--end::Actions-->
                            <!--begin::Toolbar-->
                            <div class="d-flex align-items-center d-none">
                                <!--begin::More actions-->
                                <span class="btn btn-icon btn-sm btn-clean btn-active-light-primary me-2" data-toggle="tooltip"
                                    title="More actions">
                                    <i class="ki-outline ki-setting-2 fs-2"></i>
                                </span>
                                <!--end::More actions-->
                                <!--begin::Dismiss reply-->
                                <span class="btn btn-icon btn-sm btn-clean btn-active-light-primary" data-inbox="dismiss"
                                    data-toggle="tooltip" title="Dismiss reply">
                                    <i class="ki-outline ki-trash fs-2"></i>
                                </span>
                                <!--end::Dismiss reply-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Footer-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
    </div>
    <!--end::Card-->
    @endsection

    @push('scripts')
    <script>
        var contactInput = document.querySelector('#contactTagify');
        let contact=@json($contact).map((e)=>{
            name=`${e.first_name?e.first_name+' ' :''}${e.middle_name?e.middle_name+' ' :''}${e.last_name?e.last_name+' ' :''}`;
            return {'value':name,'email':e.email};
        });

        // Init Tagify script on the above inputs
        tagify = new Tagify(contactInput, {

            placeholder: "Type Contact Name",
            tagTextProp: 'value', // very important since a custom template is used with this property as text. allows typing a
            enforceWhitelist: true,
            skipInvalid: true, // do not remporarily add invalid tags

            dropdown: {
                closeOnSelect: false,
                enabled: 0,
                classname: 'contacts-list',
                searchKeys: ['value', 'email']  // very important to set by which keys to search for suggesttions when typing
            },
            templates: {
                tag: tagTemplate,
                dropdownItem: suggestionItemTemplate
            },
            whitelist: contact,
        });

        function tagTemplate(tagData) {
            return `
                <tag title="${(tagData.value || tagData.email)}"
                        contenteditable='false'
                        spellcheck='false'
                        tabIndex="-1"
                        class="${this.settings.classNames.tag} ${tagData.class ? tagData.class : ""}"
                        ${this.getAttributes(tagData)}>
                    <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                    <div class="d-flex align-items-center">
                        <div class='tagify__tag-text'>${tagData.value} (${tagData.email})</div>
                    </div>
                </tag>
            `
        }
        function suggestionItemTemplate(tagData) {
                return `
                <div ${this.getAttributes(tagData)} class='tagify__dropdown__item d-flex align-items-center ${tagData.class ? tagData.class : ""}' tabindex="0"
                    role="option">

                    <div class="d-flex flex-column">
                        <strong>${tagData.value}</strong>
                        <span>${tagData.email}</span>
                    </div>
                </div>
                `
        }




        var addAllSuggestionsElm;
        tagify.on('dropdown:show dropdown:updated', onDropdownShow)
        tagify.on('dropdown:select', onSelectSuggestion)
                // create a "add all" custom suggestion element every time the dropdown changes
        function getAddAllSuggestionsElm() {
            // suggestions items should be based on "dropdownItem" template
            return tagify.parseTemplate('dropdownItem', [{
                class: "addAll",
                name: "Add all",
                email: tagify.settings.whitelist.reduce(function (remainingSuggestions, item) {
                    return tagify.isTagDuplicate(item.value) ? remainingSuggestions : remainingSuggestions + 1
                }, 0) + " Customers"
            }]
            )
        }
        function onDropdownShow(e) {
            var dropdownContentElm = e.detail.tagify.DOM.dropdown.content;
            if (tagify.suggestedListItems.length > 1) {
                addAllSuggestionsElm = getAddAllSuggestionsElm();
                dropdownContentElm.insertBefore(addAllSuggestionsElm, dropdownContentElm.firstChild)
            }
        }

        function onSelectSuggestion(e) {
            if (e.detail.elm == addAllSuggestionsElm) {
                // Check if the suggestion is not already selected
                tagify.settings.whitelist.forEach((item) => {
                    if (!tagify.isTagDuplicate(item.value)) {
                        // Add the item to the tags
                        tagify.addTags([item]);
                    }
                });
            }
        }

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
        theme: 'snow', // or 'bubble'
    });
    quill.on('text-change', function() {
        var quillContent = quill.root.innerHTML;
        document.getElementById('quill-content').value = quillContent;
    });
    // // Access the Tagify instance from the Quill editor
    // var quillTag = quill.getModule('tagify');

    // // Set the name attribute for the input field
    // quillTag.DOM.input.setAttribute('name', 'quill');
    </script>
    {{-- <script src={{asset("assets/js/custom/apps/inbox/compose.js")}}></script> --}}
    @endpush
