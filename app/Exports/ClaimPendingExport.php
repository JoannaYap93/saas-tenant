<?php

namespace App\Exports;

use App\Model\Worker;
use App\Model\Company;
use App\Model\Reporting;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class ClaimPendingExport implements FromView, WithEvents,ShouldAutoSize
{
    public function __construct($view, $records, $search)
    {
        $this->view = $view;
        $this->records = $records;
        $this->search = $search;
    }

    public function view(): View
    {
        return view($this->view, [
            'records' => $this->records,
            'search' => $this->search,
            'monthSel' => Reporting::get_month(),
            'companySel' => Company::get_company_for_daily_report($this->search),
            'farmManager' => Worker::get_farm_manager_sel_by_company($this->search),
        ]);
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach ($event->sheet->getDelegate()->getColumnDimensions() as $key => $value) {
                        $event->sheet->getDelegate()->getColumnDimension($key)->setAutoSize(true);
                }
            }
        ];
    }
}
