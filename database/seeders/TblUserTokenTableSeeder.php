<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblUserTokenTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_user_token')->delete();
        
        
        
    }
}