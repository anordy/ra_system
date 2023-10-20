<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRateOnHotelReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_return_items', function (Blueprint $table) {
            $table->decimal('rate',5, 2)->nullable();
            $table->decimal('rate_usd',5, 2)->nullable();
            $table->enum('rate_type', ['percentage', 'fixed'])->nullable();
            $table->enum('currency',['TZS', 'USD', 'BOTH'])->default('TZS')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
