<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBirthCertNoColumnToDlLicenseOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dl_drivers_license_owners', function (Blueprint $table) {
            $table->string('birth_certificate')->nullable();
            $table->string('birth_certificate_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dl_drivers_license_owners', function (Blueprint $table) {
            //
        });
    }
}
