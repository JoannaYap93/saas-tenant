<?php
namespace App\Imports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class InvoiceImport implements ToCollection,WithStartRow
{
    use Importable;


    public function collection(Collection $rows)
    {
    }

    public function startRow() : int{
        return 4;
   }
}
