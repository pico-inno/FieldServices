<?php

namespace App\Console\Commands;

use Exception;
use App\Helpers\UomHelper;
use App\Models\sale\sales;
use App\Models\openingStocks;
use App\Models\stock_history;
use App\Services\SaleServices;
use App\Models\Product\Product;
use Illuminate\Console\Command;
use App\Models\lotSerialDetails;
use App\Models\sale\sale_details;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\openingStockDetails;
use App\Models\purchases\purchases;
use App\Models\Stock\StockTransfer;
use function Laravel\Prompts\error;

use App\Models\purchases\purchase_details;
use App\Actions\purchase\purchaseDetailActions;

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
        $IsError=0;
        $outOFStock=[];
        try {
            DB::beginTransaction();

            // openingStocks::truncate();

            // openingStockDetails::truncate();
            // CurrentStockBalance::truncate();
            // lotSerialDetails::truncate();
            // stock_history::truncate();

            // purchases::where("status",'Received')->update([
            //     "status"=>"order",
            // ]);

            // sales::where("status",'delivered')->update([
            //     "status"=>"order",
            // ]);

            // StockTransfer::where("status",'completed')->update([
            //     "status"=>"pending",
            // ]);



            // $Purchases=purchases::where('is_delete',0)->get();
            // foreach ($Purchases as $purchase) {

            //    $purchase->where('id',$purchase['id'])->update([
            //     'status'=>'received'
            //    ]);
            //    $purchase['status']='received';

            //    $this->info($purchase['status']);
            //    $purchaseDetails=purchase_details::where('purchases_id',$purchase['id'])->where('is_delete','!=','1')->get();
            //    foreach ($purchaseDetails as  $purchaseDetail) {
            //     // $this->info($purchase);
            //         $csb=new purchaseDetailActions();
            //         $csb->currentStockBalanceAndStockHistoryCreation($purchaseDetail, $purchase, 'purchase');
            //    }
            // }

            //sale tx change status
            $saleService=new SaleServices();
            $allSale= sales::where('is_delete',0)->get();
            $bar = $this->output->createProgressBar(count($allSale));
            foreach ($allSale as $key => $sale) {
                $bar->advance();
                $sale['status']="delivered";

                $saleDetails=sale_details::where('sales_id',$sale['id'])
                            ->where('is_delete',0)->get();

                foreach ($saleDetails as $key => $saleDetail) {
                    $refUomInfoForRequestToUpdate= UomHelper::getReferenceUomInfoByCurrentUnitQty($saleDetail['quantity'], $saleDetail['uom_id']);
                    $requestToUpdateQtyByRef = $refUomInfoForRequestToUpdate['qtyByReferenceUom'];
                    $requestToUpdateUomIdByRef = $refUomInfoForRequestToUpdate['referenceUomId'];

                    $product=Product::where('id',$saleDetail['product_id'])->select('name','sku')->first();
                    if($product){

                        $changeQtyStatus = $saleService->changeStockQty(
                        $requestToUpdateQtyByRef,
                        $requestToUpdateUomIdByRef,
                        $sale['business_location_id'],
                        $saleDetail,
                        [],$sale);
                        if ($changeQtyStatus == false) {

                            $productName=$product ? $product['name'] ?? 'he':'hed';
                            $voucherNo= $sale['sales_voucher_no'];
                            // $this->error("product Out of Stock ".$productName." ".$voucherNo);
                            $IsError=1;
                            $outOFStock[]=[
                                $productName,
                                $product['sku'],
                                $voucherNo
                            ];
                            continue;
                            return throw new Exception("Error Processing Request", 1);

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
                    }

                }
                sales::where('id',$sale['id'])->update([
                    "status"=>"delivered"
                ]);
            }
            $bar->finish();




            if($IsError ==0){

            DB::commit();
            }else{
                $sortedArray = collect($outOFStock)->sortBy(function($value) {
                    return $value;
                })->values()->all();

                $this->table(
                    ['product name', 'sku','voucher'],
                    $sortedArray
                );
                return throw new Exception("Error Processing Request", 1);

            }


        DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->info($th->getMessage());
            $this->error($th->getMessage());
            return;
        }
    }
}
