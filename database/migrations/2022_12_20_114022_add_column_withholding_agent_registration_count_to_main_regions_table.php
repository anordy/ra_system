<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnWithholdingAgentRegistrationCountToMainRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_regions', function (Blueprint $table) {
            $table->unsignedInteger('withholding_agent_registration_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_regions', function (Blueprint $table) {
            $table->dropColumn('withholding_agent_registration_count');
        });
    }
}
