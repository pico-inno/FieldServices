<?php

namespace App\Http\Controllers;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

class networkPrinterController extends Controller
{

    public function print($printerData) {
        $connector = new NetworkPrintConnector("192.168.100.23", 9100);
        $printer = new Printer($connector);
        try {
            $printer->text("<h1>Hello</h1>");
            $printer->cut();
        }finally {
            $printer->close();
        }
    }
}
