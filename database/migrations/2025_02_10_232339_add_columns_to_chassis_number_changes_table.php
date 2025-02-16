<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToChassisNumberChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chassis_number_changes', function (Blueprint $table) {
            $table->string('previous_chassis_number')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('engine_capacity')->nullable();
            $table->string('year_of_manufacture')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chassis_number_changes', function (Blueprint $table) {

        });
    }
}
