<?php


class {


     public function update($id, Request $request)
    {

        $lot_control=$this->setting->lot_control;
        $sales=sales::where('id', $id)->first();
        $request_sale_details = $request->sale_details;


        DB::beginTransaction();
        try {

            // begin sale_detail_update
            if ($request_sale_details) {
                //filter old sale_details from request
                $request_old_sale_details = array_filter($request_sale_details, function ($item) {
                    return isset($item['sale_detail_id']);
                });
                // get old sale_details ids from request [1,2]
                $request_old_sale_details_ids = array_column($request_old_sale_details, 'sale_detail_id');


                // update sale detail's data and related current stock
                foreach ($request_old_sale_details as $request_old_sale) {
                    if (!$request_old_sale['product_id']) {
                        continue;
                    };
                    $sale_detail_id = $request_old_sale['sale_detail_id'];
                    unset($request_old_sale["sale_detail_id"]);
                    $request_old_sale['updated_by'] = Auth::user()->id;
                    $sale_details = sale_details::where('id', $sale_detail_id)->where('is_delete', 0)->first();

                    //get  sale_detail qty from db
                    $sale_qty_from_db = UomHelper::smallestQty($sale_details->uomset_id, $sale_details->unit_id, $sale_details->quantity);
                    // smallest qty from client
                    $requestQty = UomHelper::smallestQty($sale_details->uomset_id, $request_old_sale['unit_id'], $request_old_sale['quantity']);
                    // different between old sale_detail qty from db and qty from client request
                    $dif_sale_qty = $requestQty - $sale_qty_from_db;

                    $currentStockFromReq = CurrentStockBalance::where('business_location_id',$sales->business_location_id)
                                        ->where('id', $sale_details->current_stock_balance_id)
                                        ->where('lot_no', $sale_details->lot_no);
                    // location error
                    if ($currentStockFromReq->get()->first() == null) {
                        $product_name = Product::where('id', $request_old_sale['product_id'])->get()->first()->name;
                        return redirect()->back()->with(['warning' => "There is no $product_name stock for this location "]);
                    }

                    // When lot_control off
                    if ($lot_control == "off") {
                        $saleDetailsByParent = sale_details::where('parent_sale_details_id', $sale_detail_id)->where('is_delete', 0)->get();
                        $saleDetailQty=UomHelper::smallestQty($sale_details->uomset_id, $sale_details->unit_id, $sale_details->quantity);

                        $request_sale_details_data = [
                            'sales_id'=> $sale_details['sales_id'],
                            'product_id' => $sale_details['product_id'],
                            'parent_sale_details_id' => $sale_details['id'],
                            'variation_id' => $sale_details['variation_id'],
                            'purchase_detail_id' => $sale_details['purchase_detail_id'],
                            'expired_date' => $sale_details['expired_date'],
                            'uomset_id' => $sale_details['uomset_id'],
                            'unit_id' => $sale_details['unit_id'],
                            'sale_price_without_discount' => $request_old_sale['sale_price_without_discount'],
                            'discount_type' => $request_old_sale['discount_type'],
                            'discount_amount' => $request_old_sale['discount_amount'],
                            'sale_price' => $request_old_sale['sale_price'],
                        ];
                        if (count($saleDetailsByParent) > 0) {
                            if($request_old_sale['quantity'] >$sale_details->quantity){

                                $availableStocks = CurrentStockBalance::where('product_id', $sale_details->product_id)->where('variation_id', $sale_details->variation_id)->where('current_quantity', '>', '0')->get();

                                $qtyToRemove= $request_old_sale['quantity']- $sale_details->quantity;
                                foreach ($availableStocks as $stock) {
                                    $stockQty=$stock->current_quantity;
                                    if ($qtyToRemove > $stockQty) {
                                        CurrentStockBalance::where('id', $stock->id)->first()->update([
                                            'current_quantity' => 0,
                                        ]);

                                        $sale_details_by_stock = sale_details::where('parent_sale_details_id', $sale_detail_id)->where('is_delete', 0)->where('current_stock_balance_id', $stock->id);
                                        if ($sale_details_by_stock->exists()) {
                                            $sale_detial_qty = $sale_details_by_stock->first()->quantity;
                                            $sale_details_by_stock->update([
                                                'quantity' => $sale_detial_qty + $qtyToRemove,
                                            ]);
                                        } else {
                                            $request_sale_details_data['lot_no'] = $stock->lot_no;
                                            $request_sale_details_data['current_stock_balance_id'] = $stock->id;
                                            $request_sale_details_data['quantity'] = $qtyToRemove;
                                            dd($request_sale_details_data);
                                            sale_details::create($request_sale_details_data);
                                        }
                                        $qtyToRemove -= $stockQty;
                                    } else {

                                        $leftStockQty = $stockQty - $qtyToRemove;
                                        $stock_history_data['decrease_qty'] = $qtyToRemove;
                                        $stock_for_update=CurrentStockBalance::where('id', $stock->id)->first();
                                        $stock_for_update->update([
                                            'current_quantity' => $leftStockQty,
                                        ]);

                                        $sale_details_by_stock= sale_details::where('parent_sale_details_id', $sale_detail_id)->where('is_delete', 0)->where('current_stock_balance_id', $stock->id);

                                        if($sale_details_by_stock->exists()){
                                            $sale_detial_qty= $sale_details_by_stock->first()->quantity;
                                                $sale_details_by_stock->update([
                                                    'quantity'=> $sale_detial_qty + $qtyToRemove,
                                                ]);
                                        }else{
                                            $request_sale_details_data['lot_no'] = $stock->lot_no;
                                            $request_sale_details_data['current_stock_balance_id'] = $stock->id;
                                            $request_sale_details_data['quantity'] = $qtyToRemove;
                                            // dd($request_sale_details_data);
                                            sale_details::create($request_sale_details_data);
                                        }
                                        $qtyToRemove = 0;
                                        break;
                                    }
                                }

                            }elseif($request_old_sale['quantity'] < $sale_details->quantity) {

                                $qty_to_replace = $sale_details->quantity - $request_old_sale['quantity'];
                                foreach ($saleDetailsByParent as $sdp) {

                                    if ($qty_to_replace >= $sdp->quantity) {
                                        sale_details::where('id', $sdp->id)->first()->update([
                                            'quantity'=>0,
                                            'is_delete' => 1,
                                            'deleted_at' => now(),
                                            'deleted_by' => Auth::user()->id,
                                        ]);
                                        $smallest_qty=UomHelper::smallestQty($sdp->uomset_id,$sdp->unit_id, $sdp->quantity);
                                        $current_stock= CurrentStockBalance::where('id', $sdp->current_stock_balance_id)->first();
                                        // dd($current_stock->toArray());
                                        $current_stock->update([
                                            'current_quantity' =>  $current_stock->current_quantity+$smallest_qty,
                                        ]);
                                        $qty_to_replace -= $sdp->quantity;
                                        if($qty_to_replace==0){
                                            break;
                                        }
                                    } elseif($qty_to_replace < $sdp->quantity) {
                                        $smallest_qty=UomHelper::smallestQty($sdp->uomset_id,$sdp->unit_id,$qty_to_replace);
                                        $current_stock= CurrentStockBalance::where('id', $sdp->current_stock_balance_id)->first();

                                        sale_details::where('id', $sdp->id)->first()->update([
                                            'quantity' => $sdp->quantity- $qty_to_replace,
                                        ]);
                                        $current_stock->update([
                                            'current_quantity' =>  $current_stock->current_quantity+$smallest_qty,
                                        ]);
                                        $qty_to_replace = 0;
                                        break;
                                    }

                                }

                                // dd($saleDetailsByParent->toArray());
                            }
                        }
                    }else{

                        $current_qty = (int) $currentStockFromReq->get()->first()->current_quantity;
                        $left_qty = $current_qty - $dif_sale_qty;
                        if ($dif_sale_qty > $current_qty) {
                            return redirect()->back()->with(['warning' => 'Out of Stock']);
                        } else {
                        $currentStockFromReq->update([
                                'current_quantity' => $left_qty
                            ]);
                        }

                    }
                    $request_sale_details_data = [
                        'quantity' => $request_old_sale['quantity'],
                        'sale_price_without_discount' => $request_old_sale['sale_price_without_discount'],
                        'discount_type' => $request_old_sale['discount_type'],
                        'discount_amount' => $request_old_sale['discount_amount'],
                        'sale_price' => $request_old_sale['sale_price'],
                        'updated_by' => $request_old_sale['updated_by'],
                    ];
                    if($request_old_sale['quantity']==0){
                        $request_sale_details_data['is_delete']  = 1;
                        $request_sale_details_data['deleted_at']  = now();
                        $request_sale_details_data['is_delete']  = Auth::user()->id;
                        $sale_details->update($request_sale_details_data);
                    }else{
                        $sale_details->update($request_sale_details_data);
                    };
                    stock_history::where('transaction_details_id', $sale_detail_id)->where('transaction_type','sale')->update([
                        'decrease_qty' => $requestQty,
                    ]);
                }

                //get added sale_details_ids from database
                $fetch_sale_details = sale_details::where('sales_id', $id)->where('is_delete', 0)->select('id')->get()->toArray();
                $get_fetched_sale_details_id = array_column($fetch_sale_details, 'id');

                //to remove edited sale_detais that are already created
                $request_old_sale_details_id_for_delete = array_diff($get_fetched_sale_details_id, $request_old_sale_details_ids); //for delete row
                // sale_details for delete
                foreach ($request_old_sale_details_id_for_delete as $sale_detail_id) {
                    if($this->setting->lot_control=='off'){
                        $sale_details = sale_details::where('id', $sale_detail_id)->whereNull('parent_sale_details_id')->where('is_delete', 0);
                    }else{
                        $sale_details = sale_details::where('id', $sale_detail_id)->where('is_delete', 0);
                    }
                    $sale_details_count= count($sale_details->get()->toArray());
                    if($sale_details_count > 0) {
                        $sale_detail_data = $sale_details->get()->first();
                        $request_old_sale_detail_qty = UomHelper::smallestQty($sale_detail_data->uomset_id, $sale_detail_data->unit_id, $sale_detail_data->quantity);
                        $currentStock = CurrentStockBalance::where('id', $sale_detail_data->current_stock_balance_id);
                        $current_stock_qty = $currentStock->get()->first()->current_quantity;
                        $result = $current_stock_qty + $request_old_sale_detail_qty;
                        $currentStock->update(['current_quantity' => $result]);
                        $sale_details->update([
                            'is_delete' => 1,
                            'deleted_at' => now(),
                            'deleted_by' => Auth::user()->id,
                        ]);
                    }

                }

                //to create sale details
                $new_sale_details = array_filter($request_sale_details, function ($item) {
                    return !isset($item['sale_detail_id']);
                });
                if (count($new_sale_details) > 0) {
                    foreach ($new_sale_details as $sale_detail) {
                        $stock = CurrentStockBalance::where('product_id', $sale_detail['product_id'])
                            ->where('variation_id', $sale_detail['variation_id'])
                            ->where('lot_no', $sale_detail['lot_no'])
                            ->get()->first()->toArray();

                        $sale_details_data= [
                            'sales_id' => $id,
                            'product_id' => $sale_detail['product_id'],
                            'purchases_detail_id' => $stock['transaction_detail_id'],
                            'current_stock_balance_id' => $stock['id'],
                            'variation_id' => $sale_detail['variation_id'],
                            'lot_no' => $sale_detail['lot_no'],
                            'expired_date' => $stock['expired_date'],
                            'uomset_id' => $stock['uomset_id'],
                            'quantity' => $sale_detail['quantity'],
                            'unit_id' => $sale_detail['unit_id'],
                            'sale_price_without_discount' => $sale_detail['sale_price_without_discount'],
                            'sale_price' => $sale_detail['sale_price'],
                            'discount_type' => $sale_detail['discount_type'],
                            'discount_amount' => $sale_detail['discount_amount'],
                        ];
                        $sale_detail=sale_details::create($sale_details_data);
                        $requestQty = UomHelper::smallestQty($stock['uomset_id'], $sale_detail['unit_id'], $sale_detail['quantity']);
                        $changeQtyStatus=$this->changeStockQty($requestQty, $stock['business_location_id'], $stock, $sale_detail['lot_no'], $sale_detail->id);

                        if ($changeQtyStatus == false) {
                            return redirect()->back()->withInput()->with(['warning' => "Out of Stock In " . $stock['product']['name']]);
                        } else {
                            if ($this->setting->lot_control == "off") {
                                $datas = $changeQtyStatus;
                                foreach ($datas as $data) {
                                    $sale_details_data['parent_sale_details_id'] = $sale_detail->id;
                                    $resultQty = UomHelper::convertQtyByUnit($data['smallest_unit'], $sale_detail['unit_id'], $stock['uomset_id'], $data['stockQty']);
                                    $sale_details_data['lot_no'] = $data['lot_no'];
                                    $sale_details_data['current_stock_balance_id'] = $data['stock_id'];
                                    $sale_details_data['quantity'] = $resultQty;
                                    sale_details::create($sale_details_data);
                                }
                            }
                        }
                    }
                }
            } else {
                $sales_details = sale_details::where('sales_id', $id)->whereNull('parent_sale_details_id')->where('is_delete','0')->get()->toArray();
                foreach ($sales_details as $sale_detail) {

                    if($this->setting->lot_control=='off'){
                        $saleDetailsByParent = sale_details::where('parent_sale_details_id', $sale_detail['id'])->where('is_delete', 0)->get();
                        $qty_to_replace = $sale_detail['quantity'];
                        foreach ($saleDetailsByParent as $sdp) {
                            if ($qty_to_replace >= $sdp->quantity) {
                                $smallest_qty = UomHelper::smallestQty($sdp->uomset_id, $sdp->unit_id, $sdp->quantity);
                                $current_stock = CurrentStockBalance::where('id', $sdp->current_stock_balance_id)->first();
                                $current_stock->update([
                                    'current_quantity' =>  $current_stock->current_quantity + $smallest_qty,
                                ]);
                                $qty_to_replace -= $sdp->quantity;
                                if ($qty_to_replace == 0) {
                                    break;
                                }
                            } elseif ($qty_to_replace < $sdp->quantity) {
                                $smallest_qty = UomHelper::smallestQty($sdp->uomset_id, $sdp->unit_id, $qty_to_replace);
                                $current_stock = CurrentStockBalance::where('id', $sdp->current_stock_balance_id)->first();
                                $current_stock->update([
                                    'current_quantity' =>  $current_stock->current_quantity + $smallest_qty,
                                ]);
                                $qty_to_replace = 0;
                                break;
                            }
                        }
                    }else{

                        $currentStock = CurrentStockBalance::where('id', $sale_detail['current_stock_balance_id']);
                        $current_stock_qty = $currentStock->get()->first()->current_quantity;
                        $sale_detail_qty=UomHelper::smallestQty($sale_detail['uomset_id'], $sale_detail['unit_id'], $sale_detail['quantity']);
                        $result = $current_stock_qty + $sale_detail_qty;
                        $currentStock->update(['current_quantity' => $result]);
                    }


                }
                // update sale detail at delete
                sale_details::where('sales_id', $id)->update([
                    'is_delete' => 1,
                    'deleted_at' => now(),
                    'deleted_by' => Auth::user()->id,
                ]);
                // update sale  at delete
                sales::where('id',$id)->update([
                    'is_delete' => 1,
                    'deleted_at' => now(),
                    'deleted_by' => Auth::user()->id,
                ]);
            }


            // Begin update sell
            $sales->update([
                'contact_id' => $request->contact_id,
                'sale_amount' => $request->sale_amount,
                'status' => $request->status,
                'discount_type' => $request->discount_type,
                'discount_amount' => $request->discount_amount,
                'sale_expense' => $request->sale_expense,
                'total_sale_amount' => $request->total_sale_amount,
                'paid_amount' => $request->paid_amount,
                'balance' => $request->balance_amount,
                'updated_by' => Auth::user()->id,
            ]);


            DB::commit();
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
        }
        // dd($request->toArray());
        return redirect()->route('all_sales')->with(['success' => 'successfully updated']);
    }
}


