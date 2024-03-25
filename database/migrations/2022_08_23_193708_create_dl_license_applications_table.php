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
            $table->unsignedBigInteger('dl_drivers_license_owner_id');
            $table->unsignedBigInteger('dl_application_status_id')->nullable();
            $table->unsignedBigInteger('driving_school_id');
            $table->unsignedBigInteger('license_duration_id');
            $table->unsignedBigInteger('license_duration');
            $table->string('competence_number',50)->nullable();
            $table->string('certificate_number',50)->nullable();
            $table->string('confirmation_number',50)->nullable();
            $table->string('license_restrictions')->nullable();
            $table->string('marking')->nullable();
            $table->longText('completion_certificate')->nullable();
            $table->longText('lost_report')->nullable();
            $table->enum('type',['FRESH','DUPLICATE','RENEW'])->default('FRESH');

            $table->string('payment_status')->nullable();
            $table->string('status',255)->nullable();
            $table->timestamps();

            // $table->foreign('dl_drivers_license_owner_id')->references('id')->on('dl_drivers_license_owners');
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
