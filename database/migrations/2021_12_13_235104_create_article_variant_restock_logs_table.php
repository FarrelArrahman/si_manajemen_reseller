<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleVariantRestockLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_variant_restock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_variant_id')->constrained();
            $table->integer('restock_quantity');
            $table->datetime('restock_date');
            $table->integer('restock_status');
            $table->text('restock_note');
            $table->foreignId('restocked_by')->constrained('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_variant_restock_logs');
    }
}
