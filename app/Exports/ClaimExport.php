<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class ClaimExport implements FromView, WithEvents, ShouldAutoSize
{
    public function __construct($view, $claim_item, $claim, $claim_approval_verify, $claim_approval_approve, $excel, $claim_category_material, $claim_category_expense)
    {
        $this->view = $view;
        $this->claim = $claim;
        $this->claim_item = $claim_item;
        $this->claim_approval_verify = $claim_approval_verify;
        $this->claim_approval_approve = $claim_approval_approve;
        $this->excel = $excel;
        $this->claim_category_material = $claim_category_material;
        $this->claim_category_expense = $claim_category_expense;
    }

    public function view(): View
    {
        return view($this->view, [
          'claim_item' => $this->claim_item,
          'claim' => $this->claim,
          'claim_approval_verify' => $this->claim_approval_verify,
          'claim_approval_approve' => $this->claim_approval_approve,
          'excel' => $this->excel,
          'claim_category_material' => $this->claim_category_material,
          'claim_category_expense' => $this->claim_category_expense,
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
