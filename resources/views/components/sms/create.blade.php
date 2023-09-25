<div>
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

    <!--begin::Card-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xl" id="sale_container">
            <div class="card">
                <div class="card-header align-items-center">
                    <div class="card-title">
                        <h2>Send Message</h2>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!--begin::Form-->
                    <form id="kt_inbox_compose_form" action="{{route('sms.send',$service)}}" method="POST">
                        @csrf
                        <!--begin::Body-->
                        <div class="d-block">
                            <!--begin::To-->
                            <div class="d-flex align-items-center px-8 min-h-50px">
                                <!--begin::Label-->
                                <div class="text-dark fw-bold w-100px">Contact :</div>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <x-forms.input name='sent_to' placeholder="Enter Contact Name" id="contactTagify">
                                </x-forms.input>
                                {{-- <input type="text" class="form-control form-control-sm border-left-0" name="sent_to"
                                    id="sent_to" value="" placeholder="Enter Contact Name" /> --}}
                                {{-- <input type="text" class="form-control form-control-sm " name="sent_to" id="sent_to"
                                    value="" data-kt-inbox-form="tagify" placeholder="Enter Contact Name" /> --}}
                                <!--end::Input-->
                            </div>
                            <!--end::To-->
                            <div class="d-flex align-items-start px-8 min-h-50px mt-10">
                                <!--begin::Label-->
                                <div class="text-dark fw-bold w-100px">Message:</div>
                                <textarea class="form-control form-control- borer-0  min-h-45px mb-3" cols="30" rows="10"
                                    name="message" placeholder="Enter Message"></textarea>
                            </div>
                            <!--end::To-->
                            <!--begin::Subject-->
                            {{-- <div class="border-bottom col-12 ">
                            </div> --}}
                            <!--end::Subject-->
                            <!--begin::Message-->
                            <!--end::Message-->
                        </div>
                        <!--end::Body-->
                        <!--begin::Footer-->
                        <div class="d-flex flex-stack flex-wrap gap-2 py-5 ps-8 pe-5 border-top justify-content-center">
                            <!--begin::Actions-->
                            <div class="d-flex align-items-center me-3">
                                <!--begin::Send-->
                                <div class="btn-group me-4 text-end">
                                    <!--begin::Submit-->
                                    <button type="submit" class="btn btn-sm btn-primary fs-bold px-6"
                                        data-kt-inbox-form="send">
                                        <span class="indicator-label">Send</span>
                                        {{-- <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        --}}
                                    </button>
                                    <!--end::Submit-->
                                </div>
                            </div>
                            <!--end::Actions-->
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

        function tagTemplate(tagData) {
            return `
                <tag title="${(tagData.value || tagData.moblie)}"
                        contenteditable='false'
                        spellcheck='false'
                        tabIndex="-1"
                        class="${this.settings.classNames.tag} ${tagData.class ? tagData.class : ""}"
                        ${this.getAttributes(tagData)}>
                    <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                    <div class="d-flex align-items-center">
                        <div class='tagify__tag-text'>${tagData.value}</div><br>
                        <span class='tagify__tag-text'>(${tagData.mobile})</span>
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
                        <span>${tagData.mobile}</span>
                    </div>
                </div>
                `
                }
        var contactInput = document.querySelector('#contactTagify');
        let contact=@json($contact).map((e)=>{
            name=`${e.first_name?e.first_name+' ' :''}${e.middle_name?e.middle_name+' ' :''}${e.last_name?e.last_name+' ' :''}`;
            return {'value':name,'mobile':e.mobile};
        });

        // Init Tagify script on the above inputs
        tagify = new Tagify(contactInput, {
            whitelist: contact,
            placeholder: "Type Contact Name",
            enforceWhitelist: true,// skipInvalid: true,
            dropdown: {
                closeOnSelect: false,
                enabled: 0,
                classname: 'users-list',
                searchKeys: ['value', 'mobile']  // very important to set by which keys to search for suggesttions when typing
            },
            templates: {
                tag: tagTemplate,
                dropdownItem: suggestionItemTemplate
            },
        });
        // var tagify = new Tagify(inputElm, {
        //     tagTextProp: 'name',
        //     enforceWhitelist: true,
        //     skipInvalid: true,
        //     dropdown: {
        //         closeOnSelect: false,
        //         enabled: 0,
        //         classname: 'users-list',
        //         searchKeys: ['name', 'email']  // very important to set by which keys to search for suggesttions when typing
        //     },
        //     templates: {
        //         tag: tagTemplate,
        //         dropdownItem: suggestionItemTemplate
        //     },
        //     whitelist: usersList
        // })
    </script>
    {{-- <script src="assets/js/custom/apps/inbox/compose.js"></script> --}}
    @endpush
</div>
