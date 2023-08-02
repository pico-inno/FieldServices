<script src="{{ asset('customJs/debounce.js') }}"></script> 
<script src="{{ asset('customJs/pricelist/actions.js') }}"></script> 
<script src="{{ asset('customJs/pricelist/event-handlers.js') }}"></script> 

<script>
    $(document).ready(function() {
        $(".select_date").flatpickr({
            altInput: true, 
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        });
        
        disableDeleteButton();
    });
</script>