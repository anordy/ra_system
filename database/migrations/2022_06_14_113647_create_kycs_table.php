<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKYCSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->nullable();

            $table->unsignedBigInteger('id_type');
            $table->string('nida_no')->nullable();
            $table->string('zanid_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('permit_number')->nullable();

            $table->timestamp('nida_verified_at')->nullable();
            $table->timestamp('zanid_verified_at')->nullable();
            $table->timestamp('passport_verified_at')->nullable();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('physical_address');

            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique();
            $table->string('alt_mobile')->nullable();

            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('ward_id')->nullable();
            $table->unsignedBigInteger('street_id')->nullable();

            $table->bigInteger('verified_by')->nullable();

            $table->boolean('is_citizen');
            $table->unsignedBigInteger('country_id');

            $table->foreign('id_type')->references('id')->on('id_types');
            $table->foreign('country_id')->references('id')->on('countries');

            // Biometric Enrolled
            $table->timestamp('biometric_verified_at')->nullable();
            $table->softDeletes();

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
        Schema::dropIfExists('k_y_c_s');
    }
}
