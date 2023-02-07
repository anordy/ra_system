<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOriginalOperatorsToWorkflowTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workflow_tasks', function (Blueprint $table) {
            $table->string('original_operators')->nullable()->after('operators');
        });
    }

}
