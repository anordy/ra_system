<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('port_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id');
            $table->unsignedBigInteger('config_id');
            $table->decimal('value',38, 2);
            $table->decimal('vat',38, 2);
            $table->timestamps();
            $table->foreign('config_id')->references('id')->on('port_configs');
            $table->foreign('return_id')->references('id')->on('port_returns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('port_return_items');
    }
}
