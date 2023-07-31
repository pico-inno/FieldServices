var accounts;
var currentBalance=0;

$(document).ready(function(){
    const getAccountByCurrency=(id)=>{
        $.ajax({
            url: `/currency/get/${id}/payment-account`,
            type: 'GET',
            success: function(result) {
                accounts=result;
                let account=accounts.map((a) => {
                    let text=a.name+' ('+a.account_number+')';
                    return {id:a.id,text}
                })
                if(account.length){
                    $('#payment_accounts').empty();
                    $('#payment_accounts').select2({data:[{id:'',text:'Select Payment Account',selected: true,disabled: true},...account]});
                    $('#currentAccBalanceTxt').text('');
                }else{
                    $('#payment_accounts').empty();
                    $('#payment_accounts').select2({data:[{id:'',text:'Select Payment Account',selected: true,disabled: true}]});
                    $('#currentAccBalanceTxt').text('');
                }

            }
        })
    }
    let currency_id=$('#currency_id').val();
    if(currency_id){
        getAccountByCurrency(currency_id);
    }
    $(document).on('change','#currency_id',function(){
        currency_id=$(this).val();
        getAccountByCurrency(currency_id);

    })

    $('#payment_accounts').change(function(){
        if(accounts.length >0){}
            let paymentAccId=$(this).val();
            let selectedAccount=accounts.filter(function(a){
                return paymentAccId==a.id;
            })[0];
            selectedAccountCurrentBalance=selectedAccount.current_balance;
            $('#currentAccBalanceTxt').text(selectedAccountCurrentBalance);
            currentBalance=selectedAccountCurrentBalance;
            $('#currencySymbol').text(selectedAccount.currency.symbol);
    })

})
