<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblUserAccessTokenTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_user_access_token')->delete();
        
        
        
    }
}