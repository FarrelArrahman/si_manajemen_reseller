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
            $table->string('shop_name', 50);
            $table->string('shop_address');
            $table->integer('province');
            $table->integer('city');
            $table->integer('postal_code');
            $table->string('phone_number', 20);
            $table->json('social_media')->nullable();
            $table->string('account_number', 50);
            $table->string('bank_name');
            $table->string('bank_code', 3);
            $table->string('account_holder_name', 100);
            $table->string('reseller_status', 20);
            $table->string('reseller_registration_proof_of_payment')->nullable();
            $table->date('approval_date')->nullable();
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
