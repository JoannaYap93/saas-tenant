<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSendSmsLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_send_sms_log')->delete();
        
        
        
    }
}