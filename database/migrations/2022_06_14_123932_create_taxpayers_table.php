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
            $table->string('reference_no')->unique();

            $table->unsignedBigInteger('id_type');
            $table->string('id_number');

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->text('physical_address');
            $table->string('street');

            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->string('alt_mobile')->nullable();

            $table->enum('location', ['Unguja', 'Pemba']);

            $table->string('work_permit')->nullable();
            $table->string('residence_permit')->nullable();

            $table->boolean('is_citizen');
            $table->unsignedBigInteger('country_id')->unique();

            $table->string('password');
            $table->dateTime('biometric_verified_at');
            $table->dateTime('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();

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
