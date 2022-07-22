<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->integer('province');
            $table->integer('city');
            $table->string('postal_code');
            $table->string('customer_service_phone_number');
            $table->string('account_number');
            $table->string('bank_name');
            $table->string('bank_code');
            $table->string('account_holder_name');
            $table->string('auth_background_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configurations');
    }
}
