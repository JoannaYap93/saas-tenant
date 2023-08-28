<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCustomerPicLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_customer_pic_log')->delete();
        
        \DB::table('tbl_customer_pic_log')->insert(array (
            0 => 
            array (
                'customer_pic_log_id' => 0,
                'customer_pic_id' => 1,
                'customer_pic_log_created' => '2023-08-24 20:30:54',
                'customer_pic_log_updated' => '2023-08-24 20:30:54',
                'customer_pic_log_action' => 'Add',
                'customer_pic_log_description' => 'Customer PIC Added By Webby',
                'user_id' => 1,
            ),
        ));
        
        
    }
}