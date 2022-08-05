<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusToPetroleumReturns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('petroleum_returns', function (Blueprint $table) {
            //
            $table->enum('status',['submitted', 'complete'])->default('submitted')->after('road_lincence_fee');
            $table->string('currency')->after('road_lincence_fee');
            $table->integer('edited_count')->default(0)->after('road_lincence_fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('petroleum_returns', function (Blueprint $table) {
            //
            $table->dropColumn('currency');
            $table->dropColumn('status');
        });
    }
}
