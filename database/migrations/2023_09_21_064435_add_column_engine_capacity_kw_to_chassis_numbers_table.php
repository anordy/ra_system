<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnEngineCapacityKwToChassisNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chassis_numbers', function (Blueprint $table) {
            $table->string('engine_cubic_capacity')->nullable();
            $table->string('engine_kw_capacity')->nullable();
            $table->string('engine_hp_capacity')->nullable();
            $table->string('plate_number')->nullable();
            $table->string('filter_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chassis_numbers', function (Blueprint $table) {
            //
        });
    }
}
