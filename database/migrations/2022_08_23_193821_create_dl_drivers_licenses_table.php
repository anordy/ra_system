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
            $table->unsignedBigInteger('dl_license_application_id');
            $table->unsignedBigInteger('taxpayer_id');
            $table->string('license_number',20);
            $table->unsignedBigInteger('license_duration');
            $table->date('issued_date');
            $table->date('expiry_date');
            $table->string('license_restrictions');
            $table->enum('status',['ACTIVE','EXPIRED','LOST/DAMAGED'])->default('ACTIVE');
            $table->timestamps();

            // $table->foreign('dl_drivers_license_owner_id')->references('id')->on('dl_drivers_license_owners');
            // $table->foreign('dl_license_application_id')->references('id')->on('dl_license_applications');
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
