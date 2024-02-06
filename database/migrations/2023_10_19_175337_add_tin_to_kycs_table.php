<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTinToKycsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kycs', function (Blueprint $table) {
            $table->string('tin_no')->nullable();
            $table->dateTime('tin_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kycs', function (Blueprint $table) {
            $table->dropColumn('tin_no');
            $table->dropColumn('tin_verified_at');
        });
    }
}
