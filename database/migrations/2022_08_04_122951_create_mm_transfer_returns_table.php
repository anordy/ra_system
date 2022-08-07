<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMMTransferReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mm_transfer_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('business_id');
            $table->string('filled_type');
            $table->unsignedBigInteger('filed_by_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->integer('edited_count')->default(0);
            $table->enum('status',['submitted','complete','control-number-generating','control-number-generated','control-number-generating-failed','paid-partially'])->default('submitted');
            $table->string('currency');
            $table->string('financial_month_id');
            $table->decimal('total_amount_due', 40, 2)->default(0);
            $table->decimal('total_amount_due_with_penalties', 40, 2)->default(0);
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
        Schema::dropIfExists('mm_transfer_returns');
    }
}
