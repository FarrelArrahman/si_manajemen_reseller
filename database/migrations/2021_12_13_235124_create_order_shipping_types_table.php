<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderShippingTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_shipping_types', function (Blueprint $table) {
            $table->id();
            $table->string('order_shipping_type');
            $table->text('order_shipping_type_desc');
            $table->integer('order_shipping_type_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_shipping_types');
    }
}
