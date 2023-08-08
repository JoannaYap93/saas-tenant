<?php
namespace App\Exports;

use App\Model\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductListExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('invoice.export_product_list', [
            'records' => Product::get_product_list_for_invoice_import()
        ]);
    }
}
?>
