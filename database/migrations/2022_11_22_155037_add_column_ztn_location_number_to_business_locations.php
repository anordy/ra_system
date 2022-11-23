<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnZtnLocationNumberToBusinessLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_locations', function (Blueprint $table) {
            //
            $table->string('ztn_location_number')->after('zin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_locations', function (Blueprint $table) {
            //
            $table->dropColumn('ztn_location_number');
        });
    }
}
