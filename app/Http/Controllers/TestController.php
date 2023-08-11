<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','isActive']);
        $this->middleware('canView:warranty')->only('warranties');
        $this->middleware('canCreate:warranty')->only('warrantyAdd');
        $this->middleware('canUpdate:warranty')->only('warrantyEdit');
    }

//    public function index()
//    {
//        return view('App.dashboard');
//    }

    // Print Label
    public function printLabel()
    {
        return view('App.product.print.printLabel');
    }

    // Variation




    // Import Opening Stock
    public function importStock()
    {
        return view('App.product.import-opening-stock.importStock');
    }

    // Warranties
    public function warranties()
    {
        return view('App.product.warranty.warranties');
    }

    public function warrantyAdd()
    {
        return view('App.product.warranty.warrantyAdd');
    }

    public function warrantyEdit()
    {
        return view('App.product.warranty.warrantyEdit');
    }

}
