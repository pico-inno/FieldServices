
    numberOnly();
    function numberOnly() {
        $(".input_number").off('keypress').keypress(function(event) {
            var charCode = (event.which) ? event.which : event.keyCode;
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
            }
        });
        $(".input_number").on('change', function() {
            var inputValue = $(this).val();
            var numericValue = parseFloat(inputValue);
            if (isNaN(numericValue)) {
                $(this).val(0); // Clear the input value or take appropriate action
            } else {
                 $(this).val(numericValue);
            }
        });
    }
    function floor(number, roundAmount) {

        let decimalPlaces = roundAmount == 0 ? 1 :roundAmount;
        let powerOf10 = Math.pow(10, decimalPlaces);
        let roundedDown = Math.floor(number * powerOf10) / powerOf10
        return roundedDown;
    }

    // aside control
    if(localStorage.getItem('asideBar')=='on'){
        $("#kt_body").removeAttr('data-kt-aside-minimize','on');
    }else{
        $("#kt_body").attr('data-kt-aside-minimize','on');
        $('#kt_aside_toggle').addClass('active');
    }
    $('#kt_aside_toggle').on('click',()=>{
        let asideCtr=$("#kt_body").attr('data-kt-aside-minimize');
        if(asideCtr=='on'){
            localStorage.setItem('asideBar','on');
        }else{
            localStorage.setItem('asideBar','off');
        }
    })




    // alert sound
    _init_sound();
    $('#alertSound').click(function(e){
        e.preventDefault();
        sound=localStorage.getItem('alertSound');
        if(sound){
            if(sound=='on'){
                off();
            }else if(sound=='off'){
                on();
            }
        }else{
            alert('on done');
            on();
        }
    })

    function off() {
        $('#alertIcon').html('<i class="fa-solid fa-volume-xmark  fs-sm-7 fs-8"></i>')
        localStorage.setItem('alertSound','off');
    }
    function on() {
        $('#alertIcon').html('<i class="fa-solid fa-volume-low text-primary  fs-sm-7 fs-8"></i>')
        localStorage.setItem('alertSound','on');
    }
    function _init_sound(){
        sound=localStorage.getItem('alertSound');
        if(sound){
            if(sound=='on'){
                on();
            }else if(sound=='off'){
                off();
            }
        }else{
            off();
        }
    }


    function clipboard(){
        // Select elements
        const target = document.getElementById('clipboard');
        const button = target.nextElementSibling;

        // Init clipboard -- for more info, please read the offical documentation: https://clipboardjs.com/
        clipboard = new ClipboardJS(button, {
            target: target,
            text: function () {
                return target.innerHTML;
            }
        });

        // Success action handler
        clipboard.on('success', function (e) {
            var checkIcon = button.querySelector('.ki-check');
            var copyIcon = button.querySelector('.ki-copy');

            // Exit check icon when already showing
            if (checkIcon) {
                return;
            }

            // Create check icon
            checkIcon = document.createElement('i');
            checkIcon.classList.add('ki-duotone');
            checkIcon.classList.add('ki-check');
            checkIcon.classList.add('fs-2x');

            // Append check icon
            button.appendChild(checkIcon);

            // Highlight target
            const classes = ['text-primary-emphasis', 'fw-boldest'];
            target.classList.add(...classes);

            flotemessage('successfully copied')
            // Highlight button
            // $('.clipboard-icon').addClass('text-success')

            // Hide copy icon
            copyIcon.classList.add('d-none');

            // Revert button label after 3 seconds
            setTimeout(function () {
                // Remove check icon
                copyIcon.classList.remove('d-none');

                // Revert icon
                button.removeChild(checkIcon);

                // Remove target highlight
                target.classList.remove(...classes);

                // Remove button highlight
                button.classList.remove('btn-success');
            }, 1000);

        });
    }
