<?php

namespace App\Services\Stock;

use App\Helpers\UomHelper;
use App\Models\CurrentStockBalance;
use App\Models\lotSerialDetails;
use App\Repositories\CurrentStockBalanceRepository;
use App\Repositories\LotSerialDetailsRepository;
use App\Repositories\Stock\StockAdjustmentRepository;
use App\Repositories\Stock\StockHistoryRepository;
use App\Services\packaging\packagingServices;
use App\Services\stockhistory\stockHistoryServices;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentServices
{
    private $stockAdjustmentRepository;
    private $currentStockBalanceRepository;
    private $packagingServices;
    private $stockHistoryRepository;
    private $lotSerialDetailsRepository;
    public function __construct(
        StockAdjustmentRepository $stockAdjustmentRepository,
        CurrentStockBalanceRepository $currentStockBalanceRepository,
        packagingServices $packagingServices,
        StockHistoryRepository $stockHistoryRepository,
        LotSerialDetailsRepository $lotSerialDetailsRepository,
    )
    {
        $this->stockAdjustmentRepository = $stockAdjustmentRepository;
        $this->currentStockBalanceRepository = $currentStockBalanceRepository;
        $this->packagingServices = $packagingServices;
        $this->stockHistoryRepository = $stockHistoryRepository;
        $this->lotSerialDetailsRepository = $lotSerialDetailsRepository;
    }

    public function create($request)
    {
        if ($request->status === 'completed'){
            $status = true;
        }else{
            $status = false;
        }

        $adjustmentDetails = $request->adjustment_details;

         $preparedAdjustmentData = $this->prepareAdjustment($request);
         $preparedAdjustmentData['adjustment_voucher_no'] =stockAdjustmentVoucherNo();
         $preparedAdjustmentData['business_location'] = $request->business_location;
         $preparedAdjustmentData['created_at'] = now();
         $preparedAdjustmentData['created_by'] = Auth::id();
         $createdStockAdjustment = $this->stockAdjustmentRepository->create($preparedAdjustmentData);

        $this->createAdjustmentDetail(
            $adjustmentDetails,
            $request->business_location,
            $status,
            $createdStockAdjustment,
        );


    }

    public function update($id, $request)
    {
        if ($request->status === 'completed'){
            $status = true;
        }else{
            $status = false;
        }
        $adjustmentDetails = $request->adjustment_details;

        $preparedAdjustmentData = $this->prepareAdjustment($request);
        $preparedAdjustmentData['updated_at'] = now();
        $updatedStockAdjustment = $this->stockAdjustmentRepository->update($id, $preparedAdjustmentData);

        $existingAdjustmentDetails = array_filter($adjustmentDetails, fn($detail) => isset($detail['adjustment_detail_id']));
        $newAdjustmentDetails = array_filter($adjustmentDetails, fn($detail) => !isset($detail['adjustment_detail_id']));
        $existingAdjustmentDetailIdsToDelete = array_column($existingAdjustmentDetails, 'adjustment_detail_id');

        //Existing update session
        foreach ($existingAdjustmentDetails as $adjustmentDetail){
            $adjustmentType = $adjustmentDetail['adj_quantity'] < 0 ? 'decrease' : 'increase';
            $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($adjustmentDetail['adj_quantity'], $adjustmentDetail['uom_id']);
            $adjustQty = $referenceUomInfo['qtyByReferenceUom'];

            $currentStockBalances = $this->currentStockBalanceRepository->query()
                ->where('business_location_id', $request->business_location)
                ->where('product_id',$adjustmentDetail['product_id'])
                ->where('variation_id', $adjustmentDetail['variation_id'])
                ->where('current_quantity', '>', 0)
                ->latest()
                ->first();

            $subtotal = $status ? $currentStockBalances->ref_uom_price * $adjustQty : 0;

            $this->packagingServices->updatePackagingForTx($adjustmentDetail,$adjustmentDetail['adjustment_detail_id'],'adjustment');

            $preparedAdjustmentDetailData = $this->prepareAdjustmentDetail($adjustmentDetail);
            $preparedAdjustmentDetailData['adjustment_type'] = $adjustmentType;
            $preparedAdjustmentDetailData['uom_price'] = $status ? $currentStockBalances->ref_uom_price : 0;
            $preparedAdjustmentDetailData['subtotal'] = $subtotal;
            $preparedAdjustmentDetailData['updated_at'] = now();
            $preparedAdjustmentDetailData['updated_by'] = Auth::id();
            $updatedStockAdjustmentDetail = $this->stockAdjustmentRepository->updateDetail($adjustmentDetail['adjustment_detail_id'], $preparedAdjustmentDetailData);

            $this->completeTransaction(
                $status,
                $adjustmentDetail,
                $request->business_location,
                $adjustmentDetail['adjustment_detail_id'],
                $adjustmentType,
                $referenceUomInfo,
                $currentStockBalances->ref_uom_price,
                $this->stockAdjustmentRepository->query()->where('id', $id)->first(),
                $updatedStockAdjustmentDetail,
                $subtotal
            );

        }
        //Existing update session

        //New row create session
        $this->createAdjustmentDetail(
            $newAdjustmentDetails,
            $request->business_location,
            $status,
            $this->stockAdjustmentRepository->query()->where('id', $id)->first(),
        );
        //New row create session

        //Delete removed row
        $this->stockAdjustmentRepository->queryDetails()
            ->where('adjustment_id', $id)
            ->whereNotIn('id', $existingAdjustmentDetailIdsToDelete)
            ->update([
                'is_delete' => true,
                'deleted_by' => Auth::id(),
            ]);

        $this->stockAdjustmentRepository->queryDetails()
            ->where('adjustment_id', $id)
            ->whereNotIn('id', $existingAdjustmentDetailIdsToDelete)
            ->delete();
        //Delete removed row

    }


    protected function createAdjustmentDetail(
        $adjustmentDetails,
        $locationId,
        $status,
        $createdStockAdjustment,
    )
    {
        foreach ($adjustmentDetails as $adjustmentDetail) {
            $adjustmentType = $adjustmentDetail['adj_quantity'] < 0 ? 'decrease' : 'increase';
            $referenceUomInfo = UomHelper::getReferenceUomInfoByCurrentUnitQty($adjustmentDetail['adj_quantity'], $adjustmentDetail['uom_id']);
            $adjustQty = $referenceUomInfo['qtyByReferenceUom'];

            $currentStockBalances = $this->currentStockBalanceRepository->query()
                ->where('business_location_id', $locationId)
                ->where('product_id',$adjustmentDetail['product_id'])
                ->where('variation_id', $adjustmentDetail['variation_id'])
                ->where('current_quantity', '>', 0)
                ->latest()
                ->first();

            $subtotal = $status ? $currentStockBalances->ref_uom_price * $adjustQty : 0;

            $preparedAdjustmentDetailData = $this->prepareAdjustmentDetail($adjustmentDetail);
            $preparedAdjustmentDetailData['product_id'] = $adjustmentDetail['product_id'];
            $preparedAdjustmentDetailData['variation_id'] = $adjustmentDetail['variation_id'];
            $preparedAdjustmentDetailData['adjustment_id'] = $createdStockAdjustment->id;
            $preparedAdjustmentDetailData['adjustment_type'] = $adjustmentType;
            $preparedAdjustmentDetailData['uom_price'] = $status ? $currentStockBalances->ref_uom_price : 0;
            $preparedAdjustmentDetailData['subtotal'] = $subtotal;
            $preparedAdjustmentDetailData['created_at'] = now();
            $preparedAdjustmentDetailData['created_by'] = Auth::id();

            $createdStockAdjustmentDetail = $this->stockAdjustmentRepository->createDetail($preparedAdjustmentDetailData);

            $this->packagingServices->packagingForTx($adjustmentDetail, $createdStockAdjustmentDetail->id,'adjustment');



            $this->completeTransaction(
                $status,
                $adjustmentDetail,
                $locationId,
                $createdStockAdjustmentDetail->id,
                $adjustmentType,
                $referenceUomInfo,
                $currentStockBalances->ref_uom_price,
                $createdStockAdjustment,
                $createdStockAdjustmentDetail,
                $subtotal
            );

        }
    }

    protected function completeTransaction(
        $status,
        $adjustmentDetail,
        $locationId,
        $adjustmentDetailId,
        $adjustmentType,
        $referenceUomInfo,
        $currentStockBalancesRefUomPrice,
        $stockAdjustment,
        $stockAdjustmentDetail,
        $subtotal

    )
    {
        $adjustQty = $referenceUomInfo['qtyByReferenceUom'];
        if ($status && $adjustQty != 0){
            $batchNo = UomHelper::generateBatchNo($adjustmentDetail['variation_id'],'',6);

            $preparedStockHistory = $this->prepareStockHistory($adjustmentDetail);
            $preparedStockHistory['business_location_id'] = $locationId;
            $preparedStockHistory['transaction_details_id'] = $adjustmentDetailId;
            $preparedStockHistory['increase_qty'] = $adjustmentType == 'increase' ? $adjustQty : 0;
            $preparedStockHistory['decrease_qty'] = $adjustmentType == 'decrease' ? $adjustQty : 0;
            $preparedStockHistory['ref_uom_id'] = $referenceUomInfo['referenceUomId'];
            $preparedStockHistory['created_at'] = now();

            $this->stockHistoryRepository->create($preparedStockHistory);

            if ($adjustmentType == 'increase'){
                $this->currentStockBalanceRepository->create([
                    'business_location_id' => $locationId,
                    'product_id' => $adjustmentDetail['product_id'],
                    'variation_id' => $adjustmentDetail['variation_id'],
                    'transaction_type' => 'adjustment',
                    'transaction_detail_id' => $adjustmentDetailId,
                    'batch_no' => $batchNo,
                    'ref_uom_id' => $referenceUomInfo['referenceUomId'],
                    'ref_uom_quantity' => $adjustQty,
                    'ref_uom_price' => $currentStockBalancesRefUomPrice,
                    'current_quantity' => $adjustQty,
                    'created_at' => now(),
                ]);


                $stockAdjustment->increase_subtotal += $subtotal;
            }else{
                $currentStockBalances = CurrentStockBalance::where('business_location_id', $locationId)
                    ->where('product_id',$adjustmentDetail['product_id'])
                    ->where('variation_id', $adjustmentDetail['variation_id'])
                    ->where('current_quantity', '>', 0)
                    ->when(getSettingsValue('accounting_method') == 'fifo', function ($query) {
                        return $query->orderBy('id');
                    }, function ($query) {
                        return $query->orderByDesc('id');
                    })
                    ->get();

                foreach ($currentStockBalances as $currentStockBalance){
                    $currentQty = $currentStockBalance->current_quantity;
                    $refUomPrice = $currentStockBalance->ref_uom_price;

                    if ($currentQty > $adjustQty){
                        $leftStockQty = $currentQty - $adjustQty;

                        $currentStockBalance->update([
                            'current_quantity' => $leftStockQty,
                        ]);

                        //record decreased qty to lot serial details
                        $this->lotSerialDetailsRepository->create([
                            'transaction_detail_id' => $adjustmentDetailId,
                            'transaction_type' => 'adjustment',
                            'current_stock_balance_id' => $currentStockBalance->id,
                            'uom_id' => $currentStockBalance->ref_uom_id,
                            'uom_quantity' => $adjustQty
                        ]);


                        $totalRefUomPrice += $refUomPrice * $adjustQty;




                        break;
                    }elseif ($adjustQty >= $currentQty){

                        //record decreased qty to lot serial details
                        $this->lotSerialDetailsRepository->create([
                            'transaction_detail_id' => $adjustmentDetailId,
                            'transaction_type' => 'adjustment',
                            'current_stock_balance_id' => $currentStockBalance->id,
                            'uom_id' => $currentStockBalance->ref_uom_id,
                            'uom_quantity' => $currentQty
                        ]);


                        $totalRefUomPrice += $refUomPrice * $currentQty;


                        $currentStockBalance->update([
                            'current_quantity' => 0,
                        ]);

                        $adjustQty -= $currentQty;

                        if ($adjustQty == 0){
                            break;
                        }
                    }
                }


                $averageRefUomPrice = $totalRefUomPrice / $adjustQty;
                $subtotal = $averageRefUomPrice * $adjustQty;

                $stockAdjustmentDetail->uom_price = $averageRefUomPrice;
                $stockAdjustmentDetail->subtotal = $subtotal;
                $stockAdjustmentDetail->save();


                $stockAdjustment->decrease_subtotal += $subtotal;
            }
            $stockAdjustment->save();
        }
    }

    private function prepareAdjustment($data)
    {
        return [
            'status' => $data->status,
            'increase_subtotal' => 0,
            'decrease_subtotal' => 0,
            'adjustmented_at' => $data->status == 'completed' ? now() : null,
            'remark' => $data->remark,
        ];
    }

    private function prepareAdjustmentDetail($adjustmentDetail)
    {
        return [
            'uom_id' => $adjustmentDetail['uom_id'],
            'balance_quantity' => $adjustmentDetail['balance_quantity'],
            'gnd_quantity' => $adjustmentDetail['gnd_quantity'],
            'adj_quantity' => $adjustmentDetail['adj_quantity'],
            'remark' => $adjustmentDetail['remark'],
        ];
    }

    private function prepareStockHistory($data)
    {
        return [
            'product_id' => $data['product_id'],
            'variation_id' => $data['variation_id'],
            'lot_serial_no' => null,
            'expired_date' => null,
            'transaction_type' => 'adjustment',
        ];
    }

    private function updateOrCreateLotSerialDetails($checkLotDetail, $transferDetailId, $balance, $transactionType, $qty)
    {
        if ($checkLotDetail) {
            $checkLotDetail->uom_quantity += $qty;
            $checkLotDetail->save();
        } else {
            lotSerialDetails::create([
                'transaction_detail_id' => $transferDetailId,
                'transaction_type' => $transactionType,
                'current_stock_balance_id' => $balance['id'],
                'uom_id' => $balance['ref_uom_id'],
                'uom_quantity' => $qty
            ]);
        }
    }
}
