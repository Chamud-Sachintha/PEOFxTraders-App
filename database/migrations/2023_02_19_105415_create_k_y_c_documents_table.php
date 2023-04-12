<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKYCDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('k_y_c_documents', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('kyc_type');
            $table->string('f_image');
            $table->string('b_image');
            $table->string('selfi_image');
            $table->string('status');
            $table->string('reason');
            
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
        Schema::dropIfExists('k_y_c_documents');
    }
}
