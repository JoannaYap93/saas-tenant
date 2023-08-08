<?php

namespace App\Exports;

use App\Model\Supplier;
use App\Model\Company;
use App\Model\Reporting;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class SupplierExpensesExport implements FromView, WithEvents,ShouldAutoSize
{
    public function __construct($view, $records, $supplier_sel, $search)
    {
        $this->view = $view;
        $this->records = $records;
        $this->supplierSel = $supplier_sel;
        $this->search = $search;
    }

    public function view(): View
    {
        return view($this->view, [
            'records' => $this->records,
            'search' => $this->search,
            'monthSel' => Reporting::get_month(),
            'companySel' => Company::get_company_for_daily_report($this->search),
            // 'supplierSel' => Supplier::supplier_sel($this->search),
            'supplierSel' => $this->supplierSel,
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
