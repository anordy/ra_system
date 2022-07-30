<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBFOReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bfo_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bfo_return_id');
            $table->unsignedBigInteger('bfo_config_id');
            $table->decimal('input_amount', 40,2); 
            $table->decimal('vat_amount', 40,2);
            $table->softDeletes();
            $table->timestamps();

            // $table->foreign('bfo_return_id')->references('id')->on('bfo_returns');
            // $table->foreign('bfo_config_id')->references('id')->on('bfo_configs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bfo_return_items');
    }
}
