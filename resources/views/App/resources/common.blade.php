{{-- <script>
    alert('hello');
</script> --}}


@php
    // php code
@endphp
<script>
    let settings=@json(getSettings());

    function isNullOrNan(val){
        let v=parseFloat(val);

        if(v === '' || v === null || isNaN(v)){
            return 0;
        }else{
            return v;
        }
    }
    const pDecimal=(price)=>{
        return isNullOrNan(price).toFixed(settings.currency_decimal_places);
    }
    const qDecimal=(price)=>{
        return isNullOrNan(price).toFixed(settings.quantity_decimal_places);
    }
</script>
