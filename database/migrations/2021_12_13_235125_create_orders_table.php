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
            $table->string('code')->unique();
            $table->foreignId('ordered_by')->constrained('users');
            $table->foreignId('handled_by')->nullable()->constrained('users');
            $table->text('notes');
            $table->integer('discount');
            $table->text('address');
            $table->string('province');
            $table->string('city');
            $table->string('postal_code');
            $table->foreignId('order_type_id')->constrained();
            $table->datetime('date');
            $table->string('status');
            $table->string('rejection_reason');
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
