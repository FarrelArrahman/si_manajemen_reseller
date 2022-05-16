<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->foreignId('unit_id')->constrained();
            $table->foreignId('category_id')->default(0)->constrained();
            $table->string('default_photo')->nullable();
            $table->text('description')->nullable();
            $table->integer('product_status')->default(1);
            $table->foreignId('added_by')->nullable()->constrained('users');
            $table->foreignId('last_edited_by')->nullable()->constrained('users');
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
        Schema::dropIfExists('products');
    }
}
