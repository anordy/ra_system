<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDlFeesTableAddDefaultToGfsCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dl_fees', function (Blueprint $table) {

            $table->string('gfs_code')->default('116101')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dl_fees', function (Blueprint $table) {
            // Revert the changes made in the up() method
            $table->string('gfs_code')->change();
        });
    }
}

