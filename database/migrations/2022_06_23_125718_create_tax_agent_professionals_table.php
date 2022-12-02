<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxAgentProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_agent_professionals', function (Blueprint $table) {
            $table->id();
	        $table->string('body_name');
	        $table->string('reg_no');
	        $table->string('passed_sections');
	        $table->date('date_passed');
            $table->string('attachment')->nullable();
	        $table->string('remarks');
	        $table->unsignedBigInteger('tax_agent_id');
			$table->foreign('tax_agent_id')->references('id')->on('tax_agents');
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
        Schema::dropIfExists('tax_agent_professionals');
    }
}
