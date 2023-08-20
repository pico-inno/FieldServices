"use strict";

// Class definition
var KTAppInboxCompose = function () {
    // Private functions
    // Init reply form
    const initForm = () => {
        // Set variables
        const form = document.querySelector('#kt_inbox_compose_form');
        const allTagify = form.querySelectorAll('[data-kt-inbox-form="tagify"]');


        // Init tagify
        allTagify.forEach(tagify => {
            initTagify(tagify);
        });

    }


    // Handle submit form
    const handleSubmit = (el) => {
        const submitButton = el.querySelector('[data-kt-inbox-form="send"]');

        // Handle button click event
        submitButton.addEventListener("click", function () {
            // Activate indicator
            submitButton.setAttribute("data-kt-indicator", "on");

            // Disable indicator after 3 seconds
            setTimeout(function () {
                submitButton.removeAttribute("data-kt-indicator");
            }, 3000);
        });
    }

    // Init tagify
    const initTagify = (el) => {
        var inputElm = el;
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
                        <div class='tagify__tag__avatar-wrap ps-0'>
                            <img onerror="this.style.visibility='hidden'" class="rounded-circle w-25px me-2" src="/media/${tagData.avatar}">
                        </div>
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

                    ${tagData.avatar ? `
                            <div class='tagify__dropdown__item__avatar-wrap me-2'>
                                <img onerror="this.style.visibility='hidden'"  class="rounded-circle w-50px me-2" src="media/${tagData.avatar}">
                            </div>` : ''
                }

                    <div class="d-flex flex-column">
                        <strong>${tagData.name}</strong>
                        <span>${tagData.email}</span>
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
    }





    // Public methods
    return {
        init: function () {
            initForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAppInboxCompose.init();
});
