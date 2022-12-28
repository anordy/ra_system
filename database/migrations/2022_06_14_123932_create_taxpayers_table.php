<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxpayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxpayers', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->nullable()->unique();

            $table->unsignedBigInteger('id_type');
            $table->string('nida_no')->nullable();
            $table->string('zanid_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('permit_number')->nullable();

            $table->timestamp('nida_verified_at')->nullable();
            $table->timestamp('zanid_verified_at')->nullable();
            $table->timestamp('passport_verified_at')->nullable();

            $table->string('tin')->nullable();
            $table->string('tin_location')->nullable();
            $table->date('date_of_birth')->nullable();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('physical_address');
            $table->string('street');

            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique();
            $table->string('alt_mobile')->nullable();

            $table->unsignedBigInteger('region_id')->nullable();

            $table->boolean('is_citizen');
            $table->boolean('is_first_login')->default(true);
            $table->unsignedBigInteger('country_id');

            $table->string('password')->nullable();
            $table->dateTime('biometric_verified_at');
            $table->dateTime('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();

            $table->string('ci_payload', 4000)->nullable();
            $table->boolean('failed_verification')->default(0);

            $table->foreign('id_type')->references('id')->on('id_types');
            $table->foreign('country_id')->references('id')->on('countries');
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
        Schema::dropIfExists('taxpayers');
    }
}
