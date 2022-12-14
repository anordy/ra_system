<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaResponsiblePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wa_responsible_persons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('withholding_agent_id');
            $table->unsignedBigInteger('responsible_person_id');
            $table->unsignedBigInteger('business_id')->nullable();
            $table->string('title');
            $table->string('position');
            $table->unsignedBigInteger('officer_id');
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->foreign('officer_id')->references('id')->on('users');
            $table->foreign('withholding_agent_id')->references('id')->on('withholding_agents');
            $table->foreign('responsible_person_id')->references('id')->on('taxpayers');
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
        Schema::dropIfExists('wa_responsible_persons');
    }
}
