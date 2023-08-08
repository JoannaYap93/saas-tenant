<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Model\Invoice;
use App\Model\Product;
use App\Model\SettingSize;
use App\Model\Company;
use Maatwebsite\Excel\Concerns\FromCollection;


class InvoiceExport implements FromView, WithEvents
{
    public function __construct($view, $invoice, $search)
    {

        $this->view = $view;
        $this->invoice = $invoice;
        $this->search = $search;
    }

    public function view(): View
    {

        return view('invoice.invoice_export', [
            'records' => $this->invoice,
            'search' => $this->search,
            'product_list' => Product::get_product_for_do_list_export(),
            'setting_product_size' => SettingSize::get_size_sel(),
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
