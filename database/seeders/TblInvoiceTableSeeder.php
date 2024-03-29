<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblInvoiceTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_invoice')->delete();
        
        \DB::table('tbl_invoice')->insert(array (
            0 => 
            array (
                'invoice_id' => 1,
                'customer_id' => 1,
                'customer_name' => 'Felix',
                'customer_address' => '2, Jalan Merbah 1,',
                'customer_address2' => NULL,
                'customer_state' => 'Puchong',
                'customer_city' => 'Selangor',
                'customer_postcode' => '47100',
                'customer_country' => 'Malaysia',
                'invoice_subtotal' => '644.00',
                'invoice_total_discount' => '0.00',
                'invoice_total' => '644.00',
                'invoice_total_gst' => '0.00',
                'invoice_total_round_up' => '0.00',
                'invoice_grandtotal' => '644.00',
                'invoice_amount_paid' => NULL,
                'invoice_amount_remaining' => NULL,
                'company_id' => 1,
                'company_land_id' => 1,
                'company_bank_id' => 1,
                'user_id' => 1,
                'invoice_status_id' => 2,
                'invoice_no' => 'IN/webby/22070001',
                'invoice_created' => '2022-07-25 16:37:13',
                'invoice_updated' => '2022-07-25 16:38:23',
                'is_approved' => 0,
                'is_approved_date' => NULL,
                'invoice_date' => '2022-07-20 02:13:02',
                'invoice_remark' => NULL,
            ),
            1 => 
            array (
                'invoice_id' => 7321,
                'customer_id' => 2,
                'customer_name' => 'Mr. Hee YH',
                'customer_address' => '-',
                'customer_address2' => '-',
                'customer_state' => '-',
                'customer_city' => '-',
                'customer_postcode' => '-',
                'customer_country' => 'Malaysia',
                'invoice_subtotal' => '1046.50',
                'invoice_total_discount' => '0.00',
                'invoice_total' => '1046.50',
                'invoice_total_gst' => '0.00',
                'invoice_total_round_up' => '3.50',
                'invoice_grandtotal' => '1050.00',
                'invoice_amount_paid' => '1050.00',
                'invoice_amount_remaining' => '0.00',
                'company_id' => 7,
                'company_land_id' => 2,
                'company_bank_id' => 3,
                'user_id' => 4,
                'invoice_status_id' => 2,
                'invoice_no' => 'IN/HJF/23080028',
                'invoice_created' => '2023-08-23 16:50:16',
                'invoice_updated' => '2023-08-23 17:24:06',
                'is_approved' => 1,
                'is_approved_date' => '2023-08-23',
                'invoice_date' => '2023-08-23 16:49:26',
                'invoice_remark' => NULL,
            ),
            2 => 
            array (
                'invoice_id' => 7325,
                'customer_id' => 3,
                'customer_name' => 'Gradestar Fruits Trading',
                'customer_address' => 'N/A',
                'customer_address2' => NULL,
                'customer_state' => 'Raub',
                'customer_city' => 'Raub',
                'customer_postcode' => '000000',
                'customer_country' => 'Malaysia',
                'invoice_subtotal' => '654.60',
                'invoice_total_discount' => '0.00',
                'invoice_total' => '654.60',
                'invoice_total_gst' => '0.00',
                'invoice_total_round_up' => '0.00',
                'invoice_grandtotal' => '654.60',
                'invoice_amount_paid' => NULL,
                'invoice_amount_remaining' => NULL,
                'company_id' => 6,
                'company_land_id' => 3,
                'company_bank_id' => 2,
                'user_id' => 2,
                'invoice_status_id' => 1,
                'invoice_no' => 'IN/DFN/23080047',
                'invoice_created' => '2023-08-23 21:07:07',
                'invoice_updated' => '2023-08-23 21:07:07',
                'is_approved' => 0,
                'is_approved_date' => NULL,
                'invoice_date' => '2023-08-23 21:04:32',
                'invoice_remark' => NULL,
            ),
            3 => 
            array (
                'invoice_id' => 7340,
                'customer_id' => 4,
                'customer_name' => 'Si Mee Fong',
                'customer_address' => '98, Kg Lui,',
                'customer_address2' => '-',
                'customer_state' => 'Pahang',
                'customer_city' => 'Raub',
                'customer_postcode' => '27600',
                'customer_country' => 'Malaysia',
                'invoice_subtotal' => '32.48',
                'invoice_total_discount' => '0.00',
                'invoice_total' => '32.48',
                'invoice_total_gst' => '0.00',
                'invoice_total_round_up' => '0.02',
                'invoice_grandtotal' => '32.50',
                'invoice_amount_paid' => NULL,
                'invoice_amount_remaining' => NULL,
                'company_id' => 8,
                'company_land_id' => 4,
                'company_bank_id' => 7,
                'user_id' => 4,
                'invoice_status_id' => 1,
                'invoice_no' => 'IN/CTF/23080051',
                'invoice_created' => '2023-08-24 14:54:04',
                'invoice_updated' => '2023-08-24 14:54:04',
                'is_approved' => 0,
                'is_approved_date' => NULL,
                'invoice_date' => '2023-08-24 14:53:31',
                'invoice_remark' => NULL,
            ),
            4 => 
            array (
                'invoice_id' => 7344,
                'customer_id' => 1,
                'customer_name' => 'WEECAN FRUITCHAIN SDN BHD',
                'customer_address' => 'LOT 2068 - 2069',
                'customer_address2' => 'LADANG JELEBU',
                'customer_state' => 'KUALA KLAWANG',
                'customer_city' => 'NEGERI SEMBILAN',
                'customer_postcode' => '71600',
                'customer_country' => 'Malaysia',
                'invoice_subtotal' => '317.35',
                'invoice_total_discount' => '0.00',
                'invoice_total' => '317.35',
                'invoice_total_gst' => '0.00',
                'invoice_total_round_up' => '0.00',
                'invoice_grandtotal' => '317.35',
                'invoice_amount_paid' => NULL,
                'invoice_amount_remaining' => NULL,
                'company_id' => 7,
                'company_land_id' => 5,
                'company_bank_id' => 3,
                'user_id' => 4,
                'invoice_status_id' => 1,
                'invoice_no' => 'IN/HJF/23080029',
                'invoice_created' => '2023-08-24 15:13:12',
                'invoice_updated' => '2023-08-24 15:13:12',
                'is_approved' => 0,
                'is_approved_date' => NULL,
                'invoice_date' => '2023-08-24 15:10:21',
                'invoice_remark' => NULL,
            ),
            5 => 
            array (
                'invoice_id' => 7347,
                'customer_id' => 1,
                'customer_name' => 'Gradestar Fruits Trading',
                'customer_address' => 'N/A',
                'customer_address2' => NULL,
                'customer_state' => 'Raub',
                'customer_city' => 'Raub',
                'customer_postcode' => '000000',
                'customer_country' => 'Malaysia',
                'invoice_subtotal' => '510.20',
                'invoice_total_discount' => '0.00',
                'invoice_total' => '510.20',
                'invoice_total_gst' => '0.00',
                'invoice_total_round_up' => '0.00',
                'invoice_grandtotal' => '510.20',
                'invoice_amount_paid' => NULL,
                'invoice_amount_remaining' => NULL,
                'company_id' => 7,
                'company_land_id' => 6,
                'company_bank_id' => 3,
                'user_id' => 4,
                'invoice_status_id' => 1,
                'invoice_no' => 'IN/DFN/23080049',
                'invoice_created' => '2023-08-24 16:56:58',
                'invoice_updated' => '2023-08-24 16:56:59',
                'is_approved' => 0,
                'is_approved_date' => NULL,
                'invoice_date' => '2023-08-24 16:29:26',
                'invoice_remark' => NULL,
            ),
            6 => 
            array (
                'invoice_id' => 7348,
                'customer_id' => 4,
                'customer_name' => 'WONG SON TRADING',
                'customer_address' => 'LOT 2068 - 2069',
                'customer_address2' => 'LADANG JELEBU',
                'customer_state' => 'KUALA KLAWANG',
                'customer_city' => 'NEGERI SEMBILAN',
                'customer_postcode' => '71600',
                'customer_country' => 'Malaysia',
                'invoice_subtotal' => '2262.20',
                'invoice_total_discount' => '0.00',
                'invoice_total' => '2262.20',
                'invoice_total_gst' => '0.00',
                'invoice_total_round_up' => '0.00',
                'invoice_grandtotal' => '2262.20',
                'invoice_amount_paid' => NULL,
                'invoice_amount_remaining' => NULL,
                'company_id' => 4,
                'company_land_id' => 4,
                'company_bank_id' => 8,
                'user_id' => 6,
                'invoice_status_id' => 1,
                'invoice_no' => 'IN/HX/23080088',
                'invoice_created' => '2023-08-25 08:06:23',
                'invoice_updated' => '2023-08-25 08:06:23',
                'is_approved' => 0,
                'is_approved_date' => NULL,
                'invoice_date' => '2023-08-24 12:24:19',
                'invoice_remark' => 'DO 2612',
            ),
            7 => 
            array (
                'invoice_id' => 7351,
                'customer_id' => 3,
                'customer_name' => '5S Durian & Fruits Trading',
                'customer_address' => 'PT2432 Kabin Kekal,',
                'customer_address2' => 'Jalan Besar Tras',
                'customer_state' => 'Pahang',
                'customer_city' => 'Raub',
                'customer_postcode' => '27670',
                'customer_country' => 'Malaysia',
                'invoice_subtotal' => '102.20',
                'invoice_total_discount' => '0.00',
                'invoice_total' => '102.20',
                'invoice_total_gst' => '0.00',
                'invoice_total_round_up' => '0.80',
                'invoice_grandtotal' => '103.00',
                'invoice_amount_paid' => '103.00',
                'invoice_amount_remaining' => '0.00',
                'company_id' => 2,
                'company_land_id' => 3,
                'company_bank_id' => 4,
                'user_id' => 2,
                'invoice_status_id' => 5,
                'invoice_no' => 'IN/ER/23080040',
                'invoice_created' => '2023-08-25 12:25:45',
                'invoice_updated' => '2023-08-25 12:26:06',
                'is_approved' => 0,
                'is_approved_date' => NULL,
                'invoice_date' => '2023-08-25 09:34:02',
                'invoice_remark' => NULL,
            ),
            8 => 
            array (
                'invoice_id' => 7352,
                'customer_id' => 2,
                'customer_name' => '5 S Durian & Fruits Trading',
                'customer_address' => 'PT2432 Kabin Kekal, Jalan Besar Tras',
                'customer_address2' => NULL,
                'customer_state' => 'Pahang',
                'customer_city' => 'Raub',
                'customer_postcode' => '27670',
                'customer_country' => 'Malaysia',
                'invoice_subtotal' => '432.20',
                'invoice_total_discount' => '0.00',
                'invoice_total' => '432.20',
                'invoice_total_gst' => '0.00',
                'invoice_total_round_up' => '0.80',
                'invoice_grandtotal' => '433.00',
                'invoice_amount_paid' => '433.00',
                'invoice_amount_remaining' => '0.00',
                'company_id' => 3,
                'company_land_id' => 2,
                'company_bank_id' => 5,
                'user_id' => 1,
                'invoice_status_id' => 5,
                'invoice_no' => 'IN/NMP/23080014',
                'invoice_created' => '2023-08-25 12:28:16',
                'invoice_updated' => '2023-08-25 12:28:36',
                'is_approved' => 0,
                'is_approved_date' => NULL,
                'invoice_date' => '2023-08-25 12:27:29',
                'invoice_remark' => NULL,
            ),
        ));
        
        
    }
}