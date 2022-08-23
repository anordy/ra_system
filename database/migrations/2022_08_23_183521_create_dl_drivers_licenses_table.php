<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDlDriversLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dl_drivers_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dl_drivers_license_owner_id');
            $table->string('license_number',20);
            $table->unsignedBigInteger('dl_license_duration_id');
            $table->date('issued_date');
            $table->date('expiry_date');
            $table->unsignedBigInteger('dl_license_class_id');
            $table->string('license_restrictions');
            $table->timestamps();

            $table->foreign('dl_license_duration_id')->references('id')->on('dl_license_durations');
            $table->foreign('dl_drivers_license_owner_id')->references('id')->on('dl_drivers_license_owners');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dl_drivers_licenses');
    }
}
