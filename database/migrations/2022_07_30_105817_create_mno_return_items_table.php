<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMNOReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mno_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mno_return_id');
            $table->unsignedBigInteger('mno_config_id');
            $table->decimal('input_value',40,2);
            $table->decimal('vat',40,2);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('mno_return_id')->references('id')->on('mno_returns');
            $table->foreign('mno_config_id')->references('id')->on('mno_configs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mno_return_items');
    }
}
