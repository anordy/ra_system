<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vat_return_id');
            $table->unsignedBigInteger('vat_return_config_id');
            $table->decimal('input_amount',40,2);
            $table->decimal('vat_amount',40,2);
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
        Schema::dropIfExists('vat_return_items');
    }
}
