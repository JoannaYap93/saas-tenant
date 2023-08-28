<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyPnlSubItemTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_pnl_sub_item')->delete();
        
        \DB::table('tbl_company_pnl_sub_item')->insert(array (
            0 => 
            array (
                'company_pnl_sub_item_id' => 1,
            'company_pnl_sub_item_name' => 'K1 (New Tree)',
                'company_pnl_item_id' => 5,
                'company_pnl_sub_item_code' => 'K1',
            ),
            1 => 
            array (
                'company_pnl_sub_item_id' => 2,
            'company_pnl_sub_item_name' => 'B10 (Below 10 Years)',
                'company_pnl_item_id' => 2,
                'company_pnl_sub_item_code' => 'B10',
            ),
            2 => 
            array (
                'company_pnl_sub_item_id' => 3,
            'company_pnl_sub_item_name' => 'A10 (Above 10 Years)',
                'company_pnl_item_id' => 2,
                'company_pnl_sub_item_code' => 'A10',
            ),
            3 => 
            array (
                'company_pnl_sub_item_id' => 4,
            'company_pnl_sub_item_name' => 'KM (Old Tree Grafted Musang)',
                'company_pnl_item_id' => 4,
                'company_pnl_sub_item_code' => 'KM',
            ),
            4 => 
            array (
                'company_pnl_sub_item_id' => 5,
            'company_pnl_sub_item_name' => 'KB (Old Tree Grafted Black Thorn)',
                'company_pnl_item_id' => 4,
                'company_pnl_sub_item_code' => 'KB',
            ),
        ));
        
        
    }
}