<?php

namespace App\Http\Controllers;

use Exception;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Illuminate\Support\Facades\Auth;
use App\Models\settings\businessSettings;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


class networkPrinterController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function print($printerData, $dataForPrint)
    {

        $data = $dataForPrint;
        $invoice_row = $data['invoice_row'];
        $invoice_no= $data['invoice_no'];
        $voucherData=$data['voucherData'];
        $htmlContent = view('App.pos.print.payment', compact('invoice_row', 'invoice_no', 'voucherData'))->render();
        $this->htmlToPrint($printerData->toArray(), $htmlContent);
    }
    public function htmlToPrint(array $connection, $htmlContent)
    {
        $connector = new NetworkPrintConnector($connection['ip_address'], $connection['port'] ?? 9100);
        $printer = new Printer($connector);
        try {

            $htmlFilePath = tempnam(sys_get_temp_dir(), 'temp-html-') . ".html";
            file_put_contents($htmlFilePath, $htmlContent);
            // Define the paper width in millimeters (80mm)
            $paperWidthMM = 72;
            $dpi = 203;
            $width =  round($paperWidthMM * ($dpi / 25.4));
            // $dest = storage_path('app/public/rec.png');

            $dest = tempnam(sys_get_temp_dir(), 'escpos') . ".png";
            $command = sprintf(
                "wkhtmltoimage -n -q --width %s --user-style-sheet burmese-style.css %s %s",
                escapeshellarg($width),
                escapeshellarg($htmlFilePath),
                escapeshellarg($dest)
            );

            // echo "<img src='" . asset('storage/rec.png') . "' >";

            // dd($dest,$command);

            /* Test for dependencies */
            foreach (array("xvfb-run", "wkhtmltoimage") as $cmd) {
                $testCmd = sprintf("which %s", escapeshellarg($cmd));
                exec($testCmd, $testOut, $testStatus);
                if ($testStatus != 0) {
                    throw new Exception("You require $cmd but it could not be found");
                }
            }


            /* Run wkhtmltoimage */
            $descriptors = array(
                1 => array("pipe", "w"),
                2 => array("pipe", "w"),
            );
            $process = proc_open($command, $descriptors, $fd);
            if (is_resource($process)) {
                /* Read stdout */
                $outputStr = stream_get_contents($fd[1]);
                fclose($fd[1]);
                /* Read stderr */
                $errorStr = stream_get_contents($fd[2]);
                fclose($fd[2]);
                /* Finish up */
                $retval = proc_close($process);
                if ($retval != 0) {
                    throw new Exception("Command $cmd failed: $outputStr $errorStr");
                }
            } else {
                throw new Exception("Command '$cmd' failed to start.");
            }
            try {
                $img = EscposImage::load($dest);
                $printer->bitImage($img);
            } catch (Exception $e) {
                unlink($dest);
                throw $e;
            }
            unlink($dest);
            /* Print it */
            // $printer -> bitImage($img); // bitImage() seems to allow larger images than graphics() on the TM-T20. bitImageColumnFormat() is another option.
            $printer -> cut();
        } catch (Exception $e) {
            echo $e->getMessage();
        } finally {
            $printer->close();
        }
    }
    // public function print($printerData,$dataForPrint) {
    //     // dd($printerData->ip_address);
    //     $data=$dataForPrint;
    //     $invoice_rows=$dataForPrint['invoice_row'];
    //     try {
    //         $connector = new NetworkPrintConnector($printerData->ip_address, 9100);
    //     } catch (\Throwable $th) {
    //         //throw $th;

    //         return ['success' => 'Something Wrong With Printed'];
    //     }
    //     try {
    //         $businessName=businessSettings::where('id',Auth::user()->business_id)->first()->name;
    //         $printer = new Printer($connector);
    //         $printer->setEmphasis(true);
    //         // Center-align the store name
    //         $printer->setJustification(Printer::JUSTIFY_CENTER);
    //         $printer->text($businessName."\n\n\n");
    //         $printer->setJustification(Printer::JUSTIFY_LEFT);

    //         // Reset font emphasis and alignment
    //         // Sample data (adjust as needed)

    //         $printer->setEmphasis(false);
    //         $productName = "Item";
    //         $quantity = "Qty";
    //         $price = "price"; // Adjust as needed

    //         $text = $this->printFormat($productName, $quantity, $price);
    //         $printer->text($text);
    //         // dd($outputLine);
    //         // dd($outputLine);
    //         // die;
    //         $printer->setEmphasis(false);

    //         $printer->text("-----------------------------------------------\n\n");

    //         // print_r($formattedRow);die;
    //         // Define column widths

    //         $printer->setEmphasis(false);
    //         foreach ($invoice_rows as $ir) {
    //             $variation= $ir['variation'] ? '(' . $ir['variation'] . ')' : '';
    //             $productName =$ir['product_name'] .$variation;
    //             $quantity = number_format($ir['quantity'],2).$ir['uomName'];
    //             $price = price($ir['price']);


    //             $text = $this->printFormat($productName, $quantity, $price);
    //             $printer->text($text);
    //         }





    //         // // Generate and print the table
    //         // foreach ($tableData as $row) {
    //         //     $formattedRow = '';
    //         //     for ($i = 0; $i < count($row) - 1; $i++) {
    //         //         $formattedRow .= str_pad($row[$i], $columnWidths[$i], " ");
    //         //     }
    //         //     $formattedRow .= str_pad($row[count($row) - 1], $columnWidths[count($row) - 1], " ", STR_PAD_LEFT);
    //         //     $formattedRow .= "\n";
    //         //     $printer->text($formattedRow);
    //         // }
    //         $printer->text("-----------------------------------------------\n");

    //         $printer->setEmphasis(true);
    //         $name = 30;
    //         $price = 17;

    //         // Format the content using str_pad
    //         $formattedName = str_pad("Total", $name, " ", STR_PAD_LEFT);
    //         $formattedPrice = str_pad(price($data['total']), $price, " ", STR_PAD_LEFT);

    //         // // Combine the formatted strings
    //         $outputLine = $formattedName . $formattedPrice . "\n";
    //         $printer->text($outputLine);



    //         // Format the content using str_pad
    //         $formattedName = str_pad("paid", $name, " ", STR_PAD_LEFT);
    //         $formattedPrice = str_pad(price($data['paid']), $price, " ", STR_PAD_LEFT);
    //         // // Close the printer connection
    //         // // Combine the formatted strings
    //         $outputLine = $formattedName . $formattedPrice . "\n";
    //         $printer->text($outputLine);

    //         $printer->setEmphasis(false);
    //         $printer->text("\n\n");

    //         // $printer->text("-----------------------------------------------\n\n");
    //         $printer->setJustification(Printer::JUSTIFY_CENTER);
    //         $printer->text("Thank you for shopping!\n\n");
    //         $printer->cut();
    //         return ['success' => 'Successfully Printed'];
    //     }catch(\Throwable $e){
    //         logger($e);
    //         return ['error'=>'Something Wrong With Printer'];
    //     }
    //     finally {
    //         $printer->close();
    //     }
    //     return ['success'=>'Successfully Printed'];
    // }

    // function printFormat($productName, $quantity, $price)
    // {
    //         $columnWidthName = 23;
    //         $columnWidthQuantity = 8;
    //         $columnWidthPrice = 15;

    //         $formattedName = str_split($productName, $columnWidthName);
    //         $formattedQuantity = str_split($quantity, $columnWidthQuantity);
    //         $formattedPrice = str_split($price, $columnWidthPrice);

    //         $lengths = [
    //             'formattedName' => count($formattedName),
    //             'formattedQuantity' => count($formattedQuantity),
    //             'formattedPrice' => count($formattedPrice)
    //             ];

    //         $longestArrayName = '';
    //         $longestArrayLength = 0;

    //         foreach ($lengths as $arrayName => $arrayLength) {
    //             if ($arrayLength > $longestArrayLength) {
    //                 $longestArrayName = $arrayName;
    //                 $longestArrayLength = $arrayLength;
    //             }
    //         }
    //         $str="";
    //         foreach ($$longestArrayName as $index=>$la) {
    //             $forName= isset($formattedName[$index])? str_pad($formattedName[$index],$columnWidthName, " ") : str_pad(" ",$columnWidthName, " ");
    //             $forQty = isset($formattedQuantity[$index]) ? str_pad($formattedQuantity[$index],$columnWidthQuantity+1," ", STR_PAD_LEFT) : str_pad(" ", $columnWidthQuantity+1," ", STR_PAD_LEFT);
    //             $forPrice = isset($formattedPrice[$index] )? str_pad($formattedPrice[$index], $columnWidthPrice, " ", STR_PAD_LEFT) : str_pad(" ", $columnWidthPrice, " ", STR_PAD_LEFT);
    //             $str.= $forName. $forQty. $forPrice."\n";
    //         }
    //         return $str;
    // }


    public function printForOD($dataForPrint)
    {
        // dd($printerData->ip_address);
        $data = $dataForPrint;
        $sale_details=$data['sale_detail'];
        try {
            // $connector = new NetworkPrintConnector($printerData->ip_address, 9100);
            $connector = new NetworkPrintConnector('192.168.123.100', 9100);
        } catch (\Throwable $th) {
            //throw $th;

            return ['error' => 'Something Wrong With Printed'];
        }
        try {
            $businessName = businessSettings::where('id', Auth::user()->business_id)->first()->name;
            $printer = new Printer($connector);
            $printer->setEmphasis(true);
            // Center-align the store name
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text($businessName . "\n\n\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);


            $printer->text("Order Voucher No   :".$data['order_voucher_no'] ."\n");
            $printer->text("Service   :" . $data['services']."\n");
            $printer->text("\n\n");
            $printer->setEmphasis(false);
            $productName = "Item";
            $quantity = "Qty";

            $text = $this->printFormatForOd($productName, $quantity);
            $printer->text($text);
            $printer->setEmphasis(false);

            $printer->text("-----------------------------------------------\n\n");
            $printer->setEmphasis(false);
            foreach ($sale_details as $sd) {
                $variation = $sd['product_variation']['variation_template_value'] ? '(' . $sd['product_variation']['variation_template_value']['name']. ')' : '';
                $productName= $sd['product']['name']. $variation;
                $quantity = number_format($sd['quantity'], 2) . $sd['uom']['short_name'];


                $text = $this->printFormatForOd($productName, $quantity);
                $printer->text($text);
            }


            $printer->text("-----------------------------------------------\n");

            $printer->text("\n\n");

            // $printer->text("-----------------------------------------------\n\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Thank you for Ordering!\n\n");
            $printer->cut();
            return ['success' => 'Successfully Printed'];
        } catch (\Throwable $e) {
            logger($e);
            return ['error' => 'Something Wrong With Printer'];
        } finally {
            $printer->close();
        }
        return ['success' => 'Successfully Printed'];
    }

    function printFormatForOd($productName, $quantity)
    {
        $columnWidthName = 24;
        $columnWidthQuantity = 23;

        $formattedName = str_split($productName, $columnWidthName);
        $formattedQuantity = str_split($quantity, $columnWidthQuantity);

        $lengths = [
            'formattedName' => count($formattedName),
            'formattedQuantity' => count($formattedQuantity),
        ];

        $longestArrayName = '';
        $longestArrayLength = 0;

        foreach ($lengths as $arrayName => $arrayLength) {
            if ($arrayLength > $longestArrayLength) {
                $longestArrayName = $arrayName;
                $longestArrayLength = $arrayLength;
            }
        }
        $str = "";
        foreach ($$longestArrayName as $index => $la) {
            $forName = isset($formattedName[$index]) ? str_pad($formattedName[$index], $columnWidthName, " ") : str_pad(" ", $columnWidthName, " ");
            $forQty = isset($formattedQuantity[$index]) ? str_pad($formattedQuantity[$index], $columnWidthQuantity + 1, " ", STR_PAD_RIGHT) : str_pad(" ", $columnWidthQuantity + 1, " ", STR_PAD_LEFT);
            $str .= $forName . $forQty  . "\n";
        }
        return $str;
    }
}
