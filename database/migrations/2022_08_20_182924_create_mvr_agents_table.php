<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxpayer_id');
            $table->string('agent_number',20);
            $table->date('registration_date');
            $table->enum('status',['ACTIVE','INACTIVE']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_agents');
    }
}
