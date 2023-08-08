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

class TreeTargetReportExport implements FromView, WithEvents,ShouldAutoSize,WithColumnFormatting
{
    public function __construct($view,$search)
    {
        $this->view = $view;
        $this->search = $search;
    }

    public function view(): View
    {
        return view($this->view, [
            'companies' => Company::get_company_for_daily_report($this->search),
            'companyLand' => CompanyLand::get_company_land_name($this->search),
            'totalTreePlanted' => Reporting::get_number_tree_planted(),
            'smallTreePlanted' => Reporting::get_small_tree_planted(),
            'babyTreePlanted' => Reporting::get_baby_tree_planted(),
            'numberTreePerAcre' => Setting::where('setting_slug', 'number_of_tree_per_acre')->pluck('setting_value')->first(),
        ]);
    }

    public function columnFormats(): array
    {
        return[
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_NUMBER,
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
