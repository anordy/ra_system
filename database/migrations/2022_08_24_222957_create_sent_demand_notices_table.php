<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentDemandNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_demand_notices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debt_id');
            $table->enum('sent_by', ['job', 'user']);
            $table->unsignedBigInteger('sent_by_id')->nullable();
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
        Schema::dropIfExists('sent_demand_notices');
    }
}
