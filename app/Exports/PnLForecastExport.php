<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Model\Setting;
use App\Model\Company;
use App\Model\Reporting;
use App\Model\CompanyLand;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PnLForecastExport implements FromView, WithEvents,ShouldAutoSize
{
    public function __construct($view,$search)
    {
        $this->view = $view;
        $this->search = $search;
    }

    public function view(): View
    {
        return view($this->view, [
            'search' => $this->search,
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'companyPnlItem' => Reporting::get_pnl_item(),
            'setting' => Reporting::get_setting_forecast(),
            'forecastReportResult' => Reporting::get_forecast_report_result($this->search),
            'avgprice' => Reporting::get_average_price_for_forecast_report($this->search),
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
