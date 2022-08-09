<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtWaivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debt_waives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debt_id');
            $table->string('waive_type');
            $table->decimal('penalty',40,2)->nullable();
            $table->double('interest',3)->nullable();
            $table->text('reason');
            $table->enum('status',['approved','rejected','draft'])->default('draft');
            $table->text('comments')->nullable();
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
        Schema::dropIfExists('debt_waives');
    }
}
