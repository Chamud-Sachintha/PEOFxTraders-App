<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_payments', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('wallet_type');
            $table->string('wallet_address');
            $table->string('package_id');
            $table->string('txn_number');
            $table->double('amount_with_interest', 15, 8);
            $table->double('interest', 15, 8);
            $table->double('daily_int_amount', 15, 8);
            $table->string('status');
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
        Schema::dropIfExists('package_payments');
    }
}
