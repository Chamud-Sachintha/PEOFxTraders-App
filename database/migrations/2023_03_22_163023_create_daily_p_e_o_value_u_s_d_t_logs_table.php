<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyPEOValueUSDTLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_p_e_o_value_u_s_d_t_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->double('amount', 15, 8);
            $table->string('time');
            $table->string('status');

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
        Schema::dropIfExists('daily_p_e_o_value_u_s_d_t_logs');
    }
}
