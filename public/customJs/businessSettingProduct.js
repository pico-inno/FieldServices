            // jquery
            $("#kt_datepicker_1").flatpickr({
                dateFormat: "d-m-Y",
            });
            //in this event I have to use jquery because this ui use select box for selec2
            $('#add_sell_expriy').on('change', function () { expirayDayCk() })


            //javascript
            const enableProductExpiry=document.querySelector('#enable_product_expiry_check');
            const productExpiry=document.querySelector('#enable_product_expiry');
            const addSellExpiry=document.querySelector('#add_sell_expriy');
            const sellExpiry=document.querySelector('#sell_expiry');
            const n_day = document.querySelector('#n_day');
            const enable_cat_check = document.querySelector('#enable_categories');
            const enable_sub_cat_div = document.querySelector('#enable_sub_cat_div');

            //init
            enableProductExCk();
            enableProductExpiry.addEventListener('change', enableProductExCk);
            enableCategory();

            //enableProductExpiry
            // function enableProductExCk() {
            //     if(enableProductExpiry.checked){
            //         productExpiry.disabled=false;
            //         addSellExpiry.disabled=false;
            //         sellExpiry.classList.remove('d-none');
            //     }else{
            //         productExpiry.disabled=true;
            //         addSellExpiry.disabled=true;
            //         n_day.disabled=true;
            //         sellExpiry.classList.add('d-none');

            //     }
            // }

            //expiray Day check
            function expirayDayCk(){
                console.log(addSellExpiry.value);
                if(addSellExpiry.value=='keep_sell'){
                    n_day.disabled=true;
                }else if(addSellExpiry.value=='stop_sell'){
                    n_day.disabled=false;
                }
            }
            enable_cat_check.addEventListener('change', enableCategory);
            function enableCategory() {
                if (enable_cat_check.checked) {
                    enable_sub_cat_div.classList.remove('d-none');
                } else {
                    enable_sub_cat_div.classList.add('d-none');
                }
            }


