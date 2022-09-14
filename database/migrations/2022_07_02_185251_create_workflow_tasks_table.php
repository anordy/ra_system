<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $status_choices = array("hold", "destroy", "reject", "running", "completed", "closed");
        Schema::create('workflow_tasks', function (Blueprint $table) use ($status_choices) {
            $table->id();
            $table->string('pinstance_type');
            $table->unsignedBigInteger('pinstance_id');
            $table->unsignedBigInteger('workflow_id');
            $table->string('name');
            $table->string('from_place');
            $table->string('to_place');
            $table->enum('owner', ['staff', 'system', 'taxpayer']);
            $table->string('operator_type');
            $table->string('operators');
            $table->dateTime('approved_on')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_type')->nullable();
            $table->enum('status', $status_choices)->default('running');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('workflow_tasks');
    }
}
