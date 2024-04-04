<?php

namespace App\Services\purchase;

use Exception;
use Carbon\Carbon;
use App\Helpers\UomHelper;
use App\Models\stock_history;
use App\Models\Contact\Contact;
use App\Models\Product\Product;
use App\Services\paymentServices;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentStockBalance;
use App\Models\purchases\purchases;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Auth;
use App\Repositories\LocationRepository;
use Yajra\DataTables\Facades\DataTables;
use App\Actions\purchase\purchaseActions;
use App\Models\purchases\purchase_details;
use App\Services\packaging\packagingServices;
use App\Actions\purchase\purchaseDetailActions;

class purchasingService
{



    /**
     * create purchase
     *
     * @param  mixed $request
     * @return void
     */
    public function createPurchase($request){
        try {
            DB::beginTransaction();
            $action = new purchaseActions();
            $detailServices = new purchaseDetailServices();
            $payment = new paymentServices();

            // store purchase data
            $purchase = $action->create($this->purchaseData($request));

            // store purchaseDetail data
            $purchases_details = $request->purchase_details;
            if ($purchases_details) {
                $detailServices->create($purchases_details, $purchase);
            }

            if ($request->paid_amount > 0) {
                //store the payment transactions
                $payment->makePayment($purchase, $request->payment_account, 'purchase');
            }
            calcPayable($request->contact_id);
            DB::commit();
            return $purchase;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th);
        }
    }

    public function update($id, $request)
    {

        //initial class
        $action = new purchaseActions();
        $detailServices = new purchaseDetailServices();

        // update purchase data
        $purchasesData = $this->purchaseData($request);
        $purchasesData['balance_amount']=$request->total_purchase_amount - $request->paid_amount;
        $purchase=$action->update($id, $purchasesData);
        //update purchase detail data
        $detailServices->update($id,$purchase, $request);
        calcPayable($request->contact_id);

    }










    /**
     *prepare data to create purchase
     *
     * @param  mixed $request
     * @return void
     */
    public function purchaseData($request)
    {
        $payment_status=defStatus($request->paid_amount,$request->total_purchase_amount);
        return [
            'business_location_id' => $request->business_location_id,
            'contact_id' => $request->contact_id,
            'status' => $request->status,
            'purchase_amount' => $request->purchase_amount,
            'total_line_discount' => $request->total_line_discount,
            'extra_discount_type' => $request->extra_discount_type,
            'extra_discount_amount' => $request->extra_discount_amount,
            'total_discount_amount' => $request->total_discount_amount,
            'purchase_expense' => $request->purchase_expense,
            'total_purchase_amount' => $request->total_purchase_amount,
            'currency_id' => $request->currency_id,
            'paid_amount' => $request->paid_amount,
            'purchased_at' => $request->purchased_at,
            'balance_amount' => $request->balance_amount,
            'payment_status' => $payment_status,
            'received_at'=> $request->received_at ?? null,
        ];
    }


    /**
     * Data for list that show using datatable
     *
     * @param  mixed data from Request $request
     * @return void
     */
    public function listData($request)
    {
        // dd($request->has('from_data') , $request->has('to_date'));
        $accessUserLocation = getUserAccesssLocation();
        $purchases = purchases::where('is_delete', 0)
                    ->when($accessUserLocation[0] != 0, function ($query) use ($accessUserLocation) {
                        $query->whereIn('business_location_id', $accessUserLocation);
                    })
                    ->with('business_location_id', 'businessLocation', 'supplier')
                    ->OrderBy('id', 'desc');
        if ($request->has('from_date') && $request->has('to_date')) {
            // dd('her');
            $purchases = $purchases->whereDate('received_at', '>=', $request->from_date)
                    ->whereDate('received_at', '<=', $request->to_date);
        }
        return DataTables::of($purchases)
            ->addColumn('checkbox', function ($purchase) {
                return
                    '
                    <div class="form-check form-check-sm form-check-custom ">
                        <input class="form-check-input" type="checkbox" data-checked="delete" value=' . $purchase->id . ' />
                    </div>
                ';
            })
            ->addColumn('location', function ($purchase) {
                return businessLocationName(arr($purchase, 'businessLocation'));
            })
            ->addColumn('supplier', function ($purchase) {
                return arr($purchase->supplier, 'company_name') ?? arr($purchase->supplier, 'first_name');
            })

            ->addColumn('date', function ($purchase) {
                return fDate($purchase->created_at, true);
            })
            ->addColumn('purchaseItems', function ($purchase) {
                $purchaseDetails = $purchase->purchase_details;
                $items = '';
                foreach ($purchaseDetails as $key => $pd) {
                    $variation = $pd->productVariation;
                    $productName = $variation->product->name ?? '';
                    $sku = $variation->product->sku ?? '';
                    $variationName = $variation->variationTemplateValue->name ?? '';
                    $items .= "$productName,$variationName,$sku ;";
                }
                return $items;
            })
            ->addColumn('status', function ($purchase) {
                $html = '';
                if ($purchase->status == 'received') {
                    $html = "<span class='badge badge-success'> Received </span>";
                } elseif ($purchase->status == 'request') {
                    $html = "<span class='badge badge-secondary'>$purchase->status</span>";
                } elseif ($purchase->status == 'pending') {
                    $html = "<span class='badge badge-warning'>$purchase->status</span>";
                } elseif ($purchase->status == 'order') {
                    $html = "<span class='badge badge-primary'>$purchase->status</span>";
                } elseif ($purchase->status == 'partial') {
                    $html = "<span class='badge badge-info'>$purchase->status</span>";
                }
                return $html;
                // return $purchase->supplier['company_name'] ?? $purchase->supplier['first_name'];
            })
            ->addColumn('payment_status', function ($e) {
                if ($e->payment_status == 'due') {
                    return '<span class="badge badge-warning">Pending</span>';
                } elseif ($e->payment_status == 'partial') {
                    return '<span class="badge badge-primary">Partial</span>';
                } elseif ($e->payment_status == 'paid') {
                    return '<span class="badge badge-success">Paid</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('action', function ($purchase) {
                $editBtn = $purchase->status != "confirmed" ? '<a href=" ' . route('purchase_edit', $purchase->id) . ' " class="dropdown-item p-2 edit-unit bg-active-primary fw-semibold" >Edit</a>' : '';
                $html = '
                    <div class="dropdown ">
                        <button class="btn m-2 btn-sm btn-light btn-primary fw-semibold fs-7  dropdown-toggle " type="button" id="purchaseDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <div class="z-3">
                        <ul class="dropdown-menu z-10 p-5 " aria-labelledby="purchaseDropDown" role="menu">';
                if (hasView('purchase')) {
                    $html .= '<a class="dropdown-item p-2  px-3 view_detail  fw-semibold"   type="button" data-href="' . route('purchaseDetail', $purchase->id) . '">
                                View
                            </a>';
                }
                if (hasPrint('purchase')) {
                    $html .= ' <a class="dropdown-item p-2  cursor-pointer bg-active-danger fw-semibold print-invoice"  data-href="' . route('f', $purchase->id) . '">Print</a>';
                }
                if (hasUpdate('purchase')) {
                    $html .= $editBtn;
                }
                if (($purchase->paid_amount ?? 0) < $purchase->total_purchase_amount) {
                    $html .= '<a class="dropdown-item p-2 cursor-pointer " id="paymentCreate"   data-href="' . route('paymentTransaction.createForPurchase', ['id' => $purchase->id, 'currency_id' => $purchase->currency_id]) . '">Add Payment</a>';
                }
                $html .= '<a class="dropdown-item p-2 cursor-pointer " id="viewPayment"   data-href="' . route('paymentTransaction.viewForPurchase', $purchase->id) . '">View Payment</a>';
                if (hasDelete('purchase')) {
                    $html .= '   <a class="dropdown-item p-2  cursor-pointer bg-active-danger fw-semibold text-danger"  data-id="' . $purchase->id . '" data-kt-purchase-table="delete_row">Delete</a>';
                }
                $html .= '</ul></div></div>';

                return (hasView('purchase') && hasPrint('purchase') && hasUpdate('purchase') && hasDelete('purchase') ? $html : 'No Access');
            })
            ->rawColumns(['action', 'checkbox', 'status', 'date', 'payment_status'])
            ->make(true);
    }

}
