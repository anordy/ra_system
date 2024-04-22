<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDlApplicationLicenseClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dl_app_license_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dl_license_application_id');
            $table->unsignedBigInteger('dl_license_class_id');
            $table->timestamps();

            // $table->foreign('dl_license_class_id')->references('id')->on('dl_license_classes');
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
        Schema::dropIfExists('dl_app_license_classes');
    }
}
