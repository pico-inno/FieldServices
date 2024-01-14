<?php

namespace Modules\ComboKit\Services;

use App\Helpers\UomHelper;
use App\Models\CurrentStockBalance;
use App\Models\lotSerialDetails;
use App\Models\Product\Product;
use App\Models\settings\businessSettings;
use App\Models\stock_history;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ComboKit\Entities\ReceipeOfMaterial;
use Modules\ComboKit\Entities\ReceipeOfMaterialDetail;
use Modules\Service\Entities\KitServiceSaleDetail;
use Modules\StockInOut\Entities\KitStockoutDetail;
use App\Models\kitSaleDetails;

class RoMService
{

    public static function createRomWithDetails($data){ //Use
        $romService = new RoMService();
        return $romService->create($data);
    }


    public static function updateRomWithDetails($id, $data){ //Use
        $romService = new RoMService();
        return $romService->update($id, $data);
    }

    public static function makeDefault($id){ //Use
        $romService = new RoMService();
        return $romService->updateDefaultTemplate($id);
    }

    /**
     * Check if a product is a kit.
     *
     * @param int $product_id The ID of the product to be checked.
     *
     * @return string|null Returns 'kit' if the product is a kit, otherwise returns null.
     */
    public static function isKit($product_id){
        $rom = new RoMService();
        return  $rom->checkKit($product_id);
    }

    /**
     * Retrieve the consume products details for a Combo/Kit.
     *
     * @param int $product_id The ID of the product.
     *
     * @return array The Combo/Kit products consume products details.
     */
    public static function getKitConsumeDetails($product_id){ //Use
        $romService = new RoMService();
        return $romService->kitConsumeDetails($product_id);
    }

    /**
     * Retrieve the available quantity for a Combo/Kit at a specific business location.
     *
     * This function returns the available quantity for a Combo/Kit based on the provided location ID and product ID.
     *
     * @param int $location_id The business location ID where the Combo/Kit availability is to be checked.
     * @param int $product_id The ID of the product.
     *
     * @return int The available quantity.
     */
    public static function getKitAvailableQty($location_id, $product_id){ //update
        $romService = new RoMService();
        return $romService->checkKitAvailableQty($location_id, $product_id);

    }

    /**
     * Create Transactions for Combo/Kit Product.
     *
     * This function creates Recipe of Material (ROM) transactions for a given kit_sale_details or kit_stock_out_details, current_stock_balance, lot_serial_details and stock_histories.
     *
     * @param int $kit_tx_id The ID of the sale details or stock_out details.
     * @param string $kit_tx_type The type of kit transaction (e.g., 'kit_sale_details' or 'kit_stock_out_details').
     * @param int $location_id The business location ID.
     * @param int $product_id The product ID associated with the sale_details or stock_out_details.
     * @param int $applied_variation_id The variation ID associated with the sale_details or stock_out_details.
     * @param float $decrease_qty The quantity to decrease, either sales quantity or stock_out quantity.
     * @param int $uom_id The unit of measure ID, either sales uom_id or stock_out uom_id.
     * @return string A descriptive message or identifier for the created ROM Transactions.
     *
     * @return string A message indicating the success or details of any exception thrown.
     *
     * @throws \Exception If there is an issue during the transaction creation process.
     */
    public static function createRomTransactions($kit_tx_id, $kit_tx_type, $location_id, $product_id, $applied_variation_id, $decrease_qty, $uom_id) { //Use
        $romService = new RoMService();
        $availableQty = $romService->checkKitAvailableQty($location_id, $product_id);

        if ($availableQty >= $decrease_qty) {
            $romService->createRomTxProcess($kit_tx_id, $kit_tx_type, $location_id, $product_id, $applied_variation_id, $decrease_qty, $uom_id);
            return 'success';
        }

        return 'insufficient stock';
    }

    /**
     * Update Transactions for Combo/Kit Product.
     *
     *  This function update Recipe of Material (ROM) transactions for a given kit_sale_details or kit_stock_out_details, current_stock_balance, lot_serial_details and stock_histories.
     *
     * @param int $kit_tx_id The ID of the sale_details or stock_out_details.
     * @param string $kit_tx_type The type of transaction: 'kit_sale_details' or 'kit_stock_out_details'.
     * @param int $location_id The business location ID.
     * @param int $product_id The product ID associated with the sale_details or stock_out_details, if needed.
     * @param int $applied_variation_id The variation ID associated with the sale_details or stock_out_details, if needed.
     * @param float $update_qty The quantity to be updated for sales quantity or stock_out quantity.
     * @param int $update_uom_id The unit of measure ID to be updated for sales uom_id or stock_out uom_id.
     * @param float $old_qty The quantity before the update for sales quantity or stock_out quantity.
     * @param int $old_uom_id The unit of measure ID before the update for sales uom_id or stock_out uom_id.
     *
     * @return string A descriptive message indicating the success or failure of the update.
     *
     * @throws \Exception If an error occurs during the update process.
     */
    public static function updateRomTransactions($kit_tx_id, $kit_tx_type, $location_id, $product_id, $applied_variation_id, $update_qty, $update_uom_id, $old_qty, $old_uom_id){
        $romService = new RoMService();
        $romService->updateRomTxProcess($kit_tx_id, $kit_tx_type, $location_id, $product_id, $applied_variation_id, $update_qty, $update_uom_id, $old_qty, $old_uom_id);
        return 'success';

    }

    public static function removeRomTransactions($kit_tx_id, $kit_tx_type){

        if($kit_tx_type == 'kit_stock_out_detail'){
            $recordQuerys =  KitStockoutDetail::where('stockout_details_id', $kit_tx_id)->get();
        }elseif($kit_tx_type == 'kit_sale_detail'){
            $recordQuerys = kitSaleDetails::where('sale_details_id', $kit_tx_id)->get();
        }elseif ($kit_tx_type == 'kit_service_used_product_detail'){
            $recordQuerys = KitServiceSaleDetail::where('service_used_product_id', $kit_tx_id)->get();
        }

        foreach ($recordQuerys as $recordQuery){

            $query = lotSerialDetails::where('transaction_detail_id', $recordQuery['id']);

            if ($kit_tx_type == 'kit_stock_out_detail'){
                $restoreLotSerialDetails = $query->where('transaction_type', 'kit_stock_out_detail')->get();
            }elseif($kit_tx_type == 'kit_sale_detail'){
                $restoreLotSerialDetails = $query->where('transaction_type', 'kit_sale_detail')->get();
            }elseif ($kit_tx_type == 'kit_service_used_product_detail'){
                $restoreLotSerialDetails = $query->where('transaction_type', 'kit_service_used_product_detail')->get();
            }


            foreach ($restoreLotSerialDetails as $restoreLotSerialDetail){
                $currentStockBalance = CurrentStockBalance::where('id', $restoreLotSerialDetail->current_stock_balance_id)->first();
                $currentStockBalance->current_quantity += $restoreLotSerialDetail->uom_quantity;
                $currentStockBalance->save();
                $restoreLotSerialDetail->delete();
            }

            $recordQuery->delete();
        }

        return 'success';

    }


    //


    public function getAllRoMDetail($id = null){
        $query = ReceipeOfMaterialDetail::with([
            'applied_variation'=>function($q){
                $q->select('id','variation_template_value_id')
                    ->with(['variationTemplateValue' => function ($query) {
                        $query->select('id', 'name', 'variation_template_id')
                            ->with(['variationTemplate:id,name']);
                    }]);
            },
            'productVariation'=>function($q){
                $q->select('id','product_id','variation_template_value_id')
                    ->with(
                        [
                            'variationTemplateValue'=>function($q){
                                $q->select('id','name');
                            },
                            'product'=>function($q){
                                $q->with([
                                    'uom'=>function($q){
                                        $q->with(['unit_category' => function ($q) {
                                            $q->with('uomByCategory');
                                        }]);
                                    }
                                ]);
                            }
                        ]);
            },
        ]);

        if ($id !== null) {
            $query->where('receipe_of_material_id', $id);
        }

        return $query->get();
    }


    public function getAllProducts()
    {
        $all_product = Product::where('product_type', 'consumeable')->with(['rom','productVariations' => function ($query) {
            $query->select('id', 'product_id', 'variation_template_value_id', 'variation_sku')
                ->with(['variationTemplateValue' => function ($query) {
                    $query->select('id', 'name', 'variation_template_id')
                        ->with(['variationTemplate:id,name']);
                }]);
        },
        ])->select('id', 'name', 'product_code', 'sku', 'product_type','has_variation', 'uom_id', 'purchase_uom_id', 'receipe_of_material_id')
            ->get()->toArray();



        return $all_product;
    }

    public function getProductDetails(){
        $product_details = Product::where('product_type', 'consumeable')->with(['productVariations' => function ($query) {
            $query->select('id', 'product_id', 'variation_template_value_id', 'variation_sku')
                ->with(['variationTemplateValue' => function ($query) {
                    $query->select('id', 'name', 'variation_template_id')
                        ->with(['variationTemplate:id,name']);
                }]);
        },
        ])->select('id', 'name', 'product_code', 'sku', 'product_type','has_variation', 'uom_id', 'purchase_uom_id')
            ->get()->toArray();



        return $product_details;
    }


    public function create($data)
    {
        try {
            DB::beginTransaction();

            $recipeOfMaterialData = [
                'name' => $data->name,
                'rom_type' => $data->rom_type,
                'product_id' => $data->product_id,
                'quantity' => 1, //$data->quantity
                'uom_id' => $data->uom_id,
                'created_at' => now(),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'updated_at' => now(),
            ];

            $recipeOfMaterial = ReceipeOfMaterial::create($recipeOfMaterialData);

            if ($data->default_template) {
                Product::find($data->product_id)->update([
                    'receipe_of_material_id' => $recipeOfMaterial->id
                ]);
            }

            $this->createDetails($recipeOfMaterial->id, $data->consume_details);

            DB::commit();

            return $recipeOfMaterial->with('rom_details');
        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
        }

    }

    public function createDetails($rom_id ,array $datas){
        try {
            foreach ($datas as $data) {
                ReceipeOfMaterialDetail::create([
                    'receipe_of_material_id' => $rom_id,
                    'component_variation_id' => $data['component_variation_id'],
                    'applied_variation_id' => $data['applied_variation_id'],
                    'quantity' => $data['quantity'],
                    'uom_id' => $data['uom_id'],
                    'created_at' => now(),
                    'created_by' => Auth::id(),
                    'updated_at' => now(),
                    'updated_by' => Auth::id(),
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update($id, $data){
        try {
            DB::beginTransaction();

            $recipeOfMaterialData = [
                'name' => $data->name,
                'rom_type' => $data->rom_type,
                'product_id' => $data->product_id,
                'quantity' => 1, // $data->quantity
                'uom_id' => $data->uom_id,
                'updated_by' => Auth::id(),
                'updated_at' => now(),
            ];

            $recipeOfMaterial =  ReceipeOfMaterial::where('id',$id)->update($recipeOfMaterialData);

            if ($data->default_template) {
                Product::find($data->product_id)->update([
                    'receipe_of_material_id' => $id
                ]);
            }

            $this->updateDetails($id, $data->consume_details);

            DB::commit();
            return $recipeOfMaterial;
        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
        }
    }
    public function updateDetails($id, $datas){

        try {
            $romDetailId = array_filter($datas, function ($item) {
                return isset($item['rom_detail_id']);
            });

            $oldDetailsIds = array_column($romDetailId, 'rom_detail_id');

            ReceipeOfMaterialDetail::where('receipe_of_material_id', $id)
                ->whereNotIn('id', $oldDetailsIds)
                ->delete();

            foreach ($romDetailId as $data) {
                ReceipeOfMaterialDetail::where('receipe_of_material_id', $id)
                    ->where('id', $data['rom_detail_id'])
                    ->update([
                        'component_variation_id' => $data['component_variation_id'],
                        'applied_variation_id' => $data['applied_variation_id'],
                        'quantity' => $data['quantity'],
                        'uom_id' => $data['uom_id'],
                        'updated_at' => now(),
                        'updated_by' => Auth::id(),
                    ]);
            }

            $detailsWithoutRoMnId = array_filter($datas, function ($item) {
                return !isset($item['rom_detail_id']);
            });

            $this->createDetails($id,$detailsWithoutRoMnId);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function updateDefaultTemplate($id){ //use
        $receipeOfMaterial = ReceipeOfMaterial::where('id',$id)->first();
        if ($receipeOfMaterial) {
            Product::find($receipeOfMaterial->product_id)->update([
                'receipe_of_material_id' => $receipeOfMaterial->id
            ]);
        }
        return;
    }

    public function destroy($id){
        DB::beginTransaction();

        try {
            ReceipeOfMaterial::where('id', $id)->delete();

            ReceipeOfMaterialDetail::where('receipe_of_material_id', $id)->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAllRoM($id = null){
        if ($id == null){
            $query = ReceipeOfMaterial::where('rom_type', 'kit')->with('product')->get();
        }else{
            $query = ReceipeOfMaterial::where('rom_type', 'kit')->with([
                'product'=>function($q){
                    $q->with([
                        'productVariations',
                        'uom'=>function($q){
                            $q->with(['unit_category' => function ($q) {
                                $q->with('uomByCategory');
                            }]);
                        }
                    ]);
                }
            ])->where('id',$id)->get();
        }

        foreach ($query as $q){
            $q->status = optional($q->product)->receipe_of_material_id === $q->id ? 'Default' : '';
        }
        return $query;
    }

    private function checkKitAvailableQty($location_id, $product_id){
        $romService = new RoMService();
        $data = $romService->getKitConsumeProductsDetailsWithAvaQty($location_id, $product_id);
        $qty = [];


        foreach ($data as $item){

            if ($item['product_type'] !== 'service'){
                if ($item['is_kit'] === 'kit'){
                    $kitQty =  self::getKitAvailableQty($location_id, $item['product_id']);

                    $qty[] = $kitQty;
                }else{
                    $qty[] = $item['available_current_quantity'];
                }

            }

        }

        return min($qty);
    }

    private function kitConsumeDetails($product_id){

        $product = Product::where('id', $product_id)->with('rom')->first();

        $receipeOfMeterialDetails = ReceipeOfMaterialDetail::where('receipe_of_material_id', $product->rom->id)
            ->with([
                'uom',
                'product'=>function($q){
                    $q->with([
                        'productVariations',
                    ]);
                }
            ])
            ->get();
        $results =[];
        foreach ($receipeOfMeterialDetails as $receipeOfMeterialDetail) {

            $isKit = $this->checkKit($receipeOfMeterialDetail->product->id);

            $lableName = $receipeOfMeterialDetail->product->name.' x '.$receipeOfMeterialDetail->quantity.''.$receipeOfMeterialDetail->uom->short_name;

            $data = [
                'id' => $receipeOfMeterialDetail->id,
                'label' => $lableName,
                'receipe_of_material_id' => $receipeOfMeterialDetail->receipe_of_material_id,
                'component_variation_id' => $receipeOfMeterialDetail->component_variation_id,
                'product_id' => $receipeOfMeterialDetail->product->id,
                'original_product_type' => $receipeOfMeterialDetail->product->product_type,
                'product_name' => $receipeOfMeterialDetail->product->name,
                'applied_variation_id' => $receipeOfMeterialDetail->applied_variation_id,
                'quantity' => $receipeOfMeterialDetail->quantity,
                'uom_id' => $receipeOfMeterialDetail->uom_id,
                'uom_name' => $receipeOfMeterialDetail->uom->name,
                'uom_short_name' => $receipeOfMeterialDetail->uom->short_name,
                'product_type' => $isKit,
            ];

            $results[] = $data;
        }

        return $results;
    }


    private function createRomTxProcess($kit_tx_id, $kit_tx_type, $location_id, $product_id, $applied_variation_id, $decrease_qty, $uom_id){ //use



        $product = Product::where('id', $product_id)->with('rom')->first();


        $total_decrease_amount = $decrease_qty;
        if($uom_id != $product['uom_id']){
            $total_decrease_amount = UomHelper::changeQtyOnUom($uom_id, $product['uom_id'], $decrease_qty);
        };


        $kitConsumeDetails = $this->kitConsumeDetails($product_id);
        if ($kit_tx_type == 'kit_stock_out_detail'){
            $recordClass = KitStockoutDetail::class;
        }elseif($kit_tx_type == 'kit_sale_detail'){
            $recordClass = kitSaleDetails::class;
        }elseif ($kit_tx_type == 'kit_service_used_product_detail'){
            $recordClass = KitServiceSaleDetail::class;
        }
//
        foreach ($kitConsumeDetails as $kitConsumeDetail) {

            $consume_ref_qty = UomHelper::getReferenceUomInfoByCurrentUnitQty($kitConsumeDetail['quantity'], $kitConsumeDetail['uom_id'])['qtyByReferenceUom'];


            if ($kitConsumeDetail['product_type'] == 'kit'){
                $this->createRomTxProcess($kit_tx_id, $kit_tx_type, $location_id, $kitConsumeDetail['product_id'], $kitConsumeDetail['applied_variation_id'], $kitConsumeDetail['quantity'], $kitConsumeDetail['uom_id']);
            }else{
                if ($kitConsumeDetail['applied_variation_id'] == $applied_variation_id || $kitConsumeDetail['applied_variation_id'] == null) {

                    $qtyToDecrease = $total_decrease_amount * $consume_ref_qty;


                    $kitRecordTx = $recordClass::create([
                        'stockout_details_id' => ($kit_tx_type == 'kit_stock_out_detail') ? $kit_tx_id : null,
                        'sale_details_id' => ($kit_tx_type == 'kit_sale_detail') ? $kit_tx_id : null,
                        'service_used_product_id' => ($kit_tx_type == 'kit_service_used_product_detail') ? $kit_tx_id : null,
                        'product_id' => $kitConsumeDetail['product_id'],
                        'variation_id' => $kitConsumeDetail['component_variation_id'],
                        'uom_id' => $kitConsumeDetail['uom_id'],
                        'quantity' => $kitConsumeDetail['quantity'] * $total_decrease_amount,//5
                    ]);

                    if ($kitConsumeDetail['original_product_type'] !== "service"){

                        $currentBalances = CurrentStockBalance::where('business_location_id', $location_id)
                            ->where('variation_id', $kitConsumeDetail['component_variation_id'])
                            ->where('current_quantity', '>', 0)
                            ->when(getSettingsValue('accounting_method') == 'fifo', function ($query) {
                                return $query->orderBy('id');
                            }, function ($query) {
                                return $query->orderByDesc('id');
                            })
                            ->get();

                        foreach ($currentBalances as $balance) {
                            $stockQty = $balance->current_quantity;
                            $stock_history_data = [
                                'business_location_id' => $location_id,
                                'product_id' => $balance['product_id'],
                                'variation_id' => $balance['variation_id'],
                                'lot_serial_no' => $balance['lot_serial_no'],
                                'expired_date' => $balance['expired_date'],
                                'transaction_type' => ($kit_tx_type == 'kit_stock_out_detail') ? 'stock_out' : 'sale',
                                'transaction_details_id' => $kit_tx_id,
                                'increase_qty' => 0,
                                'decrease_qty' => $qtyToDecrease,
                                'ref_uom_id' => $balance['ref_uom_id'],
                            ];
                            stock_history::create($stock_history_data);
                            try {
                                if ($qtyToDecrease >= $stockQty) {
                                    $this->updateOrCreateLotSerialDetails(null, $kitRecordTx->id, $balance, $kit_tx_type, $stockQty);

                                    $balance->update(['current_quantity' => 0]);
                                    $qtyToDecrease -= $stockQty;
                                } else {
                                    $leftStockQty = $stockQty - $qtyToDecrease;
                                    $balance->update(['current_quantity' => $leftStockQty]);

                                    $this->updateOrCreateLotSerialDetails(null, $kitRecordTx->id, $balance, $kit_tx_type, $qtyToDecrease);
                                    break;
                                }
                            } catch (\Exception $e) {

                                $kitRecordTx->delete();
                                throw $e;
                            }
                        }
                    }
                }
            }
        }
    }

    private function updateRomTxProcess($kit_tx_id, $kit_tx_type, $location_id, $product_id, $applied_variation_id, $update_qty, $update_uom_id, $old_qty, $old_uom_id){

        if($kit_tx_type == 'kit_stock_out_detail'){
            $recordQuerys =  KitStockoutDetail::where('stockout_details_id', $kit_tx_id)->get();
        }elseif($kit_tx_type == 'kit_sale_detail'){
            $recordQuerys = kitSaleDetails::where('sale_details_id', $kit_tx_id)->get();
        }elseif ($kit_tx_type == 'kit_service_used_product_detail'){
            $recordQuerys = KitServiceSaleDetail::where('service_used_product_id', $kit_tx_id)->get();
        }

        $product = Product::where('id', $product_id)->with('rom')->first();

        foreach ($recordQuerys as $recordQuery){

            $currentBalances = CurrentStockBalance::where('business_location_id', $location_id)
                ->where('variation_id', $recordQuery['variation_id'])
                ->where('current_quantity', '>', 0)
                ->when(getSettingsValue('accounting_method') == 'fifo', function ($query) {
                    return $query->orderBy('id');
                }, function ($query) {
                    return $query->orderByDesc('id');
                })
                ->get();

            $update_ref_qty = $update_qty; //3
            if ($update_uom_id != $product['uom_id']) {
                $update_ref_qty = UomHelper::changeQtyOnUom($update_uom_id, $product['uom_id'], $update_ref_qty);
            };

            //oldQty
            $old_ref_qty = $old_qty; //5
            if ($old_uom_id != $product['uom_id']) {
                $old_ref_qty = UomHelper::changeQtyOnUom($old_uom_id, $product['uom_id'], $old_ref_qty);
            };


            $reCalcConsumeQty = $recordQuery['quantity'] / $old_ref_qty; //3



            if ($old_ref_qty > $update_ref_qty){
                $updateAbleQty = $old_ref_qty - $update_ref_qty;
                $qtyToIncrease = $updateAbleQty * $reCalcConsumeQty;
                $recordQuery['quantity'] = $reCalcConsumeQty * $update_ref_qty;
                $recordQuery->save();

                stock_history::where('transaction_type', ($kit_tx_type == 'kit_stock_out_detail') ? 'stock_out' : 'sale')
                    ->where('transaction_details_id', $kit_tx_id)
                    ->decrement('decrease_qty', $qtyToIncrease);

                if ($kit_tx_type == 'kit_stock_out_detail') {
                    $lotSerialDetailsQry = lotSerialDetails::where('transaction_detail_id', $recordQuery['id'])
                        ->where('transaction_type', 'kit_stock_out_detail');
                } elseif($kit_tx_type == 'kit_sale_detail') {
                    $lotSerialDetailsQry = lotSerialDetails::where('transaction_detail_id', $recordQuery['id'])
                        ->where('transaction_type', 'kit_sale_detail');
                }elseif($kit_tx_type == 'kit_service_used_product_detail') {
                    $lotSerialDetailsQry = lotSerialDetails::where('transaction_detail_id', $recordQuery['id'])
                        ->where('transaction_type', 'kit_service_used_product_detail');
                }

                $lotSerialDetails=$lotSerialDetailsQry->when(getSettingsValue('accounting_method') == 'fifo', function ($query) {
                    return $query->orderByDesc('current_stock_balance_id');
                }, function ($query) {
                    return $query->orderBy('current_stock_balance_id');
                })->get();


                foreach ($lotSerialDetails as $lotSerialDetail) {
                    $stockQty = $lotSerialDetail->uom_quantity;

                    if ($qtyToIncrease >= $stockQty) {
                        $qtyToIncrease -= $stockQty;
                        $currentStockBalance = CurrentStockBalance::where('id', $lotSerialDetail->current_stock_balance_id)->first();
                        $currentStockBalance->current_quantity += $stockQty;
                        $currentStockBalance->save();

                        $lotSerialDetail->delete();

                        if ($qtyToIncrease == 0) {
                            break;
                        }
                    } elseif ($stockQty > $qtyToIncrease) {
                        $currentStockBalance = CurrentStockBalance::where('id', $lotSerialDetail->current_stock_balance_id)->first();
                        $currentStockBalance->current_quantity += $qtyToIncrease;
                        $currentStockBalance->save();

                        $lotSerialDetail->uom_quantity -= $qtyToIncrease;
                        $lotSerialDetail->save();
                        break;
                    } else {
                        break;
                    }

                }

            }elseif ($update_ref_qty > $old_ref_qty){
                $updateAbleQty = $update_ref_qty - $old_ref_qty;
                $qtyToDecrease = $updateAbleQty * $reCalcConsumeQty;
                $recordQuery['quantity'] = $reCalcConsumeQty * $update_ref_qty;
                $recordQuery->save();


                stock_history::where('transaction_type', ($kit_tx_type == 'kit_stock_out_detail') ? 'stock_out' : 'sale')
                    ->where('transaction_details_id', $kit_tx_id)
                    ->increment('decrease_qty', $qtyToDecrease);

                foreach ($currentBalances as $balance) {
                    $stockQty = $balance->current_quantity;

                    $lotSerialDetails = lotSerialDetails::where('transaction_detail_id', $recordQuery['id'])
                        ->where('transaction_type', 'kit_stock_out_detail')
                        ->when(getSettingsValue('accounting_method') == 'fifo', function ($query) {
                            return $query->orderByDesc('current_stock_balance_id');
                        }, function ($query) {
                            return $query->orderBy('current_stock_balance_id');
                        })
                        ->first();

                    try {
                        if ($qtyToDecrease >= $stockQty) {
                            $this->updateOrCreateLotSerialDetails($lotSerialDetails, $recordQuery['id'], $balance, $kit_tx_type, $stockQty);

                            $balance->update(['current_quantity' => 0]);
                            $qtyToDecrease -= $stockQty;
                        } else {
                            $leftStockQty = $stockQty - $qtyToDecrease;
                            $balance->update(['current_quantity' => $leftStockQty]);

                            $this->updateOrCreateLotSerialDetails($lotSerialDetails, $recordQuery['id'], $balance, $kit_tx_type, $qtyToDecrease);
                            break;
                        }
                    } catch (\Exception $e) {
                        throw $e;
                    }
                }
            }

        }
    }


    private function getKitConsumeProductsDetailsWithAvaQty($location_id, $product_id){
        $romService = new RoMService();

        $rom = Product::where('id', $product_id)->with('rom')->first()->rom;

        if ($rom) {

            if ($rom->rom_type === 'kit') {

                $recipeOfMaterialDetail = ReceipeOfMaterialDetail::where('receipe_of_material_details.receipe_of_material_id', $rom->id)
                    ->leftJoin('product_variations', 'receipe_of_material_details.component_variation_id', '=', 'product_variations.id')
                    ->leftJoin('products', 'product_variations.product_id', '=', 'products.id')
                    ->select(
                        'receipe_of_material_details.id',
                        'receipe_of_material_details.receipe_of_material_id',
                        'receipe_of_material_details.component_variation_id',
                        'receipe_of_material_details.applied_variation_id',
                        'receipe_of_material_details.quantity',
                        'receipe_of_material_details.uom_id',
                        'product_variations.product_id',
                        'products.product_type'
                    )
                    ->get();



                $variationIds = $recipeOfMaterialDetail->pluck('component_variation_id')->toArray();
                $productIds = $recipeOfMaterialDetail->pluck('product_id')->toArray();


                $currentStockBalances = CurrentStockBalance::where('business_location_id', $location_id)
                    ->whereIn('variation_id', $variationIds)
                    ->get()
                    ->groupBy('variation_id')
                    ->map(function ($csb) {
                        return [
                            'total_current_quantity' => $csb->sum('current_quantity')
                        ];
                    });

                $result = $recipeOfMaterialDetail->map(function ($item) use ($currentStockBalances, $romService, $rom) {

                    $kitCheckResult = $romService->checkKit($item['product_id']);
                    $totalCSB = $currentStockBalances[$item['component_variation_id']]['total_current_quantity'] ?? 0;
                    $item['total_current_quantity'] = $totalCSB;

                    $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($item['quantity'], $item['uom_id']);
                    $consumeQty = $referenceUomInfo['qtyByReferenceUom'];

                    $romQty = $rom->quantity * $consumeQty; //update template to rom qty


                    $item['available_current_quantity'] = floor($romQty !== 0 ? $totalCSB / $romQty : 0);
                    $item['is_kit'] = $kitCheckResult;

                    return $item;
                });



                return $result;
            }

            return [];
        }

        return [];

    }

    private function checkKit($product_id){
        $rom = Product::find($product_id)->rom;

        return $rom ? 'kit' : null;
    }


    private function updateOrCreateLotSerialDetails($checkLotDetail, $kit_tx_id, $balance, $kit_tx_type, $qty)
    {
        if ($checkLotDetail) {
            $checkLotDetail->uom_quantity += $qty;
            $checkLotDetail->save();
        } else {
            lotSerialDetails::create([
                'transaction_detail_id' => $kit_tx_id,
                'transaction_type' => $kit_tx_type,
                'current_stock_balance_id' => $balance['id'],
                'uom_id' => $balance['ref_uom_id'],
                'uom_quantity' => $qty
            ]);
        }
    }




}
