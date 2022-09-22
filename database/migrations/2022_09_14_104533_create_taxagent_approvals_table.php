<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxagentApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxagent_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_agent_id');
            $table->string('initial_status');
            $table->string('destination_status');
            $table->string('comment')->nullable();
            $table->unsignedBigInteger('approved_by_id')->nullable();
            $table->dateTimeTz('approved_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxagent_approvals');
    }
}
