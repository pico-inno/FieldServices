const getProducts = (generalDatas) => {
    let pricelist_id = generalDatas.pricelist_id;
    let product_vari_id = generalDatas.product_variation_id;
    let quantity = generalDatas.quantity;
    let current_uom = generalDatas.uom_id;

    if(pricelist_id === 'default_selling_price') return;
    // if(price_lists_with_location.length == 0) return;

    let products_with_pricelist = isProductInPricelistLocation(product_vari_id, pricelist_id);

    if(products_with_pricelist.length === 0){
        let base_price_arr = filterBasePriceIds(pricelist_id);
        let product = getProductFromBasePrice(product_vari_id, base_price_arr);
        if(product == undefined) return;
        // console.log(product)
        let price = getPriceOnMinQty(product, quantity, current_uom, product_vari_id);

        let price_info = {'price': price.price, 'price_id': price.pricelistId}
        return price_info;
    }else{
        let products = products_with_pricelist.filter( p => {
            if(checkAvailableDate(p)){
                if(Array.isArray(p.product_variation_id)){
                    return p.product_variation_id.find( id => id == product_vari_id);
                }
                return p.product_variation_id == product_vari_id;
            }
        });
        if(products == undefined) return;

        let price = getPriceOnMinQty(products, quantity, current_uom, product_vari_id);
        // console.log(price)
        let price_info;
        if(price.price == undefined || isNaN(price.price)){
            // item ရှိပေမယ့် min qty မပြည့်လို့ base price မှာ ဆက်ရှာ
            let base_price_arr = filterBasePriceIds(pricelist_id);
            let product = getProductFromBasePrice(product_vari_id, base_price_arr);
            if(product == undefined) return;

            let price = getPriceOnMinQty(product, quantity, current_uom, product_vari_id);
            if(price == undefined) return;

            price_info = {'price': price.price, 'price_id': price.pricelistId}
        }else{
            price_info = {'price': price.price, 'price_id': price.pricelistId}
        }

        return price_info;
    }
}

const getPriceOnMinQty = (product_lists, quantity, current_uom, product_vari_id) => {
    // console.log(product_lists)
    let minQtyByRefUoM = product_lists.map( item => qtyByReferenceUom(item.uom_id, item.min_qty));

    let sortedQtys = [...minQtyByRefUoM].sort((a, b) => b - a);

    let num = null;
    sortedQtys.forEach((element, index) => {
        if(quantity >= element && num == null){
            num = element;
        }
    });

    let findIndex = minQtyByRefUoM.findIndex( item => item == num );
    let priceByUoM = priceByUOM(product_lists[findIndex], current_uom, product_vari_id, quantity);

    if(product_lists.length == 0) return;
    let pricelistId = product_lists[0].pricelist_id;

    let ref_uom_price = priceByUoM ?. ref_uom_price;
    let current_uom_value = priceByUoM ?. current_uom_value;

    let price = parseInt(ref_uom_price) * parseInt(current_uom_value);

    return {price, pricelistId};
}

const priceByUOM = (pricelist , current_uom_id, product_vari_id, quantity) => {
    /*
        price ကို reference uom ရဲ့ price အဖြစ် ပြောင်းပြီးတော့ discount ဘာညာ တွက်ပေးပြီး return ပြန်ပေးတယ်။
        price ကို return ပြန်တယ်။ နောက်တခုက current uom ရဲ့ value ကို return ပြန်တယ်။
    */

    if(!pricelist) return;
    let calcType = pricelist.cal_type;
    let all_uoms = uoms;
    let uom_category = all_uoms.find( unit => unit.id == pricelist.uom_id).unit_category_id;
    let filter_uoms = all_uoms.filter( unit => unit.unit_category_id == uom_category);

    let reference_uom = filter_uoms.find( unit => unit.unit_type == 'reference');
    let reference_uom_type = reference_uom.unit_type;

    let pricelist_uom = filter_uoms.find( unit => unit.id == pricelist.uom_id);
    let pricelist_uom_type = pricelist_uom.unit_type;

    let current_uom = filter_uoms.find( unit => unit.id == current_uom_id);
    let current_uom_type = current_uom.unit_type;

    let ref_uom_price; // reference uom ၁ခုမှာ ကျမဲ့ စျေး
    if(calcType == 'fixed'){
        if(pricelist_uom_type == 'bigger'){
            ref_uom_price = pricelist.cal_value / pricelist_uom.value;
        }else if(pricelist_uom_type == 'smaller'){
            ref_uom_price = pricelist_uom.value * pricelist.cal_value;
        }else {
            ref_uom_price = pricelist.cal_value;
        }
    }
    if(calcType == 'percentage'){
        let isPercentResult = isPercent(pricelist, product_vari_id, quantity);
        let cal_value = isPercentResult.cal_value;
        let calPrice = isPercentResult.cal_price;

        let discount = cal_value < 0 ? calPrice * (Math.abs(cal_value) / 100) : 0;
        let profit = cal_value > 0 ? calPrice * (cal_value / 100) : 0;

        let price = parseInt(calPrice) + profit - discount;

        if(pricelist_uom_type == 'bigger'){
            ref_uom_price = price / pricelist_uom.value;
        }else if(pricelist_uom_type == 'smaller'){
            ref_uom_price = pricelist_uom.value * price;
        }else {
            ref_uom_price = price;
        }
    }

    return {
        'ref_uom_price' : ref_uom_price,
        'current_uom_value' : current_uom.value
    };
}

const filterBasePriceIds = (pricelist_id) => {
    /*
        current pricelist ကနေစပြီးတော့ ,
        တဆင့်ပြီး တဆင့် base လုပ်ထားတဲ့ pricelist id တွေကို စုပြီး array အနေနဲ့ return ပြန်ပေးတယ်။
    */
    let products = price_lists_with_location.products_with_pricelist;

    let base_price_arr = [];
    let id = pricelist_id;
    do {
        let filteredProduct = products.find(p => p.pricelist_id == id);
        if (!filteredProduct) break;
        base_price_arr.push(filteredProduct.base_price);
        id = filteredProduct.base_price;
    } while (true);

    return base_price_arr;
}

const getProductFromBasePrice = (variation_id, base_price_arr) => {
    /*
        base_price_arr မှာ လက်ရှိ pricelist id နဲ့ တဆင့်ပြီး တဆင့် base လုပ်ထားတဲ့ pricelsit id တွေ ရှိတယ်။
        အဲ့သည် base_price_arr ကို loop ပတ်ပြီး , variation တူပြီး ကိုက်ညီတဲ့ product နဲ့ base price 0 ကို တွေ့ရင် break လုပ်ပြီး return ပြန်ပေးတယ်။
    */
    let products = price_lists_with_location.products_with_pricelist;

    let price_lists = [];
    for (const element of base_price_arr) {
        let filterPricelists = products.filter(item => item.pricelist_id == element);

        let filterProduct = filterPricelists.filter( p => {
            if(checkAvailableDate(p)){
                if(Array.isArray(p.product_variation_id)){
                    return p.product_variation_id.find( id => id == variation_id);
                }

                return p.product_variation_id == variation_id;
            }
        });

        if(filterProduct.length !== 0){
            price_lists.push(filterProduct[0])
        }
        if (filterProduct.length != 0 && filterProduct[0].base_price == 0) {
            break;
        }
    }

    return price_lists;
}

const isProductInPricelistLocation = (productVariId, pricelist_id) => {
    // if(price_lists_with_location.length == 0) return;
    let filtered_products;
    if (price_lists_with_location.products_with_pricelist) {
        let product_with_pricelists = price_lists_with_location.products_with_pricelist.filter( item => item.pricelist_id == pricelist_id);

        filtered_products = product_with_pricelists.filter( product => {
            if(Array.isArray(product.product_variation_id)){
                return product.product_variation_id.find( id => id == productVariId);
            }

            return product.product_variation_id == productVariId;
        });

    }
        return filtered_products;
}

const checkAvailableDate = (product) => {
    let availableDate = false;
    const from_date = product.from_date === null ? null : new Date(product.from_date);
    const to_date = product.to_date === null ? null : new Date(product.to_date);
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

const qtyByReferenceUom = (uom_id, qty) => {
    /*
        current uom ပေါ် မူတည်ပြီး quantity ကို ref uom ရဲ့ quantity အဖြစ် ပြောင်းပေးတာ
        1box (bigger) => 10ကပ် (reference) => 10ခု (smaller)
        current uom က box (bigger) ဆိုရင် qty နဲ့ bigger uom ရဲ့ value နဲ့ မြှောက်တယ်။
        PS: qty က ref uom value ပြောင်းထားပီးသား, So => qty * 12
    */
    let all_uoms = uoms;
    let uom_category = all_uoms.find( unit => unit.id == uom_id).unit_category_id;
    let filter_uoms = all_uoms.filter( unit => unit.unit_category_id == uom_category);

    let reference_uom = filter_uoms.find( unit => unit.unit_type == 'reference');

    let current_uom = filter_uoms.find( unit => unit.id == uom_id);
    let current_uom_type = current_uom.unit_type;

    let count;
    if(current_uom_type == 'bigger'){
        count = qty * current_uom.value;
    }else if(current_uom_type == 'smaller'){
        count = qty / current_uom.value;
    }else {
        count = qty * reference_uom.value;
    }

    return count;
}

const checkProductExistenceAndQuantity = (pricelistId, productVariationId, quantity) => {
    /*
        current item က pricelist ထဲ ပါ, မပါ စစ်တယ်။
        ပါခဲ့ရင် current quantity က pricelist ထဲက minimum quantity ပြည့် မပြည့် စစ်တယ်။
        return => true or false; && return => base price Id;
    */

    let isAvailable = false;
    // let basePriceId;

    let productWithPricelists = price_lists_with_location.products_with_pricelist.filter( item => item.pricelist_id == pricelistId);

    let filteredProducts = productWithPricelists.filter( product => {
        if(checkAvailableDate(product)){
            if(Array.isArray(product.product_variation_id)){
                return product.product_variation_id.find( id => id == productVariationId);
            }

            return product.product_variation_id == productVariationId;
        }
    });

    // begin check quantity
    if(filteredProducts.length !== 0){
        // basePriceId = filteredProducts[0].base_price;

        let prepareResult = filteredProducts.map( item => {
            return quantity >= qtyByReferenceUom(item.uom_id, item.min_qty)
        });

        if(prepareResult.includes(true)){
            isAvailable = true;
        }
    }

    // let result = { 'isAvailable': isAvailable, 'basePriceId': basePriceId};

    // return result;
    return isAvailable;
}

const totalCountDiscount = (pricelist, product_vari_id, quantity) => {
    let products = price_lists_with_location.products_with_pricelist;
    let count = 0;
    let price;

    let base_price_arr = [];
    let id = pricelist.pricelist_id;
    do {
        let filteredProduct = products.find(p => p.pricelist_id == id);
        if (!filteredProduct) break;
        base_price_arr.push(filteredProduct.base_price);
        id = filteredProduct.base_price;
    } while (true);

    count += parseInt(pricelist.cal_value);
    // end တဖြတ်
    if(base_price_arr[0] == 0){
        let product = productsOnSelectData.find(item => item.variation_id == product_vari_id);
        price = product.stock[0]?product.stock[0].ref_uom_price:0;
    }

    for (const element of base_price_arr) {
        if(checkProductExistenceAndQuantity(element, product_vari_id, quantity)){
            let filterPricelists = products.filter(item => item.pricelist_id == element);

            let filterProduct = filterPricelists.filter( p => {
                if(Array.isArray(p.product_variation_id)){
                    return p.product_variation_id.find( id => id == product_vari_id);
                }

                return p.product_variation_id == product_vari_id;
            });
            count += parseInt(filterProduct[0].cal_value)

            let type = filterProduct[0].cal_type;
            if ((filterProduct.length != 0 && filterProduct[0].base_price == 0) || type == 'fixed') {
                let product = productsOnSelectData.find( item => item.variation_id == product_vari_id);
                price = product.stock[0]?product.stock[0].ref_uom_price:'';
                break;
            }
        }
    }

    let data = {
        'cal_value': count,
        'cal_price': price,
    }

    return data;
}

const isPercent = (pricelist, product_vari_id, quantity) => {

    let result;
    if(!checkProductExistenceAndQuantity(pricelist.pricelist_id, product_vari_id, quantity)) return;
    let basePriceId = pricelist.base_price;
    // console.log(pricelist)
    if(basePriceId == 0){
        if(checkProductExistenceAndQuantity(pricelist.pricelist_id, product_vari_id, quantity)){
            result = totalCountDiscount(pricelist, product_vari_id, quantity);
        }
    } else {
        result = totalCountDiscount(pricelist, product_vari_id, quantity);
    }
    // console.log(result)
    return result;
}

