<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class openingStockReportController extends Controller
{
    public function summary(){
        return view('App.openingStock.report.summary');
    }
    public function detail(){
        return view('App.openingStock.report.detail');
    }
}
