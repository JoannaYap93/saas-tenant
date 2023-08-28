<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblUserModelHasRoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_user_model_has_role')->delete();
        
        \DB::table('tbl_user_model_has_role')->insert(array (
            0 => 
            array (
                'role_id' => 13,
                'model_type' => 'App\\Model\\User',
                'model_id' => 2,
            ),
            1 => 
            array (
                'role_id' => 17,
                'model_type' => 'App\\Model\\User',
                'model_id' => 1,
            ),
        ));
        
        
    }
}