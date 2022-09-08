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
            $table->string('id_number');

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('physical_address');
            $table->string('street');
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique();
            $table->string('alt_mobile')->nullable();

            $table->unsignedBigInteger('region_id')->nullable();

            $table->string('work_permit')->nullable(); // nullable
            $table->string('residence_permit')->nullable(); // nullable

            $table->boolean('is_citizen');
            $table->unsignedBigInteger('country_id');

            $table->foreign('id_type')->references('id')->on('id_types');
            $table->foreign('country_id')->references('id')->on('countries');

            // Flags
            // Information verified from NIDA/Immigration
            $table->dateTime('authorities_verified_at')->nullable();

            // Biometric Enrolled
            $table->dateTime('biometric_verified_at')->nullable();

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
