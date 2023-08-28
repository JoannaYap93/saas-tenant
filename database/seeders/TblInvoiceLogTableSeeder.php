<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblInvoiceLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_invoice_log')->delete();
        
        \DB::table('tbl_invoice_log')->insert(array (
            0 => 
            array (
                'invoice_log_id' => 1,
                'invoice_id' => 1,
                'invoice_log_created' => '2022-07-25 16:37:13',
                'invoice_log_description' => 'Invoice Created By Webby',
                'invoice_log_action' => 'Add',
                'user_id' => 1,
            ),
            1 => 
            array (
                'invoice_log_id' => 2,
                'invoice_id' => 1,
                'invoice_log_created' => '2022-07-25 16:38:23',
                'invoice_log_description' => 'Bank Slip Uploaded by Client',
                'invoice_log_action' => 'Payment',
                'user_id' => NULL,
            ),
            2 => 
            array (
                'invoice_log_id' => 21792,
                'invoice_id' => 7321,
                'invoice_log_created' => '2023-08-23 16:50:16',
                'invoice_log_description' => 'Invoice Created By Hee Yu Hock',
                'invoice_log_action' => 'Add',
                'user_id' => 1,
            ),
            3 => 
            array (
                'invoice_log_id' => 21793,
                'invoice_id' => 7321,
                'invoice_log_created' => '2023-08-23 16:51:26',
                'invoice_log_description' => 'Bank Slip Uploaded by Client',
                'invoice_log_action' => 'Payment',
                'user_id' => NULL,
            ),
            4 => 
            array (
                'invoice_log_id' => 21794,
                'invoice_id' => 7321,
                'invoice_log_created' => '2023-08-23 17:24:06',
                'invoice_log_description' => 'Invoiced Approved by JOHNNY THAM',
                'invoice_log_action' => 'Approve',
                'user_id' => 3,
            ),
            5 => 
            array (
                'invoice_log_id' => 21813,
                'invoice_id' => 7325,
                'invoice_log_created' => '2023-08-23 21:07:07',
                'invoice_log_description' => 'Invoice Created By Ng Kim Pei',
                'invoice_log_action' => 'Add',
                'user_id' => 4,
            ),
            6 => 
            array (
                'invoice_log_id' => 21860,
                'invoice_id' => 7340,
                'invoice_log_created' => '2023-08-24 14:54:04',
                'invoice_log_description' => 'Invoice Created By Calvin Teoh',
                'invoice_log_action' => 'Add',
                'user_id' => 2,
            ),
            7 => 
            array (
                'invoice_log_id' => 21864,
                'invoice_id' => 7344,
                'invoice_log_created' => '2023-08-24 15:13:12',
                'invoice_log_description' => 'Invoice Created By Hee Yu Hock',
                'invoice_log_action' => 'Add',
                'user_id' => 1,
            ),
            8 => 
            array (
                'invoice_log_id' => 21867,
                'invoice_id' => 7347,
                'invoice_log_created' => '2023-08-24 16:56:59',
                'invoice_log_description' => 'Invoice Created By Ng Kim Pei',
                'invoice_log_action' => 'Add',
                'user_id' => 4,
            ),
            9 => 
            array (
                'invoice_log_id' => 21868,
                'invoice_id' => 7348,
                'invoice_log_created' => '2023-08-25 08:06:23',
                'invoice_log_description' => 'Invoice Created By Annie Sia',
                'invoice_log_action' => 'Add',
                'user_id' => 2,
            ),
            10 => 
            array (
                'invoice_log_id' => 21874,
                'invoice_id' => 7351,
                'invoice_log_created' => '2023-08-25 12:25:45',
                'invoice_log_description' => 'Invoice Created By Jeny Tan',
                'invoice_log_action' => 'Add',
                'user_id' => 2,
            ),
            11 => 
            array (
                'invoice_log_id' => 21875,
                'invoice_id' => 7351,
                'invoice_log_created' => '2023-08-25 12:26:06',
                'invoice_log_description' => 'Bank Slip Uploaded by Client',
                'invoice_log_action' => 'Payment',
                'user_id' => NULL,
            ),
            12 => 
            array (
                'invoice_log_id' => 21876,
                'invoice_id' => 7352,
                'invoice_log_created' => '2023-08-25 12:28:17',
                'invoice_log_description' => 'Invoice Created By Looi Chee Yang',
                'invoice_log_action' => 'Add',
                'user_id' => 3,
            ),
            13 => 
            array (
                'invoice_log_id' => 21877,
                'invoice_id' => 7352,
                'invoice_log_created' => '2023-08-25 12:28:36',
                'invoice_log_description' => 'Bank Slip Uploaded by Client',
                'invoice_log_action' => 'Payment',
                'user_id' => NULL,
            ),
        ));
        
        
    }
}