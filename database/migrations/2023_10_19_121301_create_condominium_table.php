<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCondominiumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condominium', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('region_id');
            $table->integer('district_id');
            $table->integer('ward_id');
            $table->integer('street_id');
            $table->enum('status', ['complete', 'incomplete']);
            $table->integer('staff_id');
            $table->softDeletes();
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
        Schema::dropIfExists('condominium');
    }
}
