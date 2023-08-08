<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Model\Product;
use App\Model\Company;
use App\Model\CompanyLand;


class SalesSummaryByProductCompanyExportNoGrade implements FromView, WithEvents, ShouldAutoSize
{
    public function __construct($view, $sales_summary_details_by_product_company_no_grade, $search)
    {
        $this->view = $view;
        $this->sales_summary_details_by_product_company_no_grade = $sales_summary_details_by_product_company_no_grade;
        $this->search = $search;
    }

    public function view(): View
    {
        return view($this->view, [
            'records' => $this->sales_summary_details_by_product_company_no_grade,
            'search' => $this->search,
            'company_land' => CompanyLand::get_company_land_name($this->search),
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'product' => Product::get_w_size_2($this->search),
            'company' => Company::get_company_for_sales_product_company_report($this->search),
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
