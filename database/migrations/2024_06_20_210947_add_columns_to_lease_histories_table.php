<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLeaseHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('land_lease_histories', function (Blueprint $table) {
            $table->date('rent_commence_date')->nullable();
            $table->string('lease_for')->nullable();
            $table->integer('area')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('land_lease_histories', function (Blueprint $table) {
            $table->dropColumn('rent_commence_date');
            $table->dropColumn('lease_for');
            $table->dropColumn('area');
        });
    }
}
