<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnsOnMvrTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_motor_vehicles', function(Blueprint $table) {
            $table->dropConstrainedForeignId('agent_taxpayer_id');
            $table->unsignedBigInteger('mvr_agent_id')->nullable();
            $table->foreign('mvr_agent_id')->references('id')->on('mvr_agents');
        });

        Schema::table('mvr_registration_change_requests', function(Blueprint $table) {
            $table->dropConstrainedForeignId('agent_taxpayer_id');
            $table->unsignedBigInteger('mvr_agent_id')->nullable();
            $table->foreign('mvr_agent_id')->references('id')->on('mvr_agents');
        });

        Schema::table('mvr_ownership_transfer', function(Blueprint $table) {
            $table->dropConstrainedForeignId('agent_taxpayer_id');
            $table->unsignedBigInteger('mvr_agent_id')->nullable();
            $table->foreign('mvr_agent_id')->references('id')->on('mvr_agents');
        });

        Schema::table('mvr_de_registration_requests', function(Blueprint $table) {
            $table->dropConstrainedForeignId('agent_taxpayer_id');
            $table->unsignedBigInteger('mvr_agent_id');
            $table->foreign('mvr_agent_id')->references('id')->on('mvr_agents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
