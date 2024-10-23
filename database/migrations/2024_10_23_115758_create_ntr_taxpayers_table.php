<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtrTaxpayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ntr_taxpayers', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->nullable()->unique()->index('ntr_taxpayers_reference_no');
            $table->string('zanid_no')->nullable();
            $table->unsignedBigInteger('country_id');
            $table->string('passport_no')->nullable();
            $table->string('permit_number')->nullable();
            $table->timestamp('passport_verified_at')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('physical_address')->nullable();
            $table->string('email')->unique()->nullable()->index('ntr_taxpayers_email');
            $table->string('mobile')->unique();
            $table->string('alt_mobile')->nullable();
            $table->boolean('is_first_login')->default(true);
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->string('ci_payload', 4000)->nullable();
            $table->boolean('failed_verification')->default(0);
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
        Schema::dropIfExists('ntr_taxpayers');
    }
}
