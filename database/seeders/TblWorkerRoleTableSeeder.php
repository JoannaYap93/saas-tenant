<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblWorkerRoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_worker_role')->delete();
        
        \DB::table('tbl_worker_role')->insert(array (
            0 => 
            array (
                'worker_role_id' => 1,
                'worker_role_name' => 'Worker',
            ),
            1 => 
            array (
                'worker_role_id' => 2,
                'worker_role_name' => 'Farm Manager',
            ),
            2 => 
            array (
                'worker_role_id' => 3,
                'worker_role_name' => 'Management',
            ),
            3 => 
            array (
                'worker_role_id' => 4,
                'worker_role_name' => 'Director',
            ),
            4 => 
            array (
                'worker_role_id' => 5,
                'worker_role_name' => 'All',
            ),
        ));
        
        
    }
}