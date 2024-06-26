<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicServiceInterestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_service_interests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('public_service_return_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->decimal('principal', 20, 2);
            $table->decimal('interest', 20, 2);
            $table->decimal('period', 20, 2);
            $table->decimal('amount', 20, 2);
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
        Schema::dropIfExists('public_service_interests');
    }
}
