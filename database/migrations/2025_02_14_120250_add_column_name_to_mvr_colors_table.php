<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNameToMvrColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_colors', function (Blueprint $table) {
            if (Schema::hasColumn('mvr_colors', 'name')) {
                // The column exists
            } else {
                $table->string('name')->nullable();

            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mvr_colors', function (Blueprint $table) {
            //
        });
    }
}
