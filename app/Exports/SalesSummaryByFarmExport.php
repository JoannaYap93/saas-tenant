<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Model\Company;
use App\Model\CompanyLand;


class SalesSummaryByFarmExport implements FromView, WithEvents
{
    public function __construct($view, $sales_summary_details_by_farm, $search)
    {
        $this->view = $view;
        $this->sales_summary_details_by_farm = $sales_summary_details_by_farm;
        $this->search = $search;
    }

    public function view(): View
    {
        return view($this->view, [
            'sales_summary_details_by_farm' => $this->sales_summary_details_by_farm,
            'search' => $this->search,
            'company_land' => CompanyLand::get_company_land_name($this->search),
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
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