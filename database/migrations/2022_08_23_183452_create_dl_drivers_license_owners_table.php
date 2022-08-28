<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDlDriversLicenseOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dl_drivers_license_owners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('dl_blood_group_id');
            $table->date('dob');
            $table->string('competence_number',50);
            $table->string('certificate_number',50);
            $table->string('confirmation_number',50);
            $table->string('photo_path',100);
            $table->timestamps();

            $table->foreign('dl_blood_group_id')->references('id')->on('dl_blood_groups');
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');

        });
    }

    /**
    6 CONFIRMATION NUMBER
     *competence_id
     *
    7 PHOTO_IMAGE
     */

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dl_drivers_license_owners');
    }
}
