<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnExtendedDateToMvrTemporaryTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_temporary_transports', function (Blueprint $table) {
            $table->timestamp('extended_date')->nullable();
            $table->string('extended_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mvr_temporary_transports', function (Blueprint $table) {
            //
        });
    }
}
