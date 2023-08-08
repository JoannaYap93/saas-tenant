<?php

namespace App\Exports;

use App\Model\Company;
use App\Model\Reporting;
use App\Model\CompanyLand;
use Illuminate\Contracts\View\View;
use App\Model\SettingExpenseCategory;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


class CompanyExpenseExport implements FromView, WithEvents,ShouldAutoSize,WithColumnFormatting
{
    public function __construct($view, $records, $search)
    {
        $this->view = $view;
        $this->record = $records;
        $this->search = $search;
    }

    public function view(): View
    {
        return view($this->view, [
            'companyExpense' => $this->record,
            'search' => $this->search,
            'monthSel' => Reporting::get_month_w_filter($this->search),
            'companies' => Company::get_company_for_daily_report($this->search),
            'expenseCategory' => SettingExpenseCategory::get_expense_for_report($this->search),
            'companyLand' => CompanyLand::get_company_land_name($this->search),
        ]);
    }

    public function columnFormats(): array
    {
        return[
            'B' => NumberFormat::FORMAT_NUMBER_00,
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_NUMBER_00,
            'M' => NumberFormat::FORMAT_NUMBER_00,
            'N' => NumberFormat::FORMAT_NUMBER_00,
            'O' => NumberFormat::FORMAT_NUMBER_00,
            'P' => NumberFormat::FORMAT_NUMBER_00,
            'Q' => NumberFormat::FORMAT_NUMBER_00,
            'R' => NumberFormat::FORMAT_NUMBER_00,
            'S' => NumberFormat::FORMAT_NUMBER_00,
            'T' => NumberFormat::FORMAT_NUMBER_00,
            'U' => NumberFormat::FORMAT_NUMBER_00,
            'V' => NumberFormat::FORMAT_NUMBER_00,
            'W' => NumberFormat::FORMAT_NUMBER_00,
            'X' => NumberFormat::FORMAT_NUMBER_00,
            'Y' => NumberFormat::FORMAT_NUMBER_00,
            'Z' => NumberFormat::FORMAT_NUMBER_00,
            'AA' => NumberFormat::FORMAT_NUMBER_00,
            'AB' => NumberFormat::FORMAT_NUMBER_00,
            'AC' => NumberFormat::FORMAT_NUMBER_00,
            'AD' => NumberFormat::FORMAT_NUMBER_00,
            'AE' => NumberFormat::FORMAT_NUMBER_00,
            'AF' => NumberFormat::FORMAT_NUMBER_00,
            'AG' => NumberFormat::FORMAT_NUMBER_00,
            'AH' => NumberFormat::FORMAT_NUMBER_00,
            'AI' => NumberFormat::FORMAT_NUMBER_00,
            'AJ' => NumberFormat::FORMAT_NUMBER_00,
            'AK' => NumberFormat::FORMAT_NUMBER_00,
            'AL' => NumberFormat::FORMAT_NUMBER_00,
            'AM' => NumberFormat::FORMAT_NUMBER_00,
            'AN' => NumberFormat::FORMAT_NUMBER_00,
            'AO' => NumberFormat::FORMAT_NUMBER_00,
            'AP' => NumberFormat::FORMAT_NUMBER_00,
            'AQ' => NumberFormat::FORMAT_NUMBER_00,
            'AR' => NumberFormat::FORMAT_NUMBER_00,
            'AS' => NumberFormat::FORMAT_NUMBER_00,
            'AT' => NumberFormat::FORMAT_NUMBER_00,
            'AU' => NumberFormat::FORMAT_NUMBER_00,
            'AV' => NumberFormat::FORMAT_NUMBER_00,
            'AW' => NumberFormat::FORMAT_NUMBER_00,
            'AX' => NumberFormat::FORMAT_NUMBER_00,
            'AY' => NumberFormat::FORMAT_NUMBER_00,
            'AZ' => NumberFormat::FORMAT_NUMBER_00,
            'BA' => NumberFormat::FORMAT_NUMBER_00,
            'BB' => NumberFormat::FORMAT_NUMBER_00,
            'BC' => NumberFormat::FORMAT_NUMBER_00,
            'BD' => NumberFormat::FORMAT_NUMBER_00,
            'BE' => NumberFormat::FORMAT_NUMBER_00,
            'BF' => NumberFormat::FORMAT_NUMBER_00,
            'BG' => NumberFormat::FORMAT_NUMBER_00,
            'BH' => NumberFormat::FORMAT_NUMBER_00,
            'BI' => NumberFormat::FORMAT_NUMBER_00,
            'BJ' => NumberFormat::FORMAT_NUMBER_00,
            'BK' => NumberFormat::FORMAT_NUMBER_00,
            'BL' => NumberFormat::FORMAT_NUMBER_00,
            'BM' => NumberFormat::FORMAT_NUMBER_00,
            'BN' => NumberFormat::FORMAT_NUMBER_00,
            'BO' => NumberFormat::FORMAT_NUMBER_00,
            'BP' => NumberFormat::FORMAT_NUMBER_00,
            'BQ' => NumberFormat::FORMAT_NUMBER_00,
            'BR' => NumberFormat::FORMAT_NUMBER_00,
            'BS' => NumberFormat::FORMAT_NUMBER_00,
            'BT' => NumberFormat::FORMAT_NUMBER_00,
            'BU' => NumberFormat::FORMAT_NUMBER_00,
            'BV' => NumberFormat::FORMAT_NUMBER_00,
            'BW' => NumberFormat::FORMAT_NUMBER_00,
            'BX' => NumberFormat::FORMAT_NUMBER_00,
            'BY' => NumberFormat::FORMAT_NUMBER_00,
            'BZ' => NumberFormat::FORMAT_NUMBER_00,
            'CA' => NumberFormat::FORMAT_NUMBER_00,
            'CB' => NumberFormat::FORMAT_NUMBER_00,
            'CC' => NumberFormat::FORMAT_NUMBER_00,
            'CD' => NumberFormat::FORMAT_NUMBER_00,
            'CE' => NumberFormat::FORMAT_NUMBER_00,
            'CF' => NumberFormat::FORMAT_NUMBER_00,
            'CG' => NumberFormat::FORMAT_NUMBER_00,
            'CH' => NumberFormat::FORMAT_NUMBER_00,
            'CI' => NumberFormat::FORMAT_NUMBER_00,
            'CJ' => NumberFormat::FORMAT_NUMBER_00,
            'CK' => NumberFormat::FORMAT_NUMBER_00,
            'CL' => NumberFormat::FORMAT_NUMBER_00,
            'CM' => NumberFormat::FORMAT_NUMBER_00,
            'CN' => NumberFormat::FORMAT_NUMBER_00,
            'CO' => NumberFormat::FORMAT_NUMBER_00,
            'CP' => NumberFormat::FORMAT_NUMBER_00,
            'CQ' => NumberFormat::FORMAT_NUMBER_00,
            'CR' => NumberFormat::FORMAT_NUMBER_00,
            'CS' => NumberFormat::FORMAT_NUMBER_00,
            'CT' => NumberFormat::FORMAT_NUMBER_00,
            'CU' => NumberFormat::FORMAT_NUMBER_00,
            'CV' => NumberFormat::FORMAT_NUMBER_00,
            'CW' => NumberFormat::FORMAT_NUMBER_00,
            'CX' => NumberFormat::FORMAT_NUMBER_00,
            'CY' => NumberFormat::FORMAT_NUMBER_00,
            'CZ' => NumberFormat::FORMAT_NUMBER_00,
            'DA' => NumberFormat::FORMAT_NUMBER_00,
            'DB' => NumberFormat::FORMAT_NUMBER_00,
            'DC' => NumberFormat::FORMAT_NUMBER_00,
            'DD' => NumberFormat::FORMAT_NUMBER_00,
            'DE' => NumberFormat::FORMAT_NUMBER_00,
            'DF' => NumberFormat::FORMAT_NUMBER_00,
            'DG' => NumberFormat::FORMAT_NUMBER_00,
            'DH' => NumberFormat::FORMAT_NUMBER_00,
            'DI' => NumberFormat::FORMAT_NUMBER_00,
            'DJ' => NumberFormat::FORMAT_NUMBER_00,
            'DK' => NumberFormat::FORMAT_NUMBER_00,
            'DL' => NumberFormat::FORMAT_NUMBER_00,
            'DM' => NumberFormat::FORMAT_NUMBER_00,
            'DN' => NumberFormat::FORMAT_NUMBER_00,
            'DO' => NumberFormat::FORMAT_NUMBER_00,
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
