<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblUserCompanyTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_user_company')->delete();
        
        \DB::table('tbl_user_company')->insert(array (
            0 => 
            array (
                'user_company_id' => 1,
                'user_id' => 1,
                'company_id' => 1,
            ),
            1 => 
            array (
                'user_company_id' => 2,
                'user_id' => 1,
                'company_id' => 2,
            ),
            2 => 
            array (
                'user_company_id' => 3,
                'user_id' => 1,
                'company_id' => 3,
            ),
            3 => 
            array (
                'user_company_id' => 4,
                'user_id' => 1,
                'company_id' => 4,
            ),
            4 => 
            array (
                'user_company_id' => 5,
                'user_id' => 1,
                'company_id' => 5,
            ),
            5 => 
            array (
                'user_company_id' => 6,
                'user_id' => 1,
                'company_id' => 6,
            ),
            6 => 
            array (
                'user_company_id' => 7,
                'user_id' => 1,
                'company_id' => 7,
            ),
            7 => 
            array (
                'user_company_id' => 8,
                'user_id' => 1,
                'company_id' => 8,
            ),
        ));
        
        
    }
}