<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('payment_type_id')->constrained();
            $table->integer('payment_amount');
            $table->integer('payment_change');
            $table->datetime('payment_date');
            $table->integer('payment_status');
            $table->string('proof_of_payment');
            $table->foreignId('approved_by')->constrained('admins');
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
        Schema::dropIfExists('order_payments');
    }
}
