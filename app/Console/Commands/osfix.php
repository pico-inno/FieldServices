<?php

namespace App\Console\Commands;

use App\Actions\purchase\purchaseDetailActions;
use App\Helpers\UomHelper;
use App\Models\sale\sales;
use App\Models\openingStocks;
use App\Models\stock_history;
use App\Services\SaleServices;
use Illuminate\Console\Command;
use App\Models\lotSerialDetails;
use App\Models\sale\sale_details;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\openingStockDetails;
use App\Models\purchases\purchase_details;
use App\Models\purchases\purchases;

class osfix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osfix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            try {
                DB::beginTransaction();

                openingStocks::truncate();

                openingStockDetails::truncate();
                CurrentStockBalance::truncate();
                lotSerialDetails::truncate();
                stock_history::truncate();

                purchases::where("status",'Received')->update([
                    "status"=>"order",
                ]);

                sales::where("status",'delivered')->update([
                    "status"=>"order",
                ]);



                // git hub workflow
                $Purchases=purchases::where('is_delete',0)->where('status',"order")->get();
                foreach ($Purchases as $key=>$purchase) {
                   $purchaseDetails=purchase_details::where('id',$purchase['id'])->get();
                   foreach ($purchaseDetails as  $purchaseDetail) {
                    $this->info($purchase);
                        $csb=new purchaseDetailActions();
                        $csb->currentStockBalanceAndStockHistoryCreation($purchaseDetail, $purchase, 'purchase');

                        $this->info('hello');
                   }
                   $purchase->where('id',$purchase['id'])->update([
                    'status'=>'received'
                   ]);
                }

                //sale tx change status
                $saleService=new SaleServices();
                $allSale= sales::where('is_delete',0)->get();
                foreach ($allSale as $key => $sale) {


                    $saleDetails=sale_details::where('sales_id',$sale['id'])
                                ->where('is_delete',0)->get();

                    foreach ($saleDetails as $key => $saleDetail) {

                        $refUomInfoForRequestToUpdate= UomHelper::getReferenceUomInfoByCurrentUnitQty($saleDetail['quantity'], $saleDetail['uom_id']);
                        $requestToUpdateQtyByRef = $refUomInfoForRequestToUpdate['qtyByReferenceUom'];
                        $requestToUpdateUomIdByRef = $refUomInfoForRequestToUpdate['referenceUomId'];

                        $this->info($requestToUpdateQtyByRef);
                        $this->info($requestToUpdateUomIdByRef);
                        $this->info($sale['business_location_id']);
                        $this->info($saleDetail);
                        $this->info($sale);

                        $changeQtyStatus = $saleService->changeStockQty(
                        $requestToUpdateQtyByRef,
                        $requestToUpdateUomIdByRef,
                        $sale['business_location_id'],
                        $saleDetail,
                        [],$sale);

                        $this->info('hello==================');
                        if ($changeQtyStatus == false) {
                            $this->error("product Out of Stock");
                            return;
                            // return back()->with(['error' => "product Out of Stock"]);
                        } else {
                            foreach ($changeQtyStatus as $stocksTrack) {
                                $sale_uom_qty = UomHelper::changeQtyOnUom($stocksTrack['ref_uom_id'], $saleDetail['uom_id'], $stocksTrack['stockQty']);
                                lotSerialDetails::create([
                                    'transaction_type' => 'sale',
                                    'transaction_detail_id' => $saleDetail['id'],
                                    'current_stock_balance_id' => $stocksTrack['stock_id'],
                                    'lot_serial_numbers' => $stocksTrack['lot_serial_no'],
                                    'uom_quantity' => $sale_uom_qty,
                                    'uom_id' => $saleDetail['uom_id'],
                                    'ref_uom_quantity'=> $stocksTrack['stockQty'],
                                ]);
                            }
                        }

                         # code...
                    }
                    sales::where('id',$sale['id'])->update([
                        "status"=>"delivered"
                    ]);
                }
                DB::commit();

            } catch (\Throwable $th) {
                DB::rollBack();
                $this->info($th->getMessage());
                $this->error($th->getMessage());
                return;
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
