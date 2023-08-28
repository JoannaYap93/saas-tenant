<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSupplierCompanyTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_supplier_company')->delete();
        
        \DB::table('tbl_supplier_company')->insert(array (
            0 => 
            array (
                'supplier_company_id' => 2,
                'supplier_id' => 1,
                'company_id' => 8,
            ),
            1 => 
            array (
                'supplier_company_id' => 3,
                'supplier_id' => 1,
                'company_id' => 5,
            ),
            2 => 
            array (
                'supplier_company_id' => 4,
                'supplier_id' => 1,
                'company_id' => 6,
            ),
            3 => 
            array (
                'supplier_company_id' => 5,
                'supplier_id' => 1,
                'company_id' => 2,
            ),
            4 => 
            array (
                'supplier_company_id' => 6,
                'supplier_id' => 1,
                'company_id' => 7,
            ),
            5 => 
            array (
                'supplier_company_id' => 7,
                'supplier_id' => 1,
                'company_id' => 4,
            ),
            6 => 
            array (
                'supplier_company_id' => 27,
                'supplier_id' => 2,
                'company_id' => 8,
            ),
            7 => 
            array (
                'supplier_company_id' => 28,
                'supplier_id' => 2,
                'company_id' => 5,
            ),
            8 => 
            array (
                'supplier_company_id' => 29,
                'supplier_id' => 2,
                'company_id' => 6,
            ),
            9 => 
            array (
                'supplier_company_id' => 30,
                'supplier_id' => 2,
                'company_id' => 2,
            ),
            10 => 
            array (
                'supplier_company_id' => 31,
                'supplier_id' => 2,
                'company_id' => 7,
            ),
            11 => 
            array (
                'supplier_company_id' => 32,
                'supplier_id' => 2,
                'company_id' => 4,
            ),
            12 => 
            array (
                'supplier_company_id' => 40,
                'supplier_id' => 5,
                'company_id' => 2,
            ),
            13 => 
            array (
                'supplier_company_id' => 59,
                'supplier_id' => 8,
                'company_id' => 8,
            ),
            14 => 
            array (
                'supplier_company_id' => 60,
                'supplier_id' => 8,
                'company_id' => 5,
            ),
            15 => 
            array (
                'supplier_company_id' => 61,
                'supplier_id' => 8,
                'company_id' => 6,
            ),
            16 => 
            array (
                'supplier_company_id' => 62,
                'supplier_id' => 8,
                'company_id' => 2,
            ),
            17 => 
            array (
                'supplier_company_id' => 63,
                'supplier_id' => 8,
                'company_id' => 7,
            ),
            18 => 
            array (
                'supplier_company_id' => 64,
                'supplier_id' => 8,
                'company_id' => 4,
            ),
            19 => 
            array (
                'supplier_company_id' => 67,
                'supplier_id' => 7,
                'company_id' => 8,
            ),
            20 => 
            array (
                'supplier_company_id' => 68,
                'supplier_id' => 7,
                'company_id' => 5,
            ),
            21 => 
            array (
                'supplier_company_id' => 69,
                'supplier_id' => 7,
                'company_id' => 6,
            ),
            22 => 
            array (
                'supplier_company_id' => 70,
                'supplier_id' => 7,
                'company_id' => 2,
            ),
            23 => 
            array (
                'supplier_company_id' => 71,
                'supplier_id' => 7,
                'company_id' => 7,
            ),
            24 => 
            array (
                'supplier_company_id' => 72,
                'supplier_id' => 7,
                'company_id' => 4,
            ),
            25 => 
            array (
                'supplier_company_id' => 83,
                'supplier_id' => 6,
                'company_id' => 8,
            ),
            26 => 
            array (
                'supplier_company_id' => 84,
                'supplier_id' => 6,
                'company_id' => 5,
            ),
            27 => 
            array (
                'supplier_company_id' => 85,
                'supplier_id' => 6,
                'company_id' => 6,
            ),
            28 => 
            array (
                'supplier_company_id' => 86,
                'supplier_id' => 6,
                'company_id' => 2,
            ),
            29 => 
            array (
                'supplier_company_id' => 87,
                'supplier_id' => 6,
                'company_id' => 7,
            ),
            30 => 
            array (
                'supplier_company_id' => 88,
                'supplier_id' => 6,
                'company_id' => 4,
            ),
            31 => 
            array (
                'supplier_company_id' => 98,
                'supplier_id' => 9,
                'company_id' => 2,
            ),
            32 => 
            array (
                'supplier_company_id' => 147,
                'supplier_id' => 4,
                'company_id' => 8,
            ),
            33 => 
            array (
                'supplier_company_id' => 148,
                'supplier_id' => 4,
                'company_id' => 5,
            ),
            34 => 
            array (
                'supplier_company_id' => 149,
                'supplier_id' => 4,
                'company_id' => 6,
            ),
            35 => 
            array (
                'supplier_company_id' => 150,
                'supplier_id' => 4,
                'company_id' => 2,
            ),
            36 => 
            array (
                'supplier_company_id' => 151,
                'supplier_id' => 4,
                'company_id' => 7,
            ),
            37 => 
            array (
                'supplier_company_id' => 152,
                'supplier_id' => 4,
                'company_id' => 4,
            ),
            38 => 
            array (
                'supplier_company_id' => 155,
                'supplier_id' => 3,
                'company_id' => 2,
            ),
        ));
        
        
    }
}