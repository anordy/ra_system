<?php

use App\Models\Returns\StampDuty\StampDutyReturn;
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
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('financial_month_id')->nullable();
            $table->unsignedBigInteger('financial_year_id')->nullable();

            $table->integer('edited_count')->default(0);

            $table->decimal('total_amount_due', 40,2);
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->string('status');
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
