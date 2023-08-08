<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Model\Company;
use App\Model\Setting;
use App\Model\Reporting;
use App\Model\CompanyLand;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BudgetReportExport implements FromView, WithEvents,ShouldAutoSize,WithColumnFormatting
{
    public function __construct($view, $actual_budget_expense, $search)
    {
        $this->view = $view;
        $this->record = $actual_budget_expense;
        $this->search = $search;
    }

    public function view(): View
    {
        return view($this->view, [
            'expense' => $this->record,
            'search' => $this->search,
            'companies' => Company::get_company_for_daily_report($this->search),
            'default' => Setting::where('setting_slug', 'default_budget_per_tree')->pluck('setting_value')->first(),
            'formula' => Reporting::get_formula_usage_item($this->search),
            'companyLand' => CompanyLand::get_company_land_name($this->search),
        ]);
    }

    public function columnFormats(): array
    {
        return[
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
        ];
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
