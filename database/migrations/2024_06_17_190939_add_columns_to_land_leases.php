<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLandLeases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('land_leases', function (Blueprint $table) {
            $table->date('rent_commence_date');
            $table->integer('lease_status')->default(1)->comment('1=Active, 2=Inactive');
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
            $table->dropColumn('rent_commence_date');
            $table->dropColumn('lease_status');
        });
    }
}
