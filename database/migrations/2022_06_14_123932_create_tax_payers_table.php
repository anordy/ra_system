<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxPayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_payers', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();

            $table->unsignedBigInteger('id_type');
            $table->string('id_number');

            $table->string('first_name')->unique();
            $table->string('middle_name')->unique();
            $table->string('last_name')->unique();
            $table->text('physical_address');
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->string('alt_mobile');

            $table->enum('location', ['Unguja', 'Pemba']);

            $table->string('work_permit');
            $table->string('residence_permit');

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
        Schema::dropIfExists('tax_payers');
    }
}
