

    function changeQtyOnUom(currentUomInfo, newUomInfo, currentQty) {
        const currentRefQty =getReferenceUomInfoByCurrentUnitQty(currentQty,currentUomInfo.value,uom)['qtyByReferenceUom'];
        const newUomInfo = newUomInfo;
        const currentUomInfo = currentUomInfo;
        const currentUomType = currentUomInfo.unit_type;
        const newUomType = newUomInfo.unit_type;

        let result;
        if (currentUomType === 'smaller' && newUomType === 'bigger') {
            result = currentRefQty / newUomInfo.value;
        } else if (currentUomType === 'bigger' && newUomType === 'smaller') {
            result = currentRefQty * newUomInfo.value;
        } else if (currentUomType === 'reference' && newUomType === 'bigger') {
            result = currentRefQty / newUomInfo.value;
        } else if (currentUomType === 'reference' && newUomType === 'smaller') {
            result = currentRefQty * newUomInfo.value;
        } else if (currentUomType === 'bigger' && newUomType === 'bigger') {
            result = currentRefQty / newUomInfo.value;
        } else if (currentUomType === 'smaller' && newUomType === 'smaller') {
            result = currentRefQty * newUomInfo.value;
        } else {
            result = currentQty;
        }
        $('.current_stock_qty_txt').val(result);
        return result.toFixed(4);
    }

    function getReferenceUomInfoByCurrentUnitQty(qty, currentUnitId, uom) {
            const uom = uom.unit_category.uom_by_category;
            const currentUnit = uom.filter(function ($u) {
                return $u.id == currentUnitId;
            });
            const referenceUom =uom.filter(function ($u) {
                return $u.type == "reference";
            });

            const currentUnitType = currentUnit.unit_type;
            const currentUnitValue = currentUnit.value;
            const referenceUomId = referenceUom.id;
            const referenceRoundedAmount = referenceUom.rounded_amount || 4;
            const referenceValue = referenceUom.value;

            let result;
            if (currentUnitType === 'reference') {
                result = qty * referenceValue;
            } else if (currentUnitType === 'bigger') {
                result = qty * currentUnitValue;
            } else if (currentUnitType === 'smaller') {
                result = qty / currentUnitValue;
            } else {
                result = qty;
            }

            return {
                qtyByReferenceUom: result.toFixed(referenceRoundedAmount),
                referenceUomId: referenceUomId
            };
        }




