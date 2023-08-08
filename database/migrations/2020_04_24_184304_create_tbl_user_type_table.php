<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.6.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblUserTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_type', function (Blueprint $table) {
            $table->increments('user_type_id');
            $table->string('user_type_name', 45)->charset('latin1')->collation('latin1_swedish_ci');
            $table->dateTime('user_type_cdate');
            $table->dateTime('user_type_udate');
            
            
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user_type');
    }
}