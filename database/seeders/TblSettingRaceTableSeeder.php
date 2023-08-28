<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSettingRaceTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_setting_race')->delete();
        
        \DB::table('tbl_setting_race')->insert(array (
            0 => 
            array (
                'setting_race_id' => 1,
                'setting_race_name' => 'Chinese',
            ),
            1 => 
            array (
                'setting_race_id' => 2,
                'setting_race_name' => 'Bumiputera',
            ),
            2 => 
            array (
                'setting_race_id' => 3,
                'setting_race_name' => 'Myanmar',
            ),
            3 => 
            array (
                'setting_race_id' => 4,
                'setting_race_name' => 'Indonesian',
            ),
            4 => 
            array (
                'setting_race_id' => 5,
                'setting_race_name' => 'Indian',
            ),
        ));
        
        
    }
}