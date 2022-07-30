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
            $table->unsignedBigInteger('port_return_id');
            $table->unsignedBigInteger('port_return_config_id');
            $table->decimal('input_amount', 40, 2);
            $table->decimal('port_amount', 40, 2);
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
        Schema::dropIfExists('port_return_items');
    }
}
