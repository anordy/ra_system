<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDurationToDlLicenseApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP TABLE dl_license_applications CASCADE CONSTRAINTS PURGE');

        Schema::create('dl_license_applications', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->timestamp('dob');
            $table->string('confirmation_number');
            $table->unsignedBigInteger('blood_group_id');
            $table->unsignedBigInteger('license_duration_id')->nullable();
            $table->string('certificate_of_competence');
            $table->unsignedBigInteger('taxpayer_id')->nullable();
            $table->string('type')->nullable();
            $table->string('marking')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('photo_path')->nullable();
            $table->unsignedBigInteger('previous_application_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
            //
        });
    }
}
