<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatSpecialReliefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_special_reliefs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->unsignedBigInteger('vat_return_id')->nullable();
            $table->string('receipt_number');
            $table->timestamp('receipt_date');
            $table->decimal('amount',20,2);
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
        Schema::dropIfExists('vat_special_reliefs');
    }
}
