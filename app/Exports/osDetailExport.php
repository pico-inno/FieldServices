<?php

namespace App\Exports;

use App\Models\openingStocks;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class osDetailExport implements FromView,ShouldAutoSize
{
    public $query=null;
    public function __construct($query)
    {
        $this->query=$query;
    }
    public function view():View{
        $datas=$this->query->get();
        return view('App.openingStock.export.report.detail',compact('datas'));
    }

}
