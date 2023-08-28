<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblMediaTempTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_media_temp')->delete();
        
        
        
    }
}