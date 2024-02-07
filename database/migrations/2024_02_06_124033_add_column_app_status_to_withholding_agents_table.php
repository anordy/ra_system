<?php

use App\Enum\DisputeStatus;
use App\Enum\WithholdingAgentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAppStatusToWithholdingAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withholding_agents', function (Blueprint $table) {
            $table->enum('app_status', WithholdingAgentStatus::getConstants())->default(WithholdingAgentStatus::DRAFT);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withholding_agents', function (Blueprint $table) {
            $table->dropColumn('app_status');
        });
    }
}
