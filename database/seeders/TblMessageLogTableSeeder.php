<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblMessageLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_message_log')->delete();
        
        
        
    }
}