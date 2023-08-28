<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyFarmTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_farm')->delete();
        
        \DB::table('tbl_company_farm')->insert(array (
            0 => 
            array (
                'company_farm_id' => 1,
                'company_farm_name' => 'Titi',
                'company_farm_created' => '2023-08-11 16:01:03',
                'company_farm_updated' => '2023-08-11 16:01:03',
            ),
            1 => 
            array (
                'company_farm_id' => 2,
                'company_farm_name' => 'Titi',
                'company_farm_created' => '2022-02-26 11:56:19',
                'company_farm_updated' => '2022-02-26 11:56:19',
            ),
            2 => 
            array (
                'company_farm_id' => 4,
                'company_farm_name' => 'Asahan',
                'company_farm_created' => '2022-02-26 11:56:29',
                'company_farm_updated' => '2022-02-26 11:56:29',
            ),
            3 => 
            array (
                'company_farm_id' => 5,
                'company_farm_name' => 'Pagoh',
                'company_farm_created' => '2022-02-26 13:35:13',
                'company_farm_updated' => '2022-02-26 13:35:13',
            ),
            4 => 
            array (
                'company_farm_id' => 6,
                'company_farm_name' => 'Tras',
                'company_farm_created' => '2022-03-07 18:25:17',
                'company_farm_updated' => '2023-04-28 14:29:08',
            ),
            5 => 
            array (
                'company_farm_id' => 7,
                'company_farm_name' => 'San Lee',
                'company_farm_created' => '2022-03-07 18:26:12',
                'company_farm_updated' => '2022-03-19 18:45:00',
            ),
            6 => 
            array (
                'company_farm_id' => 9,
                'company_farm_name' => 'Others',
                'company_farm_created' => '2023-06-06 13:57:17',
                'company_farm_updated' => '2023-06-06 13:57:17',
            ),
        ));
        
        
    }
}