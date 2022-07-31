<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStampDutyReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stamp_duty_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filed_id');
            $table->string('filed_type');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('tax_type_id')->nullable();
            $table->unsignedBigInteger('financial_month_id')->nullable();
            $table->unsignedBigInteger('financial_year_id')->nullable();
            $table->string('financial_month');
            $table->string('financial_year');

            $table->integer('edited_count')->default(0);

            $table->string('tax_type_code');
            $table->decimal('payable_amount', 40,2);
            $table->decimal('withheld_amount', 40,2);
            $table->decimal('total_amount_due', 40,2);
            $table->boolean('has_exemption');
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
        Schema::dropIfExists('stamp_duty_returns');
    }
}
