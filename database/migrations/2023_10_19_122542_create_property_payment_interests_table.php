<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyPaymentInterestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_payment_interests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_payment_id')->index();
            $table->unsignedBigInteger('financial_year_id')->index();
            $table->unsignedBigInteger('financial_month_id')->index();
            $table->decimal('amount', 20, 2);
            $table->decimal('interest', 20, 2);
            $table->integer('period');
            $table->dateTime('payment_date');
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
        Schema::dropIfExists('property_payment_interests');
    }
}
