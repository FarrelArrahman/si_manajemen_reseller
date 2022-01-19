<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained();
            $table->integer('base_price');
            $table->integer('general_price');
            $table->integer('reseller_price');
            $table->integer('stock');
            $table->string('color');
            $table->string('article_variant_status');
            $table->foreignId('added_by')->constrained('admins');
            $table->foreignId('last_edited_by')->constrained('admins');
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
        Schema::dropIfExists('article_variants');
    }
}
