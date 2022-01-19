<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('reseller_shop_name');
            $table->string('reseller_shop_address');
            $table->string('province');
            $table->string('city');
            $table->string('zip_code');
            $table->string('phone_number');
            $table->string('social_media');
            $table->string('shopee_link');
            $table->string('reseller_status');
            $table->string('reseller_preferences');
            $table->string('reseller_approval_date');
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
        Schema::dropIfExists('resellers');
    }
}
