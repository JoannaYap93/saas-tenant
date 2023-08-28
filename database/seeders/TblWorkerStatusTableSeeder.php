<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblWorkerStatusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_worker_status')->delete();
        
        \DB::table('tbl_worker_status')->insert(array (
            0 => 
            array (
                'worker_status_id' => 1,
                'worker_status_name' => '{"en":"Whole Day","cn":"Whole Day"}',
            ),
            1 => 
            array (
                'worker_status_id' => 2,
                'worker_status_name' => '{"en":"Half Day","cn":"Half Day"}',
            ),
            2 => 
            array (
                'worker_status_id' => 3,
                'worker_status_name' => '{"en":"Resigned","cn":"Resigned"}',
            ),
            3 => 
            array (
                'worker_status_id' => 4,
                'worker_status_name' => '{"en":"Rest","cn":"Rest"}',
            ),
        ));
        
        
    }
}