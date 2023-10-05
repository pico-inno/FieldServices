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
    $('#currency_id').change(function(){
        $('#submit').attr('disabled','true');
        $.ajax({
            url:`/price-list/${$(this).val()}`,
            type: 'GET',
            error:function(e){
                status=e.status;
                if(status==405){
                    warning('Method Not Allow!');
                }else if(status==419){
                    error('Session Expired')
                }else{
                    console.log(e);
                    console.log(' Something Went Wrong! Error Status: '+status )
                };
            },
            success: function(results){
                results=[{id:0,text:'Cost'},...results];
                $('#base_price').empty();
                $('#base_price').select2({data:results});
            }

        }).always(function() {
            $('#submit').removeAttr('disabled');
        });
    })
</script>
