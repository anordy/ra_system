<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDlLicenseRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dl_license_restrictions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dl_license_application_id');
            $table->unsignedBigInteger('dl_license_id')->nullable();
            $table->unsignedBigInteger('dl_restriction_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dl_license_restrictions');
    }
}
