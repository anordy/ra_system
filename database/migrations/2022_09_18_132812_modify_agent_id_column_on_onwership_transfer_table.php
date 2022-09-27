<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAgentIdColumnOnOnwershipTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_ownership_transfer', function(Blueprint $table) {
            $table->unsignedBigInteger('mvr_agent_id')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mvr_ownership_transfer', function(Blueprint $table) {
            $table->unsignedBigInteger('mvr_agent_id')->nullable(false)->change();
        });
    }
}
