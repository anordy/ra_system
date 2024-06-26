<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlateNumberTypeIdToMvrRegistrationStatusChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_registrations_status_change', function (Blueprint $table) {
            $table->unsignedBigInteger('mvr_plate_number_type_id')->nullable();
            $table->dropColumn('plate_number_color_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mvr_registration_status_changes', function (Blueprint $table) {
            //
        });
    }
}
