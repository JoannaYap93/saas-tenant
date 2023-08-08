<?php

    namespace App\Exports;

    use App\Model\PayrollItem;
    use App\Model\WorkerRole;
    use Illuminate\Contracts\View\View;
    use Maatwebsite\Excel\Concerns\FromView;
    use Maatwebsite\Excel\Concerns\ShouldAutoSize;
    use Maatwebsite\Excel\Events\AfterSheet;

    class PayrollExport implements FromView, ShouldAutoSize
    {
        public function __construct($view, $payroll, $payroll_details, $search)
        {
            $this->view = $view;
            $this->search = $search;
            $this->payroll = $payroll;
            $this->payroll_details = $payroll_details;
        }

        public function view(): View
        {
            return view($this->view, [
                'payroll' => $this->payroll,
                'payroll_details' => $this->payroll_details,
                'worker_role_list' => WorkerRole::all(),
                'payroll_items_type_deduct' => PayrollItem::get_payroll_items(['payroll_item_type' => 'deduct']),
                'payroll_items_type_add' => PayrollItem::get_payroll_items(['payroll_item_type' => 'add']),
            ]);
        }

        public function registerEvents(): array
        {
            return [
                AfterSheet::class => function (AfterSheet $event) {
                    foreach ($event->sheet->getDelegate()->getColumnDimensions() as $key => $value) {
                            $event->sheet->getDelegate()->getColumnDimension($key)->setAutoSize(true);
                    }
                }
            ];
        }
    }

?>
