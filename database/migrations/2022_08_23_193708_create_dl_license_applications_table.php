<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDlLicenseApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dl_license_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('dl_drivers_license_owner_id')->nullable();
            $table->unsignedBigInteger('dl_blood_group_id');
            $table->unsignedBigInteger('dl_license_duration_id')->nullable();
            $table->date('dob')->nullable();
            $table->string('competence_number',50)->nullable();
            $table->string('certificate_number',50)->nullable();
            $table->string('confirmation_number',50)->nullable();
            $table->string('photo_path',100)->nullable();
            $table->string('license_restrictions')->nullable();
            $table->enum('type',['FRESH','DUPLICATE','RENEW'])->default('FRESH');
            $table->unsignedBigInteger('dl_application_status_id');
            $table->timestamps();

            $table->foreign('dl_application_status_id')->references('id')->on('dl_application_status');
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
            $table->foreign('dl_blood_group_id')->references('id')->on('dl_blood_groups');
            $table->foreign('dl_drivers_license_owner_id')->references('id')->on('dl_drivers_license_owners');
            $table->foreign('dl_license_duration_id')->references('id')->on('dl_license_durations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dl_license_applications');
    }
}
