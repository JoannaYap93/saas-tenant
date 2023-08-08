<?php

namespace App\Exports;

use App\Model\Company;
use App\Model\Product;
use App\Model\Reporting;
use App\Model\SettingExpense;
use App\Model\ProductCategory;
use App\Model\ProfitLossReporting;
use Illuminate\Contracts\View\View;
use App\Model\SettingExpenseCategory;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


class ProfitLossReportExport_y2y implements FromView, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    public function __construct($view, $sales, $expense, $expense_items, $sales_last_year, $expense_last_year, $expense_items_last_year, $search)
    {
        $this->view = $view;
        $this->sales = $sales;
        $this->expense = $expense;
        $this->expense_item = $expense_items;
        $this->sales_last_year = $sales_last_year;
        $this->expense_last_year = $expense_last_year;
        $this->expense_item_last_year = $expense_items_last_year;
        $this->search = $search;
    }

    public function view(): View
    {
        return view($this->view, [
            'monthSel' => Reporting::get_month(),
            'recordsales' => $this->sales,
            'recordcost' => $this->expense,
            'recordlastyearsales' => $this->sales_last_year,
            'recordcostlastyear' => $this->expense_last_year,
            'search' => $this->search,
            'companies' => Company::get_company_for_daily_report($this->search),
            'productcategory' =>ProductCategory::get_product_category_for_pnl_reporting(),
            'expensecategory' => SettingExpenseCategory::get_expense_category_for_pnl_reporting(),
            'expenseitem' => $this->expense_item,
            'expenseitemlastyear' => $this->expense_item_last_year,
            'expenseitemdata' => SettingExpense::get_item_expense_for_pnl_reporting(),
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