<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('username');
            $table->string('refNo');
            $table->string('refNoCpy');
            $table->string('country');
            $table->string('mobile');
            $table->string('email');
            $table->string('address');
            $table->string('state');
            $table->string('gender');
            $table->string('bdate');
            $table->string('wallet_type');
            $table->string('wallet_id');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // this sttus is used to active or inactive user by admin
            $table->string('status');
            $table->string('profile_image');
            $table->string('login_status');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
