<?php
namespace App\Imports;
use App\CompanyLandTree;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ZoneTreeImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    use Importable;
    
    public function collection(Collection $rows)
    {

        // foreach($rows as $key => $values){
        //   CompanyLandTree::create([
        //     ''
        //   ])
        // }
        // return new CompanyLandTree([
        //     'company_land_tree_no' => $row['Tree No.'],
        //     'product_id' => $row['Product'],
        //     'company_land_tree_year' => $row['Age'],
        //     'is_sick' => $row['Sick'],
        //     'is_bear_fruit' => $row['Bear Fruit'],
        //     'company_land_tree_status' => $row['Status']
        // ]);
    }
}
