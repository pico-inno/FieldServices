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

    const dfpDecimal=(number,symbolPosition='b')=>{
        let currency=settings.currency ?? '';
        let decimalSeparator=currency.decimal_separator ?? '.';
        let thousandsSeparator=currency.thoundsand_seprator=='comma' ? ',' : ',';
        return  formatNumber(isNullOrNan(number),decimalSeparator,thousandsSeparator)+' '+currency.symbol;
    }
    const nfpDecimal=(number,currency={},symbolPosition='b')=>{
        let decimalSeparator=currency ? currency.decimal_separator: '.';
        let thousandsSeparator=',';
        let symbol=currency? currency.symbol: '';
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

</script>
