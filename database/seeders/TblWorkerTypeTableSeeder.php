<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblWorkerTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_worker_type')->delete();
        
        \DB::table('tbl_worker_type')->insert(array (
            0 => 
            array (
                'worker_type_id' => 1,
                'worker_type_name' => 'Daily',
            ),
            1 => 
            array (
                'worker_type_id' => 2,
                'worker_type_name' => 'Subcon',
            ),
            2 => 
            array (
                'worker_type_id' => 3,
                'worker_type_name' => 'Month',
            ),
        ));
        
        
    }
}