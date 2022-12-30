<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtRollbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debt_rollbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debt_id');
            $table->string('debt_type'); // Returns or assessment

            $table->unsignedBigInteger('rolled_from_debt_penalty_id'); // soft deleted penalty id
            $table->unsignedBigInteger('rolled_to_debt_penalty_id')->nullable();
            $table->decimal('interest', 20, 2);
            $table->decimal('penalty', 20, 2);
            $table->decimal('outstanding_amount', 20, 2);

            $table->unsignedBigInteger('rolled_by'); // soft deleted penalty id
            $table->timestamp('rolled_at'); // soft deleted penalty id

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
        Schema::dropIfExists('debt_rollbacks');
    }
}
