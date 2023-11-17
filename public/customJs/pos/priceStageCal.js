    var priceList;
        function isNullOrNan(val){
            let v=parseFloat(val);
            if(v=='' || v==null || isNaN(v)){
                return 0;
            }else{
                return v;
            }
        }
    function getPriceList(priceListId,callBack=null) {
        if (priceListId === 'default_selling_price') {
            priceList = null;
            return;
        }
        $.ajax({
            url: `/sell/${priceListId}/price/list`,
            type: 'GET',
            error:function(e){
                let status=e.status;
                if(status==405){
                    warning('Method Not Allow!');
                }else if(status==419){
                    error('Session Expired')
                }else{
                    console.log(' Something Went Wrong! Error Status: '+status )
                };
            },
            success: function(results){
                priceList = results;
                if (typeof callBack === 'function') {
                    callBack();
                }
            }
        })
    }
    function getPrice(e){
        if(priceList){
            let parent = e.closest('tr');
            let mainPriceLists = priceList.mainPriceList ?? [];
            let mainPriceStatus = false;
            let dis_type = parent.find('input[name="discount_type"]').val();
            if (dis_type!='foc') {
                mainPriceLists.forEach((mainPriceList) => {
                    if (mainPriceStatus == true) {
                        priceSetting(mainPriceList, parent,false);
                    }else{

                    mainPriceStatus = priceSetting(mainPriceList, parent,true);
                    }
                })

                let basePriceLists=priceList.basePriceList ?? [];
                if(!mainPriceStatus){
                    if(basePriceLists.length > 0){
                        let i = 0;
                        while (i < basePriceLists.length) {
                            let basePriceList = basePriceLists[i];
                            let priceStatus = false;
                            basePriceList.forEach((bp) => {
                                if (priceStatus == true) {
                                    return;
                                }
                                priceStatus = priceSetting(bp, parent);
                            })
                            if (priceStatus) {
                                break;
                            }
                            i++;
                        }
                    }else{
                        let productId = parent.find('input[name="product_id"]').val();
                        let variationId = parent.find('input[name="variation_id"]').val();
                        let product=productsOnSelectData.filter(function(p){
                            return p.product_id==productId && variationId == p.variation_id;
                        })[0];
                        if (product){
                            let result=getPriceByUOM(parent,product,'',product.defaultSellingPrices ?? 0);
                            let price=isNullOrNan(result.resultPrice);
                            if(price == 0){
                                let uomPirce=parent.find('input[name="selling_price[]"]').val();
                                parent.find('input[name="selling_price[]"]').val(pDecimal(uomPirce ?? 0));
                                parent.find('.subtotal_price').text(pDecimal(uomPirce?? 0) );
                            }else{
                                parent.find('input[name="selling_price[]"]').val(pDecimal(price));
                                parent.find('.subtotal_price').text(pDecimal(price) );
                            }
                        }
                    }
                }
            } else {
                parent.find('input[name="selling_price[]"]').val(pDecimal(0));
                parent.find('.subtotal_price').text(pDecimal(0) );
            }

        }
    }
    function priceSetting(priceStage,parentDom){
        let productId=parentDom.find('input[name="product_id"]').val();
        let variationId = parentDom.find('input[name="variation_id"]').val();
        let product=productsOnSelectData.filter(function(p){
            return p.product_id==productId && variationId == p.variation_id;
        })[0];
        if(priceStage.applied_type=='All'){
            if(!priceSettingToUi(priceStage,parentDom,product)){
                return false
            }else{
                return true;
            };
        }else if(priceStage.applied_type=='Category'){
            let categoryId=product.category_id;
            if(priceStage.applied_value==categoryId){
                if(!priceSettingToUi(priceStage,parentDom,product)){
                    return false
                }else{
                    return true;
                };
            }
        } else if (priceStage.applied_type == 'Product') {
            if(priceStage.applied_value==productId){
                if(!priceSettingToUi(priceStage,parentDom,product)){
                    return false
                }else{
                return true;
            };
            }
        }else if(priceStage.applied_type=='Variation'){
            if(priceStage.applied_value==variationId){
                if(!priceSettingToUi(priceStage,parentDom,product)){
                    return false
                }else{
                    return true;
                };
            }
        }else{
            return false;
        }
    }
    function priceSettingToUi(priceStage,parentDom,product){
        let checkDate = checkAvailableDate(priceStage);
        if(!checkDate){
            return false;
        }
        let quantity=isNullOrNan(parentDom.find('.quantity_input').val());
        let price = priceStage.cal_value;
        if (priceStage.cal_type == 'percentage') {
            let basePriceLists=priceList.basePriceList;
            let i = 0;
            let finalBasePrice=null;//final base price means when current price is  percentage on base price, the loop reach the base price that is fix price;
            let calPers=[];
            while (i < basePriceLists.length) {
                let bps = basePriceLists[i];//base prices
                i++;
                let bp = null;
                bps.forEach(basePrice => {
                    if (basePrice.applied_type == 'All') {
                        bp = basePrice;
                        return;
                    } else if (basePrice.applied_type == 'Category') {
                        let categoryId=product.category_id;
                        if (basePrice.applied_value == categoryId) {
                            bp = basePrice;
                            return;
                        }
                    } else if (basePrice.applied_type == 'Product') {
                        let productId=product.product_id;
                        if (basePrice.applied_value == productId) {
                            bp = basePrice;
                            return;
                        }
                    } else if (basePrice.applied_type == 'Variation') {
                        let variationId=product.variation_id;
                        if (basePrice.applied_value == variationId) {
                            bp = basePrice;
                            return;
                        }
                    }
                });
                if(bp){
                    if(bp.cal_type=='percentage'){
                    if(bp.id==priceStage.id){
                        calPers=[];
                    }else{
                        calPers=[bp.cal_value,...calPers];
                    }
                }else{
                    if(calPers.length>0){
                        let currentPrcie=bp.cal_value;
                        calPers.forEach(per => {
                            percentagePrice=currentPrcie * (per/100);
                            currentPrcie=isNullOrNan(currentPrcie)+isNullOrNan(percentagePrice);
                        });
                    finalBasePrice= currentPrcie
                    }else{
                        finalBasePrice=bp.cal_value;
                    }
                    break;
                }
                }
            }
            if(finalBasePrice !== null){
                percentagePrice=finalBasePrice * (priceStage.cal_value/100);
                price = isNullOrNan(finalBasePrice) + isNullOrNan(percentagePrice);

            }else{
                let lastIndexOfStock=product.stock.length-1;
                let refPrice=product.stock[lastIndexOfStock]? product.stock[lastIndexOfStock].ref_uom_price: '';
                percentagePrice=refPrice * (priceStage.cal_value/100);
                price = isNullOrNan(refPrice) + isNullOrNan(percentagePrice);
            }
        }
        let resultAfterUomChange=getPriceByUOM(parentDom,product,priceStage.min_qty,price);
        let qtyByPriceStage=resultAfterUomChange.resultQty;
        const uoms=product.uom.unit_category.uom_by_category;
        let inputUomId=parentDom.find('.invoice_unit_select').val();
        const inputUom =uoms.filter(function ($u) {
                return $u.id ==inputUomId;
        })[0];
        if (quantity >= qtyByPriceStage) {

            parentDom.find('.subtotal_price').text(pDecimal(resultAfterUomChange.resultPrice) );
            parentDom.find('input[name="selling_price[]"]').val(pDecimal(resultAfterUomChange.resultPrice));
            parentDom.find('input[name="each_selling_price"]').val(priceStage.pricelist_id);
            parentDom.find('.price_list_id').val(priceStage.id);
            return true;
        }else{
            let lastIndexOfStock=product.stock.length-1;
            let refPrice=product.stock[lastIndexOfStock]? product.stock[lastIndexOfStock].ref_uom_price: '';
            let result=pDecimal(refPrice * isNullOrNan( inputUom.value));
            parentDom.find('.uom_price').val(result);
        }
        return false;
    }
    function getPriceByUOM(parentDom,product,priceStageQty=1,priceStageCalVal=''){
        const uoms = product.uom.unit_category.uom_by_category;
        let inputUomId=parentDom.find('.invoice_unit_select').val();
        let uomIdForSale=product.uom_id;
        const uomForSale =uoms.filter(function ($u) {
                return $u.id ==uomIdForSale;
        })[0];

        const refUOM =uoms.filter(function ($u) {
                return $u.unit_type =="reference";
        })[0];

        return changeQtyOnUom2(uomIdForSale,inputUomId,priceStageQty,uoms,priceStageCalVal);
    }


    function changeQtyOnUom2(currentUomId, newUomId, currentQty,uoms,currentUomPrice='') {
        let newUomInfo = uoms.find((uomItem) => uomItem.id == newUomId);
        let currentUomInfo = uoms.find((uomItem) => uomItem.id == currentUomId);
        let refUomInfo = uoms.find((uomItem) => uomItem.unit_type =="reference");
        let currentRefQty = isNullOrNan(getReferenceUomInfoByCurrentUomQty(currentQty,currentUomInfo,refUomInfo).qtyByReferenceUom);
        let currentUomType = currentUomInfo.unit_type;
        let newUomType = newUomInfo.unit_type;
        let newUomRounded = newUomInfo.rounded_amount || 1;
        let newUomValue=newUomInfo.value;
        let currentUomValue=currentUomInfo.value;
        let resultQty;
        let resultPrice;

        if ( newUomType == 'bigger') {
            resultQty = currentRefQty / newUomInfo.value;
        } else if (newUomType == 'smaller') {
            resultQty = currentRefQty * newUomInfo.value;
        } else {
            resultQty = currentRefQty;
        }

        if(currentUomId==newUomId){
            resultPrice = currentUomPrice ;
            return {resultQty,resultPrice};
        }
        if (currentUomType == 'reference' && newUomType == 'smaller') {
            resultPrice = currentUomPrice /(newUomInfo.value * currentUomInfo.value);
        }else if (currentUomType == 'reference' && newUomType == 'bigger') {
            resultPrice = currentUomPrice * newUomValue;
        }else if (currentUomType == 'bigger' && newUomType == 'reference') {
            resultPrice = currentUomPrice / currentUomValue;
        }else if (currentUomType == 'bigger' && newUomType == 'bigger') {
            resultPrice = currentUomPrice *( newUomInfo / currentUomValue);
        }else if (currentUomType == 'smaller' && newUomType == 'bigger') {
            resultPrice = currentUomPrice * (currentUomValue * newUomValue) ;
        }else if (currentUomType == 'smaller' && newUomType == 'smaller') {
            resultPrice = currentUomPrice / newUomValue;
        }else if (currentUomType == 'smaller' && newUomType == 'reference') {
            resultPrice = currentUomPrice * currentUomValue ;
        }else{
            resultPrice = currentUomPrice  ;
        }
        return {resultQty,resultPrice};
    }
    const checkAvailableDate = (pricelist) => {
        let availableDate = false;
        const from_date = pricelist.from_date === null ? null : new Date(pricelist.from_date);
        const to_date = pricelist.to_date === null ? null : new Date(pricelist.to_date);
        const current_date = new Date();
        if (current_date >= from_date && current_date <= to_date) {
            availableDate = true;
        } else if(from_date === null && to_date === null){
            availableDate = true;
        }else if(from_date === null && current_date <= to_date){
            availableDate = true;
        }else if(current_date >= from_date && to_date === null){
            availableDate = true;
        }else {
            availableDate = false;
        }
        return availableDate;
    }
