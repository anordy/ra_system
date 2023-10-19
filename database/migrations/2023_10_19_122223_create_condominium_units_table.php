<?php

use App\Enum\CondominiumStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCondominiumUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condominium_units', function (Blueprint $table) {
            $table->id();
            $table->integer('condominium_storey_id');
            $table->integer('condominium_id');
            $table->string('name');
            $table->enum('status', CondominiumStatus::getConstants());
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
        Schema::dropIfExists('condominium_units');
    }
}
