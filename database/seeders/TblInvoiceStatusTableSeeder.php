<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblInvoiceStatusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_invoice_status')->delete();
        
        \DB::table('tbl_invoice_status')->insert(array (
            0 => 
            array (
                'invoice_status_id' => 1,
                'invoice_status_name' => 'Pending Payment',
            ),
            1 => 
            array (
                'invoice_status_id' => 2,
                'invoice_status_name' => 'Paid',
            ),
            2 => 
            array (
                'invoice_status_id' => 3,
                'invoice_status_name' => 'Cancelled',
            ),
            3 => 
            array (
                'invoice_status_id' => 4,
                'invoice_status_name' => 'Rejected',
            ),
            4 => 
            array (
                'invoice_status_id' => 5,
                'invoice_status_name' => 'Pending Approval',
            ),
            5 => 
            array (
                'invoice_status_id' => 6,
                'invoice_status_name' => 'Partially Paid',
            ),
        ));
        
        
    }
}