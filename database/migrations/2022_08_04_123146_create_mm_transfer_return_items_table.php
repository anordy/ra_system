<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmTransferReurnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mm_transfer_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('config_id');
            $table->unsignedBigInteger('return_id');
            $table->decimal('value', 40,2); 
            $table->decimal('vat', 40,2);
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
        Schema::dropIfExists('mm_transfer_return_items');
    }
}
