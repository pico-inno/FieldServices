const getProducts = (generalDatas) => {
    let pricelist_id = generalDatas.pricelist_id;
    let product_vari_id = generalDatas.product_variation_id;
    let quantity = generalDatas.quantity;
    let current_uom = generalDatas.uom_id;
    
    if(pricelist_id === 'default_selling_price') return;
    
    let products_with_pricelist = isProductInPricelistLocation(product_vari_id, pricelist_id);
    // let products_with_pricelist = products.filter( item => item.pricelist_id == pricelist_id);    
    
    if(products_with_pricelist.length === 0){
        let base_price_arr = filterBasePriceIds(pricelist_id);
        let product = getProductFromBasePrice(product_vari_id, base_price_arr);
        if(product == undefined) return;
        let price = getPriceOnMinQty(product, quantity, current_uom);
        let price_info = {'price': price.price, 'price_id': price.pricelist_id}
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
        let price = getPriceOnMinQty(products, quantity, current_uom);
        let price_info;
        if(price.price == undefined || isNaN(price.price)){
            // item ရှိပေမယ့် min qty မပြည့်လို့ base price မှာ ဆက်ရှာ
            let base_price_arr = filterBasePriceIds(pricelist_id);
            let product = getProductFromBasePrice(product_vari_id, base_price_arr);
            if(product == undefined) return;
            
            let price = getPriceOnMinQty(product, quantity, current_uom);
            price_info = {'price': price.price, 'price_id': price.pricelist_id}
        }else{
            price_info = {'price': price.price, 'price_id': price.pricelist_id}
        }
        
        return price_info; 
    }
}

const getPriceOnMinQty = (product_lists, quantity, current_uom) => {
    // let price_lists = price_lists_with_location.price_lists;
    let min_qty_by_ref = product_lists.map( item => qtyByReferenceUom(item.uom_id, item.min_qty));
    
    // let min_qtys = product_lists.map(item => item.min_qty * 1);
    let sorted_qtys = [...min_qty_by_ref].sort((a, b) => b - a);
    
    let num = null;
    sorted_qtys.forEach((element, index) => {
        if(quantity >= element && num == null){
            num = element;
        }
    });
    
    let findIndex = min_qty_by_ref.findIndex( item => item == num );
    
    let price_by_uom = priceByUOM(product_lists[findIndex], current_uom);
    // console.log(price_by_uom)
    // let pricelist_id = product_lists[findIndex] ?. pricelist_id;
    let pricelist_id = product_lists[0].pricelist_id;
    
    let ref_uom_price = price_by_uom ?. ref_uom_price;
    let current_uom_value = price_by_uom ?. current_uom_value;
    let price = parseInt(ref_uom_price) * parseInt(current_uom_value);
    
    // let price = product_lists[findIndex] ?. price;
    
    return {price, pricelist_id};
}

const priceByUOM = (pricelist , current_uom_id) => {
    if(!pricelist) return;
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
    if(pricelist_uom_type == 'bigger'){
        ref_uom_price = pricelist.price / pricelist_uom.value;
    }else if(pricelist_uom_type == 'smaller'){
        ref_uom_price = pricelist_uom.value * pricelist.price;
    }else {
        ref_uom_price = pricelist.price;
    }

    return {
        'ref_uom_price' : ref_uom_price, 
        'current_uom_value' : current_uom.value
    };
}

const filterBasePriceIds = (pricelist_id) => {
    let products = price_lists_with_location.products_with_pricelist;
    let base_price_arr = [];
    let id = pricelist_id;
    do {
        let filteredProduct = products.find(p => p.pricelist_id == id);
        if (!filteredProduct || filteredProduct.base_price == null) break;
        base_price_arr.push(filteredProduct.base_price);
        id = filteredProduct.base_price;
    } while (true); 
    
    return base_price_arr;
}

const getProductFromBasePrice = (variation_id, base_price_arr) => {
    let products = price_lists_with_location.products_with_pricelist;
    let price_lists;
    for (const element of base_price_arr) {
        let filter_pricelists = products.filter(item => item.pricelist_id == element);
        
        let filter_product = filter_pricelists.filter( p => {
            if(checkAvailableDate(p)){
                if(Array.isArray(p.product_variation_id)){
                    return p.product_variation_id.find( id => id == variation_id);
                }
                
                return p.product_variation_id == variation_id;
            }
        });      
        
        if (filter_product.length != 0) {
          price_lists = filter_product;
          break;
        }
    }
    
    return price_lists;
}

const isProductInPricelistLocation = (productVariId, pricelist_id) => {
    let product_with_pricelists = price_lists_with_location.products_with_pricelist.filter( item => item.pricelist_id == pricelist_id);
    
    let filtered_products = product_with_pricelists.filter( product => {
        if(Array.isArray(product.product_variation_id)){
            return product.product_variation_id.find( id => id == productVariId);
        }
        
        return product.product_variation_id == productVariId;
    });

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
    let all_uoms = uoms;
    let uom_category = all_uoms.find( unit => unit.id == uom_id).unit_category_id;
    let filter_uoms = all_uoms.filter( unit => unit.unit_category_id == uom_category);

    let reference_uom = filter_uoms.find( unit => unit.unit_type == 'reference');
    let reference_uom_type = reference_uom.unit_type;

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