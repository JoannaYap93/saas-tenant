<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Model\Reporting;
use App\Model\CompanyLand;
use App\Model\SettingExpense;
use App\Model\Company;
use App\Model\User;


class ExpenseReport implements FromView, WithEvents, ShouldAutoSize
{
    public function __construct($view, $do_expense, $search)
    {
        $this->view = $view;
        $this->do_expense = $do_expense;
        // $this->collect_data = $collect_details;
        $this->search = $search;
    }

    public function view(): View
    {
        return view($this->view, [
            'monthSel' => Reporting::get_month(),
            // 'delivery_order_item_details' => Reporting::get_delivery_order_item_details($search),
            // 'collect_details' => Reporting::get_collect_details($search),
            // 'products' => Reporting::get_product_details(),
            'doExpense' => $this->do_expense,
            'expenseWtype' => SettingExpense::get_expense_for_report(),
            'companyLand' => CompanyLand::get_company_land_name($this->search),
            'companyLandSel' => CompanyLand::get_company_land_sel(),
            'companySel' => Company::get_company_sel(),
            'userSel' => ['' => 'Please Select User'] + User::get_user_sel(),
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
