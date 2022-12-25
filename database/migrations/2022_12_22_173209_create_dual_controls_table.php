<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDualControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dual_controls', function (Blueprint $table) {
            $table->id();
            $table->string('controllable_type');
            $table->unsignedBigInteger('controllable_type_id');
            $table->string('action');
            $table->string('action_detail');
            $table->string('old_values')->nullable();
            $table->string('new_values')->nullable();
            $table->unsignedBigInteger('create_by_id');
            $table->enum('status', ['pending', 'approved', 'rejected']);
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
        Schema::dropIfExists('dual_controls');
    }
}
