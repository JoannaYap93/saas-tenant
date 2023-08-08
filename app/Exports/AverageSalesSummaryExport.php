<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Model\Product;
use App\Model\Company;
use App\Model\CompanyLand;
use App\Model\Customer;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class AverageSalesSummaryExport implements FromView, ShouldAutoSize
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
            'search' => $this->search,
            'records' => $this->records,
            'product_list' => Product::get_w_size_by_company($this->search),
            'date_range' => CarbonPeriod::create($this->search['date_from'], $this->search['date_to']),
            'company_name' => Company::get_company_name_by_id(@$this->search['company_id']),
            'company_land_name' => CompanyLand::get_company_land_name_by_id(@$this->search['company_land_id'])
        ]);
    }

    // public function registerEvents(): array {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             foreach ($event->sheet->getDelegate()->getColumnDimensions() as $key => $value) {
    //                     $event->sheet->getDelegate()->getColumnDimension($key)->setAutoSize(true);
    //             }
    //         }
    //     ];
    // }
}
