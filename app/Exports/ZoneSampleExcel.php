<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Model\Reporting;
use App\Model\CompanyLand;
use App\Model\Company;
use App\Model\User;


class ZoneSampleExcel implements FromView, WithEvents
{
    public function __construct($view, $zone_trees, $product_company_land)
    {
        $this->view = $view;
        $this->zone_trees = $zone_trees;
        $this->product_company_land = $product_company_land;
    }

    public function view(): View
    {
        return view($this->view, [
          'zone_trees' => $this->zone_trees,
          'product_company_land' => $this->product_company_land,
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
