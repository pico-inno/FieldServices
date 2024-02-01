<?php

namespace App\Livewire;

use Livewire\Component;
use App\Exports\DailyReportExport;
use Maatwebsite\Excel\Facades\Excel;

class DailyReportExportBtn extends Component
{
    public $campaignId;
    public $dateFilter;
    public $toDate;
    public $showModal = false;

    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
    }

    public function export(){

        $date=now()->format('d-M-y');
        $fileName='dailyReport_'.$date.'.xlsx';
        $campaignId=$this->campaignId ;
        $dateFilter=$this->dateFilter ? date_create($this->dateFilter)->format('Y-m-d') : now();
        // dd($dateFilter);
        return Excel::download(new DailyReportExport($campaignId,$dateFilter), $fileName);
    }
    public function render()
    {
        return view('livewire.daily-report-export-btn');
    }
}
