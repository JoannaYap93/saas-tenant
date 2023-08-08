<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Model\Reporting;
use App\Model\CompanyLand;
use App\Model\Company;
use App\Model\Product;
use App\Model\User;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;




class LandTreeReport implements FromView, WithEvents,ShouldAutoSize
{
    public function __construct($view, $land_tree_information, $search)
    {
        $this->view = $view;
        $this->land_tree_information= $land_tree_information;
        $this->search = $search;
    }

    public function view(): View
    {
        return view($this->view, [
            'records' => $this->land_tree_information,
            'search' => $this->search,
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
