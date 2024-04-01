{{-- <script>
    alert('hello');
</script> --}}


@php
    // php code
    $insp=env('APP_INSP',false);
@endphp
<script>

    let settings=@json(getSettings());
    let insp="{{$insp}}";
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

    const dfpDecimal=(number,symbolPosition='b')=>{
        let currency=settings.currency ?? '';
        let decimalSeparator=currency.decimal_separator ?? '.';
        let thousandsSeparator=currency.thoundsand_seprator=='comma' ? ',' : ',';
        return  formatNumber(isNullOrNan(number),decimalSeparator,thousandsSeparator)+' '+currency.symbol;
    }
    const nfpDecimal=(number,currency={},symbolPosition='b')=>{
        let decimalSeparator,symbol;
        if(currency){
            decimalSeparator=currency ? currency.decimal_separator: '.';
            symbol=currency? currency.symbol: '';
        }else{
            let currency=settings.currency ?? '';
            decimalSeparator=".";
            symbol=currency? currency.symbol: '';
        }
        let thousandsSeparator=',';
        return  formatNumber(isNullOrNan(number),decimalSeparator,thousandsSeparator)+' '+symbol;
    }
    const fpDecimal=(number,reqs=[])=>{
        let decimalSeparator;
        let thousandsSeparator=reqs['thoundsand_seprator'] ?? ',';
        return formatNumber(isNullOrNan(number),decimalSeparator,thousandsSeparator);
    }

    function formatNumber(number, decimalSeparator='.', thousandsSeparator=',') {
        if (typeof number !== 'number') {
            throw new Error('Input must be a valid number');
        }
        const options = {
            minimumFractionDigits: settings.currency_decimal_places,
            maximumFractionDigits: settings.currency_decimal_places,
            useGrouping: true,
            decimalSeparator,
            thousandsSeparator,
        };
        return number.toLocaleString(undefined, options);
    }
    function preventEnterSubmit(event) {
      if (event.key === "Enter" && !insp) {
        event.preventDefault();
      }
    }
    document.addEventListener('contextmenu',function(event){
        if( insp==false){
            event.preventDefault();
            showPreventMessage();
        }
    });


    document.onkeydown = (e) => {
        if (e.key == 'F12' && !insp) {
            // alert('you are f12');
            e.preventDefault();
        }
    };

    function showPreventMessage(){
           // Show notification message at the click position
        const notiMessage = document.getElementById('notiMessage');
        notiMessage.style.left = `${event.clientX}px`;
        notiMessage.style.top = `${event.clientY}px`;
        notiMessage.style.display = 'block';

        // Hide the notification message after a certain duration (e.g., 2 seconds)
        setTimeout(function() {
            notiMessage.style.display = 'none';
        }, 2000); // Adjust the duration as needed
    }
</script>
