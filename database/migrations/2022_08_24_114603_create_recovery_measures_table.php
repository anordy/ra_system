<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecoveryMeasuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recovery_measures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debt_id');
            $table->string('debt_type');
            $table->timestamp('approved_on')->nullable();
            $table->enum('status', ['pending', 'approved', 'correction', 'rejected', 'unassigned'])->default('unassigned');
            $table->string('marking')->nullable();
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
        Schema::dropIfExists('recovery_measures');
    }
}
