<script>
    __init();
    let patients=@json($contacts ?? []);
    let room_rates=@json($room_rates ?? []) ;
    let rooms=@json($rooms ?? []) ;
    let rates;
    var validator;
    $('#patient_id').on('change', function() {
        if(this.value != '' && this.value!= undefined ){
            $('#patient_info').addClass('show')
            $('.accordion-header').removeClass('collapsed')
            let selectedPatient=patients.filter((patient)=>{
                return patient.id==this.value;
            })
            let p=selectedPatient[0];
            $('#patient_name').val(p.first_name);
            $('#patient_DOB').val(p.dob);
        }
    });

    $('#registration_type').on('change',function(){
        if(this.value==='OPD'){
            $('.room_info').hide();
            $('.ipd_check_in').hide();
            $('.opd_check_in').show();
            removeField();
        }else{
            $('.room_info').show();
            $('.ipd_check_in').show();
            $('.opd_check_in').hide();

        };
    })

    function __init() {
        $('.room_type').on('change',function () {
            let parent = $(this).closest('.input_group');
            let room_select=parent.find('.room_select');
            let room_rate=parent.find('.room_rate');

            let room_type_id=$(this).val();
            let room=rooms.filter(function(room){
                return room.room_type_id==room_type_id;
            });

            rates=room_rates.filter(function(rate){
                return rate.room_type_id==room_type_id;
            });

            // for  room
            let data= room.map(function(r) {
                return {id:r.id,text:r.name};
            })

            let rate_data= rates.map(function(rate) {
                return {id:rate.id,text:rate.rate_name};
            })
            room_select.empty();

            data=[{id:"",text:"select room"},...data]
            room_select.select2({
                data
            })
            let rs=document.getElementsByClassName('room_select');
            rs.forEach(function(e){
                room_select.find('option[value="' + $(e).val() + '"]').prop('disabled', true);
            })


            // for room rate
            room_rate.empty();
            room_rate.select2({
                data:rate_data
            })
            if(rates[0]){
                parent.find('.before_discount_amount').val(rates[0].rate_amount ?? 0);
            }else{
                parent.find('.before_discount_amount').val(0);
            }

            discountCal($(this));

        })
        $('.room_rate').on('change',function(){
            let parent = $(this).closest('.input_group');
            let room_rate_id=$(this).val();
            let e= rates.filter(function(v){
                return v.id==room_rate_id;
             })

            discountCal($(this));
            calTotalRoomSaleAmount();
            parent.find('.before_discount_amount').val(e[0].rate_amount ?? 0);

        })
        $(document).on('change','.discount_type, .discount_amount,.discount_price,.before_discount_amount,.after_discount_amount,.qty,.edit_check_in_date,.edit_check_in_date',function(){
            discountCal($(this));
            calTotalRoomSaleAmount();
        })
         $(document).on('change','.amount_inc_tax',function(){
            calTotalRoomSaleAmount();
            total_sale_amount();
            balance_amount();
        })
        $(document).on('change','#total_amount,#total_discount_amount,#total_discount_type',function(){
            total_sale_amount();
            balance_amount();
        })
        $(document).on('change','#total_paid_amount',function(){
            balance_amount();
        })
    }



         $('[data-flat-date="true"]').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });



    function discountCal(i) {
      setTimeout(() => {
        let parent = i.closest('.input_group');
        let before_discount_amount=parent.find('.before_discount_amount').val() ?? 0;
        let discount_type=parent.find('.discount_type').val() ;
        let discount_amount=parent.find('.discount_amount').val() ?? 0;
        let qty=isNullOrNan(parent.find('.qty').val()) ?? 1;

        let after_discount_amount_input=parent.find('.after_discount_amount');
        let amount_inc_tax=parent.find('.amount_inc_tax');
        let after_discount_amount;
        // console.log(discount_type);
        if(discount_type == 'fixed'){

        after_discount_amount=before_discount_amount - discount_amount;

        }else if(discount_type=='percentage'){
            percentage_amount=before_discount_amount* (discount_amount/100);
            after_discount_amount=(before_discount_amount - percentage_amount)

        }
        console.log(before_discount_amount,discount_amount,discount_type,after_discount_amount);
        parent.find('.after_discount_amount').val(after_discount_amount);
        after_discount_amount_input.val(after_discount_amount * qty);
        amount_inc_tax.val(after_discount_amount * qty);
      }, 100);
    }

    function calTotalRoomSaleAmount(){
        setTimeout(() => {
            let total_amount=0;
            $('.amount_inc_tax').each(function(){
                total_amount=total_amount + parseFloat($(this).val());
            });
            $('#total_amount').val(total_amount);

            total_sale_amount();
            balance_amount();
      }, 120);
    }
    function total_sale_amount(){
            let result;
            let discount_type=$('#total_discount_type').val();console.log(discount_type);
            let discount_amount=parseFloat($('#total_discount_amount').val() ?? 0);
            let total_amount=$('#total_amount').val();
            if(discount_type== 'fixed'){
                result=total_amount - discount_amount;
                $('#discount').val(discount_amount);

            }else if(discount_type=='percentage'){
                percentage_amount=total_amount * (discount_amount/100);
                $('#discount').val(discount_amount/100);
                result=(total_amount - percentage_amount);
            }
            $('#total_sale_amount').val(result)
    }
    function balance_amount(){
            let total_sale_amount=isNullOrNan($('#total_sale_amount').val());
            let total_paid_amount=isNullOrNan($('#total_paid_amount').val());
            let total_amount=$('#total_amount').val();
            let result=total_sale_amount-total_paid_amount;
            console.log(result);
            $('#total_balance_amount').val(result)
    }
    $(document).on('change', '.room_select', function()
        {
            var selectedValue = $(this).val();
            var selectBoxes = $('.room_select').not(this);
            var room_select = $('.room_select');
            let rs=document.getElementsByClassName('room_select');
            rs.forEach(function(e){
                room_select.find('option:not([value="' + $(e).val() + '"])').prop('disabled', false);
            })

            selectBoxes.find('option[value="' + $(this).val() + '"]').prop('disabled', true);
        }
    );

        $(document).on('change','.edit_check_in_date,.edit_check_out_date',function(){
            let parent = $(this).closest('.input_group');
            let checkInDate=parent.find('.edit_check_in_date').val() ;
            let checkOutDate=parent.find('.edit_check_out_date').val();
            let qty=parent.find('.qty');
            if(checkInDate && checkOutDate){
               let result= diffDate(checkOutDate,checkInDate);
               console.log(result);
               qty.val(result);

            }
        })
       function diffDate(d1,d2) {
            date1 = new Date(d1);
            date2 = new Date(d2);
            var milli_secs =date2.getTime() - date1.getTime() ;
            // Convert the milli seconds to Days
            var days = milli_secs / (1000 * 3600 * 24);
            return Math.round(Math.abs(days));
        }

    function isNullOrNan(val){
        let v=parseFloat(val);

        if(v=='' || v==null || isNaN(v)){
            return 0;
        }else{
            return v;
        }
    }


</script>
