<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateP2PTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p2_p_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_from_id');
            $table->string('transfer_to_id');
            $table->double('amount', 15, 8);
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
        Schema::dropIfExists('p2_p_transactions');
    }
}
