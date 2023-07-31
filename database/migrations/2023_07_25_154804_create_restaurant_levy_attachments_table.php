<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantLevyAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_levy_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->unsignedBigInteger('hotel_return_id')->nullable();
            $table->integer('no_of_days');
            $table->decimal('food_sales', 20,2);
            $table->decimal('beverage_sales', 20,2);
            $table->decimal('bar_sales', 20,2);
            $table->decimal('other_sales', 20,2);
            $table->decimal('total_revenue', 20,2);
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
        Schema::dropIfExists('restaurant_levy_attachments');
    }
}
