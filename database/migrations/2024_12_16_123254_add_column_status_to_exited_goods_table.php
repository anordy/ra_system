<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusToExitedGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exited_goods', function (Blueprint $table) {
            $table->string('exporter_name', 250)->nullable();
            $table->string('exporter_name', 250)->nullable();
            $table->string('destination_country_code', 4)->nullable();
            $table->string('destination_country_name', 250)->nullable();
            $table->string('exit_status', 50)->nullable();
            $table->string('tansad_form_type', 50)->nullable();
            $table->string('good_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exited_goods', function (Blueprint $table) {
            //
        });
    }
}
