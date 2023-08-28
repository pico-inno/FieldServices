<?php

namespace App\Http\Controllers;

use App\Models\settings\businessSettings;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

use function Laravel\Prompts\error;

class networkPrinterController extends Controller
{

    public function print($printerData,$dataForPrint) {
        // dd($printerData->ip_address);
        $data=$dataForPrint;
        $invoice_rows=$dataForPrint['invoice_row'];
        try {
            $connector = new NetworkPrintConnector($printerData->ip_address, 9100);
        } catch (\Throwable $th) {
            //throw $th;

            return ['success' => 'Something Wrong With Printed'];
        }
        try {
            $businessName=businessSettings::where('id',Auth::user()->business_id)->first()->name;
            $printer = new Printer($connector);
            $printer->setEmphasis(true);
            // Center-align the store name
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text($businessName."\n\n\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);

            // Reset font emphasis and alignment
            // Sample data (adjust as needed)

            $printer->setEmphasis(false);
            $productName = "Item";
            $quantity = "Qty";
            $price = "price"; // Adjust as needed

            $text = $this->printFormat($productName, $quantity, $price);
            $printer->text($text);
            // dd($outputLine);
            // dd($outputLine);
            // die;
            $printer->setEmphasis(false);

            $printer->text("-----------------------------------------------\n\n");

            // print_r($formattedRow);die;
            // Define column widths

            $printer->setEmphasis(false);
            foreach ($invoice_rows as $ir) {
                $variation= $ir['variation'] ? '(' . $ir['variation'] . ')' : '';
                $productName =$ir['product_name'] .$variation;
                $quantity = number_format($ir['quantity'],2).$ir['uomName'];
                $price = price($ir['price']);


                $text = $this->printFormat($productName, $quantity, $price);
                $printer->text($text);
            }





            // // Generate and print the table
            // foreach ($tableData as $row) {
            //     $formattedRow = '';
            //     for ($i = 0; $i < count($row) - 1; $i++) {
            //         $formattedRow .= str_pad($row[$i], $columnWidths[$i], " ");
            //     }
            //     $formattedRow .= str_pad($row[count($row) - 1], $columnWidths[count($row) - 1], " ", STR_PAD_LEFT);
            //     $formattedRow .= "\n";
            //     $printer->text($formattedRow);
            // }
            $printer->text("-----------------------------------------------\n");

            $printer->setEmphasis(true);
            $name = 30;
            $price = 17;

            // Format the content using str_pad
            $formattedName = str_pad("Total", $name, " ", STR_PAD_LEFT);
            $formattedPrice = str_pad(price($data['total']), $price, " ", STR_PAD_LEFT);

            // // Combine the formatted strings
            $outputLine = $formattedName . $formattedPrice . "\n";
            $printer->text($outputLine);



            // Format the content using str_pad
            $formattedName = str_pad("paid", $name, " ", STR_PAD_LEFT);
            $formattedPrice = str_pad(price($data['paid']), $price, " ", STR_PAD_LEFT);
            // // Close the printer connection
            // // Combine the formatted strings
            $outputLine = $formattedName . $formattedPrice . "\n";
            $printer->text($outputLine);

            $printer->setEmphasis(false);
            $printer->text("\n\n");

            // $printer->text("-----------------------------------------------\n\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Thank you for shopping!\n\n");
            $printer->cut();
            return ['success' => 'Successfully Printed'];
        }catch(\Throwable $e){
            logger($e);
            return ['error'=>'Something Wrong With Printer'];
        }
        finally {
            $printer->close();
        }
        return ['success'=>'Successfully Printed'];
    }

function printFormat($productName, $quantity, $price)
{
        $columnWidthName = 23;
        $columnWidthQuantity = 8;
        $columnWidthPrice = 15;

        $formattedName = str_split($productName, $columnWidthName);
        $formattedQuantity = str_split($quantity, $columnWidthQuantity);
        $formattedPrice = str_split($price, $columnWidthPrice);

        $lengths = [
            'formattedName' => count($formattedName),
            'formattedQuantity' => count($formattedQuantity),
            'formattedPrice' => count($formattedPrice)
            ];

        $longestArrayName = '';
        $longestArrayLength = 0;

        foreach ($lengths as $arrayName => $arrayLength) {
            if ($arrayLength > $longestArrayLength) {
                $longestArrayName = $arrayName;
                $longestArrayLength = $arrayLength;
            }
        }
        $str="";
        foreach ($$longestArrayName as $index=>$la) {
            $forName= isset($formattedName[$index])? str_pad($formattedName[$index],$columnWidthName, " ") : str_pad(" ",$columnWidthName, " ");
            $forQty = isset($formattedQuantity[$index]) ? str_pad($formattedQuantity[$index],$columnWidthQuantity+1," ", STR_PAD_LEFT) : str_pad(" ", $columnWidthQuantity+1," ", STR_PAD_LEFT);
            $forPrice = isset($formattedPrice[$index] )? str_pad($formattedPrice[$index], $columnWidthPrice, " ", STR_PAD_LEFT) : str_pad(" ", $columnWidthPrice, " ", STR_PAD_LEFT);
            $str.= $forName. $forQty. $forPrice."\n";
        }
        return $str;
}
}
