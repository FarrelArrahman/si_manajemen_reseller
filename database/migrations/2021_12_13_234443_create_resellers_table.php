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
            $table->string('shop_name');
            $table->string('shop_address');
            $table->string('province');
            $table->string('city');
            $table->string('postal_code');
            $table->string('phone_number');
            $table->json('social_media')->nullable();
            $table->string('shopee_link');
            $table->string('reseller_status');
            $table->string('reseller_registration_proof_of_payment')->nullable();
            $table->string('approval_date')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->string('rejection_reason')->nullable();
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
