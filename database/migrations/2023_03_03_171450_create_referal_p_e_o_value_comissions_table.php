<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferalPEOValueComissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referal_p_e_o_value_comissions', function (Blueprint $table) {
            $table->id();
            $table->string('from_user_id');
            $table->string('to_user_id');
            $table->string('peo_value');
            $table->string('time');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referal_p_e_o_value_comissions');
    }
}
