<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxAgentTrainingExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_agent_training_experiences', function (Blueprint $table) {
            $table->id();
	        $table->string('org_name');
	        $table->date('from');
	        $table->date('to');
	        $table->string('position_held');
	        $table->text('description');
	        $table->unsignedBigInteger('taxpayer_id');
	        $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
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
        Schema::dropIfExists('tax_agent_training_experiences');
    }
}
