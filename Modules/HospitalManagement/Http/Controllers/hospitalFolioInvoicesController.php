<?php


namespace Modules\HospitalManagement\Http\Controllers;

use App\Helpers\UomHelper;
use Illuminate\Http\Request;
use function Termwind\render;
use App\Http\Controllers\Controller;

use Modules\HospitalManagement\Entities\hospitalFolioInvoices;
use Modules\HospitalManagement\Entities\hospitalFolioInvoiceDetails;

class hospitalFolioInvoicesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function getJoinedFolioDatas(Request $request)
    {
        $registration_id = $request['registration_id'];
        $folio = hospitalFolioInvoices::where('registration_id', $registration_id)->first();
        $folioDetails = hospitalFolioInvoiceDetails::where('folio_invoice_id', $folio->id)->get();
        $htmlForAllTabs = "";
        $htmlForRoomSale = "";
        $saleHtml = "";

        $pricesforAllTab=[
            'sale_amount'=>0,
            'total_sale_amount'=>0,
            'total_item_discount'=>0,
            'balance_amount'=>0,
            'extra_discount_amount'=>0,
        ];
        $pricesforSaleTab=[
            'sale_amount'=>0,
            'total_sale_amount'=>0,
            'total_item_discount'=>0,
            'extra_discount_amount'=>0,
        ];
        $pricesforRoomSaleTab=[
            'sale_amount'=>0,
            'total_sale_amount'=>0,
            'total_item_discount'=>0,
        ];
        foreach ($folioDetails as $key => $fd) {
            $sale = '';
            $voucher_no = '';
            $type = '';


            if ($fd->transaction_type == 'sale') {
                $fd->load('sales');
                $sale = $fd->sales;
                $voucher_no = $sale->sales_voucher_no;
                $type = "sale";
                $saleDetailHtml = "";
                $saleDetails = $sale->sale_details;
                $extraDiscountType = $sale['extra_discount_type'] == "fixed" ? 'ks' : '%';
                $extraDiscount = round($sale['extra_discount_amount'], 2) . $extraDiscountType;
                foreach ($saleDetails as  $sd) {
                    $product = $sd->product;
                    $variationName = $sd->productVariation->variationTemplateValue->name ?? '';
                    $variation = $variationName ? '(' . $variationName . ')' : '';
                    $uom = $sd->uom;
                    $DiscountType = $sd->discount_type == "fixed" ? 'ks' : '%';
                    $Discount = round($sd->per_item_discount, 2) . $DiscountType;
                    $saleDetailHtml .= '
                                <tr>
                                    <td class="min-w-150px text-end">
                                        ' . $product['name'] . '
                                        ' . $variation . '
                                    </td>
                                    <td class="min-w-150px text-end">' . $sd->quantity . '</td>
                                    <td class="min-w-150px text-end">' . $uom->name . '</td>
                                    <td class="min-w-150px text-end">' . $sd->uom_price . '</td>
                                    <td class="min-w-150px text-end">' . $sd->subtotal . '</td>
                                </tr>
                        ';
                }
                $saleArray = $sale->toArray();
                $saleHtml .= '
                            <tr class="server_tab_' . $registration_id . '">
                                <td class="appendChildFromSer_' . $registration_id . ' cursor-pointer" data-id="s_s' . $sale->id . $fd->id . '" >
                                    <i class="fa-solid fa-circle-plus fs-3 text-primary d-block fa-icon"></i>
                                </td>
                                <td>' . $saleArray["sales_voucher_no"] . '</td>
                                <td class=" text-end">' . $saleArray['sale_amount'] . '</td>
                                <td class=" text-end"> <a  class="btn btn-sm btn-light btn-active-light-primary">' . round($saleArray['total_item_discount'], 2) . '</a></td>
                                <td class="min-w-150px text-end ">
                                ' . $extraDiscount . '
                                </td>
                                <td class=" text-end">' . $saleArray['total_sale_amount'] . '</td>
                                <td class=" text-end">' . $saleArray['paid_amount'] . '</td>
                                <td class=" text-end">' . $saleArray['balance_amount'] . '</td>
                                <td class=" text-center">' . date_format(date_create($sale->sold_at), "d-m-y h:i A") . '</td>
                                <td class=" text-end"> ' . $sale->sold->username . '</td>
                            </tr>
                            <tr  class="server_tab_' . $registration_id . '" id="s_s' . $sale->id . $fd->id . '" style="display: none;">
                                <td colspan="7">
                                    <table class="table table-row-bordered ">
                                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                            <tr>
                                                <td class="text-end">Product</td>
                                                <td class=" text-end">quantity</td>
                                                <td class=" text-end">uom </td>
                                                <td class=" text-end">uom price</td>
                                                <td class=" text-end">Subtotal</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           ' . $saleDetailHtml . '
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        ';
                        $pricesforSaleTab['sale_amount'] += $sale->sale_amount;
                        $pricesforSaleTab['total_sale_amount'] += $sale->total_sale_amount;
                        $pricesforSaleTab['total_item_discount'] += $sale->total_item_discount;
                        $pricesforSaleTab['extra_discount_amount'] += DiscAmountCal($saleArray['sale_amount'],$saleArray['extra_discount_type'],$saleArray['extra_discount_amount'] ?? 0);
            } elseif ($fd->transaction_type == "room") {
                $fd->load('roomSales');
                $sale = $fd->roomSales;
                $voucher_no = $sale->room_sales_voucher_no;
                $type = "room sale";
                $roomSaleDetails = $sale->room_sale_details;
                $roomSaleHtml = "";

                foreach ($roomSaleDetails as $rsd) {
                    $roomType = $rsd->room_type;
                    $roomRate = $rsd->room_rate;
                    $Room = $rsd->room;
                    $DiscountType = $rsd->discount_type == "fixed" ? 'ks' : '%';
                    $Discount = round($rsd->per_item_discount, 2) . $DiscountType;
                    $roomSaleHtml .= '
                            <tbody>
                                <tr>
                                    <td class="min-w-150px text-end">' . $roomType['name'] . '</td>
                                    <td class="min-w-150px text-end">' . $roomRate['rate_name'] . '</td>
                                    <td class="min-w-150px text-end">' . $Room['name'] . '</td>
                                    <td class="min-w-150px text-end ">' . $rsd->room_fees . '</td>
                                    <td class="min-w-150px text-end ">' . $rsd->subtotal . '</td>
                                    <td class="min-w-150px text-center ">' . $Discount . '</td>
                                    <td class="min-w-150px text-center">' . date_format(date_create($rsd->check_in_date), "d-m-y h:i A") . '</td>
                                    <td class="min-w-175px text-center">' . date_format(date_create($rsd->check_out_date), "d-m-y h:i A") . '</td>
                                </tr>
                            </tbody>
                        ';
                }

                // dd($sale->toArray());

                $htmlForRoomSale .= '
                        <tr class="server_tab_' . $registration_id . '">
                            <td class="appendChildFromSer_' . $registration_id . ' cursor-pointer" data-id="rs_s' . $sale->id . $fd->id . '" >
                                <i class="fa-solid fa-circle-plus fs-3 text-primary d-block fa-icon"></i>
                            </td>
                            <td>' . $sale->room_sales_voucher_no . '</td>
                            <td class=" text-end">' . $sale->sale_amount . '</td>
                            <td class=" text-end"> <a  class="btn btn-sm btn-light btn-active-light-primary"> ' . round($sale->total_item_discount, 2) . ' </a></td>
                            <td class=" text-end">' . $sale->total_sale_amount . '</td>
                            <td class=" text-end">' . $sale->paid_amount . '</td>
                            <td class=" text-end">' . $sale->balance_amount . '</td>
                        </tr>
                        <tr  class="server_tab_' . $registration_id . '" id="rs_s' . $sale->id . $fd->id . '" style="display: none;">
                            <td colspan="7">
                                <table class="table table-row-bordered ">
                                    <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                        <tr>
                                            <td class="text-end">Room Rate</td>
                                            <td class=" text-end">Room Type</td>
                                            <td class=" text-end">Room </td>
                                            <td class=" text-end">Room Fees</td>
                                            <td class=" text-end">Subtotal</td>
                                            <td class=" text-center">Discount</td>
                                            <td class="text-center">Check In</td>
                                            <td class="text-center">Check Out</td>
                                        </tr>
                                    </thead>
                                    ' . $roomSaleHtml . '
                                </table>
                            </td>
                        </tr>

                    ';
                    $pricesforRoomSaleTab['sale_amount'] += $sale->sale_amount;
                    $pricesforRoomSaleTab['total_sale_amount'] += $sale->total_sale_amount;
                    $pricesforRoomSaleTab['total_item_discount'] += $sale->total_item_discount;
            };


            // FOR ALL FOLIO TAB
            $extraDiscountType = $sale['extra_discount_type'] == "fixed" ? 'ks' : '%';
            $extraDiscount = round($sale['extra_discount_amount'], 2) . $extraDiscountType;
            $htmlForAllTabs .= '
                    <tr class="server_tab_' . $registration_id . ' ">
                        <td></td>
                        <td>' . $voucher_no . '</td>
                        <td>' . $type . '</td>
                        <td class="text-end">' . $sale->sale_amount . '</td>
                        <td class="text-end">
                            <a  class="btn btn-sm btn-light btn-active-light-primary">' . round($sale->total_item_discount, 2) . '</a>
                        </td>
                        <td class="min-w-150px text-end ">
                           ' . $extraDiscount . '
                        </td>
                        <td class="text-end">' . $sale->total_sale_amount . '</td>
                        <td class="text-end">' . $sale->paid_amount . '</td>
                        <td class="text-end">' . $sale->balance_amount . '</td>
                    </tr>
                ';
                $pricesforAllTab['sale_amount'] += $sale->sale_amount;
                $pricesforAllTab['total_sale_amount'] += $sale->total_sale_amount;
                $pricesforAllTab['total_item_discount'] += $sale->total_item_discount;
                $pricesforAllTab['extra_discount_amount'] += DiscAmountCal($sale->sale_amount,$sale->extra_discount_type,$sale->extra_discount_amount ?? 0);
        }
        // dd($htmlForRoomSale);
        $data = [
            'ForAllTab' => $htmlForAllTabs,
            'pricesforAllTab' => $pricesforAllTab,
            'ForRoomSaleTab' => $htmlForRoomSale ,
            'pricesforRoomSaleTab'=>$pricesforRoomSaleTab,
            'ForSaleTab' => $saleHtml ,
            'pricesforSaleTab'=>$pricesforSaleTab,

        ];
        // dd($data);
        return response()->json($data, 200);
    }



    public function getFolioInvoicesForAllTab(Request $request){
        $registrationId=$request->registrationId;
        $folioIds=[];
        foreach ($registrationId as $id) {
            $folio=hospitalFolioInvoices::where('registration_id',$id)->select('id')->first();
            $folioIds[]=$folio->id;
        }
        $invoiceHtml = view('hospitalmanagement::App.registration.folioInvoices.folioInvoiceForAll',compact('folioIds'))->render();
        return response()->json(['html' => $invoiceHtml]);
    }
}
