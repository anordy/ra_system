<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZmReconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zm_recons', function (Blueprint $table) {
            $table->id();
            $table->date('TnxDt')->nullable();
            $table->integer('ReconcOpt')->nullable();
            $table->string('ReconcStsCode', 50)->nullable();
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
        Schema::dropIfExists('zm_recons');
    }
}
