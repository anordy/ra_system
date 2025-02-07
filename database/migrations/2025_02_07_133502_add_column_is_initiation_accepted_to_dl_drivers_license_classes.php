<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsInitiationAcceptedToDlDriversLicenseClasses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dl_drivers_license_classes', function (Blueprint $table) {
            $table->boolean('is_initiation_accepted')->default(false);
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
