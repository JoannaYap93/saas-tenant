<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblUserTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_user_type')->delete();
        
        \DB::table('tbl_user_type')->insert(array (
            0 => 
            array (
                'user_type_id' => 1,
                'user_type_name' => 'Super Administrator',
                'user_type_cdate' => '2022-10-31 20:09:06',
                'user_type_udate' => '2022-10-31 20:09:06',
            ),
            1 => 
            array (
                'user_type_id' => 2,
                'user_type_name' => 'Administrator',
                'user_type_cdate' => '2022-10-31 20:09:06',
                'user_type_udate' => '2022-10-31 20:09:06',
            ),
            2 => 
            array (
                'user_type_id' => 3,
                'user_type_name' => 'Read Only Administrator',
                'user_type_cdate' => '2022-10-31 20:09:06',
                'user_type_udate' => '2022-10-31 20:09:06',
            ),
        ));
        
        
    }
}