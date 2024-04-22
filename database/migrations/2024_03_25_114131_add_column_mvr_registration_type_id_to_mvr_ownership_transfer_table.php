<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMvrRegistrationTypeIdToMvrOwnershipTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_ownership_transfer', function (Blueprint $table) {
            $table->string('mvr_registration_type_id')->nullable();
            $table->string('old_plate_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mvr_ownership_transfer', function (Blueprint $table) {
            //
        });
    }
}
