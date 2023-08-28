<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSyncCustomerTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_sync_customer')->delete();
        
        
        
    }
}