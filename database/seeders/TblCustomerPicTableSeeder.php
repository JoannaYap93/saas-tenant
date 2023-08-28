<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCustomerPicTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_customer_pic')->delete();
        
        \DB::table('tbl_customer_pic')->insert(array (
            0 => 
            array (
                'customer_pic_id' => 1,
                'customer_pic_name' => 'Ivan',
                'customer_pic_ic' => '891202105679',
                'customer_pic_mobile_no' => '60183342091',
                'customer_pic_created' => '2023-08-24 20:30:54',
                'customer_pic_updated' => '2023-08-24 20:30:54',
                'customer_id' => 0,
            ),
        ));
        
        
    }
}