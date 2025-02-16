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
<<<<<<< HEAD
            if (Schema::hasColumn('mvr_colors', 'name')) {
                // The column exists
            } else {
                $table->string('name')->nullable();

            }
=======
            // $table->string('name')->nullable();
>>>>>>> 26e0dbd1b (uncomment on migration which throw expection)
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
