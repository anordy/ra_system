<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsToAirbnbAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('airbnb_attachments', function (Blueprint $table) {
            $table->integer('no_of_pax_for_r')->nullable()->change();
            $table->integer('no_of_pax_for_nr')->nullable()->change();
            $table->decimal('revenue_for_food', 20,2)->nullable()->change();
            $table->decimal('revenue_for_beverage', 20,2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('airbnb_attachments', function (Blueprint $table) {
            //
        });
    }
}
