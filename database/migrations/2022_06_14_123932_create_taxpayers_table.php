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
            $table->string('id_number');
            $table->string('extra_id_number')->nullable();

            $table->string('tin')->nullable();
            $table->string('tin_location')->nullable();
            $table->date('date_of_birth')->nullable();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->text('physical_address');
            $table->string('street');

            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique();
            $table->string('alt_mobile')->nullable();

            $table->unsignedBigInteger('region_id')->nullable();

            $table->string('permit_number')->nullable();

            $table->boolean('is_citizen');
            $table->boolean('is_first_login')->default(true);
            $table->unsignedBigInteger('country_id');

            $table->string('password')->nullable();
            $table->dateTime('biometric_verified_at');
            $table->dateTime('authorities_verified_at');
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
