<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReliefItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relief_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('vfms_item_id')->nullable();
            $table->unsignedBigInteger('relief_id');
            $table->string('item_name');
            $table->decimal('quantity');
            $table->string('unit')->nullable();
            // $table->string('description');
            $table->decimal('amount_per_item');
            $table->decimal('amount');
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
        Schema::dropIfExists('relief_items');
    }
}
