<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtScheduleStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debt_schedule_states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debt_id');
            $table->string('debt_type'); // Returns or assessment
            $table->unsignedBigInteger('previous_debt_penalty_id')->nullable();
            $table->unsignedBigInteger('current_debt_penalty_id')->nullable();
            $table->decimal('interest', 20, 2);
            $table->decimal('penalty', 20, 2);
            $table->decimal('total_amount', 20, 2);
            $table->decimal('outstanding_amount', 20, 2);
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
        Schema::dropIfExists('debt_schedule_states');
    }
}
