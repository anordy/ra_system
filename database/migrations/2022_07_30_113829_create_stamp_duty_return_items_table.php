<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStampDutyReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stamp_duty_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stamp_duty_return_id');
            $table->unsignedBigInteger('stamp_duty_return_config_id');
            $table->decimal('input_amount',40,2);
            $table->decimal('tax_amount',40,2);
            $table->unsignedBigInteger('taxpayer_id');
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
        Schema::dropIfExists('stamp_duty_return_items');
    }
}
