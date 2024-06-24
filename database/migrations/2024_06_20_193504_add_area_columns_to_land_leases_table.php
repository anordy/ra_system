<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreaColumnsToLandLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('land_leases', function (Blueprint $table) {
            $table->integer('area')->nullable();
            $table->string('lease_for')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('land_leases', function (Blueprint $table) {
            $table->dropColumn('area');
            $table->dropColumn('lease_for');
        });
    }
}
