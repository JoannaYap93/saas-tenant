<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblUserLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_user_log')->delete();
        
        
        
    }
}