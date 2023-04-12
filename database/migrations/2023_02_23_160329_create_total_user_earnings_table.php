<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTotalUserEarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_user_earnings', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->double('total_earnings', 15, 8);
            $table->double('total_without_deduct', 15, 8);
            $table->string('earning_status');
            
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
        Schema::dropIfExists('total_user_earnings');
    }
}
