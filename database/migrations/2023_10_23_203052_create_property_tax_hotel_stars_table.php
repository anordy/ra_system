<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyTaxHotelStarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_tax_hotel_stars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('no_of_stars');
            $table->decimal('amount_charged', 20,2);
            $table->unsignedBigInteger('currency_id');
            $table->string('is_approved')->default(0);
            $table->boolean('is_updated')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_tax_hotel_stars');
    }
}
