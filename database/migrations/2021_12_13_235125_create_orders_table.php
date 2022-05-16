<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained();
            $table->foreignId('handled_by')->constrained('users');
            $table->text('order_notes');
            $table->integer('discount');
            $table->text('order_address');
            $table->string('province');
            $table->string('city');
            $table->string('zip_code');
            $table->foreignId('order_shipping_type_id')->constrained();
            $table->datetime('order_date');
            $table->string('order_status');
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
        Schema::dropIfExists('orders');
    }
}
