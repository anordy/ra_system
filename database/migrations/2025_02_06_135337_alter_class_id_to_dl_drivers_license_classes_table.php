<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterClassIdToDlDriversLicenseClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dl_drivers_license_classes', function (Blueprint $table) {
            $table->unsignedBigInteger('dl_license_application_id')->nullable();
            $table->string('certificate_number')->nullable();
            $table->timestamp('certificate_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dl_drivers_license_classes', function (Blueprint $table) {
            //
        });
    }
}
