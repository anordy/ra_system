<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalColumnsToDlLicenseApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dl_license_applications', function (Blueprint $table) {

            // Drop foreign key constraints
            $table->dropForeign(['dl_application_status_id']);
            $table->dropForeign(['taxpayer_id']);
            $table->dropForeign(['dl_blood_group_id']);
            $table->dropForeign(['dl_drivers_license_owner_id']);
            $table->dropForeign(['dl_license_duration_id']);

          // Drop existing columns
            $table->dropColumn('taxpayer_id');
            $table->dropColumn('dl_blood_group_id');
            $table->dropColumn('dl_license_duration_id');
            $table->dropColumn('dob');
            $table->dropColumn('photo_path');
            $table->dropColumn('dl_application_status_id');

            // Add new columns
            $table->unsignedBigInteger('dl_application_status_id')->nullable();
            $table->unsignedBigInteger('driving_school_id');
            $table->unsignedBigInteger('license_duration_id');
            $table->unsignedBigInteger('license_duration');
            $table->longText('completion_certificate')->nullable();
            $table->longText('lost_report')->nullable();
            $table->string('license_restrictions')->nullable();
            $table->string('marking')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('status', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dl_license_applications', function (Blueprint $table) {

            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('dl_drivers_license_owner_id')->nullable();
            $table->unsignedBigInteger('dl_blood_group_id');
            $table->unsignedBigInteger('dl_license_duration_id')->nullable();
            $table->date('dob')->nullable();
            $table->string('photo_path', 100)->nullable();
            $table->unsignedBigInteger('dl_application_status_id');
        });
    }
}
