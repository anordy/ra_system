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
            $table->string('reference_no')->unique();

            $table->unsignedBigInteger('id_type');
            $table->string('id_number');

            $table->string('first_name')->unique();
            $table->string('middle_name')->unique();
            $table->string('last_name')->unique();
            $table->string('physical_address')->unique();
            $table->string('street');
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->string('alt_mobile');

            $table->enum('location', ['Unguja', 'Pemba']);

            $table->string('work_permit')->nullable(); // nullable
            $table->string('residence_permit')->nullable(); // nullable

            $table->boolean('is_citizen');
            $table->unsignedBigInteger('country_id')->unique();

            $table->foreign('id_type')->references('id')->on('id_types');
            $table->foreign('country_id')->references('id')->on('countries');

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
