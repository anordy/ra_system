<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('business_id');

            $table->string('filled_type');
            $table->unsignedBigInteger('filled_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->integer('edited_count')->default(0);
            $table->enum('status',['submitted', 'control-number-generating', 'control-number-generated', 'control-number-generating-failed', 'paid-partially', 'complete'])->default('submitted');

            $table->decimal('hotel_infrastructure_tax', 40, 2)->nullable();
            $table->string('financial_month_id');
            $table->decimal('total_amount_due', 40, 2)->default(0);
            $table->decimal('total_amount_due_with_penalty', 40, 2)->default(0);
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('paid_at')->nullable();

            $table->foreign('business_location_id')->references('id')->on('business_locations');
            $table->foreign('business_id')->references('id')->on('businesses');
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
        Schema::dropIfExists('hotel_returns');
    }
}
