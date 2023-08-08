<?php

namespace App\Exports;

use App\Model\Company;
use App\Model\Product;
use App\Model\Reporting;
use App\Model\SettingExpense;
use App\Model\ProductCategory;
use App\Model\ProfitLossReporting;
use Illuminate\Contracts\View\View;
use App\Model\SettingExpenseCategory;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


class WorkerAttendanceReportingExport implements FromView, WithEvents
{
    public function __construct($view, $records, $day_array, $month_sel, $search, $worker_list,$current_day)
    {
        $this->view = $view;
        $this->records = $records;
        $this->day_array = $day_array;
        $this->month_sel = $month_sel;
        $this->search = $search;
        $this->worker_list = $worker_list;
        $this->current_day = $current_day;
    }

    public function view(): View
    {
        return view($this->view, [
            'monthSel' => $this->month_sel,
            'daySel' => $this->day_array,
            'records' => $this->records,
            'search' => $this->search,
            'workerList' => $this->worker_list,
            'currentday' => $this->current_day,
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
