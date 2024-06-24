<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReliefNumberColumnToVatSpecialReliefs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vat_special_reliefs', function (Blueprint $table) {
            //
            $table->string('relief_number')->nullable();
            $table->string('relief_percentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vat_special_reliefs', function (Blueprint $table) {
            //
            $table->dropColumn('relief_number');
            $table->dropColumn('relief_percentage');
        });
    }
}
