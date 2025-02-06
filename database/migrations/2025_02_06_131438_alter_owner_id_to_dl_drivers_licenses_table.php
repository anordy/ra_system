<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOwnerIdToDlDriversLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('dl_drivers_licenses');

        Schema::create('dl_drivers_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dl_license_application_id');
            $table->unsignedBigInteger('taxpayer_id')->nullable();
            $table->string('license_number')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('issued_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->string('marking')->nullable();
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
        Schema::table('dl_drivers_licenses', function (Blueprint $table) {
            //
        });
    }
}
