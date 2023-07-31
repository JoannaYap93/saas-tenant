<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        \DB::table('tbl_user_type')->insert(array (
            0 => 
            array (
                'user_type_id' => 1,
                'user_type_name' => 'Super Administrator',
                'user_type_cdate' => '0000-00-00 00:00:00',
                'user_type_udate' => '0000-00-00 00:00:00',
            ),
            1 => 
            array (
                'user_type_id' => 2,
                'user_type_name' => 'Administrator',
                'user_type_cdate' => '0000-00-00 00:00:00',
                'user_type_udate' => '0000-00-00 00:00:00',
            ),
            2 => 
            array (
                'user_type_id' => 3,
                'user_type_name' => 'Read Only Administrator',
                'user_type_cdate' => '0000-00-00 00:00:00',
                'user_type_udate' => '0000-00-00 00:00:00',
            ),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
