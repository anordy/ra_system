<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDlDriversLicenseOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dl_drivers_license_owners', function (Blueprint $table) {
            // Add new columns
		$table->id();

            $table->unsignedBigInteger('taxpayer_id');
            $table->string('blood_group',5);

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('physical_address');

            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique();
            $table->string('alt_mobile')->nullable();

            $table->date('dob');
            $table->string('certificate_number',50)->nullable();
            $table->longText('certificate_path')->nullable();
            $table->longText('photo_path')->nullable();
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
        Schema::dropIfExists('dl_drivers_license_owners');
    }
}
