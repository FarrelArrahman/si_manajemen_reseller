<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->string('product_variant_name');
            $table->integer('base_price');
            $table->integer('general_price');
            $table->integer('reseller_price');
            $table->integer('stock');
            $table->string('color');
            $table->string('photo');
            $table->string('product_variant_status');
            $table->foreignId('added_by')->constrained('users');
            $table->foreignId('last_edited_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}
