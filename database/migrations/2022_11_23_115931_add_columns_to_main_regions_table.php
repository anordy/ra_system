<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMainRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_regions', function (Blueprint $table) {
            //
            $table->unsignedInteger('vat_local')->default(0);
            $table->unsignedInteger('hotel_vat')->default(0);
            $table->unsignedInteger('mno_vat')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_regions', function (Blueprint $table) {
            //
            $table->dropColumn('vat_local');
            $table->dropColumn('hotel_vat');
            $table->dropColumn('mno_vat');
        });
    }
}
